<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Postulantes Eliminados</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('postulantes.index') }}"
                class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ← Volver al listado
            </a>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre completo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($postulantes as $postulante)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $postulante->nombre }} 
                                    {{ $postulante->paterno }} {{ $postulante->materno }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $postulante->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('postulantes.restore', $postulante->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de restaurar este postulante?')">
                                        @csrf
                                        <button type="submit"  class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 w-24 text-center rounded inline-block">Restaurar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center">No hay postulantes eliminados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
