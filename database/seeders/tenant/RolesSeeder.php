<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Modules\Tenant\Roles\Models\Roles;
use App\Modules\Tenant\Permissions\Models\Permissions;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Create admin role
        $adminRole = Roles::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'is_active' => true
            ]
        );

        // Get all permissions
        $permissions = Permissions::all();

        // Attach all permissions to admin role
        $adminRole->permissions()->sync($permissions->pluck('id'));

        // Create user role
        $userRole = Roles::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'User',
                'is_active' => true
            ]
        );

        // Attach basic permissions to user role
        $basicPermissions = $permissions->whereIn('name', [
            'users.view',
            'users.edit',
            'roles.view',
            'permissions.view'
        ]);
        $userRole->permissions()->sync($basicPermissions->pluck('id'));
    }
} 