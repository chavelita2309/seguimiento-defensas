<x-app-layout>
    <x-slot name="header">Crear Usuario</x-slot>

    <div class="max-w-2xl mx-auto mt-6">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Selecciona un rol --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('users.index') }}" class="text-gray-600 hover:underline mr-4">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </div>
</x-app-layout>
