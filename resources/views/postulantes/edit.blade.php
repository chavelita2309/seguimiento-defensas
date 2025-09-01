<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Edición de Postulantes
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6">
        <form action="{{ route('postulantes.update', $postulante) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $postulante->nombre) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Paterno</label>
                <input type="text" name="paterno" value="{{ old('paterno', $postulante->paterno) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                
            </div>  
          
            <div>
                <label class="block text-sm font-medium text-gray-700">Materno</label>
                <input type="text" name="materno" value="{{ old('materno', $postulante->materno) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $postulante->fecha_nacimiento) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
               
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Cédula de identidad</label>
                <input type="text" name="ci" value="{{ old('ci', $postulante->ci) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
               
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Registro universitario</label>
                <input type="text" name="ru" value="{{ old('ru', $postulante->ru) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
               
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="text" name="email" value="{{ old('email', $postulante->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
              
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Celular</label>
                <input type="text" name="telefono" value="{{ old('telefono', $postulante->telefono) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
               
            </div>

            <div class="flex justify-end">
                <a href="{{ route('postulantes.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</x-app-layout>

