<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Proyectos Eliminados</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('proyectos.index') }}"
                class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                ← Volver al listado
            </a>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Título del Proyecto</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Postulante</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Tutor</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($proyectos as $proyecto)
                            <tr>
                                <td class="px-6 py-4 break-words max-w-xs">
                                    {{ $proyecto->titulo }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $proyecto->postulante->nombre }} {{ $proyecto->postulante->paterno }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $proyecto->tutor->nombre }} {{ $proyecto->tutor->paterno }}
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('proyectos.restore', $proyecto->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de restaurar este proyecto?')">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-1 rounded">
                                            Restaurar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay proyectos eliminados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

