<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Gestión de Postulantes
        </h2>
    </x-slot>
    <div class="container size-11/12 m-auto">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('postulantes.create') }}"
            class="inline-block mb-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Nuevo
            Postulante</a><br>
        <a href="{{ route('postulantes.eliminados') }}"
            class="inline-block mb-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            🗑 Ver postulantes eliminados
        </a><br>

        <form action="{{ route('postulantes.importar.api') }}" method="POST">
            @csrf
            <input type="text" name="ci" placeholder="Ingrese CI">
            <button type="submit" class="inline-block mb-4 bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Importar desde API</button>
        </form>

        <!-- Tabla -->
        <table class="min-w-full border-collapse block md:table">
            <thead class="block md:table-header-group">
                <tr
                    class="border border-grey-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Apellido paterno</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Apellido materno</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Nombres</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Fecha de nacimiento</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Cédula de identidad</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Registro universitario</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Correo electrónico</th>
                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Celular</th>

                    <th
                        class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">
                        Operaciones</th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @foreach ($postulantes as $postulante)
                    <tr class=" border border-gray-500 md:border-none block md:table-row">
                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Apellido paterno</span>
                            <a href="{{ route('postulantes.show', $postulante->id) }}">{{ $postulante->paterno }}</a>
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Apellido
                                materno</span>{{ $postulante->materno }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Nombres</span>{{ $postulante->nombre }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Fecha de
                                nacimiento</span>{{ \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('d/m/Y') }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Cédula de
                                identidad</span>{{ $postulante->ci }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Registro
                                universitario</span>{{ $postulante->ru }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Correo
                                electrónico</span>{{ $postulante->email }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span
                                class="inline-block w-1/3 md:hidden font-bold">Celular</span>{{ $postulante->telefono }}
                        </td>

                        <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                            <span class="inline-block w-1/3 md:hidden font-bold">Operaciones</span>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('postulantes.edit', $postulante) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 w-24 text-center rounded inline-block">Editar</a>
                                <a href="{{ route('proyectos.create', ['postulante_id' => $postulante->id]) }}"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 w-24 text-center rounded inline-block">
                                    Registrar Proyecto
                                </a>

                                <form action="{{ route('postulantes.destroy', $postulante) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este postulante?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 w-24 text-center rounded inline-block">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($postulantes->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center p-4">No hay postulantes registrados</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
    {{ $postulantes->links() }}
</x-app-layout>
