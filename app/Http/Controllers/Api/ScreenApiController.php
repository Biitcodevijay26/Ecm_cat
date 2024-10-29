<?php

namespace App\Http\Controllers\Api;

use App\Models\Inverter;
use App\Http\Traits\UserTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;

class ScreenApiController extends Controller
{
    //
    use UserTrait;
    // api for the remote-access-view/65e5f198f6f78502b9021270 inside systemoverview
    public function index(Request $request,$cid='',$id=''){

        $userdata=JwtHelper::getUserData();

        if(!$userdata){
            return response()->json(['message'=>'invalide token pass the token!!']);
        }

        if(!$id)
        {
            $id = $cid;
        }
        $inverter = Inverter::where('_id', $id)->first();
        if (!$inverter) {
            return response()->json(['message' => 'Inverter not found'], 404);
        }
        $isValid = 'true';
    $sessionError = '';
    if ($inverter->channel_id === null && !$inverter->agent_id) {
        $isValid = 'false';
        $sessionError = "Please add Channel and Agent.";
    } else if ($inverter->channel_id === null) {
        $isValid = 'false';
        $sessionError = "Please add Channel.";
    } else if (!$inverter->agent_id) {
        $isValid = 'false';
        $sessionError = "Please add Agent.";
    }

  //error data
    if ($isValid == 'false') {
        $data = [
            'title'           => 'Remote Access',
            'sessionError'    => $sessionError,
            'device_details'  => $inverter,
            'id'              => $id,
        ];
        return response()->json(['message' => 'Validation error', 'data' => $data], 400);
    }

    // If inverter details are valid, proceed to create session
    $channelData = [
        'idChannel' => (int)$inverter->channel_id,
        'idAgent'   => (string)$inverter->agent_id,
    ];

   
    $session = $this->createDwsSession($channelData);
    $session1 = json_decode($session, true);
    $sessionError = '';

    // 
    if ($session1['status'] == true && isset($session1['data']['url'])) {
        // Successful session creation
    } else if (isset($session1['data']['message'])) {
        $sessionError = $session1['data']['message'];
    } else {
        return response()->json(['message' => 'Session creation failed', 'session_data' => $session], 500);
    }

    
    $ip = $inverter->ip;
    $port = $inverter->port;
    $vncport = $inverter->client_vnc_port;

    $data = [
        'title'          => 'Remote Access',
        'port'           => $port,
        'url'            => $session1['data']['url'] ?? '',
        'sessionError'   => $sessionError,
        'device_details' => $inverter,
        'id'             => $id,
    ];

    // Return data as JSON
    return response()->json(['message' => 'Data sent successfully', 'data' => $data], 200);
}
}
