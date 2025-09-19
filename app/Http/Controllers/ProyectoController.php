<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Postulante;
use App\Models\Tutor;
use App\Models\Modalidad;
use App\Models\Tribunal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


// Controlador para la gestión de trabajos de grado

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Muestra todos los proyectos
    public function index(Request $request)
    {

        // Se carga la consulta base con las relaciones necesarias 
        $query = Proyecto::with(['postulante', 'tutor', 'modalidad']);

        // Filtro por título (búsqueda parcial)
        if ($request->filled('search_titulo')) {
            $query->where('titulo', 'like', '%' . $request->input('search_titulo') . '%');
        }

        // Filtro por resolución (búsqueda parcial)
        if ($request->filled('search_resolucion')) {
            $query->where('resolucion', 'like', '%' . $request->input('search_resolucion') . '%');
        }

        // Filtro por postulante (busca por nombre o apellidos)
        if ($request->filled('search_postulante')) {
            $searchTerm = '%' . $request->input('search_postulante') . '%';
            $query->whereHas('postulante', function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', $searchTerm)
                    ->orWhere('paterno', 'like', $searchTerm)
                    ->orWhere('materno', 'like', $searchTerm);
            });
        }

        // Filtro por tutor (busca por nombre o apellidos)
        if ($request->filled('search_tutor')) {
            $searchTerm = '%' . $request->input('search_tutor') . '%';
            $query->whereHas('tutor', function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', $searchTerm)
                    ->orWhere('paterno', 'like', $searchTerm)
                    ->orWhere('materno', 'like', $searchTerm);
            });
        }

        // Filtro por modalidad (búsqueda parcial por nombre)
        if ($request->filled('search_modalidad')) {
            $query->whereHas('modalidad', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->input('search_modalidad') . '%');
            });
        }

        // Filtro por tribunales (busca por nombre o apellidos)
        if ($request->filled('search_tribunales')) {
            $searchTerm = '%' . $request->input('search_tribunales') . '%';
            $query->whereHas('tribunales', function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', $searchTerm)
                    ->orWhere('paterno', 'like', $searchTerm)
                    ->orWhere('materno', 'like', $searchTerm);
            });
        }

        // Aplica el orden y la paginación, manteniendo los parámetros de la URL
        $proyectos = $query->orderBy('titulo')->paginate(10)->withQueryString();

        // Calcular fecha límite y días restantes para cada proyecto
        $proyectos->getCollection()->transform(function ($proyecto) {
            if ($proyecto->fecha) {
                $fechaResolucion = Carbon::parse($proyecto->fecha);
                $fechaLimite = $fechaResolucion->copy()->addYears(2);
                $diasRestantes = now()->startOfDay()->diffInDays($fechaLimite->startOfDay(), false);
                $proyecto->fecha_limite = $fechaLimite;
                $proyecto->dias_restantes = $diasRestantes;
                $proyecto->vencido = $diasRestantes < 0;
            } else {
                $proyecto->fecha_limite = null;
                $proyecto->dias_restantes = null;
                $proyecto->vencido = false;
            }
            return $proyecto;
        });

        return view('proyectos.index', compact('proyectos'));
    }
    /**
     * Show the form for creating a new resource.
     */
    //muestra el formulario para crear un nuevo proyecto
    public function create(Request $request)
    {
        $postulanteId = $request->query('postulante_id');

        // Verifica si el postulante ya tiene un proyecto
        $postulante = Postulante::with('proyecto')->findOrFail($postulanteId);
        if ($postulante->proyecto) {
            return redirect()->route('postulantes.index')
                ->with('error', 'Este postulante ya tiene un proyecto registrado.');
        }
        // carga los datos necesarios para los selects  
        $tutores = Tutor::orderBy('paterno')->get();
        $modalidades = Modalidad::orderBy('nombre')->get();
        $tribunales = Tribunal::orderBy('paterno')->get();

        return view('proyectos.create', compact('postulanteId', 'tutores', 'modalidades', 'tribunales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    //guarda un nuevo proyecto
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'resolucion' => 'required|string|max:100',
            'fecha' => 'required|date',
            'tutor_id' => 'required|exists:tutores,id',
            'modalidad_id' => 'required|exists:modalidades,id',
            'tribunales' => 'required|array|size:3',
            'tribunales.*.id' => 'required|exists:tribunales,id',
            //'tribunales.*.rol' => 'required|string|max:50',
        ]);

        // Asegura de que el postulante no tenga ya un proyecto
        $postulante = Postulante::findOrFail($request->input('postulante_id'));
        if ($postulante->proyecto) {
            return redirect()->route('postulantes.index')
                ->with('error', 'Este postulante ya tiene un proyecto registrado.');
        }

        DB::beginTransaction();
        try {
            // Crea el proyecto
            $proyecto = Proyecto::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'resolucion' => $request->resolucion,
                'fecha' => $request->fecha,
                'postulante_id' => $postulante->id,
                'tutor_id' => $request->tutor_id,
                'modalidad_id' => $request->modalidad_id,
            ]);

            // Asocia tribunales
            foreach ($request->tribunales as $index => $tribunalId) {
                $rol = $request->roles[$index] ?? null; // puede venir vacío
                $proyecto->tribunales()->attach($tribunalId, ['rol' => $rol]);
            }

            DB::commit();
            return redirect()->route('postulantes.index')
                ->with('success', 'Proyecto registrado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el proyecto: ' . $e->getMessage());
        }
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
    //Muestra el formulario para editar un proyecto
    public function edit(Proyecto $proyecto)
    {
        $tutores = Tutor::orderBy('paterno')->get();
        $modalidades = Modalidad::all();
        $tribunales = Tribunal::orderBy('paterno')->get();

        $proyecto->load('tribunales'); // Cargar con la relación para preselección

        return view('proyectos.edit', compact('proyecto', 'tutores', 'modalidades', 'tribunales'));
    }


    /**
     * Update the specified resource in storage.
     */
    // Actualiza un proyecto
    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'resolucion' => 'required|string',
            'fecha' => 'required|date',
            'tutor_id' => 'required|exists:tutores,id',
            'modalidad_id' => 'required|exists:modalidades,id',
            'tribunales' => 'array|nullable',
            'tribunales.*' => 'exists:tribunales,id',
        ]);

        $proyecto->update($request->only([
            'titulo',
            'descripcion',
            'resolucion',
            'fecha',
            'tutor_id',
            'modalidad_id'
        ]));

        // Actualizar los tribunales (si se envían)
        $sincronizar = [];
        if ($request->has('tribunales')) {
            foreach ($request->tribunales as $key => $tribunalId) {
                $rol = $request->roles[$key] ?? null;
                $sincronizar[$tribunalId] = ['rol' => $rol];
            }
            $proyecto->tribunales()->sync($sincronizar);
        }

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    //elimina un proyecto (soft delete)
    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente.');
    }

    // Restaurar un proyecto eliminado
    public function restore($id)
    {
        $proyecto = Proyecto::withTrashed()->findOrFail($id);
        $proyecto->restore();

        // Buscar el usuario relacionado
        return redirect()->route('proyectos.index')
            ->with('success', 'Proyecto restaurado correctamente');
    }


    public function eliminados()
    {
        // Obtener solo los proyectos eliminados (soft deleted)
        $proyectos = Proyecto::onlyTrashed()
            ->orderBy('titulo')
            ->get();

        return view('proyectos.eliminados', compact('proyectos'));
    }

    // Generar informe en PDF si el tribunal líder aprobó
    public function generarInforme($id)
    {
        $proyecto = Proyecto::with([
            'postulante',
            'tutor',
            'modalidad',
            'tribunales.user'
        ])->findOrFail($id);

        // valida que el tribunal líder realmente aprobó
        $liderAprobado = $proyecto->avances()
            ->whereHas('revisiones', function ($q) {
                $q->where('estado', 'aprobado')
                    ->whereHas('revisor.tribunal', function ($q2) {
                        $q2->whereHas('proyectos', function ($q3) {
                            $q3->wherePivot('rol', 'lider');
                        });
                    });
            })->exists();

        if (! $liderAprobado) {
            abort(403, 'El informe solo puede generarse si el tribunal líder aprobó.');
        }

        $pdf = Pdf::loadView('reportes.informe_final', compact('proyecto'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('informe_final.pdf');
    }
}
