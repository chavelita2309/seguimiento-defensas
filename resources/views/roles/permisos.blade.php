<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Asignar Permisos al Rol: {{ $role->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes de sesión --}}
            @if (session('success'))
                <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 text-red-600 bg-red-100 p-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6">
                <form method="POST" action="{{ route('roles.permisos.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">
                            Selecciona los permisos para el rol:
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    >
                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Guardar Permisos
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
