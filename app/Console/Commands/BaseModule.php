<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class BaseModule extends Command
{
    protected $moduleDirectories = [
        'Controllers',
        'Models',
        'Services',
        'Repositories',
        'Routes',
        'Requests',
    ];

    protected function makeDirectory($path)
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    protected function createModuleStructure($path, $name)
    {
        // Create directories
        foreach ($this->moduleDirectories as $dir) {
            $this->makeDirectory($path . '/' . $dir);
        }

        // Create files from stubs
        $this->createFileFromStub($path, 'Controllers/' . $name . 'Controller.php', 'controller', $name, $this->getModuleType());
        $this->createFileFromStub($path, 'Services/' . $name . 'Service.php', 'service', $name, $this->getModuleType());
        $this->createFileFromStub($path, 'Repositories/' . $name . 'Repository.php', 'repository', $name, $this->getModuleType());
        $this->createFileFromStub($path, 'Models/' . $name . '.php', 'model', $name, $this->getModuleType());
        $this->createFileFromStub($path, 'Routes/api.php', 'api', $name, $this->getModuleType());
        
        // Create Request files
        $this->createFileFromStub($path, 'Requests/Store' . $name . 'Request.php', 'request', $name, 'Store');
        $this->createFileFromStub($path, 'Requests/Update' . $name . 'Request.php', 'request', $name, 'Update');
    }

    protected function createFileFromStub($path, $file, $stub, $module, $type = '')
    {
        $stubContent = File::get(__DIR__ . '/stubs/' . $stub . '.stub');
        
        $content = str_replace(
            ['{{MODULE}}', '{{MODULE_LOWER}}', '{{TYPE}}', '{{TABLE}}'],
            [$module, Str::lower($module), $type, Str::plural(Str::snake($module))],
            $stubContent
        );

        File::put($path . '/' . $file, $content);
    }

    protected function createMigration($name)
    {
        $table = Str::plural(Str::snake($name));
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . "_create_{$table}_table.php";

        $this->createFileFromStub(
            database_path('migrations/' . strtolower($this->getModuleType())),
            $filename,
            'migration',
            $name
        );
    }

    protected function generatePostmanCollection($name)
    {
        $collectionPath = base_path('postman/' . strtolower($this->getModuleType()) . '.json');
        $this->makeDirectory(dirname($collectionPath));

        $collection = [];
        if (File::exists($collectionPath)) {
            $collection = json_decode(File::get($collectionPath), true);
        }

        if (!isset($collection['info'])) {
            $collection['info'] = [
                'name' => 'Trases API',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json'
            ];
        }

        if (!isset($collection['item'])) {
            $collection['item'] = [];
        }

        if (!isset($collection['variable'])) {
            $collection['variable'] = [
                [
                    'key' => 'base_url',
                    'value' => 'http://localhost'
                ],
                [
                    'key' => 'token',
                    'value' => 'YOUR_AUTH_TOKEN'
                ],
                [
                    'key' => 'id',
                    'value' => '1'
                ]
            ];
        }

        // Create folder for the module
        $moduleFolder = [
            'name' => $name,
            'item' => $this->generatePostmanRequests($name)
        ];

        // Check if folder already exists
        $folderExists = false;
        foreach ($collection['item'] as &$item) {
            if (isset($item['name']) && $item['name'] === $name) {
                $folderExists = true;
                break;
            }
        }

        if (!$folderExists) {
            $collection['item'][] = $moduleFolder;
        }

        File::put($collectionPath, json_encode($collection, JSON_PRETTY_PRINT));
    }

    protected function generatePostmanRequests($name)
    {
        $baseUrl = $this->getModuleType() === 'Core' ? 'api' : 'api/tenant';
        $nameLower = Str::lower($name);

        return [
            [
                'name' => 'Get All',
                'request' => [
                    'method' => 'GET',
                    'url' => [
                        'raw' => '{{base_url}}/' . $baseUrl . '/' . $nameLower,
                        'host' => ['{{base_url}}'],
                        'path' => explode('/', $baseUrl . '/' . $nameLower)
                    ],
                    'header' => [
                        [
                            'key' => 'Accept',
                            'value' => 'application/json'
                        ],
                        [
                            'key' => 'Authorization',
                            'value' => 'Bearer {{token}}'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Get Single',
                'request' => [
                    'method' => 'GET',
                    'url' => [
                        'raw' => '{{base_url}}/' . $baseUrl . '/' . $nameLower . '/{{id}}',
                        'host' => ['{{base_url}}'],
                        'path' => explode('/', $baseUrl . '/' . $nameLower . '/{{id}}')
                    ],
                    'header' => [
                        [
                            'key' => 'Accept',
                            'value' => 'application/json'
                        ],
                        [
                            'key' => 'Authorization',
                            'value' => 'Bearer {{token}}'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Create',
                'request' => [
                    'method' => 'POST',
                    'url' => [
                        'raw' => '{{base_url}}/' . $baseUrl . '/' . $nameLower,
                        'host' => ['{{base_url}}'],
                        'path' => explode('/', $baseUrl . '/' . $nameLower)
                    ],
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
                    'body' => [
                        'mode' => 'raw',
                        'raw' => json_encode([
                            'name' => 'Test ' . $name,
                            'is_active' => true
                        ]),
                        'options' => [
                            'raw' => [
                                'language' => 'json'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Update',
                'request' => [
                    'method' => 'PUT',
                    'url' => [
                        'raw' => '{{base_url}}/' . $baseUrl . '/' . $nameLower . '/{{id}}',
                        'host' => ['{{base_url}}'],
                        'path' => explode('/', $baseUrl . '/' . $nameLower . '/{{id}}')
                    ],
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
                    'body' => [
                        'mode' => 'raw',
                        'raw' => json_encode([
                            'name' => 'Updated ' . $name,
                            'is_active' => true
                        ]),
                        'options' => [
                            'raw' => [
                                'language' => 'json'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Delete',
                'request' => [
                    'method' => 'DELETE',
                    'url' => [
                        'raw' => '{{base_url}}/' . $baseUrl . '/' . $nameLower . '/{{id}}',
                        'host' => ['{{base_url}}'],
                        'path' => explode('/', $baseUrl . '/' . $nameLower . '/{{id}}')
                    ],
                    'header' => [
                        [
                            'key' => 'Accept',
                            'value' => 'application/json'
                        ],
                        [
                            'key' => 'Authorization',
                            'value' => 'Bearer {{token}}'
                        ]
                    ]
                ]
            ]
        ];
    }

    abstract protected function getModuleType();
} 