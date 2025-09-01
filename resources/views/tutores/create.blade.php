<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Registro de Tutores
        </h2>
    </x-slot>
    <div class='flex items-center justify-center min-h-screen from-teal-100 via-teal-300 to-teal-500 bg-gradient-to-br'>
        <div class='w-full max-w-lg px-10 py-8 mx-auto bg-white rounded-lg shadow-xl'>
            <div class='max-w-md mx-auto space-y-6'>

                <form action="{{ route('tutores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2 class="text-2xl font-bold">Nuevo tutor</h2>
                    <p class="my-4 opacity-70">Registre los datos del tutor</p>
                    <hr class="my-6">
                    <label class="uppercase text-sm font-bold opacity-70">Nombres</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="nombre">
                    <label class="uppercase text-sm font-bold opacity-70">Apellido paterno</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="paterno">
                    <label class="uppercase text-sm font-bold opacity-70">Apellido materno</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="materno">
                    <label class="uppercase text-sm font-bold opacity-70">Fecha de nacimiento</label>
                    <input type="date" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded"
                        name="fecha_nacimiento">
                    <label class="uppercase text-sm font-bold opacity-70">Cédula de identidad</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="ci">
                    <label class="uppercase text-sm font-bold opacity-70">Correo electrónico</label>
                    <input type="email" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="email">

                    <label class="uppercase text-sm font-bold opacity-70">Nº de celular</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="telefono">
                    <label class="uppercase text-sm font-bold opacity-70">Grado académico</label>
                    <input type="text" required class="p-3 mt-2 mb-4 w-full bg-slate-200 rounded" name="grado">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    <div class="flex flex-wrap gap-2">
                        <!-- Botón Guardar ) -->
                        <input type="submit" value="Guardar "
                            class="inline-block py-3 px-6 my-2 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition duration-300" />

                        <!-- Botón Cancelar -->
                        <a href="{{ route('tutores.index') }}"
                            class="inline-block py-3 px-6 my-2 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition duration-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
