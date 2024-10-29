<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;

class CheckModuleAccessAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ... $permissions)
    {
        // Check if is_module_access is set to true in the session
        if (Session::get('is_module_access_agent') === 'true') {
            return $next($request);
        }
        abort(403,'THIS ACTION IS UNAUTHORIZED.');
    }
}
