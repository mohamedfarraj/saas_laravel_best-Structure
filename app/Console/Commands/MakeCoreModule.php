<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCoreModule extends BaseModule
{
    protected $signature = 'make:core:module {name}';
    protected $description = 'Create a new core module';

    public function handle()
    {
        $name = $this->argument('name');
        $moduleName = Str::studly($name);
        $modulePath = app_path("Modules/Core/{$moduleName}");

        // Create module directory
        if (!File::exists($modulePath)) {
            File::makeDirectory($modulePath, 0755, true);
        }

        // Create module structure
        $this->createModuleStructure($modulePath, $moduleName);

        // Create migration
        $this->createMigration($moduleName);

        $this->info("Core module {$moduleName} created successfully!");
    }

    protected function getModuleType(): string
    {
        return 'Core';
    }

    protected function createModuleStructure($path, $name)
    {
        // Create Controllers
        $this->createDirectory($path, 'Controllers');
        $this->createFile($path, "Controllers/{$name}Controller.php", $this->getControllerStub($name));

        // Create Models
        $this->createDirectory($path, 'Models');
        $this->createFile($path, "Models/{$name}.php", $this->getModelStub($name));

        // Create Services
        $this->createDirectory($path, 'Services');
        $this->createFile($path, "Services/{$name}Service.php", $this->getServiceStub($name));

        // Create Repositories
        $this->createDirectory($path, 'Repositories');
        $this->createFile($path, "Repositories/{$name}Repository.php", $this->getRepositoryStub($name));

        // Create Requests
        $this->createDirectory($path, 'Requests');
        $this->createFile($path, "Requests/Store{$name}Request.php", $this->getRequestStub($name, 'Store'));
        $this->createFile($path, "Requests/Update{$name}Request.php", $this->getRequestStub($name, 'Update'));

        // Create Resources
        $this->createDirectory($path, 'Resources');
        $this->createFile($path, "Resources/{$name}Resource.php", $this->getResourceStub($name));

        // Create Routes
        $this->createDirectory($path, 'Routes');
        $this->createFile($path, 'Routes/api.php', $this->getRoutesStub($name));
    }

    protected function createDirectory($path, $name)
    {
        if (!File::exists("{$path}/{$name}")) {
            File::makeDirectory("{$path}/{$name}", 0755, true);
        }
    }

    protected function createFile($path, $name, $content)
    {
        if (!File::exists("{$path}/{$name}")) {
            File::put("{$path}/{$name}", $content);
        }
    }

    protected function getControllerStub($name)
    {
        $stub = File::get(app_path('Console/Commands/stubs/controller.stub'));
        return str_replace(
            ['{{MODULE}}', '{{MODULE_LOWER}}', '{{TYPE}}'],
            [$name, strtolower($name), 'Core'],
            $stub
        );
    }

    protected function getModelStub($name)
    {
        $stub = File::get(app_path('Console/Commands/stubs/model.stub'));
        return str_replace(
            ['{{MODULE}}', '{{TYPE}}'],
            [$name, 'Core'],
            $stub
        );
    }

    protected function getServiceStub($name)
    {
        $stub = File::get(app_path('Console/Commands/stubs/service.stub'));
        return str_replace(
            ['{{MODULE}}', '{{MODULE_LOWER}}', '{{TYPE}}'],
            [$name, strtolower($name), 'Core'],
            $stub
        );
    }

    protected function getRepositoryStub($name)
    {
        $stub = File::get(app_path('Console/Commands/stubs/repository.stub'));
        return str_replace(
            ['{{MODULE}}', '{{TYPE}}'],
            [$name, 'Core'],
            $stub
        );
    }

    protected function getRequestStub($name, $type)
    {
        $stub = File::get(app_path('Console/Commands/stubs/request.stub'));
        return str_replace(
            ['{{MODULE}}', '{{TYPE}}'],
            [$name, $type],
            $stub
        );
    }

    protected function getResourceStub($name)
    {
        $stub = File::get(app_path('Console/Commands/stubs/resource.stub'));
        return str_replace(
            ['{{MODULE}}', '{{TYPE}}'],
            [$name, 'Core'],
            $stub
        );
    }

    protected function getRoutesStub($name)
    {
        return "<?php\n\nuse App\\Modules\\Core\\{$name}\\Controllers\\{$name}Controller;\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::apiResource('" . strtolower($name) . "', {$name}Controller::class);\n";
    }
} 