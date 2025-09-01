<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Avance;
use App\Models\RevisionAvance;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport;
use Barryvdh\DomPDF\Facade\Pdf;

// Controlador para la generación de reportes
class ReporteController extends Controller
{
    // Muestra todos los proyectos
    public function proyectos()
    {
        $proyectos = Proyecto::with([
            'postulante.user',
            'tutor.user',
            'modalidad',
            'tribunales' => function ($query) {
                // Carga solo los usuarios que están directamente vinculados como tribunales al proyecto
                // La relación ya está definida en el modelo, esto asegura que se carguen los correctos
                $query->withPivot('rol');
            },
            'avances'
        ])->paginate(10);

        return view('reportes.proyectos', compact('proyectos'));
    }

    // Muestra los detalles de un proyecto con respecto a los avances y revisiones
    public function detalle($id)
    {
        $proyecto = Proyecto::with([
            'postulante.user',
            'tutor.user',
            'modalidad',
            'avances.revisiones.revisor', // trae usuario revisor
            'tribunales' // para cruzar roles
        ])->findOrFail($id);

        return view('reportes.detalle', compact('proyecto'));
    }

    // Exporta el reporte de seguimiento a Excel
    public function exportExcel()
    {
        return Excel::download(new ReportesExport, 'reporte_seguimiento.xlsx');
    }

    // Exporta el reporte general a PDF
    public function exportGeneralPDF()
    {
        $proyectos = Proyecto::with([
            'postulante.user',
            'tutor.user',
            'modalidad',
            'avances.revisiones.revisor',
            'tribunales'
        ])->get();

        $pdf = Pdf::loadView('reportes.general_pdf', compact('proyectos'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('reporte_general.pdf');
    }
}
