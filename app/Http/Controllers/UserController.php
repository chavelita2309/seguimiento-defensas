<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Lista todos los usuarios
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Muestra el formulario para crear un nuevo usuario
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // Almacena un nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Muestra el formulario para editar un usuario existente
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }
    
    // Actualiza un usuario existente
    public function update(Request $request, User $user)
    {

        if ($user->hasRole('superadmin')) {
            return redirect()->route('users.index')->with('error', 'No se puede modificar al superadministrador.');
        }
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|exists:roles,name',
        ]);

        $user->update($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Elimina un usuario existente
    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->route('users.index')->with('error', 'No se puede eliminar al usuario superadministrador.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }
}
