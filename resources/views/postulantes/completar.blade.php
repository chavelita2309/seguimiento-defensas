<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Sistema Web de Seguimiento y Control de Titulación - Completar Registro de Postulante
        </h2>
    </x-slot>

    <div class='flex items-center justify-center min-h-screen from-teal-100 via-teal-300 to-teal-500 bg-gradient-to-br'>
        <div class='w-full max-w-lg px-10 py-8 mx-auto bg-white rounded-lg shadow-xl'>
            <div class='max-w-md mx-auto space-y-6'>

                <form action="{{ route('postulantes.importar.api') }}" method="POST">
                    @csrf

                    <h2 class="text-2xl font-bold">Completar datos del postulante</h2>
                    <p class="my-4 opacity-70">Algunos datos provienen de la API, otros deben ingresarse manualmente.</p>
                    <hr class="my-6">

                    {{-- Nombres --}}
                    <label class="uppercase text-sm font-bold opacity-70">Nombres</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="nombre" value="{{ $data['nombre'] }}" readonly>

                    {{-- Apellido paterno --}}
                    <label class="uppercase text-sm font-bold opacity-70">Apellido paterno</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="paterno" value="{{ $data['paterno'] }}" readonly>

                    {{-- Apellido materno --}}
                    <label class="uppercase text-sm font-bold opacity-70">Apellido materno</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="materno" value="{{ $data['materno'] }}" readonly>

                    {{-- CI --}}
                    <label class="uppercase text-sm font-bold opacity-70">Cédula de identidad</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="ci" value="{{ $data['ci'] }}" readonly>

                    {{-- Email --}}
                    <label class="uppercase text-sm font-bold opacity-70">Correo electrónico</label>
                    <input type="email" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="email" value="{{ $data['email'] }}" readonly>

                    {{-- Celular --}}
                    <label class="uppercase text-sm font-bold opacity-70">Nº de celular</label>
                    <input type="text" class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" 
                        name="telefono" value="{{ $data['celular'] }}" readonly>

                    {{-- RU (manual) --}}
                    <label class="uppercase text-sm font-bold opacity-70">Registro universitario</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="ru">

                    {{-- Fecha de nacimiento (manual) --}}
                    <label class="uppercase text-sm font-bold opacity-70">Fecha de nacimiento</label>
                    <input type="date" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="fecha_nacimiento">

                    <div class="flex flex-wrap gap-2">
                        <!-- Botón Guardar -->
                        <input type="submit" value="Guardar"
                            class="inline-block py-3 px-6 my-2 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition duration-300" />

                        <!-- Botón Cancelar -->
                        <a href="{{ route('postulantes.index') }}"
                            class="inline-block py-3 px-6 my-2 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition duration-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
