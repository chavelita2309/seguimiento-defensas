<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Gestión de Backups</h2>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <!-- Botón para generar backup -->
    <form action="{{ route('backups.run') }}" method="POST" class="mb-4">
        @csrf
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Generar Backup
        </button>
    </form>

    <!-- Listado de backups -->
    <table class="w-full border">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Archivo</th>
                <th class="p-2 border">Tamaño</th>
                <th class="p-2 border">Fecha</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($backups as $backup)
                <tr>
                    <td class="p-2 border">{{ $backup['name'] }}</td>
                    <td class="p-2 border">{{ $backup['size'] }}</td>
                    <td class="p-2 border">{{ $backup['date'] }}</td>
                    <td class="p-2 border">
                        <a href="{{ route('backups.download', $backup['name']) }}"
                           class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Descargar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-2 text-center">No hay backups disponibles</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
