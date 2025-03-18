<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use App\Modules\Core\Permissions\Models\Permissions;
use App\Modules\Core\Roles\Models\Roles;


class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Core Module Permissions
        $this->createModulePermissions('users', [
            'index' => 'View Users',
            'store' => 'Create Users',
            'show' => 'View One Users',
            'update' => 'Edit Users',
            'destroy' => 'Delete Users',
        ]);

        $this->createModulePermissions('roles', [
            'index' => 'View Roles',
            'store' => 'Create Roles',
            'show' => 'View One Roles',
            'update' => 'Edit Roles',
            'destroy' => 'Delete Roles',
        ]);

        $this->createModulePermissions('permissions', [
            'index' => 'View Permissions',
            'store' => 'Create Permissions',
            'show' => 'View One Permissions',
            'update' => 'Edit Permissions',
            'destroy' => 'Delete Permissions',
        ]);

        $this->createModulePermissions('tenants', [
            'index' => 'View Tenants',
            'store' => 'Create Tenants',
            'show' => 'View One Tenants',
            'update' => 'Edit Tenants',
            'destroy' => 'Delete Tenants',
        ]);

        

     




      
        
// Add more core module permissions as needed

        $adminRole = Roles::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permissions::all());
        }
    }

    protected function createModulePermissions($module, array $actions)
    {
        foreach ($actions as $action => $description) {
            Permissions::firstOrCreate(
                ['name' => "{$module}.{$action}"],
                ['display_name' => $description]
            );
        }
    }
} 