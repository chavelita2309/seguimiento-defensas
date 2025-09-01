<div class="bg-white shadow rounded-lg overflow-x-auto mb-8">
    <table class="min-w-full table-auto divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Postulante</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tutor</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Entrega</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Límite</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Documento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @php $user = auth()->user(); @endphp
            @foreach ($revisiones as $revision)
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
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $revision->avance->titulo }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $revision->avance->proyecto->postulante->nombre }} {{ $revision->avance->proyecto->postulante->paterno }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $revision->avance->proyecto->tutor->nombre }} {{ $revision->avance->proyecto->tutor->paterno }}</td>
                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($revision->avance->fecha_entrega)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($revision->avance->fecha_limite_revision)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ asset('storage/' . $revision->avance->archivo_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm">Ver documento</a>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if ($esLider)
                            <form action="{{ route('revisiones.update', $revision->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <select name="estado" class="w-full border-gray-300 rounded shadow-sm text-sm">
                                    <option value="en_revision" {{ $revision->estado == 'en_revision' ? 'selected' : '' }}>En revisión</option>
                                    <option value="aprobado" {{ $revision->estado == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="observado" {{ $revision->estado == 'observado' ? 'selected' : '' }}>Observado</option>
                                </select>
                                <textarea name="observaciones" rows="2" placeholder="Escriba observaciones..." class="w-full border border-gray-300 rounded shadow-sm text-sm">{{ $revision->observaciones }}</textarea>
                                <input type="file" name="informe_path" accept=".pdf,.doc,.docx" class="block w-full text-sm border border-gray-300 rounded p-1">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">Guardar</button>
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
