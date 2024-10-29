<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiGuards
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
        
        if (Auth::guard('web')->check()) {
            Auth::shouldUse('web');
        } elseif (Auth::guard('api')->check()) {
            Auth::shouldUse('api');
        }
        elseif(Auth::guard('admin')->check()){
            Auth::shouldUse('admin');
        }
        return $next($request);
    }
}
