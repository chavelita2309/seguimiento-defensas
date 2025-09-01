<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del rol -->
                    <div class="mb-4">
                        <x-label value="Nombre del Rol" />
                        <x-input name="name" value="{{ old('name', $role->name) }}" class="w-full" />
                    </div>
               
                    <div class="flex justify-end">
                         <a href="{{ route('roles.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
