<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use App\Modules\Core\Roles\Models\Roles;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Roles::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'is_active' => true
            ]
        );
    }
} 