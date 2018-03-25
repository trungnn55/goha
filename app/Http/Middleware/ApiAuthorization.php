<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');

        if ($token && $token === config('app.header_api_token')) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid Authorization token'
        ]);
    }
}
