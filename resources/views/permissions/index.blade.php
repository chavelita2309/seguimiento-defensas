<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Gestión de Permisos</h2>
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

            <div class="mb-4">
                <a href="{{ route('permissions.create') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                    + Crear Permiso
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nro.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $n = 1; @endphp

                        @foreach ($permissions as $permission)
                            <tr>
                                <td class="border px-4 py-2">{{ $n++ }}</td>
                                <td class="border px-4 py-2">{{ $permission->name }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('permissions.edit', $permission) }}" class="text-blue-600">Editar</a>
                                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 ml-2" onclick="return confirm('¿Estás seguro de eliminar este permiso?')">Eliminar</button>
                                    </form>
                                    {{-- <a href="{{ route('roles.permisos.edit', $role->id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        Gestionar Permisos
                                    </a> --}}

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
</x-app-layout>