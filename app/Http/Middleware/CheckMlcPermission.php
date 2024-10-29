<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckMlcPermission
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
        if (!Auth::guard('admin')->check()) {
            return redirect('/login');
        }

        $superAdmin = \Config::get('constants.roles.Master_Admin');
        $user = auth()->guard('admin')->user();

        if (auth()->guard('admin')->user()->role_id != $superAdmin) {
            return redirect('/login');
        }


        // \Log::info(auth()->guard('admin')->user()->id);
        /*if ( $user->hasAnyRole(['Admin']) ) {
            return $next($request);
        }*/
        // if( isSuperAdmin()){

            return $next($request);
        // }

        /*foreach($permissions as $permission) {

            if($user->hasRolePermission($permission))
                return $next($request);
        }*/
        /*if($user->hasRolePermission($permissions)){
            return $next($request);
        }*/


        abort(403,'You do not have permission to access this page.');
       // return $next($request);
    }
}
