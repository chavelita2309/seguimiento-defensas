<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sistema Web de Seguimiento y Control de Titulación - Avances
        </h2>
    </x-slot>
<table class="w-full mt-4 table-auto">
    <thead>
        <tr>
            <th class="text-left">Título</th>
            <th>Fecha Entrega</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($avances as $avance)
            <tr>
                <td>{{ $avance->titulo }}</td>
                <td>{{ \Carbon\Carbon::parse($avance->fecha_entrega)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($avance->estado) }}</td>
                <td>
                    <a href="{{ asset('storage/' . $avance->archivo_path) }}" target="_blank" class="text-blue-600 hover:underline">Ver</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</x-app-layout>