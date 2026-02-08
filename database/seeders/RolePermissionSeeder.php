<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'view dashboard',
            'apply for loan',
            'view loan status',
            'upload kyc',
            'manage users',
            'approve loans',
            'reject loans',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $user  = Role::firstOrCreate(['name' => 'user']); // borrower

        // Assign permissions
        $admin->givePermissionTo(Permission::all());

        $user->givePermissionTo([
            'view dashboard',
            'apply for loan',
            'view loan status',
            'upload kyc',
        ]);
    }
}
