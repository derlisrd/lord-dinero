<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    protected function render($request, Closure $next, ...$guards)
    {

    }
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return response()->json([
                'success'=>false,
                'message'=>'No autenticated'
            ],401);
            //return route('login');
        }
    }
}
