<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;
use Symfony\Component\HttpFoundation\Response;

class HandleNoTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        
        if (!Tenant::where('domain', $domain)->exists()) {
            return response()->json([
                'error' => 'Tenant not found',
                'message' => 'The requested tenant does not exist or is not accessible.',
                'domain' => $domain
            ], 404);
        }

        return $next($request);
    }
} 