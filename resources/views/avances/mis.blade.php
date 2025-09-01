<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mis Avances</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('avances.create') }}"
            class="mb-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Subir nuevo avance
        </a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($avances->isEmpty())
            <p class="text-gray-600">Aún no has registrado ningún avance.</p>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Entrega
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Informes de
                                Revisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($avances as $avance)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $avance->titulo }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($avance->fecha_entrega)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2">{{ ucfirst($avance->estado) }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ asset('storage/' . $avance->archivo_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Ver documento</a>
                                    @if ($avance->estado === 'observado')
                                        <div class="text-sm text-red-600">Tiene observaciones</div>
                                    @elseif($avance->estado === 'en_revision')
                                        <div class="text-sm text-yellow-600">Revisión en curso</div>
                                    @elseif($avance->estado === 'aprobado')
                                        <div class="text-sm text-green-600">Aprobado por todos</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if ($avance->created_at->gt(now()->subHour()))
                                        <form action="{{ route('avances.destroy', $avance->id) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este avance?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 w-24 text-center rounded">Eliminar</button>
                                        </form>
                                    @else
                                        <span class="text-sm text-red-600">No se puede eliminar (pasó 1 hora)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{-- El controlador ya filtra las revisiones, solo necesitamos iterar sobre ellas --}}
                                    @if ($avance->revisiones->isNotEmpty())
                                        <div class="flex flex-wrap gap-4">
                                            @foreach ($avance->revisiones as $revision)
                                                <div class="border rounded-lg p-3 bg-gray-50 shadow-sm w-64">
                                                    <div class="font-bold text-gray-800">
                                                        {{ $revision->revisor->name ?? 'Revisor desconocido' }}
                                                    </div>
                                                    <div class="text-sm mt-1">
                                                        Estado:
                                                        <span
                                                            class="font-semibold capitalize text-{{ $revision->estado === 'aprobado' ? 'green' : ($revision->estado === 'observado' ? 'red' : 'yellow') }}-600">
                                                            {{ $revision->estado }}
                                                        </span>
                                                    </div>
                                                    @if ($revision->observaciones)
                                                        <div class="mt-1 text-gray-600 italic text-sm">
                                                            “{{ $revision->observaciones }}”
                                                        </div>
                                                    @endif
                                                    @if ($revision->informe_path)
                                                        <div class="mt-2">
                                                            <a href="{{ asset('storage/' . $revision->informe_path) }}"
                                                                target="_blank"
                                                                class="text-blue-600 hover:underline text-sm">
                                                                Ver informe
                                                            </a>
                                                        </div>
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