<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiSession;
use App\Http\Middleware\HandleNoTenant;
use App\Models\Tenant;
use App\Http\Middleware\Tenant\CheckPermission as TenantCheckPermission;
use App\Http\Middleware\Core\CheckPermission as CoreCheckPermission;

// Welcome route
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'tenants' => Tenant::all(['id', 'name', 'domain'])
    ]);
});

// Public routes
Route::middleware(['api', ApiSession::class])->group(function () {
    $modules = glob(app_path('Modules/Core/*'), GLOB_ONLYDIR);
    foreach ($modules as $module) {
        $moduleName = basename($module);
        
        // Load API routes
    
        if (file_exists($module . '/Routes/api.php')) {
            if ($moduleName !== 'Auth') {
                Route::middleware(['api', 'auth:sanctum',CoreCheckPermission::class])
                    ->group($module . '/Routes/api.php');
            } else {
                Route::middleware(['api'])
                    ->group($module . '/Routes/api.php');
            }
        }
    }
    
});

// Tenant routes
Route::middleware(['api', ApiSession::class, HandleNoTenant::class, 'tenant'])->prefix('tenant')->group(function() {
    Route::get('/', function (Request $request) {
        return response()->json([
            'message' => 'Current tenant information',
            'tenant' => app('currentTenant')
        ]);
    });

    $modulesTenant = glob(app_path('Modules/Tenant/*'), GLOB_ONLYDIR);
    foreach ($modulesTenant as $module) {
        $moduleName = basename($module);
        if (file_exists($module . '/Routes/api.php')) {
            if ($moduleName !== 'Auth') {
                Route::middleware(['api', 'auth:sanctum',TenantCheckPermission::class])
                    ->group($module . '/Routes/api.php');
            } else {
                Route::middleware(['api'])
                    ->group($module . '/Routes/api.php');
            }
        }
    }
});
