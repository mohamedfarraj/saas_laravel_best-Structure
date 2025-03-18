<?php

use App\Modules\Tenant\Users\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UsersController::class);
