<x-app-layout>
    <x-slot name="header">Editar Usuario</x-slot>

    <div class="max-w-2xl mx-auto mt-6">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña (dejar en blanco si no desea cambiarla)</label>
                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('users.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</x-app-layout>
