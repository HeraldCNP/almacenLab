<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleTecnica = Role::firstOrCreate(['name' => 'Dirección Técnica']);
        $roleOperador = Role::firstOrCreate(['name' => 'Operador']);

        // Define Permissions
        $manageInventory = Permission::firstOrCreate(['name' => 'manage-inventory']);

        // Assign Permissions
        $roleAdmin->givePermissionTo($manageInventory);
        $roleTecnica->givePermissionTo($manageInventory);
        // Operador might have limited access, leaving for now
    }
}
