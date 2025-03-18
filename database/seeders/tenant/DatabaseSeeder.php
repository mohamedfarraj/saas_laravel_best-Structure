<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Database\Seeders\Tenant\AdminUserSeeder;
use Database\Seeders\Tenant\RolesSeeder;
use Database\Seeders\Tenant\PermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Add tenant-specific seeders here
        $this->call([
            RolesSeeder::class,
            AdminUserSeeder::class,
            PermissionsSeeder::class,
           
            

        ]);
    }
} 