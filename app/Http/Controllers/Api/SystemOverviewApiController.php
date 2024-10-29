<?php

namespace App\Http\Controllers\Api;

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
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;
use App\Models\IconSetting;
use Carbon\Carbon;
use App\Models\Inverter;

class SystemOverviewApiController extends Controller
{
    // 
    public function getSystemOverview($id = '')
    {
        // Get Master Admin Role ID from the configuration
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
    
        // Check if the user is active or if the user has the Master Admin role
        $user = auth('api')->user();
        if ($user->is_active == 0 && $user->role_id != $adminRoleId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 404);
        }
    
        // Handle company login for admin if the role is Master Admin
        if ($user->role_id == $adminRoleId) {
            if (isCompanyLogin() == 'false' && $id) {
                companyLoginByAdmin($id); // Call the company login function
            }
        }
    
        // Define card colors
        $colors = [
            'bg-primary br-tr-4 br-tl-4', 'bg-info br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4',
            'bg-warning br-tr-4 br-tl-4', 'bg-secondary br-tr-4 br-tl-4', 'bg-danger br-tr-4 br-tl-4',
            'bg-success br-tr-4 br-tl-4'
        ];
    
        // Fetch the list of devices with status 1
        $deviceList = Device::where('status', 1)->get();
    
        // Prepare the response data
        $response = [
            'status'       => 'success',
            'title'        => 'Home',
            'heading'      => 'System Overview',
            'device_list'  => $deviceList,
            'card_colors'  => $colors,
            'company_id'   => $id
        ];
    
        // Return the response as JSON
        return response()->json($response, 200);
    }
    

// add cluster 
public function addCluster()
{
    // Get Master Admin Role ID from the configuration
    $adminRoleId = \Config::get('constants.roles.Master_Admin');

    // Check if the user is active or if the user has the Master Admin role
    $user = auth('api')->user();
    if ($user->is_active == 0 && $user->role_id != $adminRoleId) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 404);
    }

    // Prepare the response data
    $response = [
        'status'   => 'success',
        'title'    => 'Home',
        'module'   => 'System Overview',
        'heading'  => 'Add Group',
    ];

    // Return the response as JSON
    return response()->json($response, 200);
}

public function addDevice(){
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $user = auth('api')->user();
    if ($user->is_active == 0 && $user->role_id != $adminRoleId) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized access'], 404);
    }

    // Determine the company ID either from session or the authenticated user
    $company_login_id = $user->company_id;
    $company_id = $company_login_id ? $company_login_id : $user->company_id;

    // Fetch clusters for the given company and active status
    $clusters = Cluster::where(['status' => 1, 'company_id' => $company_id])->get();

    // Prepare the response data
    $response = [
        'status'   => 'success',
        'title'    => 'Home',
        'module'   => 'System Overview',
        'heading'  => 'Add POWRBANK',
        'clusters' => $clusters,
    ];

   
    return response()->json($response, 200);

}

     //save 
     public function saveDevice(Request $request)
     {
         // Fetch user data from the JWT token using JwtHelper
         $userData = JwtHelper::getUserData(); // Assuming JwtHelper provides user data like id, role, company_id
     
         if (!$userData) {
             return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
         }
     
         // Check for required fields
         if ($request->has('device_name') && $request->device_name && $request->has('macid') && $request->macid) {
             $now_time = Carbon::now()->toDateTimeString();
     
             $locationData = [
                 'lat' => $request->lat ?? '',
                 'long' => $request->long ?? '',
                 'address' => $request->search_address ?? '',
             ];
     
             // Update existing device
             if ($request->has('id') && $request->id) {
                 $inverter = Inverter::find($request->id);
                 if (!$inverter) {
                     return response()->json(['status' => 'error', 'message' => 'Device not found'], 404);
                 }
     
                 // Handle install_code verification
                 if ($request->has('install_code') && $request->install_code) {
                     if (isset($inverter->install_code) && $inverter->install_code != $request->install_code) {
                         $exits = CompanyAgentDetail::where('company_id', $inverter->company_id)
                             ->where('install_code', (int) $request->install_code)
                             ->first();
     
                         if (!$exits) {
                             return response()->json(['status' => 'exits', 'response_msg' => "The installation code doesn't match our company"]);
                         } elseif ($exits->used == 1) {
                             return response()->json(['status' => 'exits', 'response_msg' => "The installation code already used"]);
                         }
                         $exits->used = 1;
                         $exits->save();
                     }
                 }
     
                 // Update the device data
                 $macID = $inverter->macid;
                 $loc_changed = $locationData['lat'] != $inverter->location['lat'] || $locationData['long'] != $inverter->location['long'];
     
                 $inverter->name = $request->device_name ?? '';
                 $inverter->description = $request->description ?? '';
                 $inverter->serial_no = $request->serial_no ?? '';
                 $inverter->location = $locationData;
                 $inverter->hardware = $request->hardware ?? '';
                 $inverter->cluster_id = $request->cluster_id ?? '';
                 $inverter->install_code = $request->install_code ?? '';
                 $inverter->status = $request->status ? 1 : 0;
                 $inverter->updated_at = $now_time;
                 $inverter->macid = $request->macid ?? '';
     
                 if ($request->has('install_code') && $request->install_code) {
                     $agent_id = getAgentId($inverter->company_id, $request->install_code);
                     $inverter->agent_id = $agent_id ?? '';
                 }
     
                 $inverter->save();
     
                 // Update if MAC ID changes
                 if ($macID != $request->macid) {
                     $route = 'add_inverter';
                     $data = [
                         'macid' => $request->macid,
                         'user_id' => $userData['id'],
                     ];
     
                     // Call trait to add device
                     $this->addDiviceApi($route, $data);
                 }
     
                 // Log daily activity
                 $dataActivity = [
                     'user_id' => $userData['id'],
                     'device_id' => $inverter->id,
                     'company_id' => $inverter->company_id ?? '',
                     'macid' => $request->macid ?? '',
                     'status' => 'device_updated',
                 ];
                 saveDailyActivity($dataActivity);
     
                 // Log location update if applicable
                 if ($loc_changed) {
                     $dataActivity['status'] = 'device_location_updated';
                     saveDailyActivity($dataActivity);
                 }
     
                 return response()->json(['status' => 'true', 'user_id' => $inverter->id]);
     
             } else {
                 // Create a new device
                 $company_id = $userData['company_id'] ?? '';
     
                 // Handle install_code verification for new devices
                 if ($request->has('install_code') && $request->install_code) {
                     $exits = CompanyAgentDetail::where('company_id', $company_id)
                         ->where('install_code', (int) $request->install_code)
                         ->first();
     
                     if (!$exits) {
                         return response()->json(['status' => 'exits', 'response_msg' => "The installation code doesn't match our company"]);
                     } elseif ($exits->used == 1) {
                         return response()->json(['status' => 'exits', 'response_msg' => "The installation code already used"]);
                     }
     
                     $exits->used = 1;
                     $exits->save();
                 }
     
                 // Save new device data
                 $inverter = new Inverter();
                 $inverter->user_id = $userData['id'];
                 $inverter->name = $request->device_name ?? '';
                 $inverter->description = $request->description ?? '';
                 $inverter->macid = $request->macid ?? '';
                 $inverter->serial_no = $request->serial_no ?? '';
                 $inverter->location = $locationData;
                 $inverter->hardware = $request->hardware ?? '';
                 $inverter->cluster_id = $request->cluster_id ?? '';
                 $inverter->status = $request->status ? 1 : 0;
                 $inverter->company_id = $company_id;
                 $inverter->install_code = $request->install_code ?? '';
                 if ($request->has('install_code') && $request->install_code) {
                     $agent_id = getAgentId($company_id, $request->install_code);
                     $inverter->agent_id = $agent_id ?? '';
                 }
                 $inverter->created_by = $userData['id'];
                 $inverter->created_at = $now_time;
                 $inverter->updated_at = $now_time;
                 $inverter->save();
     
                 // Log daily activity
                 $dataActivity = [
                     'user_id' => $userData['id'],
                     'device_id' => $inverter->id,
                     'company_id' => $company_id,
                     'macid' => $request->macid ?? '',
                     'status' => 'new_device_add',
                 ];
                 saveDailyActivity($dataActivity);
     
                 // Add the new device
                 $route = 'add_inverter';
                 $data = [
                     'macid' => $request->macid,
                     'user_id' => $userData['id'],
                 ];
                 $this->addDiviceApi($route, $data);
     
                 return response()->json(['status' => 'true', 'user_id' => $inverter->id]);
             }
     
         } else {
             return response()->json(['status' => 'false', 'message' => 'Required fields missing']);
         }
     }
       
     // get the 
     
     public function saveCluster(Request $request)
     {
         // Fetch user data from the JWT token using JwtHelper
         $userData = JwtHelper::getUserData();
     
         if ($request->has('name') && $request->name && $request->has('map_address') && $request->map_address)
         {
             $mytime = Carbon::now();
             $now_time = $mytime->toDateTimeString();
             
             $locationData = [
                 'lat'     => $request->lat ?? '',
                 'long'    => $request->long ?? '',
                 'address' => $request->map_address ?? '',
             ];
     
             // Fetch company_id from JWT payload if available
             $company_id = $userData['company_id'] ?? '';
     
             \DB::table('cluster')->insert([
                 'name'       => $request->name ?? '',
                 'location'   => $locationData,
                 'status'     => (isset($request->status) && $request->status ? 1 : 0),
                 'company_id' => $company_id,
                 'created_by' => $userData['id'] ?? '',  // Use the user ID from JWT token
                 'created_at' => $now_time,
                 'updated_at' => $now_time
             ]);
     
             return response()->json(['status' => 'true']);
         }
         else
         {
             return response()->json(['status' => 'false','message'=>'data is not inserted']);
         }
     }
     
     
     // get the devices Groups 
     public function getTmpDeviceListClusterWise(Request $request)
     {
         $post = [];
         $post['limit']  = $request->input('limit', 4);  // Get limit from request or default to 4
         $post['offset'] = $request->input('offset', 0); // Get offset from request or default to 0
     
         // Use helper to get JWT payload data
         $userData = JwtHelper::getUserData();
         $adminRoleId = \Config::get('constants.roles.Master_Admin');
         $search      = $request->input('search_name', '');
         $company_id  = $request->input('company_id', '');
     
         // Start building the query
         $listData = Cluster::with('inverters')->select('*');
     
         // Role-based data filtering
         if ($userData['role_id'] != $adminRoleId) {
             // Non-admin users should only access their own company's data
             $company_id = $userData['company_id'];  // Ensure company_id is set to the current user's company
             $listData->where('company_id', $company_id);
         } else {
             // Admin users can filter by the company_id provided in the request
             if (!empty($company_id)) {
                 $listData->where('company_id', $company_id);
             }
         }
     
         // Apply search, pagination, and other filters
         $listData->skip($post['offset'])
                  ->take($post['limit'])
                  ->when($search, function ($query, $search) {
                      $query->where('name', 'like', "%{$search}%");
                  })
                  ->where('status', 1)
                  ->orderBy('created_at', 'desc');
     
         $listData = $listData->get();
         $responseData = []; // This array will store the modified data for the response
         function getActiveIconSettings($company_id) {
             // Assuming you have an Icon model with an 'active' column
             return IconSetting::where('company_id', $company_id)
                         ->where('status', 'active')  // Assuming 'status' indicates active/inactive
                         ->get();
         }
         // Check if data is found
         if ($listData && $listData->count() > 0) {
             foreach ($listData as $key => $list) {
                 $clusterData = $list->toArray();  // Convert cluster data to array
     
                 // Ensure inverters relationship is loaded and iterate through it
                 if ($list->inverters && $list->inverters->count() > 0) {
                     $clusterData['system_calculated'] = [];
                     $clusterData['sub_system_calculated'] = [];
                     $clusterData['battery_data'] = [];
                     $clusterData['PV'] = [];
     
                     foreach ($list->inverters as $inverter) {
                         // Access the macid from each inverter
                         $macid = $inverter->macid;
     
                         // Fetch related data based on the macid
                         $System_calculated     = Data::where('data->Contain', 'System_calculated')
                                                      ->where('macid', $macid)
                                                      ->orderBy('created_at', 'desc')
                                                      ->first();
                         $Sub_System_calculated = Data::where('data->Contain', 'Sub_System_calculated')
                                                      ->where('macid', $macid)
                                                      ->orderBy('created_at', 'desc')
                                                      ->first();
                         $battery               = Data::where('data->Contain', 'Battery')
                                                      ->where('macid', $macid)
                                                      ->orderBy('created_at_timestamp', 'desc')
                                                      ->first();
                         $PV                    = Data::where('data->Contain', 'PV')
                                                      ->where('macid', $macid)
                                                      ->orderBy('created_at_timestamp', 'desc')
                                                      ->first();
     
                         // Add fetched data to the array
                         $clusterData['system_calculated'][] = $System_calculated;
                         $clusterData['sub_system_calculated'][] = $Sub_System_calculated;
                         $clusterData['battery_data'][] = $battery;
                         $clusterData['PV'][] = $PV;
                     }
                 } else {
                     // Handle cases where no inverters are found
                     $clusterData['system_calculated'] = null;
                     $clusterData['sub_system_calculated'] = null;
                     $clusterData['battery_data'] = null;
                     $clusterData['PV'] = null;
                 }
     
                 // Add the modified cluster data to the response array
                 $responseData[] = $clusterData;
             }
             $activeIcons = getActiveIconSettings($company_id);
             // Prepare the response data
             $colors = ['bg-primary br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4', 'bg-info br-tr-4 br-tl-4', 'bg-warning br-tr-4 br-tl-4', 'bg-secondary br-tr-4 br-tl-4', 'bg-danger br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4'];
             $data = [
                 'cluster_list' => $responseData,
                 'card_colors'  => $colors,
                 'icons_setting' => $activeIcons,
             ];
     
             // Return JSON response with the data
             return response()->json([
                 'is_data' => true,
                 'data'    => $data,
                 'offset'  => $post['offset'] + $post['limit'],
             ]);
         } else {
             // Return a response when no data is found
             return response()->json([
                 'is_data' => false,
                 'offset'  => $post['offset'] + $post['limit'],
                 'message' => 'No more data available',
             ]);
         }
     }
     
     
     
     // api for url http://3.7.110.174/device_details/65e5f198f6f78502b9021270 after click on view 
     
         public function deviceDetailPageApi(Request $request, $id = '', $deviceId = '')
         {
            
             $payload = JwtHelper::getUserData(); 
         
            
             if (!$payload) {
                 return response()->json(['error' => 'Unauthorized'], 401); 
             }
         
     
             $userRoleId = $payload['role_id']; 
         //master admin role id - 6440caeb7f86dd3c207e508f
      
             $isActive = $payload['is_active'];  
            // $companyId = $payload['company_id']; 
         
             $adminRoleId = \Config::get('constants.roles.Master_Admin');
         
          
             if ($isActive == 0 && $userRoleId != $adminRoleId) {
                 return response()->json(['error' => 'Not found'], 404); 
             }
         
             
             if ($deviceId) {
                 $id = $deviceId;
             }
         
            
             $dataOne = Device::find($id);
             if (!$dataOne) {
                 return response()->json(['error' => 'Device not found'], 404);
             }
         
             
             // if ($this->isCompanyLogin() === false && $deviceId) {
             //     $this->companyLoginByAdmin($dataOne->company_id);
             // }
         
          
             $machineData = Data::where('macid', $dataOne->macid)->orderBy('created_at', 'desc')->first();
             $machine_status = "ON";
         
             if (isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected") {
                 $machine_status = "OFF";
             } elseif (isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected") {
                 $machine_status = "Inverter_disconnected";
             }
         
            
             $now = Carbon::now();
             $last5Minutes = $now->subMinutes(5);
             $latestRecord = Data::where('macid', $dataOne->macid)
                                 ->where('created_at', '>=', $last5Minutes)
                                 ->get();
         
             $is_latest_data = count($latestRecord) > 0;
         
           
             $data = [
                 'title'           => 'Home',
                 'module'          => "System Overview",
                 'heading'         => "POWRBANK Details",
                 'device_details'  => $dataOne,
                 'id'              => $id,
                 'machine_status'  => $machine_status,
                 'is_latest_data'  => $is_latest_data,
             ];
         
             return response()->json($data, 200); 
         }
         
     
     
         // battery details page api 
         
         public function batteryDetailPage(Request $request, $id = '', $deviceId = ''){
     
             $payload = JwtHelper::getUserData(); 
             if (!$payload) {
                 return response()->json(['error' => 'Unauthorized'], 401); 
             }
             $userRoleId = $payload['role_id'];  
             $isActive = $payload['is_active'];
             $adminRoleId = \Config::get('constants.roles.Master_Admin');
             if ($isActive == 0 && $userRoleId != $adminRoleId) {
                 return response()->json(['error' => 'Not found'], 404); 
             }
             if ($deviceId) {
                 $id = $deviceId;
             }
             $dataOne = Device::find($id);
         if (!$dataOne) {
             return response()->json(['error' => 'Battery not found'], 404); // Return 404 if battery device not found
         }
         $machineData = Data::where('macid', $dataOne->macid)->orderBy('created_at', 'desc')->first();
         $machine_status = "ON";
     
         if (isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected") {
             $machine_status = "OFF";
         } elseif (isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected") {
             $machine_status = "Inverter_disconnected";
         }
     
         // Get data from the last 5 minutes
         $now = Carbon::now();
         $last5Minutes = $now->subMinutes(5);
         $latestRecord = Data::where('macid', $dataOne->macid)
                             ->where('created_at', '>=', $last5Minutes)
                             ->get();
     
         $is_latest_data = count($latestRecord) > 0;
     
         //  response data
         $data = [
             'title'            => 'Home',
             'module'           => "System Overview",
             'heading'          => "Battery",
             'battery_details'  => $dataOne,
             'id'               => $id,
             'machine_status'   => $machine_status,
             'is_latest_data'   => $is_latest_data,
         ];
     
         return response()->json($data, 200);
     
         }
     
         
         // edit device page 
         public function editDeviepage(Request $request,$id='',$deviceId='')   {
             $payload = JwtHelper::getUserData(); 
             if (!$payload) {
                 return response()->json(['error' => 'Unauthorized'], 401); 
             }
             $userRoleId = $payload['role_id'];  
             $isActive = $payload['is_active'];
             $userCompanyId = $payload['company_id'];
             $adminRoleId = \Config::get('constants.roles.Master_Admin');
             if ($isActive == 0 && $userRoleId != $adminRoleId) {
                 return response()->json(['error' => 'Not found'], 404); 
             }
             if ($deviceId) {
                 $id = $deviceId;
             }
             $dataOne = Device::where('_id', $id)->first();
             if (!$dataOne) {
                 return response()->json(['error' => 'Device not found'], 404); // Return 404 if device not found
             }
         
             // Determine company ID based on user's session or role
             if (session()->has('company_login_id')) {
                 $company_id = session()->get('company_login_id');
             } else if ($userRoleId == $adminRoleId) {
                 $company_id = $dataOne->company_id; // Master Admin can access any company's devices
             } else {
                 $company_id = $userCompanyId; // For non-admin users, restrict to their company
             }
             $clusters = Cluster::where(['status' => 1, 'company_id' => $company_id])->get();
     
       
             $data = [
                 'title'     => 'Home',
                 'module'    => "System Overview",
                 'heading'   => "POWRBANK",
                 'clusters'  => $clusters,
                 'device'    => $dataOne,
             ];
             return response()->json(['message'=>'edit data is here','data'=>$data]);   
         }
     
}
