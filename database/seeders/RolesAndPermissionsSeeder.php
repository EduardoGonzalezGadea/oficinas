<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- PERMISOS ---
        // Permiso de Administración Total
        Permission::create(['name' => 'acceso-total']);

        // Permisos de Gestión de Usuarios
        Permission::create(['name' => 'gestionar-roles-global']);
        Permission::create(['name' => 'crear-usuarios']);

        // Permisos por Módulo (ejemplo con Jefatura y DCA)
        $modulos = ['Jefatura', 'DCA', 'Tesorería', 'Contabilidad', 'Art. 222', 'Asesoría Contable'];
        foreach ($modulos as $modulo) {
            Permission::create(['name' => "ver-modulo-$modulo"]);
            Permission::create(['name' => "gestionar-usuarios-modulo-$modulo"]);
        }

        // --- ROLES ---
        // Rol de Administrador
        $adminRole = Role::create(['name' => 'ADMINISTRADOR']);
        $adminRole->givePermissionTo('acceso-total');

        // Roles de Módulos
        foreach ($modulos as $modulo) {
            // Rol Supervisor
            $supervisorRole = Role::create(['name' => "Supervisor $modulo"]);
            $supervisorRole->givePermissionTo("ver-modulo-$modulo");
            $supervisorRole->givePermissionTo("gestionar-usuarios-modulo-$modulo");
            $supervisorRole->givePermissionTo('crear-usuarios');

            // Rol Usuario
            $userRole = Role::create(['name' => "Usuario $modulo"]);
            $userRole->givePermissionTo("ver-modulo-$modulo");
        }

        // --- USUARIO ADMINISTRADOR ---
        $adminUser = User::factory()->create([
            'name' => 'Administrador General',
            'username' => 'admin',
            'email' => 'admin@oficinas.com.uy',
            'password' => bcrypt('12345678'), // ¡Cambiar en producción!
        ]);
        $adminUser->assignRole($adminRole);
    }
}
