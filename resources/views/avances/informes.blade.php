<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            📂 Informes de Revisión
        </h2>
    </x-slot>

    <div class="container mx-auto mt-4">

        {{-- INFORMES APROBADOS --}}
        <h3 class="text-lg font-bold mb-2 text-green-700">✅ Aprobados</h3>
        @if($aprobadas->count() > 0)
            <table class="min-w-full border-collapse border border-gray-300 mb-6">
                <thead>
                    <tr>
                        <th class="border p-2">Postulante</th>
                        <th class="border p-2">Proyecto</th>
                        <th class="border p-2">Tutor</th>
                        <th class="border p-2">Archivo</th>
                        <th class="border p-2">Fecha</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aprobadas as $rev)
                        <tr>
                            <td class="border p-2">{{ $rev->avance->proyecto->postulante->user->name ?? '-' }}</td>
                            <td class="border p-2">{{ $rev->avance->titulo ?? '-' }}</td>
                            <td class="border p-2">{{ $rev->avance->proyecto->tutor->user->name ?? '-' }}</td>
                            <td class="border p-2">
                                <a href="{{ asset('storage/'.$rev->informe_path) }}" target="_blank" class="text-blue-500 underline">
                                    Ver Informe
                                </a>
                            </td>
                            <td class="border p-2">{{ \Carbon\Carbon::parse($rev->fecha_revision)->format('d/m/Y') }}</td>
                            <td class="border p-2">
                                <a href="{{ route('informes.descargar', $rev->id) }}" target="_blank" class="bg-indigo-600 text-white px-2 py-1 rounded text-sm">
                                    📄 Descargar Informe Final
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 mb-6">No hay informes aprobados.</p>
        @endif


        {{-- INFORMES OBSERVADOS --}}
        <h3 class="text-lg font-bold mb-2 text-red-700">⚠️ Observados</h3>
        @if($observadas->count() > 0)
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border p-2">Postulante</th>
                        <th class="border p-2">Proyecto</th>
                        <th class="border p-2">Tutor</th>
                        <th class="border p-2">Archivo</th>
                        <th class="border p-2">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($observadas as $rev)
                        <tr>
                            <td class="border p-2">{{ $rev->avance->proyecto->postulante->user->name ?? '-' }}</td>
                            <td class="border p-2">{{ $rev->avance->titulo ?? '-' }}</td>
                            <td class="border p-2">{{ $rev->avance->proyecto->tutor->user->name ?? '-' }}</td>
                            <td class="border p-2">
                                <a href="{{ asset('storage/'.$rev->informe_path) }}" target="_blank" class="text-blue-500 underline">
                                    Ver Informe
                                </a>
                            </td>
                            <td class="border p-2">{{ \Carbon\Carbon::parse($rev->fecha_revision)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">No hay informes observados.</p>
        @endif
    </div>
</x-app-layout>


