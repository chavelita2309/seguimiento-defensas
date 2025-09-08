<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'ver dashboard',
            'gestionar postulantes',
            'gestionar tutores',
            'gestionar tribunales',
            'gestionar proyectos',
            'gestionar avances',
            'gestionar informes',
            'ver reportes',
            'gestionar usuarios',
            'gestionar roles',
            'gestionar permisos',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crear roles
        $roles = [
            'postulante',
            'tutor',
            'tribunal',
            'superadmin',
            'director',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Asignar permisos por rol
        Role::findByName('postulante')->syncPermissions(['ver dashboard', 'gestionar avances']);
        Role::findByName('tutor')->syncPermissions(['ver dashboard', 'gestionar avances', 'gestionar informes']);
        Role::findByName('tribunal')->syncPermissions(['ver dashboard', 'gestionar informes']);
        Role::findByName('director')->syncPermissions(['ver dashboard', 'gestionar proyectos', 'ver reportes']);
        Role::findByName('superadmin')->syncPermissions(Permission::all());

        // Crear usuario superadmin
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@upea.edu.bo'],
            [
                'name' => 'Super Administrador',
                'password' => bcrypt('admin123'),
            ]
        );
        if (!$admin->hasRole('superadmin')) {
            $admin->assignRole('superadmin');
        }
    }
}