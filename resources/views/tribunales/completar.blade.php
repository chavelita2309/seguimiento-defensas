<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Sistema Web de Seguimiento y Control de Titulación - Completar Registro de Tribunal
        </h2>
    </x-slot>

    <div class='flex items-center justify-center min-h-screen from-teal-100 via-teal-300 to-teal-500 bg-gradient-to-br'>
        <div class='w-full max-w-lg px-10 py-8 mx-auto bg-white rounded-lg shadow-xl'>
            <div class='max-w-md mx-auto space-y-6'>

                <form action="{{ route('tribunales.storeDesdeApi') }}" method="POST">
                    @csrf

                    <h2 class="text-2xl font-bold">Completar datos del tribunal</h2>
                    <p class="my-4 opacity-70">Complete solo los datos faltantes</p>
                    <hr class="my-6">

                    {{-- Nombre --}}
                    <label class="uppercase text-sm font-bold opacity-70">Nombres</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ $data['nombre'] }}" readonly>
                    <input type="hidden" name="nombre" value="{{ $data['nombre'] }}">

                    {{-- Apellido paterno --}}
                    <label class="uppercase text-sm font-bold opacity-70">Apellido paterno</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ $data['paterno'] }}" readonly>
                    <input type="hidden" name="paterno" value="{{ $data['paterno'] }}">

                    {{-- Apellido materno --}}
                    <label class="uppercase text-sm font-bold opacity-70">Apellido materno</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ $data['materno'] }}" readonly>
                    <input type="hidden" name="materno" value="{{ $data['materno'] }}">

                    {{-- CI --}}
                    <label class="uppercase text-sm font-bold opacity-70">Cédula de identidad</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ $data['ci'] }}" readonly>
                    <input type="hidden" name="ci" value="{{ $data['ci'] }}">

                   {{-- Email --}}
                    <label class="uppercase text-sm font-bold opacity-70">Correo electrónico</label>
                    <input type="email" name="email" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ old('email', $data['email'] ?? '') }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror

                    {{-- Teléfono --}}
                    <label class="uppercase text-sm font-bold opacity-70">Nº de celular</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        value="{{ $data['celular'] }}" readonly>
                    <input type="hidden" name="telefono" value="{{ $data['celular'] }}">

                    {{-- Fecha de nacimiento --}}
                    <label class="uppercase text-sm font-bold opacity-70">Fecha de nacimiento</label>
                    <input type="date" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="fecha_nacimiento">
                    
                    {{-- Grado académico --}}
                    <label class="uppercase text-sm font-bold opacity-70">Grado académico</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="grado" placeholder="Ingrese el grado académico">

                    {{-- Botones --}}


                    <div class="flex flex-wrap gap-2">
                        <!-- Botón Guardar -->
                        <input type="submit" value="Guardar"
                            class="inline-block py-3 px-6 my-2 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition duration-300" />

                        <!-- Botón Cancelar -->
                        <a href="{{ route('tribunales.index') }}"
                            class="inline-block py-3 px-6 my-2 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition duration-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
