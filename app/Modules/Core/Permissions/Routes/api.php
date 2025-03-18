<?php

use App\Modules\Core\Permissions\Controllers\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('permissions', PermissionsController::class);
