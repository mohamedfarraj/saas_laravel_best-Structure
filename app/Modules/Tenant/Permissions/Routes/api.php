<?php

use App\Modules\Tenant\Permissions\Controllers\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('permissions', PermissionsController::class);
