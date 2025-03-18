<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    protected $signature = 'tenant:migrate {--fresh} {--seed} {--tenant=}';
    protected $description = 'Run migrations for tenant databases';

    public function handle()
    {
        $this->info('Running tenant migrations...');

        // Switch to tenant database
        config(['database.default' => 'tenant']);

        if ($this->option('tenant')) {
            // Run migrations for specific tenant
            $tenant = Tenant::where('domain', $this->option('tenant'))->first();
            
            if (!$tenant) {
                $this->error("Tenant not found: {$this->option('tenant')}");
                return 1;
            }

            $this->runMigrationsForTenant($tenant);
        } else {
            // Run migrations for all tenants
            Tenant::all()->each(function ($tenant) {
                $this->runMigrationsForTenant($tenant);
            });
        }

        $this->info('Tenant migrations completed successfully!');
    }

    protected function runMigrationsForTenant(Tenant $tenant)
    {
        $this->info("Running migrations for tenant: {$tenant->domain}");

        // Switch to tenant's database
        $tenant->makeCurrent();

        if ($this->option('fresh')) {
            $this->call('migrate:fresh', [
                '--path' => 'database/migrations/tenant',
                '--realpath' => true,
                '--force' => true
            ]);
        } else {
            $this->call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--realpath' => true,
                '--force' => true
            ]);
        }

        if ($this->option('seed')) {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\Tenant\\DatabaseSeeder',
                '--force' => true
            ]);
        }

        // Switch back to landlord database
        config(['database.default' => 'landlord']);
    }
} 