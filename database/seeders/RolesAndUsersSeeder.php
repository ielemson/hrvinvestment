<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndUsersSeeder extends Seeder
{
    public function run()
    {
        // Clear cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */
        $permissions = [
            'view dashboard',
            'apply for loan',
            'view loan status',
            'upload kyc',
            'approve loans',
            'reject loans',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']); // borrower

        // Assign permissions
        $adminRole->givePermissionTo(Permission::all());

        $userRole->givePermissionTo([
            'view dashboard',
            'apply for loan',
            'view loan status',
            'upload kyc',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin User
        |--------------------------------------------------------------------------
        */
        $admin = User::firstOrCreate(
            ['email' => 'admin@hvrfinvestments.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        /*
        |--------------------------------------------------------------------------
        | Borrower User
        |--------------------------------------------------------------------------
        */
        $borrower = User::firstOrCreate(
            ['email' => 'user@hvrfinvestments.com'],
            [
                'name' => 'Test Borrower',
                'password' => Hash::make('User@12345'),
            ]
        );

        if (! $borrower->hasRole('user')) {
            $borrower->assignRole($userRole);
        }
    }
}
