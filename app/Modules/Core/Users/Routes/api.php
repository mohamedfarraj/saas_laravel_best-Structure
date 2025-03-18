<?php

use App\Modules\Core\Users\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UsersController::class);
