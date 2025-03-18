<?php

namespace App\Modules\Tenant\Auth\Services;

use App\Modules\Tenant\Users\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Sanctum\HasApiTokens;

class AuthService
{
    public function __construct(protected Users $repository)
    {
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);
        
        $token = $user->createToken('tenant_auth_token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $credentials): array
    {
        // Use tenant connection for authentication
        $user = Users::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            \Log::error('Tenant login failed for email:', [
                'email' => $credentials['email'],
                'user_exists' => Users::where('email', $credentials['email'])->exists()
            ]);
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The provided credentials are incorrect.',
                    'status' => 'error'
                ], 401)
            );
        }
        
        \Log::info('Tenant login successful for user:', ['user_id' => $user->id]);
        
        $token = $user->createToken('tenant_auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function getAuthenticatedUser()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        return Users::with(['roles' => function($query) {
            return $query->with('permissions');
        }])->find($user->id);
    }

    public function logout(): void
    {
        $user = Auth::user();
        if ($user instanceof HasApiTokens) {
            $user->tokens()->delete();
        }
    }
}