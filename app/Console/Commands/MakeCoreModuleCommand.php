<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCoreModuleCommand extends Command
{
    protected $signature = 'make:core:module {name}';
    protected $description = 'Create a new core module';

    public function handle()
    {
        $name = $this->argument('name');
        $moduleName = Str::studly($name);
        $modulePath = app_path("Modules/Core/{$moduleName}");

        // Create module directory structure
        $this->createDirectoryStructure($modulePath);

        // Create module files
        $this->createModuleFiles($modulePath, $moduleName);

        // Add permissions to seeder
        $this->addPermissionsToSeeder($moduleName);

        // Update Postman collection
        $this->updatePostmanCollection($moduleName);

        $this->info("Core module {$moduleName} created successfully!");
    }

    protected function createDirectoryStructure($path)
    {
        $directories = [
            'Controllers',
            'Models',
            'Services',
            'Repositories',
            'Routes',
            'Requests',
            'Resources',
            'Database/Migrations',
            'Database/Seeders'
        ];

        foreach ($directories as $dir) {
            File::makeDirectory("{$path}/{$dir}", 0755, true);
        }
    }

    protected function createModuleFiles($path, $moduleName)
    {
        // Create Routes
        $this->createRouteFile($path, $moduleName);

        // Create Controller
        $this->createControllerFile($path, $moduleName);

        // Create Model
        $this->createModelFile($path, $moduleName);

        // Create Service
        $this->createServiceFile($path, $moduleName);

        // Create Repository
        $this->createRepositoryFile($path, $moduleName);

        // Create Resource
        $this->createResourceFile($path, $moduleName);

        // Create Request
        $this->createRequestFile($path, $moduleName);
    }

    protected function createRouteFile($path, $moduleName)
    {
        $content = "<?php\n\nuse Illuminate\Support\Facades\Route;\n\nRoute::prefix('{$moduleName}')->group(function () {\n    // Add your routes here\n});";
        File::put("{$path}/Routes/api.php", $content);
    }

    protected function createControllerFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Controllers;\n\nuse App\\Modules\\Base\\Controllers\\BaseController;\n\nclass {$moduleName}Controller extends BaseController\n{\n    // Add your controller methods here\n}";
        File::put("{$path}/Controllers/{$moduleName}Controller.php", $content);
    }

    protected function createModelFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Models;\n\nuse App\\Modules\\Base\\Models\\BaseModel;\n\nclass {$moduleName} extends BaseModel\n{\n    protected \$fillable = [\n        // Add your fillable fields here\n    ];\n}";
        File::put("{$path}/Models/{$moduleName}.php", $content);
    }

    protected function createServiceFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Services;\n\nuse App\\Modules\\Base\\Services\\BaseService;\nuse App\\Modules\\Core\\{$moduleName}\\Repositories\\{$moduleName}Repository;\n\nclass {$moduleName}Service extends BaseService\n{\n    public function __construct({$moduleName}Repository \${$moduleName}Repository)\n    {\n        parent::__construct(\${$moduleName}Repository);\n    }\n}";
        File::put("{$path}/Services/{$moduleName}Service.php", $content);
    }

    protected function createRepositoryFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Repositories;\n\nuse App\\Modules\\Base\\Repositories\\BaseRepository;\nuse App\\Modules\\Core\\{$moduleName}\\Models\\{$moduleName};\n\nclass {$moduleName}Repository extends BaseRepository\n{\n    public function __construct({$moduleName} \$model)\n    {\n        parent::__construct(\$model);\n    }\n}";
        File::put("{$path}/Repositories/{$moduleName}Repository.php", $content);
    }

    protected function createResourceFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Resources;\n\nuse Illuminate\\Http\\Request;\nuse Illuminate\\Http\\Resources\\Json\\JsonResource;\n\nclass {$moduleName}Resource extends JsonResource\n{\n    public function toArray(Request \$request): array\n    {\n        return [\n            // Add your resource fields here\n        ];\n    }\n}";
        File::put("{$path}/Resources/{$moduleName}Resource.php", $content);
    }

    protected function createRequestFile($path, $moduleName)
    {
        $content = "<?php\n\nnamespace App\\Modules\\Core\\{$moduleName}\\Requests;\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass Store{$moduleName}Request extends FormRequest\n{\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    public function rules(): array\n    {\n        return [\n            // Add your validation rules here\n        ];\n    }\n}";
        File::put("{$path}/Requests/Store{$moduleName}Request.php", $content);
    }

    protected function addPermissionsToSeeder($name)
    {
        $seederPath = database_path('seeders/Core/PermissionsSeeder.php');
        $content = file_get_contents($seederPath);
        
        $insertPosition = strrpos($content, '// Add more core module permissions as needed');
        
        $moduleLower = Str::lower($name);
        $newPermissions = "\n\n        \$this->createModulePermissions('{$moduleLower}', [\n            'index' => 'View " . Str::plural($name) . "',\n            'store' => 'Create " . Str::singular($name) . "',\n            'show' => 'View One " . Str::singular($name) . "',\n            'update' => 'Edit " . Str::singular($name) . "',\n            'destroy' => 'Delete " . Str::singular($name) . "',\n        ]);\n";
        
        $newContent = substr_replace($content, $newPermissions, $insertPosition, 0);
        
        file_put_contents($seederPath, $newContent);
        
        $this->info("Permissions for {$name} module added to Core PermissionsSeeder.php");
    }

    protected function updatePostmanCollection($moduleName)
    {
        $postmanPath = base_path('postman/saas_laravel.postman_collection.json');
        $collection = json_decode(file_get_contents($postmanPath), true);

        $moduleLower = Str::lower($moduleName);
        $modulePlural = Str::plural($moduleName);
        $moduleSingular = Str::singular($moduleName);

        // Find or create Core folder
        $coreFolder = null;
        foreach ($collection['item'] as &$item) {
            if ($item['name'] === 'Core') {
                $coreFolder = &$item;
                break;
            }
        }

        if (!$coreFolder) {
            $coreFolder = [
                'name' => 'Core',
                'item' => []
            ];
            $collection['item'][] = $coreFolder;
        }

        // Create new folder for the module
        $newFolder = [
            'name' => $modulePlural,
            'item' => [
                [
                    'name' => 'List ' . $modulePlural,
                    'request' => [
                        'method' => 'GET',
                        'header' => [
                            [
                                'key' => 'Accept',
                                'value' => 'application/json'
                            ],
                            [
                                'key' => 'Authorization',
                                'value' => 'Bearer {{token}}'
                            ]
                        ],
                        'url' => [
                            'raw' => '{{base_url}}/api/core/' . $moduleLower,
                            'host' => ['{{base_url}}'],
                            'path' => ['api', 'core', $moduleLower]
                        ],
                        'description' => 'Get all ' . $modulePlural
                    ]
                ],
                [
                    'name' => 'Create ' . $moduleSingular,
                    'request' => [
                        'method' => 'POST',
                        'header' => [
                            [
                                'key' => 'Accept',
                                'value' => 'application/json'
                            ],
                            [
                                'key' => 'Authorization',
                                'value' => 'Bearer {{token}}'
                            ],
                            [
                                'key' => 'Content-Type',
                                'value' => 'application/json'
                            ]
                        ],
                        'url' => [
                            'raw' => '{{base_url}}/api/core/' . $moduleLower,
                            'host' => ['{{base_url}}'],
                            'path' => ['api', 'core', $moduleLower]
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => '{\n    // Add your request body here\n}'
                        ],
                        'description' => 'Create a new ' . $moduleSingular
                    ]
                ],
                [
                    'name' => 'Show ' . $moduleSingular,
                    'request' => [
                        'method' => 'GET',
                        'header' => [
                            [
                                'key' => 'Accept',
                                'value' => 'application/json'
                            ],
                            [
                                'key' => 'Authorization',
                                'value' => 'Bearer {{token}}'
                            ]
                        ],
                        'url' => [
                            'raw' => '{{base_url}}/api/core/' . $moduleLower . '/{{id}}',
                            'host' => ['{{base_url}}'],
                            'path' => ['api', 'core', $moduleLower, '{{id}}']
                        ],
                        'description' => 'Get a specific ' . $moduleSingular
                    ]
                ],
                [
                    'name' => 'Update ' . $moduleSingular,
                    'request' => [
                        'method' => 'PUT',
                        'header' => [
                            [
                                'key' => 'Accept',
                                'value' => 'application/json'
                            ],
                            [
                                'key' => 'Authorization',
                                'value' => 'Bearer {{token}}'
                            ],
                            [
                                'key' => 'Content-Type',
                                'value' => 'application/json'
                            ]
                        ],
                        'url' => [
                            'raw' => '{{base_url}}/api/core/' . $moduleLower . '/{{id}}',
                            'host' => ['{{base_url}}'],
                            'path' => ['api', 'core', $moduleLower, '{{id}}']
                        ],
                        'body' => [
                            'mode' => 'raw',
                            'raw' => '{\n    // Add your request body here\n}'
                        ],
                        'description' => 'Update a specific ' . $moduleSingular
                    ]
                ],
                [
                    'name' => 'Delete ' . $moduleSingular,
                    'request' => [
                        'method' => 'DELETE',
                        'header' => [
                            [
                                'key' => 'Accept',
                                'value' => 'application/json'
                            ],
                            [
                                'key' => 'Authorization',
                                'value' => 'Bearer {{token}}'
                            ]
                        ],
                        'url' => [
                            'raw' => '{{base_url}}/api/core/' . $moduleLower . '/{{id}}',
                            'host' => ['{{base_url}}'],
                            'path' => ['api', 'core', $moduleLower, '{{id}}']
                        ],
                        'description' => 'Delete a specific ' . $moduleSingular
                    ]
                ]
            ]
        ];

        // Add the new folder to the Core folder
        $coreFolder['item'][] = $newFolder;

        // Save the updated collection
        file_put_contents($postmanPath, json_encode($collection, JSON_PRETTY_PRINT));

        $this->info("Postman collection updated with Core {$moduleName} module routes");
    }
} 