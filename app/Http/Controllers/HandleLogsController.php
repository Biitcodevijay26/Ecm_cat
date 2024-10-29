<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
class HandleLogsController extends Controller
{
    //

 public function index(Request $request) {

    try{
        $logPath = storage_path('logs/laravel-' . date('Y-m-d') . '.log');
        // dd($logPath);
         if (File::exists($logPath)) {
             // Read the contents of the log file
             $logs = File::get($logPath);
         } else {
             $logs = 'Log file not found for today.';
         }
         Log::info('user retrived log & monitor files successfully',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
         return view('admin.logmonitor', compact('logs'));

    }
    catch(Exception $e){
       Log::error('error occured while retriving  log & monitor files',['error'=>$e->getMessage() ,'ip_address' => $request->ip(),
       'user_id' => auth()->user()->email]);


    }
       

    }


}
