<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Sistema Web de Seguimiento y Control de Titulación - Reportes de Proyectos
        </h2>
    </x-slot>

    <div class="container size-11/12 m-auto">
        <a href="{{ route('reportes.general.pdf') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" target="_blank">
        Descargar Reporte General PDF
    </a><br><br>
        <h2 class="text-xl font-bold mb-4">Listado de Proyectos</h2>
        <table class="min-w-full border-collapse block md:table">
            <thead class="block md:table-header-group">
                <tr
                    class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Postulante</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Título del tema de grado</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Modalidad</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Tutor</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Tribunales</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Avances</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Ver</th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @foreach ($proyectos as $proyecto)
                    <tr class=" border border-gray-500 md:border-none block md:table-row">
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            {{ $proyecto->postulante->user->name ?? 'Sin nombre' }}</td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            {{ $proyecto->titulo ?? '-' }}</td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            {{ $proyecto->modalidad->nombre ?? '-' }}
                        </td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            {{ $proyecto->tutor->user->name ?? '-' }}</td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            @forelse($proyecto->tribunales as $tribunal)
                                <p>{{ $tribunal->nombre }} {{ $tribunal->paterno }}</p>
                            @empty
                                -
                            @endforelse
                        </td>


                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            {{ $proyecto->avances()->count() }}</td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <a href="{{ route('reportes.proyectos.detalle', $proyecto->id) }}"
                                class="text-blue-500 underline">Ver Detalle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        

    </div>
    
</x-app-layout>
