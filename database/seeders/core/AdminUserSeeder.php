<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Core\Roles\Models\Roles;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Roles::where('name', 'admin')->first();
        
        if ($adminRole) {
            $admin = User::firstOrCreate(
                ['email' => 'admin@core.com'],
                [
                    'name' => 'Core Administrator',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $admin->roles()->sync([$adminRole->id]);
        }
    }
} 