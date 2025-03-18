<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CoreMigrate extends Command
{
    protected $signature = 'core:migrate {--fresh} {--seed}';
    protected $description = 'Run migrations for the core (landlord) database';

    public function handle()
    {
        $this->info('Running core migrations...');

        // Switch to landlord database
        config(['database.default' => 'landlord']);

        // Get the full path to migrations
        $migrationsPath = base_path('database/migrations/core');

        // Run migrations
        $migrateCommand = $this->option('fresh') ? 'migrate:fresh' : 'migrate';
        $this->call($migrateCommand, [
            '--path' => 'database/migrations/core',
            '--realpath' => true,
            '--force' => true
        ]);

        if ($this->option('seed')) {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\Core\\TenantSeeder',
                '--force' => true
            ]);
        }

        $this->info('Core migrations completed successfully!');
    }
} 