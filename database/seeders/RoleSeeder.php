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

        // create roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAlmacenista = Role::create(['name' => 'almacenista']);
        $roleUser = Role::create(['name' => 'user']);
        $roleInvitado = Role::create(['name' => 'invitado']);

        // Future: create permissions and assign to roles
        // Permission::create(['name' => 'edit articles']);
    }
}
