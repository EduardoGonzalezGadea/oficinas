<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // Importante para transacciones
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Usar una transacción de base de datos para asegurar que todo o nada se ejecute.
        Schema::disableForeignKeyConstraints();

        // Truncar las tablas en el orden correcto
        Role::truncate();
        Permission::truncate();
        User::truncate(); // Si quieres limpiar usuarios también

        Schema::enableForeignKeyConstraints();

        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. CREAR TODOS LOS PERMISOS PRIMERO ---
        $permissions = [
            'acceso-total',
            'gestionar-roles-global',
            'crear-usuarios',
        ];

        $modulos = config('modules', []);
        foreach ($modulos as $key => $details) {
            $permissions[] = "ver-modulo-$key";
            $permissions[] = "gestionar-usuarios-modulo-$key";
        }

        // Crear todos los permisos en una sola tanda
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // --- 2. CREAR ROLES Y ASIGNAR PERMISOS ---

        // Rol de Administrador (ahora tiene acceso a todos los permisos recién creados)
        $adminRole = Role::create(['name' => 'ADMINISTRADOR']);
        $adminRole->givePermissionTo(Permission::all());

        // Roles de Módulos
        foreach ($modulos as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];

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

        // --- 3. CREAR USUARIO ADMINISTRADOR ---
        // (La comprobación de existencia es buena, la mantenemos)
        if (!User::where('username', 'admin')->exists()) {
            $adminUser = User::factory()->create([
                'name' => 'Administrador General',
                'username' => 'admin',
                'email' => 'admin@oficinas.com.uy',
                'password' => Hash::make('password'), // ¡Cambiar en producción!
            ]);
            $adminUser->assignRole($adminRole);
        }

        // Opcional: Crear usuarios de prueba para cada módulo
        // Descomentar si quieres datos de prueba
        foreach ($modulos as $key => $details) {
            $moduleName = $details['display_name'] ?? $details['name'];
            if (!User::where('username', strtolower($key).'_supervisor')->exists()) {
                $supervisorUser = User::factory()->create([
                    'name' => "Supervisor de $moduleName",
                    'username' => strtolower($key).'_supervisor',
                    'email' => strtolower($key).'_sup@oficinas.com.uy',
                    'password' => Hash::make('password'),
                ]);
                $supervisorUser->assignRole("Supervisor $moduleName");
            }
        }
    }
}
