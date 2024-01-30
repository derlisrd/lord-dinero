<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('x-api-key');

        if ( !$key || $key !== env('X_API_KEY')) {
            return response()->json([
                'success'=>false,
                'message'=>'There is not x-api-key'
            ],401);
        }

        return $next($request);
    }
}
