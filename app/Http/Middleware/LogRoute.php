<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info("Constant");
        $response = $next($request);
        $adminRole = \Config::get('constants.roles.Master_Admin');
        $user = auth()->user();
        $info = $user->id ?? '';
        $infoName = $user ? $user->first_name . ' ' . $user->last_name : '';
        $infoString = 'USER-ID : '. $info . ' NAME : ' . $infoName . ' ';
        //if (app()->environment('local')) {
            $log = [
                'URI'    => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                //'RESPONSE' => $response->getContent(),
                'ip'         => request()->ip(),
            ];

            Log::info( $infoString . ' ===>>> ' . $request->getUri() . json_encode($log) );
            // Log::info(  $request->getUri() , $log );
        //}

        return $response;
    }
}
