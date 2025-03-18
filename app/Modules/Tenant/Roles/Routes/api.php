<?php

use App\Modules\Tenant\Roles\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RolesController::class);
