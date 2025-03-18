<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpFoundation\Response;

class InitializeSession extends StartSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('session.driver')) {
            config(['session.driver' => 'file']);
        }

        return parent::handle($request, $next);
    }
} 