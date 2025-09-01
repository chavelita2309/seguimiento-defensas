    <x-app-layout>
        <x-slot name="header">
            <h2 class="text-xl font-semibold text-gray-800">Subir Borrador del Proyecto</h2>
        </x-slot>

        <div class="max-w-2xl mx-auto py-6">
            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded">{{ session('error') }}</div>
            @endif

            <form action="{{ route('avances.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium text-sm text-gray-700">Título del avance</label>
                    <input type="text" name="titulo" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700">Descripción (opcional)</label>
                    <textarea name="descripcion" rows="4" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700">Archivo (PDF o Word, máx. 50 MB)</label>
                    <input type="file" name="archivo" accept=".pdf,.doc,.docx" required>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">Cancelar</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Subir Borrador</button>
                </div>
            </form>
        </div>
    </x-app-layout>

