<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Registro de Proyectos
        </h2>
    </x-slot>

    <div class='flex items-center justify-center min-h-screen from-teal-100 via-teal-300 to-teal-500 bg-gradient-to-br'>
        <div class='w-full max-w-lg px-10 py-8 mx-auto bg-white rounded-lg shadow-xl'>
            <div class='max-w-md mx-auto space-y-6'>
                <form action="{{ route('proyectos.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="postulante_id" value="{{ $postulanteId }}">

                    <h2 class="text-2xl font-bold">Nuevo proyecto</h2>
                    <p class="my-4 opacity-70">Registre los datos del proyecto</p>
                    <hr class="my-6">
                    <!-- Campo Título -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Título del Proyecto</label>
                        <textarea name="titulo" rows="4" required class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>

                    </div>

                    <!-- Campo Descripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text" name="descripcion" required
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    </div>

                    <!-- Resolución -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Resolución</label>
                        <input type="text" name="resolucion" required
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" required
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    </div>

                    <!-- Tutor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tutor</label>
                        <select name="tutor_id" required class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Seleccione un tutor</option>
                            @foreach ($tutores as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->nombre }} {{ $tutor->paterno }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Modalidad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modalidad</label>
                        <select name="modalidad_id" required
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                            <option value="">Seleccione una modalidad</option>
                            @foreach ($modalidades as $modalidad)
                                <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tribunales -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tribunales (Seleccione solo a uno de los tribunales con el rol de lider)</label>

                        @for ($i = 0; $i < 3; $i++)
                            <div class="mt-2 p-4 border rounded bg-gray-50">
                                <label class="block text-sm font-medium text-gray-600">Tribunal
                                    {{ $i + 1 }}</label>
                                <select name="tribunales[{{ $i }}][id]" required
                                    class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                    <option value="">Seleccione un tribunal</option>
                                    @foreach ($tribunales as $tribunal)
                                        <option value="{{ $tribunal->id }}">{{ $tribunal->nombre }}
                                            {{ $tribunal->paterno }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="roles[{{ $i }}]" class="form-input w-full"
                                    placeholder="Rol">

                            </div>
                        @endfor
                    </div>
                  

                    <!-- Botones -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('postulantes.index') }}"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cancelar</a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Registrar
                            Proyecto</button>
                    </div>
                </form>
            </div>
</x-app-layout>
