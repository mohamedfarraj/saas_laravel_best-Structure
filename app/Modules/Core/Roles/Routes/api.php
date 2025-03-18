<?php

use App\Modules\Core\Roles\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RolesController::class);
