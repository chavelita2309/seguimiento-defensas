<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Gestión de Proyectos
        </h2>
    </x-slot>
    <div class="container size-11/12 m-auto">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('proyectos.eliminados') }}"
            class="inline-block mb-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            🗑 Ver proyectos eliminados
        </a><br>

        <!-- Formulario de Búsqueda  -->
        <div class="my-4 p-4 bg-gray-100 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-2">Filtros de Búsqueda</h3>
            <form action="{{ route('proyectos.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search_titulo" class="block text-sm font-medium text-gray-700">Título</label>
                    <input type="text" name="search_titulo" id="search_titulo" value="{{ request('search_titulo') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="search_resolucion" class="block text-sm font-medium text-gray-700">Resolución</label>
                    <input type="text" name="search_resolucion" id="search_resolucion"
                        value="{{ request('search_resolucion') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="search_postulante" class="block text-sm font-medium text-gray-700">Postulante</label>
                    <input type="text" name="search_postulante" id="search_postulante"
                        value="{{ request('search_postulante') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="search_tutor" class="block text-sm font-medium text-gray-700">Tutor</label>
                    <input type="text" name="search_tutor" id="search_tutor" value="{{ request('search_tutor') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="search_modalidad" class="block text-sm font-medium text-gray-700">Modalidad</label>
                    <input type="text" name="search_modalidad" id="search_modalidad"
                        value="{{ request('search_modalidad') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="search_tribunales" class="block text-sm font-medium text-gray-700">Tribunales</label>
                    <input type="text" name="search_tribunales" id="search_tribunales"
                        value="{{ request('search_tribunales') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="col-span-full md:col-span-2 lg:col-span-4 flex items-end space-x-2 mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                        Buscar
                    </button>
                    <a href="{{ route('proyectos.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
        <!-- Fin del Formulario de Búsqueda -->

        <!-- Tabla -->
        <table class="min-w-full border-collapse block md:table">
            <thead class="block md:table-header-group">
                <tr
                    class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Título</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Resolución de perfil</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Fecha de resolución</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Fecha límite</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Postulante</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Tutor</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Modalidad</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Tribunales</th>


                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Operaciones</th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @foreach ($proyectos as $proyecto)
                    <tr class=" border border-gray-500 md:border-none block md:table-row">
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Título</span>
                            {{-- <a href="{{ route('proyectos.show', $proyecto->id) }}"> --}}
                            {{ $proyecto->titulo }}</a>
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Resolución de
                                perfil</span>{{ $proyecto->resolucion }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Fecha de
                                resolución</span>{{ \Carbon\Carbon::parse($proyecto->fecha)->format('d/m/Y') }}
                        </td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            @if ($proyecto->fecha_limite)
                                {{ $proyecto->fecha_limite->format('d/m/Y') }} <br>
                                @if ($proyecto->vencido)
                                    <span class="text-red-600 font-bold">⚠ Vencido</span>
                                @else
                                    <span class="text-yellow-600">⏳ {{ $proyecto->dias_restantes }} días
                                        restantes</span>
                                @endif
                            @else
                                <span class="text-gray-500">Sin fecha</span>
                            @endif
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span
                                class="inline-block w-1/3 md:hidden font-bold">Postulante</span>{{ $proyecto->postulante->nombre }}
                            {{ $proyecto->postulante->paterno }} {{ $proyecto->postulante->materno }}
                        </td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span
                                class="inline-block w-1/3 md:hidden font-bold">Tutor</span>{{ $proyecto->tutor->nombre }}
                            {{ $proyecto->tutor->paterno }} {{ $proyecto->tutor->materno }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span
                                class="inline-block w-1/3 md:hidden font-bold">Modalidad</span>{{ $proyecto->modalidad->nombre }}
                        </td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Tribunales</span>
                            @foreach ($proyecto->tribunales as $tribunal)
                                {{ $tribunal->nombre }} {{ $tribunal->paterno }}<br>
                            @endforeach
                        </td>
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Operaciones</span>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('proyectos.edit', $proyecto) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 w-24 text-center rounded inline-block">Editar</a>

                                <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este proyecto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 w-24 text-center rounded inline-block">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($proyectos->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center p-4">No hay proyectos registrados</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
    {{ $proyectos->appends(request()->query())->links() }}
</x-app-layout>
