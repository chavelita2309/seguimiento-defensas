<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Avance;
use App\Models\Proyecto;
use App\Models\RevisionAvance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AvanceSubidoMail;
use App\Http\Controllers\Controller;

// controlador para la gestión de avances de trabajos de grado

class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Si es postulante: ve solo los avances de su proyecto
        if ($user->hasRole('Postulante')) {
            $proyecto = $user->postulante->proyecto;
            $avances = $proyecto ? $proyecto->avances()->latest()->get() : collect();
        } else {
            // Tutor o tribunal ve los avances de proyectos asociados
            $avances = Avance::where('created_at', '<=', now()->subHour())
                ->whereHas('proyecto', function ($q) use ($user) {
                    $q->where('tutor_id', $user->tutor->id ?? 0)
                        ->orWhereHas('tribunales', fn($q) => $q->where('tribunal_id', $user->tribunal->id ?? 0));
                })
                ->latest()
                ->get();
        }

        return view('avances.index', compact('avances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Obtener proyecto del postulante autenticado
        $proyecto = Proyecto::where('postulante_id', $user->postulante->id ?? null)->first();

        if (!$proyecto) {
            return redirect()->back()->with('error', 'No tienes un proyecto asignado aún.');
        }

        return view('avances.create', compact('proyecto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:51200', // 50MB
        ]);

        $user = Auth::user();
        $postulante = optional($user->postulante);

        if (!$postulante || !$postulante->proyecto) {
            return redirect()->back()->with('error', 'No se encontró el proyecto para registrar el avance.');
        }

        $proyecto = $postulante->proyecto;

        // Guardar archivo
        $archivoPath = $request->file('archivo')->store('avances', 'public');

        // Crear el avance
        $avance = Avance::create([
            'proyecto_id' => $proyecto->id,
            'user_id' => $user->id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'archivo_path' => $archivoPath,
            'fecha_entrega' => now(),
            'fecha_limite_revision' => $this->sumarDiasHabiles(now(), 10), // 10 días hábiles
            'estado' => 'pendiente',
            'observaciones' => '',
        ]);

        // Crear revisiones para los tribunales
        $tribunales = DB::table('proyecto_tribunal')
            ->join('tribunales', 'proyecto_tribunal.tribunal_id', '=', 'tribunales.id')
            ->where('proyecto_tribunal.proyecto_id', $proyecto->id)
            ->pluck('tribunales.user_id');

        foreach ($tribunales as $userId) {
            RevisionAvance::firstOrCreate([
                'avance_id' => $avance->id,
                'revisor_id' => $userId,
            ], [
                'rol' => 'tribunal',
                'observaciones' => '',
            ]);
        }

        // Crear revisión para el tutor
        $tutorUserId = optional($proyecto->tutor)->user_id;

        if ($tutorUserId) {
            RevisionAvance::firstOrCreate([
                'avance_id' => $avance->id,
                'revisor_id' => $tutorUserId,
            ], [
                'rol' => 'tutor',
                'observaciones' => '',
            ]);
        }

         // Enviar correo a cada tribunal (con manejo de errores)
        // foreach ($tribunales as $userId) {
        //     $tribunalUser = \App\Models\User::find($userId);
        //     if ($tribunalUser && $tribunalUser->email) {
        //         try {
        //             Mail::to($tribunalUser->email)->send(
        //                 new AvanceSubidoMail($avance, $postulante)
        //             );
        //         } catch (\Exception $e) {
        //             \Log::error("Error enviando correo a {$tribunalUser->email}: " . $e->getMessage());
        //         }
        //     }
        // }
        return redirect()->route('avances.mis')->with('success', 'Avance registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $avance = Avance::findOrFail($id);

        if ($avance->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este avance.');
        }

        if ($avance->created_at->lte(now()->subHour())) {
            return redirect()->back()->with('error', 'Ya no puedes eliminar este avance. Ha pasado más de una hora.');
        }

        // Elimina el archivo
        Storage::disk('public')->delete($avance->archivo_path);

        // Marca quién eliminó
        $avance->deleted_by = auth()->id();
        $avance->save();

        $avance->delete(); // Soft delete

        return redirect()->route('avances.mis')->with('success', 'Avance eliminado correctamente.');
    }

    // calcula los feriados moviles
    private function getFeriadosMoviles($year)
    {
        $feriados = [];

        // Fecha de Pascua
        $pascua = Carbon::createFromTimestamp(easter_date($year));

        // Carnaval: lunes y martes antes de Pascua
        $feriados[] = $pascua->copy()->subDays(48)->format('Y-m-d'); // Lunes de Carnaval
        $feriados[] = $pascua->copy()->subDays(47)->format('Y-m-d'); // Martes de Carnaval

        // Viernes Santo: 2 días antes de Pascua
        $feriados[] = $pascua->copy()->subDays(2)->format('Y-m-d');

        // Corpus Christi: 60 días después de Pascua
        $feriados[] = $pascua->copy()->addDays(60)->format('Y-m-d');

        return $feriados;
    }

    // Suma días hábiles a una fecha, considerando fines de semana y feriados
    private function sumarDiasHabiles($fecha, $dias)
    {
        // Feriados fijos (mes-día)
        $feriadosFijos = [
            '01-01', // Año Nuevo
            '01-22', // Día del Estado Plurinacional
            '05-01', // Día del Trabajo
            '06-21', // Año Nuevo Aymara
            '07-16', // Aniversario de La Paz
            '08-06', // Independencia
            '11-02', // Todos Santos
            '12-25', // Navidad
        ];

        $fecha = Carbon::parse($fecha);
        $year = $fecha->year;
        $feriadosMoviles = $this->getFeriadosMoviles($year);

        while ($dias > 0) {
            $fecha->addDay();

            $feriadoFijo = $fecha->format('m-d');
            $feriadoMovil = $fecha->format('Y-m-d');

            if (
                !$fecha->isWeekend() &&
                !in_array($feriadoFijo, $feriadosFijos) &&
                !in_array($feriadoMovil, $feriadosMoviles)
            ) {
                $dias--;
            }
        }

        return $fecha;
    }


    public function misAvances()
    {
        $user = auth()->user();

        // Validar que tenga postulante y proyecto
        if (!$user->postulante || !$user->postulante->proyecto) {
            return view('avances.mis', ['avances' => collect()]);
        }

        $proyecto = $user->postulante->proyecto;

        // Obtener los IDs de los tribunales líderes
        // La relación debe ser del proyecto a la tabla 'proyecto_tribunal'
        // y luego a la tabla 'tribunales' para obtener el 'user_id' asociado.
        $lideresIds = $proyecto->tribunales()
            ->where('rol', 'lider') // Este es el rol en la tabla pivote 'proyecto_tribunal'
            ->pluck('tribunal_id')
            ->toArray();

        // Ahora obtenemos los user_ids a partir de los tribunal_ids
        $lideresUsers = \App\Models\Tribunal::whereIn('id', $lideresIds)->pluck('user_id')->toArray();

        // Cargar los avances con las revisiones correspondientes
        // Filtramos las revisiones por los user_ids de los líderes y que tienen un informe
        $avances = Avance::with(['revisiones' => function ($query) use ($lideresUsers) {
            $query->whereIn('revisor_id', $lideresUsers)
                ->whereNotNull('informe_path');
        }, 'revisiones.revisor'])
            ->where('proyecto_id', $proyecto->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('avances.mis', compact('avances'));
    }


    // Vista para tutores
    public function verComoTutor()
    {
        $tutor = \App\Models\Tutor::where('user_id', auth()->id())->first();

        if (!$tutor) {
            abort(403, 'No se encontró tutor asociado al usuario.');
        }

        $proyectosDelTutor = Proyecto::where('tutor_id', $tutor->id)->pluck('id');

        $avances = Avance::whereIn('proyecto_id', $proyectosDelTutor)
            ->with([
                'proyecto.postulante',
                'proyecto.tribunales', // Carga los modelos User que son tribunales
                'revisiones' => function ($query) {
                    // Filtramos las revisiones para que solo tengan informe
                    $query->whereNotNull('informe_path');
                },
                'revisiones.revisor'
            ])
            ->latest()
            ->get();

        $avances->each(function ($avance) {
            // Obtener user_id de los tribunales líderes
            // Usamos pluck('id') porque $avance->proyecto->tribunales es una colección de modelos User.
            $lideresUserIds = $avance->proyecto->tribunales
                ->where('pivot.rol', 'lider')
                ->pluck('id') 
                ->toArray();

            // Filtramos solo revisiones de los líderes
            $avance->revisiones_lider = $avance->revisiones->filter(function ($revision) use ($lideresUserIds) {
                return in_array($revision->revisor_id, $lideresUserIds);
            });
        });

        return view('avances.tutor', compact('avances'));
    }
}
