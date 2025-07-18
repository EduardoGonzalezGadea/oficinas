<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar caché y resetear las tablas de Spatie y Users
        // Esto es crucial para ejecuciones repetidas del seeder.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Desactivar foreign key checks para poder truncar sin problemas.
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Database\Eloquent\Model::unguard();

        Permission::truncate();
        Role::truncate();
        User::truncate();

        \Illuminate\Database\Eloquent\Model::reguard();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 2. Crear todos los permisos
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
        echo "Permisos creados con éxito.\n";

        // 3. Crear todos los roles
        $adminRole = Role::create(['name' => 'ADMINISTRADOR']);

        $moduleRoles = [];
        foreach ($modules as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];
            $moduleRoles["Supervisor {$moduleName}"] = Role::create(['name' => "Supervisor {$moduleName}"]);
            $moduleRoles["Usuario {$moduleName}"] = Role::create(['name' => "Usuario {$moduleName}"]);
        }
        echo "Roles creados con éxito.\n";

        // 4. Asignar permisos a los roles
        $adminRole->givePermissionTo(Permission::all());

        foreach ($modules as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];
            $moduleRoles["Supervisor {$moduleName}"]->givePermissionTo([
                "ver-modulo-{$key}",
                "gestionar-usuarios-modulo-{$key}",
                'crear-usuarios',
            ]);
            $moduleRoles["Usuario {$moduleName}"]->givePermissionTo("ver-modulo-{$key}");
        }
        echo "Permisos asignados a roles con éxito.\n";

        // 5. Crear usuarios y asignarles roles (de forma explícita, sin factory)
        $adminUser = User::create([
            'name' => 'Administrador General',
            'username' => 'admin',
            'email' => 'admin@oficinas.com.uy',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($adminRole);
        echo "Usuario Administrador creado y rol asignado.\n";

        // Crear usuarios de prueba para cada módulo
        foreach ($modules as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];
            $supervisorUser = User::create([
                'name' => "Supervisor de {$moduleName}",
                'username' => strtolower($key) . '_supervisor',
                'email' => strtolower($key) . '_sup@oficinas.com.uy',
                'password' => Hash::make('password'),
            ]);
            $supervisorUser->assignRole($moduleRoles["Supervisor {$moduleName}"]);
        }
        echo "Usuarios Supervisores de prueba creados y roles asignados.\n";
    }
}
