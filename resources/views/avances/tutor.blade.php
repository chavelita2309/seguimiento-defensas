<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            👨‍🏫 Avances de tus postulantes
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if ($avances->isEmpty())
            <p class="text-gray-600">No se encontraron avances para mostrar.</p>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Postulante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Entrega
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Límite
                                Revisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tribunal
                                asignado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Informes de
                                Revisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($avances as $avance)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $avance->proyecto->postulante->nombre }}
                                    {{ $avance->proyecto->postulante->paterno }}</td>
                                <td class="px-4 py-2">{{ $avance->titulo }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($avance->fecha_entrega)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($avance->fecha_limite_revision)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    {!! $avance->proyecto->tribunales->map(function ($tribunal) {
                                            return $tribunal->nombre . ' ' . $tribunal->paterno . ' ' . $tribunal->materno;
                                        })->implode('<br>') !!}
                                </td>

                                <td class="px-4 py-2">
                                    @if ($avance->revisiones->isNotEmpty())
                                        {!! $avance->revisiones->map(function ($revision) {
                                                return '<span class="capitalize">' . $revision->estado . '</span>';
                                            })->implode('<br>') !!}
                                    @else
                                        <span class="text-gray-500">Sin revisiones</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ asset('storage/' . $avance->archivo_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Ver documento</a>
                                </td>
                                <td class="px-4 py-2">
                                    @if ($avance->revisiones->isNotEmpty())
                                        <div class="flex flex-col gap-2">
                                            @foreach ($avance->revisiones as $revision)
                                                <div class="border rounded-lg p-2 bg-gray-50 shadow-sm">
                                                    <div class="font-bold text-gray-800">
                                                        Revisor: {{ $revision->revisor->name ?? 'Desconocido' }}
                                                    </div>
                                                    <div class="text-sm mt-1">
                                                        Estado:
                                                        <span
                                                            class="font-semibold capitalize text-{{ $revision->estado === 'aprobado' ? 'green' : ($revision->estado === 'observado' ? 'red' : 'yellow') }}-600">
                                                            {{ $revision->estado }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm mt-1">
                                                        Fecha de Revisión:
                                                        {{ \Carbon\Carbon::parse($revision->fecha_revision)->format('d/m/Y') }}
                                                        @if ($revision->informe_path)
                                                            <a href="{{ asset('storage/' . $revision->informe_path) }}"
                                                                target="_blank"
                                                                class="text-blue-600 hover:underline text-sm mt-1 block">Ver
                                                                informe</a>
                                                        @endif
                                                    </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Sin revisiones aún</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
