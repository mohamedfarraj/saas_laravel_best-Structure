<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Tenant\Users\Models\Users;
use App\Core\Helpers\ResponseHelper;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        $authUser = Auth::user();
        $permission = $request->route()->getName();
        $user = Users::findOrFail($authUser->id);
        $userRole = $user->roles->first();
        $userPermissions = $userRole->permissions;

        if (!$user) {
            return ResponseHelper::error(
                'Unauthorized',
                401
            );
        }

        if (!$userPermissions->contains('name', $permission)) {
            return ResponseHelper::error(
                'You are not authorized to access this resource',
                403
            );
        }

        return $next($request);
    }
} 