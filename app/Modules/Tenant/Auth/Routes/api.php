<?php

use App\Modules\Tenant\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register'])->name('tenant.auth.register');
Route::post('auth/login', [AuthController::class, 'login'])->name('tenant.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me'])->name('tenant.auth.me');
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('tenant.auth.logout');
});
