<?php

namespace App\Modules\Core\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Exceptions\HttpResponseException;


class AuthService
{
    public function __construct(protected User $repository)
    {
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            \Log::error('Login failed for email:', [
                'email' => $credentials['email'],
                'user_exists' => User::where('email', $credentials['email'])->exists()
            ]);
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The provided credentials are incorrect.',
                    'status' => 'error'
                ], 401)
            );
        }
        
        \Log::info('Login successful for user:', ['user_id' => Auth::id()]);
        $user = Auth::user();
        
        $token = $user->createToken('auth_token')->plainTextToken;

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
       
        
        return User::with(['roles' => function($query) {
            return $query->with('permissions');
        }])->find($user->id);
    }

    public function logout(): void
    {
        $user = Auth::user();
        if ($user instanceof \Laravel\Sanctum\HasApiTokens) {
            $user->tokens()->delete();
        }
    }
}