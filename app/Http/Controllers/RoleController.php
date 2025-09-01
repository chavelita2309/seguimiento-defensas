<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // 
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    // Muestra el formulario para crear un nuevo rol
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Almacena un nuevo rol en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
    }

    // Muestra el formulario para editar un rol existente
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    // Actualiza un rol existente
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'No se puede editar el rol superadministrador.');
        }
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente');
    }

    // Elimina un rol existente
    public function destroy(Role $role)
    {
        if ($role->name === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar el rol superadministrador.');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado');
    }

    // Muestra el formulario para editar los permisos de un rol
    public function editPermisos($id)
    {
        $role = Role::findById($id);
        if ($role->name === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'No puedes modificar los permisos del rol superadministrador.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.permisos', compact('role', 'permissions', 'rolePermissions'));
    }

    // Actualiza los permisos de un rol
    public function updatePermisos(Request $request, $id)
    {
        $role = Role::findById($id);

        if ($role->name === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'No puedes modificar los permisos del rol superadministrador.');
        }

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Permisos del rol actualizados correctamente.');
    }
}
