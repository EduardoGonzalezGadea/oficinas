<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Usar Hash facade es más limpio

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- PERMISOS GLOBALES ---
        Permission::create(['name' => 'acceso-total']);
        Permission::create(['name' => 'gestionar-roles-global']);
        Permission::create(['name' => 'crear-usuarios']);

        // --- ROLES Y PERMISOS DE MÓDULOS (AHORA DINÁMICOS) ---
        $modulos = config('modules', []); // Leemos desde nuestro nuevo archivo de config

        foreach ($modulos as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];

            // Permisos por Módulo
            Permission::create(['name' => "ver-modulo-$key"]);
            Permission::create(['name' => "gestionar-usuarios-modulo-$key"]);

            // Rol Supervisor
            $supervisorRole = Role::create(['name' => "Supervisor $moduleName"]);
            $supervisorRole->givePermissionTo([
                "ver-modulo-$key",
                "gestionar-usuarios-modulo-$key",
                'crear-usuarios',
            ]);

            // Rol Usuario
            $userRole = Role::create(['name' => "Usuario $moduleName"]);
            $userRole->givePermissionTo("ver-modulo-$key");
        }

        // --- ROL DE ADMINISTRADOR ---
        $adminRole = Role::create(['name' => 'ADMINISTRADOR']);
        $adminRole->givePermissionTo(Permission::all()); // El admin puede hacer todo

        // --- USUARIO ADMINISTRADOR ---
        if (!User::where('username', 'admin')->exists()) {
            $adminUser = User::factory()->create([
                'name' => 'Administrador General',
                'username' => 'admin',
                'email' => 'admin@oficinas.com.uy',
                'password' => Hash::make('password'), // ¡Cambiar en producción!
            ]);
            $adminUser->assignRole($adminRole);
        }
    }
}
