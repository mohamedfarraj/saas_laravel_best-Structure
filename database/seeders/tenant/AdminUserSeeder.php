<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Modules\Tenant\Users\Models\Users;
use App\Modules\Tenant\Roles\Models\Roles;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Get admin role
        $adminRole = Roles::where('name', 'admin')->first();

        if (!$adminRole) {
            throw new \Exception('Admin role not found. Please run RolesSeeder first.');
        }

        // Create admin user
        $adminUser = Users::firstOrCreate(
            ['email' => 'admin@tenant.com'],
            [
                'name' => 'Tenant Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]
        );

        // Attach admin role to user
        $adminUser->roles()->sync([$adminRole->id]);
    }
} 