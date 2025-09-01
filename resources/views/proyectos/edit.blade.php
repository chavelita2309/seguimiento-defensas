<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Edición de Proyectos
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6">
        <form action="{{ route('proyectos.update', $proyecto) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $proyecto->titulo) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                <input type="text" name="descripcion" value="{{ old('descripcion', $proyecto->descripcion) }}"
                    required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Resolución de perfil</label>
                <input type="text" name="resolucion" value="{{ old('resolucion', $proyecto->resolucion) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de resolución</label>
                <input type="date" name="fecha" value="{{ old('fecha', $proyecto->fecha) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Postulante</label>
                <input type="text" value="{{ $proyecto->postulante->nombre }} {{ $proyecto->postulante->paterno }}"
                    disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100">
            </div>

    <div>
    <label class="block text-sm font-medium text-gray-700">Tutor</label>
    <select name="tutor_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @foreach ($tutores as $tutor)
            <option value="{{ $tutor->id }}" {{ $proyecto->tutor_id == $tutor->id ? 'selected' : '' }}>
                {{ $tutor->nombre }} {{ $tutor->paterno }}
            </option>
        @endforeach
    </select>
</div>


<div>
    <label class="block text-sm font-medium text-gray-700">Modalidad</label>
    <select name="modalidad_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @foreach ($modalidades as $modalidad)
            <option value="{{ $modalidad->id }}" {{ $proyecto->modalidad_id == $modalidad->id ? 'selected' : '' }}>
                {{ $modalidad->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Tribunales</label>
    @for ($i = 0; $i < 3; $i++)
        @php
            $tribunalSeleccionado = $proyecto->tribunales[$i]->id ?? null;
            $rolAsignado = $proyecto->tribunales[$i]->pivot->rol ?? '';
        @endphp
        <div class="mt-2 p-4 border rounded bg-gray-50">
            <label class="block text-sm font-medium text-gray-600">Tribunal {{ $i + 1 }}</label>
            <select name="tribunales[{{ $i }}]" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                <option value="">Seleccione un tribunal</option>
                @foreach ($tribunales as $tribunal)
                    <option value="{{ $tribunal->id }}" {{ $tribunalSeleccionado == $tribunal->id ? 'selected' : '' }}>
                        {{ $tribunal->nombre }} {{ $tribunal->paterno }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="roles[{{ $i }}]" value="{{ $rolAsignado }}" placeholder="Rol (opcional)"
                class="form-input mt-1 block w-full">
        </div>
    @endfor
</div>


            <div class="flex justify-end">
                <a href="{{ route('proyectos.index') }}"
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Cancelar</a>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</x-app-layout>
