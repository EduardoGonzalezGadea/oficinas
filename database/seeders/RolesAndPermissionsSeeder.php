<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar caché y resetear las tablas
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();

        // Truncar todas las tablas relevantes, incluyendo las pivote de Spatie
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        Permission::truncate();
        Role::truncate();
        User::truncate();

        Schema::enableForeignKeyConstraints();

        // 2. Crear Permisos
        $permissions = [
            'acceso-total',
            'gestionar-roles-global',
            'crear-usuarios',
        ];
        $modules = config('modules', []);
        foreach ($modules as $key => $details) {
            $permissions[] = "ver-modulo-{$key}";
            $permissions[] = "gestionar-usuarios-modulo-{$key}";
        }
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Crear Roles
        $adminRole = Role::create(['name' => 'ADMINISTRADOR']);

        $supervisorRoles = [];
        $userRoles = [];
        foreach ($modules as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];
            $supervisorRoles[$key] = Role::create(['name' => "Supervisor {$moduleName}"]);
            $userRoles[$key] = Role::create(['name' => "Usuario {$moduleName}"]);
        }

        // 4. Asignar Permisos a Roles
        $adminRole->givePermissionTo(Permission::all());

        foreach ($modules as $key => $details) {
            $supervisorRoles[$key]->givePermissionTo([
                "ver-modulo-{$key}",
                "gestionar-usuarios-modulo-{$key}",
                'crear-usuarios',
            ]);
            $userRoles[$key]->givePermissionTo("ver-modulo-{$key}");
        }

        // 5. Crear Usuarios y Asignar Roles
        $adminUser = User::create([
            'name' => 'Administrador General',
            'username' => 'admin',
            'email' => 'admin@oficinas.com.uy',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($adminRole); // Ahora esto funcionará gracias al Trait

        foreach ($modules as $key => $details) {
            $supervisorUser = User::create([
                'name' => "Supervisor " . ($details['display_name'] ?? $details['name']),
                'username' => strtolower($key) . '_supervisor',
                'email' => strtolower($key) . '_sup@oficinas.com.uy',
                'password' => Hash::make('password'),
            ]);
            $supervisorUser->assignRole($supervisorRoles[$key]);
        }

        echo "Seeder ejecutado con éxito. El usuario 'admin' con contraseña 'password' ha sido creado y tiene el rol de ADMINISTRADOR.\n";
    }
}
