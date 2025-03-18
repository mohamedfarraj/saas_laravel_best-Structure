<?php

namespace Database\Seeders\Core;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::updateOrCreate(
            [
                'name' => 'Test Tenant',
                'domain' => 'test.localhost',
                'database' => 'tenant_test'
            ]
        );
    }
} 