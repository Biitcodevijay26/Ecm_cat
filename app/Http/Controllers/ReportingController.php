<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportingController extends Controller
{
    //
    public function showreport(Request $request) {
      try{
        Log::info('reporting page  accessed by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

        return view('reporting.showreporting');
      }
      catch (\Exception $e) {
        Log::error('Error in reporting page', ['exception' => $e]);
      }
        /*$uri = $request->path();
        echo '<br>URI: '.$uri;
        
        $url = $request->url();
        echo '<br>';
        
        echo 'URL: '.$url;
        $method = $request->method();
        echo '<br>';
        
        echo 'Method: '.$method;*/
    
     }
}
