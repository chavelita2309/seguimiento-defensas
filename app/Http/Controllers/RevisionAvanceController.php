<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Avance;
use App\Models\RevisionAvance;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InformeRevisionSubido;
use Illuminate\Support\Facades\Mail;

// Controlador para la gestión de revisiones del tribunal de los avances de sus postulantes

class RevisionAvanceController extends Controller
{

    // Muestra los avances asignados al revisor actual
    public function index()
    {
        $user = auth()->user();

        // Revisiones pendientes o en curso
        $revisionesPendientes = RevisionAvance::where('revisor_id', $user->id)
            ->whereIn('estado', [null, 'pendiente', 'en_revision'])
            ->whereHas('avance', function ($query) {
                $query->where('created_at', '<=', now()->subHour());
            })
            ->with('avance.proyecto.postulante', 'avance.proyecto.tutor')
            ->latest()
            ->get()
            ->map(function ($revision) {
        $revision->atrasado = \Carbon\Carbon::now()->greaterThan(
            \Carbon\Carbon::parse($revision->avance->fecha_limite_revision)
        );
        return $revision;
    });

        // Revisiones finalizadas (aprobadas u observadas)
        $revisionesFinalizadas = RevisionAvance::where('revisor_id', $user->id)
            ->whereIn('estado', ['aprobado', 'observado'])
            ->with('avance.proyecto.postulante', 'avance.proyecto.tutor')
            ->latest()
            ->get();

        return view('avances.revision', [
            'avances' => $revisionesPendientes,
            'pendientes' => $revisionesPendientes,
            'finalizadas' => $revisionesFinalizadas
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($avanceId)
    {
        $user = auth()->user();

        $avance = Avance::where('id', $avanceId)
            ->where('user_id', $user->id) // Asegura que es su avance
            ->with(['revisiones.revisor']) // Cargar revisores
            ->firstOrFail();

        return view('avances.revisiones', compact('avance'));
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
    // Actualiza una revisión de avance
    public function update(Request $request, RevisionAvance $revision)
    {
        $request->validate([
            'estado' => 'required|in:en_revision,aprobado,observado',
            'observaciones' => 'nullable|string',
            'informe_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Verifica que la revisión pertenece al usuario autenticado
        if ($revision->revisor_id !== auth()->id()) {
            abort(403, 'No tienes permiso para revisar este avance.');
        }

        $usuario = $revision->revisor; // Usuario autenticado

        // Verifica si es tribunal lider
        $esLider = false;
        if ($usuario->tribunales) {
            $esLider = $usuario->tribunales
                ->proyectos()
                ->where('proyecto_id', $revision->avance->proyecto_id)
                ->wherePivot('rol', 'lider')
                ->exists();
        }

        if (!$esLider) {
            abort(403, 'Solo el tribunal líder puede subir el informe.');
        }

        // Guardar archivo si se adjunta
        if ($request->hasFile('informe_path')) {
            $path = $request->file('informe_path')->store('informes', 'public');
            $revision->informe_path = $path;
        }

        // Actualizar datos de la revisión
        $revision->estado = $request->estado;
        $revision->observaciones = $request->observaciones;
        $revision->fecha_revision = now();
        $revision->save();

        // Actualizar estado general del avance
        $avance = $revision->avance;
        $revisiones = $avance->revisiones;

        if ($revisiones->contains('estado', 'observado')) {
            $avance->estado = 'observado';
        } elseif ($revisiones->every(fn($rev) => $rev->estado === 'aprobado')) {
            $avance->estado = 'aprobado';
        } else {
            $avance->estado = 'en_revision';
        }

        $avance->save();

        if ($request->input('estado') === 'aprobado' && $request->hasFile('informe_path')) {
            return redirect()
                ->route('avances.revision')
                ->with('success', 'Revisión guardada correctamente. El informe final para presentar a Dirección ha sido generado.')
                ->with('reporte_aprobado_id', $revision->id); // Pasa el ID de la revisión a la sesión
        }

        // enviar correo al postulante y tutor

        // if ($esLider && $revision->informe_path) {
        //     $postulante = $avance->proyecto->postulante->user;
        //     $tutor      = $avance->proyecto->tutor->user;

        //     if ($postulante) {
        //         Mail::to($postulante->email)->send(new InformeRevisionSubido($revision));
        //     }

        //     if ($tutor) {
        //         Mail::to($tutor->email)->send(new InformeRevisionSubido($revision));
        //     }
        // }

        // 🔹 Redirigir a index con datos frescos y ancla para el scroll
        return redirect()
            ->route('avances.revision') 
            ->withFragment('revision-' . $revision->id) 
            ->with('success', 'Revisión guardada correctamente.');
    }

    // Muestra los informes de revisión
    public function informes()
    {
        $userId = auth()->id();

        // Revisiones aprobadas
        $aprobadas = RevisionAvance::with([
            'avance.proyecto.postulante.user',
            'avance.proyecto.tutor.user',
            'avance.proyecto.tribunales'
        ])
            ->where('estado', 'aprobado')
            ->whereNotNull('informe_path')
            ->whereHas('avance.proyecto.tribunales', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderByDesc('updated_at')
            ->get();

        // Revisiones observadas
        $observadas = RevisionAvance::with([
            'avance.proyecto.postulante.user',
            'avance.proyecto.tutor.user',
            'avance.proyecto.tribunales'
        ])
            ->where('estado', 'observado')
            ->whereNotNull('informe_path')
            ->whereHas('avance.proyecto.tribunales', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderByDesc('updated_at')
            ->get();

        return view('avances.informes', compact('aprobadas', 'observadas'));
    }

    // Descarga el informe de revisión de avance aprobado
    public function descargarInforme($id)
    {
        $revision = RevisionAvance::with([
            'avance.proyecto.postulante.user',
            'avance.proyecto.tutor.user',
            'avance.proyecto.tribunales'
        ])->findOrFail($id);

        // Obtener el proyecto desde la revisión
        $proyecto = $revision->avance->proyecto;
        $postulante = $proyecto->postulante;
        $tutor = $proyecto->tutor;
        $tribunales = $proyecto->tribunales;

        // Generar PDF con todas las variables que la vista espera
        $pdf = Pdf::loadView('reportes.informe_final', compact(
            'revision',
            'proyecto',
            'postulante',
            'tutor',
            'tribunales'
        ))
            ->setPaper('letter', 'portrait') // Tamaño de papel: Carta
            ->setOptions([
                'margin-top' => 30,    // 3 cm
                'margin-right' => 30,  // 3 cm
                'margin-bottom' => 30, // 3 cm
                'margin-left' => 30    // 3 cm
            ]);

        return $pdf->stream('informe_final_' . $revision->id . '.pdf');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
