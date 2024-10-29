<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Log;

class ChannelController extends Controller
{
    use UserTrait;

    // Agent List
    public function list(Request $request){
        try{
            $company_list = Company::where('status',1)->get();
            $data = [
                'heading'     => 'Channel',
                'title'       => 'Home',
                'getCompany'  => $company_list
            ];
            Log::info('channel list page retrived by user successfully',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('channel/list',$data); 
        }
        catch(Exception  $e){
            Log::error('channel list page error',['error'=>$e->getMessage()]);
        }

    	
    }

    public function getChannelList(Request $request){
        if ($request->ajax()) {

            $record         = CompanyChannel::with(['company'])->select('*');
            $seacrh_name    = $request->get('seacrh_name');

            $record->when($seacrh_name, function ($query, $seacrh_name) {
                $query->where('company_id', $seacrh_name);
            });

            $data = $record->get();

            return Datatables::of($data)
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

        }
    }

    public function channelAdd(Request $request) {
        try{
            $company_list = Company::where('status',1)->get();
            $data = [
                'heading'     => 'Add Channel',
                'module'      => 'Channel',
                'getCompany'  => $company_list
            ];
            Log::info('channel add page retrived by user succssfully ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
    
            return view('channel/add_edit_channel',$data);
        }
        catch(Exception  $e){
            Log::error('error occured in channel add',$e->getMessage());

        }
    }

    public function channelEdit(Request $request,$id = '') {
        try{
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
            Log::info('edit channel  page retrived by user succssfully ',['ip_address' => $request->ip(),

                'user_id' => auth()->user()->email]);
            return view('channel/add_edit_channel',$data);
        }
        catch(Exception $e){
            Log::error('error occured in channel edit',$e->getMessage());
        }
       
    }

    public function save(Request $request){
        try{
            if($request->has('company_name') && $request->company_name && $request->has('channel') && $request->channel){
                if($request->has('id') && $request->id){
                    // Edit Section
                    $record = CompanyAgent::where('_id',$request->id)->first();
                    $record->company_id         = $request->company_name ?? ''; 
                    $record->number_of_channel  = $request->channel ?? '';
                    $record->cost_json_data     = $request->channel ?? '';
    
                    // if($record->save()){
                    //     return response()->json(['status' => 'true']);
                    // } else {
                    //     return response()->json(['status' => 'false']);
                    // }
                    return response()->json(['status' => 'false']);
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
                        Log::info('the channel record  save  is successful by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
    
                        return response()->json(['status' => 'true']);
    
                    } else {
                        Log::info('the channel record  save  is failed', ['response_msg'=>$res_api_data]);
    
                        return response()->json(['status' => 'false','response_msg' => $res_api_data['message']]);
                    }
    
                }
            }
        }
        catch(Exception $e){
            Log::error('the channel record  save  is failed', ['response_msg'=>$e->getMessage()]);
        }
    
    }

    public function activeInactiveChannel(Request $request){
        $record = CompanyAgent::where('_id',$request->id)->first();
		if($record){

			$status = 1;
			if($record->status == 1){
				$status = 0;
			} else {
				$status = 1;
			}

			$record->status = $status;
			$record->save();

			return '1';
		} else {
			return '0';
		}
    }

    public function channelAssign(Request $request,$id = ''){
        try{
            $dataOne = CompanyChannel::with('company')->where('_id',$id)->first();
            if(!$dataOne){
                abort(404);
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
            Log::info('channel view page retrived succssfully by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('channel/view_channel_assign',$data);
        }
        catch(Exception $e){
            Log::error('the channel assign  is failed', ['response_msg'=>$e->getMessage()]);
        }
        
    }

    public function getAssignDeviceList(Request $request){
        if ($request->ajax()) {

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

            return Datatables::of($data)
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

        }
    }

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

    public function removeServicesChannel(Request $request){
        if($request->has('id') && $request->id){
            $com_channel = CompanyChannel::where('_id',$request->id)->first();
            if($com_channel){
 
                // CALL API Remove Service
                $data_curl = [
                   'agents'      => 0,
                   'channelsIDs' => [$com_channel->channels_basic_added_ids]
               ];
               $res_api_data = $this->removeServicesChannelAPI($data_curl);
               $res_api_data = json_decode($res_api_data,true);
               if($res_api_data['status'] == '200'){


                $devices = Device::where('channel_id',(string)$com_channel->channels_basic_added_ids)->get();
                if($devices && count($devices) > 0){
                    foreach ($devices as $key => $value) {
                        $value->channel_id = null;
                        $value->save();
                    }
                }
                // Remove channel
                $com_channel->delete();
                return response()->json(['status' => 'true']);
               } else {
                    return response()->json(['status' => 'false','response_msg' => $res_api_data['message']]);
               }
            } else {
                return response()->json(['status' => 'true']);
            }
        }
    }
}


