<x-app-layout>
    <h2 class="font-semibold text-xl">Crear Rol</h2>

   <div class="p-6">
    <form action="{{ route('roles.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 font-medium text-gray-700">Nombre del rol</label>
            <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
        </div>

        <div class="flex space-x-4 mt-4">
            <button type="submit"
                class="inline-flex items-center justify-center w-32 h-11 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md transition duration-200">
                Guardar
            </button>

            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center justify-center w-32 h-11 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md transition duration-200">
                Cancelar
            </a>
        </div>
    </form>
</div>

</x-app-layout>
