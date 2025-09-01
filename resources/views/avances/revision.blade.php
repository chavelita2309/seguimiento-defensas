<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            📚 Sistema Web de Seguimiento y Control de Titulación - Revisión de trabajos de grado
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-700">📂 Avances para Revisión</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- BOTÓN DE DESCARGA CONDICIONAL --}}
        @if (session('reporte_aprobado_id'))
            <div
                class="mb-6 p-4 bg-indigo-100 border border-indigo-400 text-indigo-700 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <p class="font-bold">¡Revisión Aprobada!</p>
                    <p>El informe final de revisión ha sido generado y está listo para ser descargado.</p>
                </div>
                <a href="{{ route('informes.descargar', session('reporte_aprobado_id')) }}" target="_blank"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">
                    📄 Descargar Informe Final
                </a>
            </div>
        @endif

        @if ($avances->isEmpty())
            <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                No hay avances pendientes de revisión en este momento.
            </div>
        @else
            <div class="bg-white shadow rounded-lg overflow-x-auto">

                {{-- ALERTA VISUAL DE ATRASO --}}
                @if ($avances->where('atrasado', true)->count() > 0)
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        ⚠️ Tienes {{ $avances->where('atrasado', true)->count() }} revisión(es) pendientes con más de 10
                        días de retraso.
                    </div>
                @endif

                <table class="min-w-full table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Título</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Postulante
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tutor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Entrega</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Límite</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $user = auth()->user(); @endphp

                        @foreach ($avances as $revision)
                            @php
                                $esLider = false;
                                $tribunal = $user->tribunales;

                                if ($tribunal) {
                                    $proyectoId = $revision->avance->proyecto_id;
                                    $esLider = DB::table('proyecto_tribunal')
                                        ->where('proyecto_id', $proyectoId)
                                        ->where('tribunal_id', $tribunal->id)
                                        ->where('rol', 'lider')
                                        ->exists();
                                }
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $revision->avance->titulo }}<br>
                                    <span class="text-gray-400 text-xs">{{ $revision->avance->proyecto->titulo }}</span>
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $revision->avance->proyecto->postulante->nombre }}
                                    {{ $revision->avance->proyecto->postulante->paterno }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $revision->avance->proyecto->tutor->nombre }}
                                    {{ $revision->avance->proyecto->tutor->paterno }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($revision->avance->fecha_entrega)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($revision->avance->fecha_limite_revision)->format('d/m/Y') }}
                                    @if ($revision->atrasado)
                                        <span
                                            class="ml-2 px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">
                                            ⏰ Vencido
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <a href="{{ asset('storage/' . $revision->avance->archivo_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline text-sm">
                                        Ver documento
                                    </a>
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if ($esLider)
                                        <form action="{{ route('revisiones.update', $revision->id) }}" method="POST"
                                            enctype="multipart/form-data" class="space-y-2">
                                            @csrf
                                            @method('PUT')

                                            {{-- Estado --}}
                                            <select name="estado"
                                                class="w-full border-gray-300 rounded shadow-sm text-sm">
                                                <option value="en_revision"
                                                    {{ $revision->estado == 'en_revision' ? 'selected' : '' }}>En
                                                    revisión</option>
                                                <option value="aprobado"
                                                    {{ $revision->estado == 'aprobado' ? 'selected' : '' }}>Aprobado
                                                </option>
                                                <option value="observado"
                                                    {{ $revision->estado == 'observado' ? 'selected' : '' }}>Observado
                                                </option>
                                            </select>

                                            {{-- Comentario --}}
                                            <textarea name="observaciones" rows="2" placeholder="Escriba observaciones..."
                                                class="w-full border border-gray-300 rounded shadow-sm text-sm" required>{{ $revision->observaciones }}</textarea>

                                            {{-- Subir archivo --}}
                                            <input type="file" name="informe_path" accept=".pdf,.doc,.docx"
                                                class="block w-full text-sm text-gray-600 border border-gray-300 rounded p-1">

                                            {{-- Botón --}}
                                            <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                                Guardar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">No autorizado para revisar.</span>
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
