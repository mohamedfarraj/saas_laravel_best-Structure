<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core Seeders
        $this->call([
            Core\RolesSeeder::class,
            Core\AdminUserSeeder::class,
            Core\PermissionsSeeder::class,
        ]);

       
    }
}
