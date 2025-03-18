<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpFoundation\Response;

class ApiSession extends StartSession
{
    public function handle($request, Closure $next)
    {
        config(['session.driver' => 'file']);
        return parent::handle($request, $next);
    }
} 