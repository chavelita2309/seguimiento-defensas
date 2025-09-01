<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Proyectos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; font-size: 10px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte General de Proyectos con Última Revisión</h2>

    <table>
        <thead>
            <tr>
                <th>Nº</th>
                <th>Postulante</th>
                <th>Título del Proyecto</th>
                <th>Modalidad</th>
                <th>Tutor</th>
                <th>Tribunales</th>
                <th>Último Avance</th>
                
                <th>Tribunal Líder</th>
                <th>Estado Revisión</th>
                <th>Fecha de Entrega</th>
                <th>Fecha Revisión</th>
                <th>Fecha Límite de Revisión</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
                @php
                    // Obtener el último avance por fecha_entrega
                    $ultimoAvance = $proyecto->avances->sortByDesc('fecha_entrega')->first();

                    $revisionLider = null;
                    if ($ultimoAvance) {
                        foreach($ultimoAvance->revisiones as $rev) {
                            $tribunal = $proyecto->tribunales->firstWhere('user_id', $rev->revisor_id);
                            if ($tribunal && $tribunal->pivot->rol === 'lider') {
                                $revisionLider = $rev;
                                break;
                            }
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $proyecto->postulante->nombre }} {{ $proyecto->postulante->paterno }} {{ $proyecto->postulante->materno }} </td>
                    <td>{{ $proyecto->titulo }}</td>
                    <td>{{ $proyecto->modalidad->nombre }}</td>
                    <td>{{ $proyecto->tutor->nombre }} {{ $proyecto->tutor->paterno }} {{ $proyecto->tutor->materno }}</td>
                    <td>
                        @forelse($proyecto->tribunales as $tribunal)
                            <p>{{ $tribunal->nombre }} {{ $tribunal->paterno }}</p>
                        @empty
                            -
                        @endforelse
                    
                    <td>{{ $ultimoAvance?->titulo ?? '---' }}</td>
                    
                    <td>{{ $revisionLider?->revisor->name ?? '---' }}</td>
                    <td>{{ $revisionLider ? ucfirst($revisionLider->estado) : '---' }}</td>
                    <td>{{ $ultimoAvance ? \Carbon\Carbon::parse($ultimoAvance->fecha_entrega)->format('d/m/Y') : '---' }}</td>
                    <td>
                        {{ $revisionLider && $revisionLider->fecha_revision
                            ? \Carbon\Carbon::parse($revisionLider->fecha_revision)->format('d/m/Y')
                            : '---' }}
                    </td>
                     <td>{{ \Carbon\Carbon::parse($ultimoAvance?->fecha_limite_revision)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
