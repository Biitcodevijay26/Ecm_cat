<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Jobs\RemoveDeviceJob;
use App\Models\Cluster;
use App\Models\CompanyAgentDetail;
use App\Models\DailyActivity;
use App\Models\Data;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use App\Models\Error;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inverter;
use App\Models\Warning;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use LDAP\Result;
use \DataTables;
use DateTimeZone;
use Illuminate\Support\Facades\Log; 

class SyetemOverviewController extends Controller
{
    use UserTrait;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function list(Request $request ,$id = '')
    {
     try{
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }

        if(auth()->guard('admin')->user()->role_id == $adminRoleId){
            if(isCompanyLogin() == 'false' && $id){
                companyLoginByAdmin($id);
            }
        }

        $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
        $data = [
            'title'        => 'Home',
            'heading'      => "System Overview",
            'device_list'  => Device::where('status',1)->get(),
            'card_colors'  => $colors,
            'company_id'   => $id,
        ];
        Log::info('the Systemoverview page is retrived successfully by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return view('system_overview.list', $data);
     }
     catch(Expection  $e){
        Log::error('error occured in Systemoverview page',$e->getMessage());
     }
       
    }

    public function addCluster(Request $request)
    {
        try{
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
            {
                abort(404);
            }
    
            $data = [
                'title'    => 'Home',
                'module'   => "System Overview",
                'heading'  => "Add Group",
            ];
            Log::info('add cluster page retrived by user successflly',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('system_overview.add_edit_cluster', $data);
        }
        catch(Expection   $e){
            Log::error('error occured in add cluster',$e->getMessage());
        }

       
    }

    public function addDevice(Request  $request)

    {
        // if (Gate::allows('DeviceManagementAdd')) {

        // } else {
        //     abort(403,'THIS ACTION IS UNAUTHORIZED.');
        // }
    try{
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }
        $company_login_id = session()->get('company_login_id');
        if($company_login_id)
        {
            $company_id = $company_login_id;
        } else {
            $company_id = auth()->guard('admin')->user()->company_id;
        }
        $data = [
            'title'     => 'Home',
            'module'    => "System Overview",
            'heading'   => "Add POWRBANK ",
            'clusters'  => Cluster::where(['status' => 1,'company_id' => $company_id])->get(),
        ];
        Log::info('add device page is retrived by user successfully',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

        return view('system_overview.add_edit_device', $data);
    }
    catch(Expection $e){
        Log::error('error occured in add device',$e->getMessage());
    }
        
    }
    public function editDevice( Request $request,$id='',$deviceId='')
    {
        try{
            if($deviceId)
            {
                $id = $deviceId;
            }
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
            {
                abort(404);
            }
    
            $dataOne = Device::where('_id', $id)->first();
            if(!$dataOne)
            {
                abort(404);
            }
    
            $company_login_id = session()->get('company_login_id');
            if($company_login_id)
            {
                $company_id = $company_login_id;
            } else if (auth()->guard('admin')->user()->role_id == $adminRoleId) {
                $company_id = $dataOne->company_id;
            } else {
                $company_id = auth()->guard('admin')->user()->company_id;
            }
            $data = [
                'title'     => 'Home',
                'module'    => "System Overview",
                'heading'   => "POWRBANK",
                'clusters'  => Cluster::where(['status' => 1,'company_id' => $company_id])->get(),
                'data'      => $dataOne,
            ];
            Log::info('edit device page retrived succssfully by user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('system_overview.add_edit_device', $data);
        }
       catch(Expection  $e){
        Log::error('error occured in edit device',$e->getMessage());
       }

    }

    public function saveCluster(Request $request)
    {
        try{
            if($request->has('name') && $request->name && $request->has('map_address') && $request->map_address)
            {
                $mytime   = Carbon::now();
                $now_time = $mytime->toDateTimeString();
                $locationData = [
                    'lat'     => $request->lat ?? '',
                    'long'    => $request->long ?? '',
                    'address' => $request->map_address ?? '',
                ];
                $company_login_id = session()->get('company_login_id');
                if($company_login_id)
                {
                    $company_id = $company_login_id;
                } else {
                    $company_id = auth()->guard('admin')->user()->company_id ?? '';
                }
                //create a new token to be sent to the user.
                \DB::table('cluster')->insert([
                    'name'       => $request->name ?? '',
                    'location'   => $locationData,
                    'status'     => (isset($request->status) && $request->status ? 1 : 0),
                    'company_id' => $company_id,
                    'created_by' => auth()->guard('admin')->user()->id ?? '',
                    'created_at' => $now_time,
                    'updated_at' => $now_time
                ]);
               Log::info('save cluster data by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email] );
                return response()->json(['status' => 'true']);
            } else {
                Log::info('data is not save by user');
    
                return response()->json(['status' => 'false']);
            }
        }
     catch(Expection   $e){
        Log::error('error occured in save cluster',$e->getMessage());
     }

    }

    public function saveDevice(Request $request)
    {
        try{
            if($request->has('device_name') && $request->device_name && $request->has('macid') && $request->macid)
            {
                $mytime       = Carbon::now();
                $now_time     = $mytime->toDateTimeString();
                $locationData = [
                    'lat'     => $request->lat ?? '',
                    'long'    => $request->long ?? '',
                    'address' => $request->search_address ?? '',
                ];
                if($request->has('id') && $request->id)
                {
                    $inverter   = Inverter::find($request->id);
                    if ($request->has('install_code') && $request->install_code ) {
                        if(isset($inverter->install_code) && $inverter->install_code != $request->install_code){
                            $exits = CompanyAgentDetail::where('company_id',$inverter->company_id)->where('install_code',(int)$request->install_code)->first();
                            if(!$exits){
                                return response()->json(['status' => 'exits','response_msg' => "The installation code doesn't match our company"]);
                            } else if($exits && $exits->used == 1){
                                return response()->json(['status' => 'exits','response_msg' => "The installation code already used"]);
                            }
                            $exits->used = 1;
                            $exits->save();
                        } else if(!$inverter->install_code){
                            $exits = CompanyAgentDetail::where('company_id',$inverter->company_id)->where('install_code',(int)$request->install_code)->first();
                            if(!$exits){
                                return response()->json(['status' => 'exits','response_msg' => "The installation code doesn't match our company"]);
                            } else if($exits && $exits->used == 1){
                                return response()->json(['status' => 'exits','response_msg' => "The installation code already used"]);
                            }
                            $exits->used = 1;
                            $exits->save();
                        }
                    }
    
                    // This Update Section
    
                    $macID      = $inverter->macid;
                    $loc_changed = true;
                    if($locationData['lat'] == $inverter->location['lat'] && $locationData['long'] == $inverter->location['long']){
                        $loc_changed = false;
                    }
    
                    $inverter->name         = $request->device_name ?? '';
                    $inverter->description  = $request->description ?? '';
                    $inverter->serial_no    = $request->serial_no ?? '';
                    $inverter->location     = $locationData;
                    $inverter->hardware     = $request->hardware ?? '';
                    $inverter->cluster_id   = $request->cluster_id ?? '';
                    $inverter->install_code = $request->install_code ?? '';
                    $inverter->status       = (isset($request->status) && $request->status ? 1 : 0);
                    $inverter->updated_at   = $now_time;
                    $inverter->macid        = $request->macid ?? '';
                    if ($request->has('install_code') && $request->install_code ) {
                        $agent_id = getAgentId($inverter->company_id,$request->install_code);
                        $inverter->agent_id     = $agent_id ?? '';
                    }
    
                    $inverter->save();
    
                    $user_id = '';
                    if($macID != $request->macid)
                    {
                        $route = 'add_inverter';
                        $data  = [
                            'macid'   => $request->macid,
                            'user_id' => auth()->guard('admin')->user()->id,
                        ];
    
    
                        // CALL UserTrait For Add Device
                        $this->addDiviceApi($route,$data);
                        $user_id = $request->id;
                    }
                    $dataActivity = [
                        'user_id'    => auth()->guard('admin')->user()->id,
                        'device_id'  => $inverter->id,
                        'company_id' => $inverter->company_id ?? '',
                        'macid'      => $request->macid ?? '',
                        'status'     => 'device_updated',
                    ];
    
                    // Save Daily Activity Reports
                    saveDailyActivity($dataActivity);
                    if($loc_changed == true)
                    {
                        // if device location updated
                        $dataActivity['status']  = 'device_location_updated';
    
                        // Save Daily Activity Reports
                        saveDailyActivity($dataActivity);
                    }
                    Log::info('update the device by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                    return response()->json(['status' => 'true','user_id' => $user_id]);
    
                } 
                else {
    
                    $company_login_id = session()->get('company_login_id');
                    if($company_login_id)
                    {
                        $company_id = $company_login_id;
                    } else {
                        $company_id = auth()->guard('admin')->user()->company_id ?? '';
                    }
                    if ($request->has('install_code') && $request->install_code) {
    
                        $exits = CompanyAgentDetail::where('company_id',$company_id)->where('install_code',(int)$request->install_code)->first();
    
                        if(!$exits){
                            return response()->json(['status' => 'exits','response_msg' => "The installation code doesn't match our company"]);
                        } else if($exits && $exits->used == 1){
                            return response()->json(['status' => 'exits','response_msg' => "The installation code already used"]);
                        }
                        $exits->used = 1;
                        $exits->save();
                    }
    
                    // This Saved Section
                    $inverter = new Inverter();
                    $inverter->user_id      = auth()->guard('admin')->user()->id;
                    $inverter->user_id_str  = auth()->guard('admin')->user()->id;
                    $inverter->name         = $request->device_name ?? '';
                    $inverter->description  = $request->description ?? '';
                    $inverter->macid        = $request->macid ?? '';
                    $inverter->serial_no    = $request->serial_no ?? '';
                    $inverter->location     = $locationData;
                    $inverter->hardware     = $request->hardware ?? '';
                    $inverter->cluster_id   = $request->cluster_id ?? '';
                    $inverter->status       = (isset($request->status) && $request->status ? 1 : 0);
                    $inverter->company_id   = $company_id ?? '';
                    $inverter->install_code = $request->install_code ?? '';
                    if ($request->has('install_code') && $request->install_code ) {
                        $agent_id = getAgentId($company_id,$request->install_code);
                        $inverter->agent_id     = $agent_id ?? '';
                    }
                    $inverter->agent_id     = $agent_id ?? '';
                    $inverter->created_by   = auth()->guard('admin')->user()->id ?? '';
                    $inverter->created_at   = $now_time;
                    $inverter->updated_at   = $now_time;
                    $inverter->save();
    
                    $route = 'add_inverter';
                    $data  = [
                        'macid'   => $request->macid,
                        'user_id' => auth()->guard('admin')->user()->id,
                    ];
    
                    $dataActivity = [
                        'user_id'    => auth()->guard('admin')->user()->id,
                        'device_id'  => $inverter->id,
                        'company_id' => $company_id ?? '',
                        'macid'      => $request->macid ?? '',
                        'status'     => 'new_device_add',
                    ];
                    // Save Daily Activity Reports
                    saveDailyActivity($dataActivity);
    
                    // CALL UserTrait For Add Device
                    $this->addDiviceApi($route,$data);
                    Log::info('save device by user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                    return response()->json(['status' => 'true','user_id' => $inverter->id]);
                }
    
            } else {
                Log::error('the data not found ');
                return response()->json(['status' => 'false']);
            }
        }
       catch(Execption $e){
        Log::error('error in save device', ['error' => $e->getMessage()]);
       }
    }

    public function getTmpDeviceList(Request $request)
    {
        $post           = [];
        $post['limit']  = 4;
        $post['offset'] = (isset($request->offset) && $request->offset) ? $request->offset : 0;
        $search         = $request->search_name ?? '';
        $company_id     = $request->company_id ?? '';
        $adminRoleId    = \Config::get('constants.roles.Master_Admin');
        $listData       =  Device::where('cluster_id','')->select('*');
                    if(auth()->guard('admin')->user()->role_id != $adminRoleId)
                    {
                        $company_id = auth()->guard('admin')->user()->company_id;
                        $listData->where('company_id',auth()->guard('admin')->user()->company_id);
                    } else {
                        $listData->where('company_id',$company_id);
                    }
                    $listData->skip($post['offset'])
                    ->take($post['limit'])
                    ->when($search, function ($query, $search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orderBy('created_at','desc');
        $listData = $listData->get();
        if($listData && count($listData) > 0) {
            foreach ($listData as $key => $list) {
                $System_calculated        = Data::where('data.data.Contain','System_calculated')->where('macid',$list->macid)->orderBy('created_at','desc')->first();
                $Sub_System_calculated    = Data::where('data.data.Contain','Sub_System_calculated')->where('macid',$list->macid)->orderBy('created_at','desc')->first();
                $battery              = Data::where('data.data.Contain','Battery')->where('macid',$list->macid)->orderBy('created_at_timestamp','desc')->first();
                $PV                   = Data::where('data.data.Contain','PV')->where('macid',$list->macid)->orderBy('created_at_timestamp','desc')->first();
                $listData[$key]['system_calculated']       = $System_calculated;
                $listData[$key]['sub_system_calculated']   = $Sub_System_calculated;
                $listData[$key]['battery_data'] = $battery;  // Battery means Unit
                $listData[$key]['PV'] = $PV;  // For AC and DC Solar
            }
            $colors = ['bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
            $data  = [
                'device_list'   => $listData,
                'card_colors'   => $colors,
                'icons_setting' => getIconSettings($company_id)
            ];
            $msg = view('system_overview.tmp_devices', $data)->render();
            $ret_data = [
                'is_data'   => true,
                'html'      => $msg,
                'offset'    => $post['offset'] + $post['limit'],
            ];
        } else {
			$msg = '<div class="col-lg-8 mx-auto device-error">
                        <div class="mt-2 mb-3 text-center">
                            <div class="card-title-overview-page custom-color-danger">No More Power Bank !!!.</div>
                        </div>
                    </div>';
			$ret_data = [
				'offset'  => $post['offset'] + $post['limit'],
				'is_data' => false,
				'html'    => '', //$msg,
			];
		}
        echo json_encode($ret_data);
    }

    public function getTmpDeviceListClusterWise(Request $request)
    {
        $post           = [];
        $post['limit']  = 4;
        $post['offset'] = (isset($request->offset) && $request->offset ? $request->offset : 0);
        $adminRoleId    = \Config::get('constants.roles.Master_Admin');
        $search     = $request->search_name ?? '';
        $company_id = $request->company_id ?? '';
        $listData   =  Cluster::with('device')->select('*');
                    if(auth()->guard('admin')->user()->role_id != $adminRoleId)
                    {
                        $company_id = auth()->guard('admin')->user()->company_id;
                        $listData->where('company_id',auth()->guard('admin')->user()->company_id);
                    } else {
                        $listData->where('company_id',$company_id);
                    }
                    $listData->skip($post['offset'])
                    ->take($post['limit'])
                    ->when($search, function ($query, $search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->where('status',1)
                    ->orderBy('created_at','desc');

        $listData = $listData->get();
        if($listData && count($listData) > 0) {
            foreach ($listData as $key => $list) {
                $System_calculated        = Data::where('data.data.Contain','System_calculated')->where('macid',$list->macid)->orderBy('created_at','desc')->first();
                $Sub_System_calculated    = Data::where('data.data.Contain','Sub_System_calculated')->where('macid',$list->macid)->orderBy('created_at','desc')->first();
                $battery              = Data::where('data.data.Contain','Battery')->where('macid',$list->macid)->orderBy('created_at_timestamp','desc')->first();
                $PV                   = Data::where('data.data.Contain','PV')->where('macid',$list->macid)->orderBy('created_at_timestamp','desc')->first();
                $listData[$key]['system_calculated']       = $System_calculated;
                $listData[$key]['sub_system_calculated']   = $Sub_System_calculated;
                $listData[$key]['battery_data'] = $battery;  // Battery means Unit
                $listData[$key]['PV'] = $PV;  // For AC and DC Solar
            }
            $colors = ['bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
            $data  = [
                'cluster_list'  => $listData,
                'card_colors'   => $colors,
                'icons_setting' => getIconSettings($company_id)
            ];

            $msg = view('system_overview.tmp_devices_cluster_wise', $data)->render();
            $ret_data = [
                'is_data'   => true,
                'html'      => $msg,
                'offset'    => $post['offset'] + $post['limit'],
            ];
        } else {
			$msg = '<div class="col-lg-8 mx-auto cluster-error">
                        <div class="mt-2 mb-3 text-center">
                            <div class="card-title-overview-page custom-color-danger">No More Group !!!.</div>
                        </div>
                    </div>';
			$ret_data = [
				'offset'  => $post['offset'] + $post['limit'],
				'is_data' => false,
				'html'    => '', //$msg,
			];
		}
        echo json_encode($ret_data);
    }

    public function deviceDetailPage(Request $request, $id='',$deviceId='')
    {
        try{
            if($deviceId)
        {

            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }
        $dataOne = Device::find($id);

        if( !$dataOne ){
            abort(404);
        }
        if(isCompanyLogin() == 'false' && $deviceId){
            companyLoginByAdmin($dataOne->company_id);
        }
        $machineData     = Data::where('macid',$dataOne->macid)->orderBy('created_at','desc')->first();
        $machine_status  = "ON";
        if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected")
        {
            $machine_status = "OFF";
        }
        else if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected")
        {
            $machine_status = "Inverter_disconnected";
        }

         // Get Latest 5 minit data
         $now          = Carbon::now();
         $last5Minutes = $now->subMinutes(5);
         $latestRecord = Data::where('macid',$dataOne->macid)->where('created_at', '>=', $last5Minutes)->get();
         $is_latest_data = false;
         if(isset($latestRecord) && count($latestRecord) > 0){
             $latestRecord = $latestRecord;
             $is_latest_data = true;
         }

        $data = [
            'title'           => 'Home',
            'module'          => "System Overview",
            'heading'         => "POWRBANK  Details",
            'device_details'  => $dataOne,
            'id'              => $id,
            'machine_status'  => $machine_status,
            'is_latest_data'  => $is_latest_data,
        ];
        Log::info('device details page retrived by user successfully ', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return view('system_overview.view_device_details', $data);
        }
        catch(Exception  $e){
            Log::error('error in device details page',['message' => $e->getMessage(),]);
        }
    }

    public function deviceDetailPageCopy($id='',$deviceId='')
    {
        if($deviceId)
        {
            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }
        $dataOne = Device::find($id);

        if( !$dataOne ){
            abort(404);
        }
        $machineData     = Data::where('macid',$dataOne->macid)->orderBy('created_at','desc')->first();
        $machine_status  = "ON";
        if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected")
        {
            $machine_status = "OFF";
        }
        else if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected")
        {
            $machine_status = "Inverter_disconnected";
        }

        // Get Latest 5 minit data
        $now          = Carbon::now();
        $last5Minutes = $now->subMinutes(5);
        $latestRecord = Data::where('macid',$dataOne->macid)->where('created_at', '>=', $last5Minutes)->get();
        $is_latest_data = false;
        if(isset($latestRecord) && count($latestRecord) > 0){
            $latestRecord = $latestRecord;
            $is_latest_data = true;
        }

        $data = [
            'title'           => 'Home',
            'module'          => "System Overview",
            'heading'         => "Power Bank Details",
            'device_details'  => $dataOne,
            'id'              => $id,
            'machine_status'  => $machine_status,
            'is_latest_data'  => $is_latest_data,
        ];
        return view('system_overview.view_device_details_copy', $data);
    }

    public function checkDeviceVerified(Request $request)
    {
        $input = $request->all();
        if(isset($input['id']) && $input['id'])
        {
            $device = Device::find($input['id']);
            if(isset($device->is_verified) && $device->is_verified == 1)
            {
                return response()->json(['status' => 'true']);
            } else {
                return response()->json(['status' => 'false']);
            }
        }
    }

    public function deviceAlarmsList(Request $request,$macid="",$id = "")
    {
       try{

        if($id)
        {
            $macid = $id;
        }
        $dataOne = Device::where('macid',$macid)->first();
        $data = [
            'title'       => 'Home',
            'module'      => "System Overview",
            'heading'     => "Notification List",
            'macid'       => $macid,
            'data'        => $dataOne
        ];
        Log::info('Deviec Alaam list retrived successfuly by user ', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return view('system_overview.view_alarms_list', $data);
       } 
       catch(Exception $e){
              Log::error('Error in device Alarms list retrived by user ', ['message'=>$e->getMessage()]);
       }
       
    }

    public function getAlarmsList(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $macid       = $request->get('macid');
            $data        = Data::where('data.data.Contain','Alarms/State')->where('macid',$macid);
            // $data        = Data::where('data.data.Contain','Warning')->where('macid',$macid)->get();

            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('BMS_BAT_CH', function($row){
                $BMS_BAT_CH = '';
                if(isset($row['data']['data']) && isset($row['data']['data']['Battery Alarms']))
                {
                    $BMS_BAT_CH = $row['data']['data']['Battery Alarms']['BMS_BAT_CH'];
                }
                return $BMS_BAT_CH;
            })
            ->addColumn('BMS_BAT_DH', function($row){
                $BMS_BAT_DH = '';
                if(isset($row['data']['data']) && isset($row['data']['data']['Battery Alarms']))
                {
                    $BMS_BAT_DH = $row['data']['data']['Battery Alarms']['BMS_BAT_DH'];
                }
                return $BMS_BAT_DH;
            })
            ->addColumn('BMS_ERR', function($row){
                $BMS_ERR = '';
                if(isset($row['data']['data']) && isset($row['data']['data']['Battery Alarms']))
                {
                    $BMS_ERR = $row['data']['data']['Battery Alarms']['BMS_ERR'];
                }
                return $BMS_ERR;
            })
            ->addColumn('BUS_SOC', function($row){
                $BUS_SOC = '';
                if(isset($row['data']['data']) && isset($row['data']['data']['Battery Alarms']))
                {
                    $BUS_SOC = $row['data']['data']['Battery Alarms']['BUS_SOC(%)'];
                }
                return $BUS_SOC;
            })
            ->addColumn('CELL_IMB', function($row){
                $CELL_IMB = '';
                if(isset($row['data']['data']) && isset($row['data']['data']['Battery Alarms']))
                {
                    $CELL_IMB = $row['data']['data']['Battery Alarms']['CELL_IMB'];
                }
                return $CELL_IMB;
            })
            ->addColumn('created_at', function($row){
                $startDate = date_create($row['created_at']);
                return date_format($startDate,"Y-m-d h:m A");
            })
            // ->orderColumn('name', function ($query, $order) {
            //     $query->orderBy('first_name', $order);
            // })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            // ->rawColumns(['action'])
            ->make(true);
        }
    }

    // Get Warning List
    public function getWarningList(Request $request)
    {
        if ($request->ajax()) {

            $request->merge(array(
                'start'  => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $macid = $request->get('macid');
            // $warningData  = Data::where('data.data.Contain','Warning')->where('data.data.totalcount','>',1)->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();
            // $warningData = \DB::table('device_warnings')->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();
            $warningData = DeviceWarning::with('warning')->where('macid',$macid);

            return Datatables::of($warningData)
            ->addIndexColumn()
            ->addColumn('error_code', function($row){
                return $row->code ?? '';
            })
            ->addColumn('title', function($row){
                return $row->warning->title ?? '';
            })
            ->addColumn('message', function($row){
                return $row->warning->message ?? '';
            })
            ->addColumn('code_date', function($row){
                if($row['code_date'])
                {
                    $date = $row['code_date']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->addColumn('created_at', function($row){
                if($row['created_at'])
                {
                    $date = $row['created_at']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->rawColumns(['title','message','error_code'])
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->make(true);

        }
    }
    // Get Current Warning List
    public function getCurrentWarningList(Request $request)
    {
        if ($request->ajax()) {

            $request->merge(array(
                'start'  => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $macid = $request->get('macid');
            // $warningData  = Data::where('data.data.Contain','Warning')->where('data.data.totalcount','>',1)->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();
            // $warningData = \DB::table('device_warnings')->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();
            $warningData = DeviceWarning::with('warning')->where('macid',$macid)->where("updated_at",">",Carbon::now()->subMinutes(10));
            return Datatables::of($warningData)
            ->addIndexColumn()
            ->addColumn('error_code', function($row){
                return $row->code ?? '';
            })
            ->addColumn('title', function($row){
                return $row->warning->title ?? '';
            })
            ->addColumn('message', function($row){
                return $row->warning->message ?? '';
            })
            ->addColumn('code_date', function($row){
                if($row['code_date'])
                {
                    $date = $row['code_date']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->addColumn('created_at', function($row){
                if($row['created_at'])
                {
                    $date = $row['created_at']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->rawColumns(['title','message','error_code'])
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->make(true);

        }
    }

    // Get Notification List
    public function getCurrentNotificationList(Request $request)
    {
        if ($request->ajax()) {

            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $macid = $request->get('macid');

            $warningData = DeviceNotification::with('notification')->where('macid',$macid)->where("updated_at",">",Carbon::now()->subMinutes(10));

            return Datatables::of($warningData)
            ->addIndexColumn()
            ->addColumn('error_code', function($row){
                return $row->code ?? '';
            })
            ->addColumn('title', function($row){
                return $row->notification->title ?? '';
            })
            ->addColumn('message', function($row){
                return $row->notification->message ?? '';
            })
            ->addColumn('code_date', function($row){
                if($row['code_date'])
                {
                    $date = $row['code_date']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->addColumn('created_at', function($row){
                if($row['created_at'])
                {
                    $date = $row['created_at']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->rawColumns(['title','message','error_code'])
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->make(true);
        }
    }

    // Get Notification List
    public function getNotificationList(Request $request)
    {
        if ($request->ajax()) {

            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $macid = $request->get('macid');
            // $warningData  = Data::where('data.data.Contain','Notification')->where('data.data.totalcount','>',1)->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();
            // $warningData = \DB::table('device_notifications')->where('macid',$macid)->orderBy('created_at','desc')->get()->toArray();

            $warningData = DeviceNotification::with('notification')->where('macid',$macid);

            // $warningMasterData = Warning::all();
            // $warningCode  = [];
            // $ctn = 0;
            // if($warningData)
            // {
            //     foreach ($warningData as $key => $warning) {
            //         if(isset($warning['data']['data']) && $warning['data']['data'])
            //         {
            //             unset($warning['data']['data']['Contain']);
            //             unset($warning['data']['data']['totalcount']);
            //             if(count($warning['data']['data']) > 1)
            //             {
            //                 $code = '';
            //                 $title = '';
            //                 $message = '';
            //                 $created_at = '';
            //                 foreach ($warning['data']['data'] as $ckey => $dk) {
            //                     $values = $warningMasterData->filter(function($item) use($ckey){
            //                         return $item->error_code == $ckey;
            //                     })->first();
            //                     $code .= $values->error_code .'</br>';
            //                     $title .= $values->title .',</br>';
            //                     $message .= $values->message .',</br>';
            //                     $created_at = $dk;
            //                 }
            //                 $warningCode[$ctn]['title']       = $title ?? '';
            //                 $warningCode[$ctn]['message']     = $message ?? '';
            //                 $warningCode[$ctn]['code']       = $code ?? '';
            //                 $warningCode[$ctn]['created_at'] = $created_at ?? '';
            //                 $ctn = $ctn + 1;
            //             } else {
            //                 foreach ($warning['data']['data'] as $ckey => $dk) {
            //                     $values = $warningMasterData->filter(function($item) use($ckey){
            //                         return $item->error_code == $ckey;
            //                     })->first();
            //                     $warningCode[$ctn]['title']       = $values->title ?? '';
            //                     $warningCode[$ctn]['message']     = $values->message ?? '';
            //                     $warningCode[$ctn]['code']       = $values->error_code ?? '';
            //                     $warningCode[$ctn]['created_at'] = $created_at ?? '';
            //                     $ctn = $ctn + 1;
            //                 }
            //             }

            //         }
            //     }
            // }

            return Datatables::of($warningData)
            ->addIndexColumn()
            ->addColumn('error_code', function($row){
                return $row->code ?? '';
            })
            ->addColumn('title', function($row){
                return $row->notification->title ?? '';
            })
            ->addColumn('message', function($row){
                return $row->notification->message ?? '';
            })
            ->addColumn('code_date', function($row){
                if($row['code_date'])
                {
                    $date = $row['code_date']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->addColumn('created_at', function($row){
                if($row['created_at'])
                {
                    $date = $row['created_at']->toDateTime()->setTimezone(new DateTimeZone('UTC'));
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    return $formattedDate;
                }
            })
            ->rawColumns(['title','message','error_code'])
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->make(true);
        }
    }

    // Tmp Charts
    public function getCharts(Request $request)
    {
        $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
        $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
        $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();

        $data = [
            'title'       => 'Charts',
            'macid'       => '',
            'grid_data'   => $grid,
            'battery'     => $battery, // Battery means Unit
            'PV'          => $PV, // For AC and DC Solar
        ];
        return view('system_overview.tmp_charts', $data);
    }

    public function getGridGensetGraphData(Request $request)
    {
        $current_date = '';
        if($request->has('current_date') && $request->current_date)
        {

            $current_date = date('Y-m-d', strtotime($request->current_date));
        }
        $enchartFilter = $request->filter_type ?? 'today';
        $current_date  = $current_date;
        $chart_type    = $request->chart_type ?? '';      // Chart Type means (Grid/Genset)
        $selected      = $request->selected ?? '';        // Selected Checkbox name
        $selected_type = $request->selected_type ?? '';  // Means (W) OR (V)
        $selected_key  = $request->selected_key ?? '';  // Means Array Key (Power) OR (Frequency)
        $timezone      = config('app.timezone');

        $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key) {
            if($enchartFilter == 'day'){
                $dt  = $current_date . ' ' . '00:00:00';
                $dt1 = $current_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
        });
        if($data){
            if($enchartFilter == 'today'){
                $fulldate = $current_date;
                $x = 0;
                while($x <= 23) {
                    $labels[] = $x;
                    $seachStr = $fulldate . ' ' . ($x < 10 ? '0'.$x : $x);
                    $filtered = $data->where('_id', $seachStr)->first();

                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }
                    $x++;
                }
            }

           if($selected_type == "W")
           {
                $dataset[0]['label']           = "(W)";
                $dataset[0]['content']         = $chart_type;
                $dataset[0]['backgroundColor'] = 'transparent';
                $dataset[0]['borderColor']     = '#6259ca';
                $dataset[0]['borderWidth']     = 2;
                $dataset[0]['pointBackgroundColor']     = "#ffffff";
                $dataset[0]['fill']            = false;
                $dataset[0]['lineTension']     = 0.3;
                $dataset[0]['line_id']         = $selected;
                $dataset[0]['yAxisID']         = "y";
           } else {
                $dataset[0]['label']           = "(V)";
                $dataset[0]['content']         = $chart_type;
                $dataset[0]['backgroundColor'] = 'transparent';
                $dataset[0]['borderColor']     = '#eb6f33';
                $dataset[0]['borderWidth']     = 2;
                $dataset[0]['pointBackgroundColor']     = "#ffffff";
                $dataset[0]['fill']            = false;
                $dataset[0]['lineTension']     = 0.3;
                $dataset[0]['line_id']         = $selected;
                $dataset[0]['yAxisID']         = "y1";
           }


        }

        $mainData['labels'] = $labels;
        $mainData['datasets'] = $dataset;
        $mainData['y_axis_label'] = '( '.$selected_type.' )';

        return $mainData;
    }

    public function getChartsData(Request $request)
    {
        $input = $request->all();
        if(isset($input['chart']) && $input['chart'])
        {
            $dataset = [];
            $dataset['labels'] = [];
            foreach ($input['chart'] as $key => $value) {
                $datas = $this->getDatasetsDynamic($value);
                $dataset['labels']          = $datas['labels'] ?? [];
                $dataset['dataset'][$key]   = $datas['datasets'];
            }
            return $dataset;
        }
    }

    public function getDatasetsDynamic($value)
    {
        $current_date = '';
        if(isset($value['current_date']) && $value['current_date'])
        {

            $current_date = date('Y-m-d', strtotime($value['current_date']));
        }
        $enchartFilter = $value['filter_type'] ?? 'today';
        $current_date  = $current_date;
        $chart_type    = $value['chart_type'] ?? '';      // Chart Type means (Grid/Genset)
        $selected      = $value['selected'] ?? '';        // Selected Checkbox name
        $selected_type = $value['selected_type'] ?? '';  // Means (W) OR (V)
        $selected_key  = $value['selected_key'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $filter_value  = $value['filter_value'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $timezone      = config('app.timezone');

        $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key,$filter_value) {
            if($enchartFilter == 'today'){
                $dt  = $current_date . ' ' . '00:00:00';
                $dt1 = $current_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
            else if($enchartFilter == 'days')
            {
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                $dt  = $start_date . ' ' . '00:00:00';
                $dt1 = $end_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'month')
            {
                $start_date   = Carbon::now()->format('Y-'.$filter_value.'-01');
                $date         = new Carbon($start_date);
                $end_date     = $date->endOfMonth()->format('Y-m-d');
                $dt           = $start_date . ' ' . '00:00:00';
                $dt1          = $end_date . ' ' . '23:59:59';

                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'year')
            {
                $dt  = $filter_value . '-01-01' . ' ' . '00:00:00';
                $dt1 = $filter_value . '-12-31' . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if ($enchartFilter == 'global') {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'last_30_days')
            {
                $start_date   = Carbon::now()->subDays(30)->format('Y-m-d');
                // $date         = new Carbon($start_date);
                $end_date     = Carbon::now()->format('Y-m-d');
                $dt           = $start_date . ' ' . '00:00:00';
                $dt1          = $end_date . ' ' . '23:59:59';

                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
        });

        if($data){
            if($enchartFilter == 'today'){
                $fulldate = $current_date;
                $x = 0;
                while($x <= 23) {
                    $labels[] = $x;
                    $seachStr = $fulldate . ' ' . ($x < 10 ? '0'.$x : $x);
                    $filtered = $data->where('_id', $seachStr)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                    $x++;
                }
            }
            else if($enchartFilter == "days"){
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                $periodDates = periodDates($start_date,$end_date);
                $labels      = $periodDates;
                foreach ($periodDates as $key => $dates) {
                    $filtered = $data->where('_id', $dates)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if($enchartFilter == "month"){
                $start_date   = Carbon::now()->format('Y-'.$filter_value.'-01');
                $date         = new Carbon($start_date);
                $end_date     = $date->endOfMonth()->format('Y-m-d');
                $dt           = $start_date . ' ' . '00:00:00';
                $dt1          = $end_date . ' ' . '23:59:59';
                $periodDates = periodDates($start_date,$end_date);
                $labels      = $periodDates;
                foreach ($periodDates as $key => $dates) {
                    $filtered = $data->where('_id', $dates)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "year") {
                $dt  = $filter_value . '-01-01';
                $dt1 = $filter_value . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay   = Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("m");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "global") {
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset['data'][] = $value['sum'];
                }
            }
           if($selected_type == "W")
           {
                $dataset['label']           = "(W)";
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = '#6259ca';
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = "#4180FF";
                $dataset['fill']            = true;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
           }
           else if($selected_type == "Hz"){
                $dataset['label']           = "(Hz)";
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = '#eb6f33';
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = "#eb6f33";
                $dataset['fill']            = true;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y1";
           }
           else if($selected_type == "AH"){
                $dataset['label']           = "(AH)";
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = '#FBAF16';
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = "#FBAF16";
                $dataset['fill']            = true;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y2";
            }
           else if($selected_type == "KWH"){
                $dataset['label']           = "(KWH)";
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = '#45BF55';
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = "#45BF55";
                $dataset['fill']            = true;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y3";
            }
           else if($selected_type == "A"){
                $dataset['label']           = "(A)";
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = '#FF2966';
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = "#FF2966";
                $dataset['fill']            = true;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y4";
            }


        }

        $mainData['labels'] = $labels ?? [];
        $mainData['datasets'] = $dataset;
        $mainData['y_axis_label'] = '( '.$selected_type.' )';

        return $mainData;
    }

    public function getChartsNew()
    {
        $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
        $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
        $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();

        $data = [
            'title'       => 'Charts New',
            'macid'       => '',
            'grid_data'   => $grid,
            'battery'     => $battery, // Battery means Unit
            'PV'          => $PV, // For AC and DC Solar
        ];
        return view('system_overview.tmp_charts_new', $data);
    }


    public function verifiedDevice(Request $request)
    {
        $input = $request->all();
        if(isset($input['macid']) && $input['macid'])
        {
            $route = 'add_inverter';
            $data  = [
                'macid'   => $input['macid'],
                'user_id' => auth()->guard('admin')->user()->id,
            ];

            // CALL UserTrait For Add Device
           $response =  $this->addDiviceApi($route,$data);
           $response = json_decode($response,true);
           $return = 'false';
           if(isset($response['data']) && $response['data'])
           {
                if(isset($response['data']['is_verified']) && $response['data']['is_verified'] == 1)
                {
                    $return = 'true';
                    
                }
           }
           return response()->json(['is_verified' => $return]);
        }
    }

    public function batteryDetailPage($id='',$deviceId='')
    {
        if($deviceId)
        {
            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }

        $dataOne = Device::find($id);
        if( !$dataOne ){
            abort(404);
        }
        $machineData     = Data::where('macid',$dataOne->macid)->orderBy('created_at','desc')->first();

        $machine_status  = "ON";
        if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected")
        {
            $machine_status = "OFF";
        }
        else if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected")
        {
            $machine_status = "Inverter_disconnected";
        }

        // Get Latest 5 minit data
        $now          = Carbon::now();
        $last5Minutes = $now->subMinutes(5);
        $latestRecord = Data::where('macid',$dataOne->macid)->where('created_at', '>=', $last5Minutes)->get();
        $is_latest_data = false;
        if(isset($latestRecord) && count($latestRecord) > 0){
            $latestRecord = $latestRecord;
            $is_latest_data = true;
        }

        $data = [
            'title'            => 'Home',
            'module'           => "System Overview",
            'heading'          => "Battery",
            'battery_details'  => $dataOne,
            'id'               => $id,
            'machine_status'   => $machine_status,
            'is_latest_data'   => $is_latest_data,
        ];
        Log::info('battery details page',['id'=>$id]);
        return view('system_overview.view_battery_details', $data);
    }

    public function batteryDetailPageCopy($id='',$deviceId='')
    {
        if($deviceId)
        {
            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }

        $dataOne = Device::find($id);
        if( !$dataOne ){
            abort(404);
        }
        $machineData     = Data::where('macid',$dataOne->macid)->orderBy('created_at','desc')->first();

        $machine_status  = "ON";
        if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected")
        {
            $machine_status = "OFF";
        }
        else if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected")
        {
            $machine_status = "Inverter_disconnected";
        }

        // Get Latest 5 minit data
        $now          = Carbon::now();
        $last5Minutes = $now->subMinutes(5);
        $latestRecord = Data::where('macid',$dataOne->macid)->where('created_at', '>=', $last5Minutes)->get();
        $is_latest_data = false;
        if(isset($latestRecord) && count($latestRecord) > 0){
            $latestRecord = $latestRecord;
            $is_latest_data = true;
        }

        $data = [
            'title'            => 'Home',
            'module'           => "System Overview",
            'heading'          => "Battery",
            'battery_details'  => $dataOne,
            'id'               => $id,
            'machine_status'   => $machine_status,
            'is_latest_data'  => $is_latest_data,
        ];
        return view('system_overview.view_battery_details_copy', $data);
    }

    public function remortAccess($id='',$deviceId='')
    {
        if($deviceId)
        {
            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user()->is_active == 0 && auth()->guard('admin')->user()->role_id != $adminRoleId)
        {
            abort(404);
        }

        $dataOne = Device::find($id);
        if( !$dataOne ){
            abort(404);
        }

        saveLogs('remote-access-view-setting-page');
        $data = [
            'title'            => 'Home',
            'module'           => "System Overview",
            'heading'          => "Remort Access",
            'device_details'   => $dataOne,
            'id'               => $id,
        ];
        Log::info('remote access page', ['id'=>$id]);
        return view('system_overview.remort_access', $data);
    }

    public function saveRemortAccess(Request $request)
    {
        if($request->has('id') && $request->id && $request->has('ip') && $request->ip && $request->has('port') && $request->port)
        {
            $Records = Device::find($request->id);
            $Records->ip = $request->ip ?? '';
            $Records->port = $request->port ?? '';
            $Records->client_vnc_port = $request->client_vnc_port ?? '5900';
            $Records->save();

            $dataActivity = [
                'user_id'    => auth()->guard('admin')->user()->id,
                'device_id'  => $Records->id ?? '',
                'company_id' => $Records->company_id ?? '',
                'macid'      => $Records->macid ?? '',
                'status'     => 'remote_access_updated',
            ];

            // Save Daily Activity Reports
            saveDailyActivity($dataActivity);
         
            return response()->json(['status' => 'true']);
        } else {
            return response()->json(['status' => 'false']);
        }
    }

    public function getErrorMsgData($code)
    {
        $warning = Warning::where('error_code',$code)->first();
        if($warning)
        {
            $data = [
                'title'      => $warning->title ?? '',
                'message'    => $warning->message ?? '',
                'error_code' => $warning->error_code ?? '',
            ];
            return $data;
        } else {
            return [];
        }
    }

    // Test testEnergyHour
    public function testEnergyHour(Request $request)
    {
        $startDate = Carbon::now('UTC')->startOfDay(); // GMT start of today
        $endDate   = Carbon::now('UTC')->endOfDay();   // GMT end of today

        $records = Data::where('data.data.Contain','Battery')->whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'asc')->get();

        $previousTimestamp = null;
        $totalMinutes = 0;
        foreach ($records as $key => $record) {
            if ($previousTimestamp !== null) {
                $diffInMinutes = $record->created_at->diffInMinutes($previousTimestamp);
                $totalMinutes += $diffInMinutes;
            }
            $previousTimestamp = $record->created_at;
        }
        if($totalMinutes > 0)
        {
            $totalMinutes = $totalMinutes + 5;
        }
        echo "Total difference in minutes: " . $totalMinutes . " minutes";

        exit();
    }

    public function getLast5MinitData(Request $request) {
        if ($request->has('macid') && $request->macid) {
            $now          = Carbon::now();
            $last5Minutes = $now->subMinutes(5);
            $latestRecord = Data::where('macid',$request->macid)->where('created_at', '>=', $last5Minutes)->get();
           if($latestRecord && count($latestRecord) > 0){
                return response()->json(['status' => 'true','data' => $latestRecord]);
           } else {
                return response()->json(['status' => 'false','data' => []]);
           }
        }
    }

    // Device Assign to Group(Cluster)
    function deviceAssignToGroup(Request $request) {
        if($request->has('cluster_id') && $request->cluster_id && $request->has('device_id') && $request->device_id){
            $device = Device::where('_id',$request->device_id)->first();
            $device->cluster_id = $request->cluster_id ?? '';
            if($device->save()){
                return response()->json(['status' => 'true']);
            } else {
                return response()->json(['status' => 'false']);
            }
        }
    }

    // Remove Device
    public function deleteDevice(Request $request){
        if($request->has('device_id') && $request->device_id){
            $device = Device::where('_id',$request->device_id)->first();
            if($device){
                $macid = $device->macid ?? '';
                if($macid){
                    DeviceWarning::where('macid',$macid)->delete();

                    DeviceNotification::where('macid',$macid)->delete();

                    DailyActivity::where('macid',$macid)->delete();

                    Warning::where('device_id',$request->device_id)->delete();

                    Error::where('device_id',$request->device_id)->delete();

                    Data::where('macid',$macid)->delete();
                }
                $device->delete();
                return response()->json(['status' => 'true']);
            } else {
                return response()->json(['status' => 'false']);
            }

        } else {
            return response()->json(['status' => 'false']);
        }
    }
}
