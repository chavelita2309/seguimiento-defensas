<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Gestión de Usuarios</h2>
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
                <a href="{{ route('users.create') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                    + Nuevo Usuario
                </a>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nro.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php $n = 1; @endphp
                        @foreach ($users as $user)
                            <tr class="border-b">
                                <td class="border px-4 py-2">{{ $n++ }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->getRoleNames()->join(', ') }}</td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('users.edit', $user) }}" class="text-blue-600">Editar</a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
