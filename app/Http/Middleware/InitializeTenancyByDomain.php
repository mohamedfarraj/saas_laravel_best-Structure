<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Multitenancy\Http\Middleware\NeedsTenant;

class InitializeTenancyByDomain extends NeedsTenant
{
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next);
    }
} 