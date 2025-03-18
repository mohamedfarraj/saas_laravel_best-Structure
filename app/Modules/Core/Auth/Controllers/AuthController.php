<?php

namespace App\Modules\Core\Auth\Controllers;


use App\Modules\Core\Auth\Requests\LoginRequest;
use App\Modules\Core\Auth\Requests\RegisterRequest;
use App\Modules\Core\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Modules\Base\Controllers\BaseController;
class AuthController extends BaseController
{
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->service->register($request->validated());
        return $this->respondWithSuccess($data, 'User registered successfully');
    }

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
       
        $data = $this->service->login($request->validated());
        return $this->respondWithSuccess($data, 'Logged in successfully');
    }

    /**
     * Get authenticated user
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
            $user = $this->service->getAuthenticatedUser();
            return $this->respondWithSuccess($user);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->service->logout();
        return $this->respondWithSuccess(null, 'Successfully logged out');
    }
}