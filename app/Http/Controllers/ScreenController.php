<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inverter;
use Carbon\Carbon;
use App\Http\Traits\UserTrait;
use Illuminate\Support\Facades\Log;

class ScreenController extends Controller
{
    use UserTrait;
    public function index(Request $request,$cid='',$id='')
    {
        try{
            if(!$id)
            {
                $id = $cid;
            }
            $inverter = Inverter::where('_id',$id)->first();
            if(!$inverter){
                return redirect()->back();
            }
            $isValid      = 'true';
            $sessionError = '';
            if($inverter->channel_id === null && !$inverter->agent_id){
                $isValid = 'false';
                $sessionError = "Please add Channel and Agent.";
            }
            else if($inverter->channel_id === null){
                $isValid = 'false';
                $sessionError = "Please add Channel.";
            }
            else if(!$inverter->agent_id){
                $isValid = 'false';
                $sessionError = "Please add Agent.";
            }
    
            if($isValid == 'false'){
                $data = [
                    'title'           => 'Remote Access',
                    'sessionError'    => $sessionError,
                    'device_details'  => $inverter,
                    'id'              => $id,
    
                ];
                Log::info('Screen of dws services retrived by user ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return view('dwservice.dwservice_error', $data);
            } else{
    
                $channelData = [
                    'idChannel' => (int)$inverter->channel_id,
                    'idAgent'   => (string)$inverter->agent_id,
                ];
    
                $session = $this->createDwsSession($channelData);
                $session1 = json_decode($session,true);
                $sessionError = '';
                if($session1['status'] == true && isset($session1['data']['url']) ){
    
                } else if(isset($session1['data']['message'])){
                    $sessionError = $session1['data']['message'];
                } else {
                    dd($session);
                }
    
                $ip = $inverter->ip;
                $port = $inverter->port;
                $vncport = $inverter->client_vnc_port;
    
                $data = [
                    'title' => 'Remote Access',
                    'port'  => $port,
                    'url'   => $session1['data']['url'] ?? '',
                    'sessionError'    => $sessionError,
                    'device_details'  => $inverter,
                    'id'              => $id,
                ];
                Log::info('Screen of dws services  retrived by user ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return view('dwservice.dwservice', $data);
            }
        }
        catch(Exception  $e){
            Log::error('Error in dws services',$e->getMessage());
        }

        

    }
    public function index1(Request $request,$cid='',$id='')
    {
      try{
        if(!$id)
        {
            $id = $cid;
        }
        $inverter = Inverter::where('_id',$id)->first();
        if(!$inverter){
            return redirect()->back();
        }

        $ip = $inverter->ip;
        $port = $inverter->port;
        $vncport = $inverter->client_vnc_port;

        // disconnect on given port
        $cmd0 = '/usr/bin/sudo kill -9 `sudo lsof -t -i:'.$port.'` > /dev/null 2>&1 &';
        $output = exec($cmd0);

        // Start VNC
        $cmd1 = '/usr/bin/sudo -S novnc --listen '.$port.' --vnc '.$ip.':'.$vncport.' > /dev/null 2>&1 &';
        $output = exec($cmd1);

        //dd($output);
        saveLogs('remote-access-view-page');

        $data = [
            'title' => 'Remote Access',
            'server_ip' => '3.108.245.156',
            'port' => $port,
        ];
        Log::info('the  user is trying to access the remote access page', ['ip_address' => $request->ip(),
        'user_id' => auth()->user()->email]);

        return view('novnc.vnc1', $data);
      }
      catch(Exception   $e){
        Log::error('Error in index1',$e->getMessage());
      }

        
    }

    public function dwserviceCreateAgent(){
        $create = $this->createDwsAgent([]);
        dd($create);
    }
    public function dwservice($cid='',$id='')
    {
        try{
        $session = $this->createDwsSession([]);
        $session1 = json_decode($session,true);
        $sessionError = '';
        if($session1['status'] == true && isset($session1['data']['url']) ){

        } else if(isset($session1['data']['message'])){
            $sessionError = $session1['data']['message'];
        } else {
            dd($session);
        }
        if(!$id)
        {
            $id = $cid;
        }
        $id='64e601b79a6a3c8fc400e403';
        $inverter = Inverter::where('_id',$id)->first();
        if(!$inverter){
            return redirect()->back();
        }

        $ip = $inverter->ip;
        $port = $inverter->port;
        $vncport = $inverter->client_vnc_port;


        $data = [
            'title' => 'Remote Access',
            'port' => $port,
            'url' => $session1['data']['url'] ?? '',
            'sessionError' => $sessionError
        ];
        Log::info('Screen of dws services',$data);
        return view('dwservice.dwservice', $data);
    }
    catch(Exception  $e){
        Log::error('Error in dwservice',$e->getMessage());
    }

}}
