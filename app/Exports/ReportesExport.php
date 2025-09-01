<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Proyecto::with(['postulante.user', 'tutor.user', 'modalidad'])
            ->get()
            ->map(function ($proyecto) {
                return [
                    'Postulante' => $proyecto->postulante->user->name ?? '',
                    'Tutor' => $proyecto->tutor->user->name ?? '',
                    'Modalidad' => $proyecto->modalidad->nombre ?? '',
                    'Cantidad de avances' => $proyecto->avances->count(),
                    'Último avance' => optional($proyecto->avances->last())->titulo,
                ];
            });
    }

    public function headings(): array
    {
        return ['Postulante', 'Tutor', 'Modalidad', 'Cantidad de avances', 'Último avance'];
    }
}
