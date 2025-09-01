<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Sistema Web de Seguimiento y Control de Titulación - Detalles del Proyecto
        </h2>
    </x-slot>
    <h2 class="text-xl font-bold mb-4">Detalle del Proyecto</h2>

    <div class="mb-4">
        <p><strong>Postulante:</strong> {{ $proyecto->postulante->nombre }} {{ $proyecto->postulante->paterno }}
            {{ $proyecto->postulante->materno }}</p>
        <p><strong>Tutor:</strong> {{ $proyecto->tutor->nombre }} {{ $proyecto->tutor->paterno }}
            {{ $proyecto->tutor->materno }}</p>
        <p><strong>Modalidad:</strong> {{ $proyecto->modalidad->nombre }}</p>
    </div>

    <h3 class="text-lg font-semibold mb-2">Avances y Revisiones</h3>



    @foreach ($proyecto->avances as $avance)
        <div class="mb-4 border p-3 rounded bg-gray-100">
            <p><strong>Título:</strong> {{ $avance->titulo }}</p>
            <p><strong>Fecha de entrega:</strong> {{ $avance->fecha_entrega->format('d/m/Y') }}</p>
            @php
                $fechaLimite = \Carbon\Carbon::parse($avance->fecha_limite_revision);
                $atrasado = now()->greaterThan($fechaLimite);
            @endphp

            <p><strong>Fecha límite de revisión:</strong>
                {{ $fechaLimite->format('d/m/Y') }}

                @if ($atrasado)
                    <span class="ml-2 px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">
                        ⏰ Vencido (Documento no revisado a tiempo)
                    </span>
                @endif
            </p>

            <p><strong>Documento de avance: </strong><a href="{{ Storage::url($avance->archivo_path) }}" target="_blank"
                    class="text-blue-500">Ver documento</a></p>


            <h4 class="font-semibold mt-2">Revisión del Tribunal :</h4>
            <ul class="ml-4 list-disc">
                @foreach ($avance->revisiones as $rev)
                    @php
                        // Buscar si el revisor es tribunal y tiene rol "lider"
                        $tribunal = $proyecto->tribunales->firstWhere('user_id', $rev->revisor_id);
                    @endphp

                    @if ($tribunal && $tribunal->pivot->rol === 'lider')
                        <li>
                            <strong>Tribunal Líder:</strong> {{ $rev->revisor->name ?? '---' }} |
                            <strong>Estado:</strong> {{ ucfirst($rev->estado) }} |
                            <strong>Fecha de revisión:</strong>
                            {{ $rev->fecha_revision ? \Carbon\Carbon::parse($rev->fecha_revision)->format('d/m/Y') : '---' }}
                            @if ($rev->informe_path)
                                | <a href="{{ Storage::url($rev->informe_path) }}" target="_blank"
                                    class="text-blue-500">Ver informe</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>

        </div>
    @endforeach
    <div class="mt-6">
        <a href="{{ route('reportes.proyectos') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Volver al listado de proyectos</a>
</x-app-layout>
