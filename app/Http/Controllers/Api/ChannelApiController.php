<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserTrait;
use App\Models\Company;
use App\Models\CompanyAgent;
use App\Models\CompanyAgentDetail;
use App\Models\CompanyChannel;
use App\Models\Device;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\JwtHelper;

class ChannelApiController extends Controller
{
    use UserTrait;
    //
    public function list(Request $request){
    	$company_list = Company::where('status',1)->get();
        $data = [
            'heading'     => 'Channel',
            'title'       => 'Home',
            'getCompany'  => $company_list
        ];
    	//return view('channel/list',$data);
        return response()->json($data);
    }

    // get list channel 
    public function getlistChannels(Request $request){
        $userdata=JwtHelper::getUserData();
        $userId = $userdata['user_id'] ?? null;

        // Check for valid token and user
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Invalid token.'
            ], 401);
        }
        $record         = CompanyChannel::with(['company'])->select('*');
        $seacrh_name    = $request->get('seacrh_name');

        $record->when($seacrh_name, function ($query, $seacrh_name) {
            $query->where('company_id', $seacrh_name);
        });
        $data = $record->get();
      $getchannellist  = Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $clientCount =  0;
            $actionBtn = '';
            $actionBtn .= '<a href="/channel-assign/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to Assign"><i><i class="fa fa-check-square" aria-hidden="true"></i></a>';
            // $actionBtn .= '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to view"><i><i class="fe fe-eye" aria-hidden="true"></i></a>';
            if($row->status == 1){
                // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
            } else {
                // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
            }
            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger deleteChannel" data-id="'.$row->id.'" title="Click to Delete"><i class="fa fa-trash"></i></a>';
            return $actionBtn;
        })
        ->addColumn('company_name', function($row){
            if(isset($row->company) &&  $row->company->company_name){
                return  $row->company->company_name;
            } else {
                return  '';
            }
        })
        ->addColumn('channel_ids', function($row){
            if(isset($row->channels_basic_added_ids) &&  $row->channels_basic_added_ids){
                return $row->channels_basic_added_ids;
            } else {
                return 0;
            }
        })
        ->addColumn('assign_counts', function($row){
            return getAssignCounts($row->company_id,$row->channels_basic_added_ids);
        })
        ->addColumn('status', function($row){
            if($row->status == 1){
                return  '<span class="badge bg-success">Active</span>';
            } else {
                return  '<span class="badge bg-danger">Inactive</span>';
            }
        })
        ->addColumn('created_at', function($row){
            $startDate = date_create($row->created_at);
            return date_format($startDate,"Y-m-d H:m A");
        })
        ->rawColumns(['action','status'])
        ->make(true);

     return response()->json([$getchannellist]);

     return response()->json([
        'status' => 'error',
        'message' => 'Invalid request type. This endpoint accepts only AJAX requests.'
    ], 400);
    }

    // add new record
    public function channelAdd(Request $request) {
        $company_list = Company::where('status',1)->get();
        $data = [
            'heading'     => 'Add Channel',
            'module'      => 'Channel',
            'getCompany'  => $company_list
        ];
    	//return view('channel/add_edit_channel',$data);
        return response()->json($data);
    }

    // edit records
    public function channelEdit(Request $request, $id) {
        $dataOne = CompanyChannel::where('_id',$id)->first();
        if(!$dataOne){
            abort(404);
        }
        $company_list = Company::where('status',1)->get();
        $data = [
            'heading'     => 'Edit Channel',
            'module'      => 'Channel',
            'getCompany'  => $company_list,
            'data'        => $dataOne
        ];
    //	return view('channel/add_edit_channel',$data);
    return response()->json([$data]);
    }

    // save the records 
    public function save(Request $request){
        $validator=  $request->validate([
            'company_name'=>'required',
            'channel'=>'required'
         ]);

         if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'response_msg' => $validator->errors()->first()
            ], 400);
        }
  try {
        if ($request->has('id') && $request->id) {
            // Edit Section: Update an existing company agent record
            $record = CompanyChannel::where('_id', $request->id)->first();
            if ($record) {
                $record->company_id         = $request->company_name ?? ''; 
                $record->number_of_channel  = $request->channel ?? '';
                $record->cost_json_data     = $request->channel ?? '';

                if ($record->save()) {
                    return response()->json(['status' => 'true'], 200);
                } else {
                    return response()->json(['status' => 'false', 'response_msg' => 'Unable to update record.'], 500);
                }
            } else {
                return response()->json(['status' => 'false', 'response_msg' => 'Record not found.'], 404);
            }

        }
        else {
            // CALL API Add Single Curl
            $data_single_curl = [
                'agents'        => 0,
                'channelsBasic' => (int)$request->channel
            ];
            $res_api_data = $this->addDwsServices($data_single_curl);
            $res_api_data = json_decode($res_api_data,true);

            if($res_api_data['status'] == '200'){
                // Add Section
                $res_data = json_decode($res_api_data['data'],true);
                if(gettype($res_data['channelsBasicAddedIDs']) == 'array')
                {
                    foreach ($res_data['channelsBasicAddedIDs'] as $key => $value) {

                        $mytime   = Carbon::now();
                        $now_time = $mytime->toDateTimeString();
                        $record   =  new CompanyChannel();
                        $record->company_id         = $request->company_name ?? '';
                        $record->number_of_channel  = $request->channel ?? '';
                        $record->cost_json_data             = json_encode($res_data['cost']) ?? '';
                        $record->channels_basic_added_ids   = $value ?? '';
                        $record->status       = 1;
                        $record->created_at   = $now_time;
                        $record->save();
                    }
                } else {
                    $mytime   = Carbon::now();
                    $now_time = $mytime->toDateTimeString();
                    $record   =  new CompanyChannel();
                    $record->company_id         = $request->company_name ?? '';
                    $record->number_of_channel  = $request->channel ?? '';
                    $record->cost_json_data             = json_encode($res_data['cost']) ?? '';
                    $record->channels_basic_added_ids   = $res_data['channelsBasicAddedIDs'] ?? '';
                    $record->status       = 1;
                    $record->created_at   = $now_time;
                    $record->save();
                }
                return response()->json(['status' => 'true']);
            } else {
                return response()->json(['status' => 'false','response_msg' => $res_api_data['message']]);
            }
        
    }
}
     catch (\Exception $e) {
        return response()->json(['status' => 'false', 'response_msg' => $e->getMessage()], 500);
    }
    }

    // channel assign page
     public function channelAssign(Request $request,$id){
        $dataOne = CompanyChannel::with('company')->where('_id',$id)->first();
        if(!$dataOne){
           // abort(404);
           return response()->json(['status' => 'false','response_msg' => 'No Data Found']);
        }
        $company_list = Company::where('status',1)->get();
        $data = [
            'heading'     => 'Channel Assign',
            'module'      => 'Channel',
            'getCompany'  => $company_list,
            'data'        => $dataOne,
            'company_id'  => $dataOne->company_id,
            'channel_id'  => $dataOne->channels_basic_added_ids
        ];
        return response()->json(['data'=>$data]);

     }
// channel assign page device list table 
public function getAssignDeviceList(Request $request){
     // $company_id=6442771eaa6eb38964069033;
    
   // if ($request->ajax()) {

        $record  = Device::where('company_id', $request->company_id)
            ->where(function ($query) use ($request) {
                $query->where('channel_id', $request->channel_id)
                      ->orWhereNull('channel_id');
            })->select('*');
        $seacrh_name    = $request->get('seacrh_name');

        $record->when($seacrh_name, function ($query, $seacrh_name) {
            $query->where('name', 'like', "%{$seacrh_name}%");
        });

        $data = $record->get();

        $devicelist= Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $clientCount =  0; 
            $actionBtn = '';
            $actionBtn .= '<a href="/channel-assign/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2 " title="Click to Assign"><i><i class="fa fa-check-square" aria-hidden="true"></i></a>';
            // $actionBtn .= '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to view"><i><i class="fe fe-eye" aria-hidden="true"></i></a>';
            if($row->status == 1){
                // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
            } else {
                // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
            }
            return $actionBtn;
        })
        ->addColumn('assign_chkbox', function ($row) {
            if($row->channel_id === null){
                return ' <label class="colorinput"><input type="checkbox" value="'.$row->id.'" class="colorinput-input channel_chk_box" ><span class="colorinput-color bg-dark"></span></label>';
            } else {
                return ' <label class="colorinput"><input type="checkbox" value="'.$row->id.'" class="colorinput-input channel_chk_box" checked><span class="colorinput-color bg-dark"></span></label>';
            }
        })

        ->addColumn('created_at', function($row){
            $startDate = date_create($row->created_at);
            return date_format($startDate,"Y-m-d H:m A");
        })
        ->rawColumns(['action','assign_chkbox'])
        ->make(true);
      return response()->json([$devicelist]);
//}

}

// update the checkbox 

public function updateChannelIntoDevice(Request $request){

    if ($request->has('device_id') && $request->device_id) {
        $device = Device::where('_id',$request->device_id)->first();

        if(isset($device->channel_id)){
            $device->channel_id = null;
        } else {
            $device->channel_id = $request->channel_id;
        }
        if($device->save()){
            return response()->json(['status' => 'true']);
        } else {
            return response()->json(['status' => 'false']);
        }
    }
}


//  delete the channel 
public function removeServicesChannel(Request $request){
  
    $userdata=JwtHelper::getUserData();
    if(!$userdata){
        return response()->json(['status' => 'false','message'=>'unauthorized data']);
    }

    if ($request->has('id') && $request->id) {
        $com_channel = CompanyChannel::where('_id', $request->id)->first();
        if ($com_channel) {
   // Prepare data for API call
   $data_curl = [
    'agents'      => 0,
    'channelsIDs' => [$com_channel->channels_basic_added_ids]
         ];
        $res_api_data = $this->removeServicesChannelAPI($data_curl);
        $res_api_data = json_decode($res_api_data, true);
        if ($res_api_data['status'] == '200') {

            // Find devices associated with this channel and update them
            $devices = Device::where('channel_id', (string) $com_channel->channels_basic_added_ids)->get();
            if ($devices && count($devices) > 0) {
                foreach ($devices as $device) {
                    $device->channel_id = null;
                    $device->save();
                }
            }
            $com_channel->delete();
            return response()->json(['status' => 'true']);
        } else {
            // Handle API error response
            return response()->json(['status' => 'false', 'response_msg' => $res_api_data['message']]);
        }
    } else {
        // If no channel found, return true 
        return response()->json(['status' => 'true']);
    }
} else {
    // If 'id' is not provided in the request
    return response()->json(['status' => 'false', 'message' => 'Channel ID is required']);
}

}
}