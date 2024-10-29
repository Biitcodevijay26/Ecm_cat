<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;
use App\Models\Cluster;
use App\Models\CurrencyRate;
use App\Models\Data;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use App\Models\User;
use App\Models\Inverter;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class DashboardApiController extends Controller
{
    // 
    public function index1()
    {
        $userData = JwtHelper::getUserData();


        if ($userData) {
            // echo  $userData['role_id'];
            // die;
            return response()->json([
                'success' => true,
                'data' => $userData['role_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid token or no user data found',
        ], 401);
    }

    // api for index method
    // the index method for api 

public function apiindex(Request $request,$id=''){
//   jwt token 

$userData = JwtHelper::getUserData();

    $data = [];
    $filterMacIds = [];
    if($id) {
        $filterMacIds = getFilterMacIds($id); // 
    }
    $user = auth('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401); // Return 401 if not authenticated
    }
    $data['user_currency']      = $user->currency ?? 'USD';
    $data['user_liquid_unit']   = $user->liquid_unit ?? 'gallons';
    $data['user_weight_unit']   = $user->weight_unit ?? 'lbs';
    $data['title']         = 'Dashboard';
    $data['totalUser']     = null;
    $data['totalInveter']  = null;
    $data['admin_role']       = \Config::get('constants.roles.Master_Admin');
    $connected_minits         = \Config::get('constants.CONNECTED_TIME_IN_MINITS');
    if ($userData['role_id'] == \Config::get('constants.roles.Master_Admin')) {

        $company_login_id = $request->user()->company_login_id; //?? session()->get('company_login_id');

        if ($company_login_id) {
            // Get dropdown data for Master Admin with a specific company ID
            $group_dropdown = Cluster::where(['company_id' => $company_login_id, 'status' => 1])->get();
            $powerbank_dropdown = Device::where(['company_id' => $company_login_id])->get();
        } else {
            // Get dropdown data for Master Admin without a specific company
            $powerbank_dropdown = Device::all();
            $group_dropdown = Cluster::where(['status' => 1])->get();
        }

        // Fetch device notifications based on the filter
        $data['device_notification'] = DeviceNotification::with('notification')
            ->when($filterMacIds, function ($query) use ($filterMacIds) {
                $query->whereIn('macid', $filterMacIds);
            })
            ->latest()->take(20)->get();

        $data['group_dropdown']     = $group_dropdown;
        $data['powerbank_dropdown'] = $powerbank_dropdown;
        $data['filter_id']          = $id;

        // Return data as JSON
        return response()->json($data);

    } else {
        // Handle non-Master Admin users (e.g., Company Admin)

        // Get the company ID from the token payload
        $company_id = $userData['company_id'];

        // Get macids for devices related to the user's company
        $macids = Device::where(['company_id' => $company_id])->pluck('macid')->toArray();

        // Fetch device notifications based on the filter or all macids
        if ($filterMacIds) {
            $device_notification = DeviceNotification::whereIn('macid', $filterMacIds)
                ->with('notification')
                ->latest()
                ->take(20)
                ->get();
        } else {
            $device_notification = DeviceNotification::whereIn('macid', $macids)
                ->with('notification')
                ->latest()
                ->take(20)
                ->get();
        }

        // Prepare data for non-Master Admin users
        $data['device_notification'] = $device_notification;
        $data['is_company_login']    = 'false';
        $data['group_dropdown']      = Cluster::where(['company_id' => $company_id, 'status' => 1])->get();
        $data['powerbank_dropdown']  = Device::where(['company_id' => $company_id])->get();
        $data['filter_id']           = $id;

        // Return data as JSON
        return response()->json($data);
    }

 
}
// get api for getalert 
public function getAlerts(Request $request)
{
    try {
        // Parse the token and get the payload
        $token = JWTAuth::parseToken();
        $payload = JWTAuth::getPayload();

        // Get the role_id from the payload
        $role_id = $payload->get('role_id');
        $company_id = $payload->get('company_id'); // 

        // Get the filter_id from request
        $filter_id = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $filterMacIds = [];
        if ($filter_id) {
            $filterMacIds = getFilterMacIds($filter_id);
        }

        $data = [];

        // Check if the role_id is Master Admin
        if ($role_id == \Config::get('constants.roles.Master_Admin')) {
            // Check for company_login_id in session
            $company_login_id = session()->get('company_login_id');
            if ($company_login_id) {
                $data = DeviceWarning::with(['warning'])->where('company_id', $company_login_id)
                    ->when($filterMacIds, function($query) use ($filterMacIds) {
                        $query->whereIn('macid', $filterMacIds);
                    })->latest()->take(20)->get();
            } else {
                $data = DeviceWarning::with(['warning'])
                    ->when($filterMacIds, function($query) use ($filterMacIds) {
                        $query->whereIn('macid', $filterMacIds);
                    })->latest()->take(20)->get();
            }

            if ($data) {
                foreach ($data as $key => $value) {
                    $value->device_name = getDeviceNameByMacId($value->macid);
                    $value->warning_title = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                    $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                    $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                    $value->code_date_format = $formattedDate;
                }
            }
        } else {
            // Handle non-Master_Admin roles
            if ($filterMacIds) {
                $data = DeviceWarning::with('warning')
                    ->where('company_id', $company_id)
                    ->when($filterMacIds, function($query) use ($filterMacIds) {
                        $query->whereIn('macid', $filterMacIds);
                    })->latest()->take(20)->get();
                
                if ($data) {
                    foreach ($data as $key => $value) {
                        $value->device_name = getDeviceNameByMacId($value->macid);
                        $value->warning_title = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                        $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                        $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                    }
                }
            } else {
                // Get the list of mac IDs for the current user's company
                $macids = Device::where(['company_id' => $company_id])->pluck('macid')->toArray();
                $data = DeviceWarning::whereIn('macid', $macids)
                    ->with('warning')->latest()->take(20)->get();
                
                if ($data) {
                    foreach ($data as $key => $value) {
                        $value->device_name = getDeviceNameByMacId($value->macid);
                        $value->warning_title = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                        $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                        $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                    }
                }
            }
        }

        return response()->json($data);

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token not provided or invalid'
        ], 401);
    }
}
// get saving
public function getMySavings(Request $request)
{
    try {
        // Get the token payload using JWTAuth
        $token = JWTAuth::parseToken();
        $payload = JWTAuth::getPayload();

        // Get the necessary user data from the token
        $user_id = $payload->get('sub');
        $role_id = $payload->get('role_id');
        $company_id = $payload->get('company_id');
        $currency = $payload->get('currency') ?? '';

        // Handle filter data
        $filter_id = $request->filter_id ?? '';
        $filterMacIds = [];
        if ($filter_id) {
            $filterMacIds = getFilterMacIds($filter_id);
        }

        $data = [];
        
        // Role check using role_id from the token
        // if ($role_id == \Config::get('constants.roles.Master_Admin')) {
        //     // Get company_login_id from session if exists
        //     $company_login_id = session()->get('company_login_id');
        //     if ($company_login_id) {
        //         $data = $this->getDashboardPieChart($company_login_id, $filterMacIds);
        //     } else {
        //         $data = $this->getDashboardPieChart('', $filterMacIds);
        //     }
        // } else {
        //     // Use company_id from the token for other roles
        //     $data = $this->getDashboardPieChart($company_id, $filterMacIds);
        // }

        // Handle currency and rates
        $user_currency_rate = 0;
        $user_currency_sign = '';
        
        if ($currency && $currency != 'USD') {
            $rates = CurrencyRate::where('base_currency', 'USD')->first();
            $contriesRates = isset($rates->rates) ? json_decode($rates->rates, true) : [];

            if ($contriesRates) {
                if (isset($contriesRates[$currency])) {
                    $user_currency_rate = $contriesRates[$currency];
                    $user_currency_sign = $currency;
                }
            }
        }

        // Add currency data to response
        $data['user_currency_rate'] = $user_currency_rate;
        $data['user_currency_sign'] = $user_currency_sign;

        return response()->json($data);

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token not provided or invalid'
        ], 401);
    }
}
// group lists 
public function getGroupList(Request $request)
{
    try {
        // Parse the JWT token and retrieve payload
        $token = JWTAuth::parseToken();
        $payload = JWTAuth::getPayload();

        // Get user role_id and company_id from token payload
        $role_id = $payload->get('role_id');
        $company_id = $payload->get('company_id');

        // Prepare the query
        $Records = Cluster::select('*')->with(['company', 'device:_id,cluster_id']);

        // Role-based filtering
        if ($role_id == \Config::get('constants.roles.Master_Admin')) {
            $company_login_id = session()->get('company_login_id');
            if ($company_login_id) {
                $Records->where(['company_id' => $company_login_id]);
            }
        } else {
            $Records->where(['company_id' => $company_id]);
        }

        // Filter only active clusters
        $Records->where('status', 1);
        $data = $Records->get();

        // Format the data for the API response
        $responseData = $data->map(function($row) use ($role_id) {
            return [
                'id' => $row['_id'],
                'name' => $row->name,
                'company_name' => isset($row->company->company_name) ? $row->company->company_name : '',
                'address' => isset($row->location['address']) ? $row->location['address'] : '',
                'device_count' => isset($row->device) ? count($row->device) : 0,
                'system_overview_url' => $role_id == \Config::get('constants.roles.Master_Admin') 
                    ? url('/company/' . $row['company_id'] . '/system-overview') 
                    : url('/system-overview')
            ];
        });

        // Return the data as JSON
        return response()->json([
            'success' => true,
            'data' => $responseData
        ], 200);

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token not provided or invalid'
        ], 401);
    }
}

// daily activity api
public function getDailyActivity(Request $request)
{
    $userData = JwtHelper::getUserData();

    if ($userData) {
        $role_id = $userData['role_id'];
        $company_id = $userData['company_id'];

        $filter_id = $request->filter_id ?? '';
        $data = [];

        // Check if the user is authenticated
        $authUser = auth('api')->user();
        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Checking role as Master_Admin
        if ($authUser->role_id == \Config::get('constants.roles.Master_Admin')) {
            $company_login_id = $userData['company_id'];
            if ($company_login_id) {
                $data = getDailyActivity($company_login_id, $filter_id);
            } else {
                $data = getDailyActivity('', $filter_id);
            }
        } else {
            // Check if the authenticated user has company_id
            if (isset($authUser->company_id)) {
                $company_id = $authUser->company_id;
                $data = getDailyActivity($company_id, $filter_id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Company ID not available for the user'
                ], 400);
            }
        }

        return response()->json($data);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid JWT Token or user data not available'
    ], 401);
}

// powerbank ussage notification
public function getPowerBankNotification(Request $request)
{
    // Fetch user data from the JWT token using the JwtHelper
    $userData = JwtHelper::getUserData();

    if (!$userData) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid token or user not authenticated'
        ], 401);
    }

    $role_id = $userData['role_id'];
    $company_id = $userData['company_id']; // Access the company_id from JWT payload

    $filter_id = (isset($request->filter_id) && $request->filter_id) ? $request->filter_id : '';
    $filterMacIds = [];

    if ($filter_id) {
        $filterMacIds = getFilterMacIds($filter_id);
    }

    $data = [];

    // If the role is Master_Admin (according to token payload)
    if ($role_id == \Config::get('constants.roles.Master_Admin')) {
        $company_login_id = $company_id; // Get company ID from JWT token
        if ($company_login_id) {
            $data = DeviceNotification::with('notification')
                ->where('company_id', $company_login_id)
                ->when($filterMacIds, function ($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                })
                ->latest()
                ->take(20)
                ->get();
        } else {
            $data = DeviceNotification::with('notification')
                ->when($filterMacIds, function ($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                })
                ->latest()
                ->take(20)
                ->get();
        }
    } else {
        // Non Master_Admin users, using company_id from JWT token
        if ($filterMacIds) {
            $data = DeviceNotification::with('notification')
                ->where('company_id', $company_id)
                ->when($filterMacIds, function ($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                })
                ->latest()
                ->take(20)
                ->get();
        } else {
            $macids = Device::where(['company_id' => $company_id])
                ->pluck('macid')
                ->toArray();

            $data = DeviceNotification::whereIn('macid', $macids)
                ->with('notification')
                ->latest()
                ->take(20)
                ->get();
        }
    }

    // Process the data
    if ($data) {
        foreach ($data as $key => $value) {
            $value->device_name = getDeviceNameByMacId($value->macid);
            $value->notification_title = isset($value->notification) && $value->notification->title ? $value->notification->title : '';
            $value->notification_message = isset($value->notification) && $value->notification->message ? $value->notification->message : '';
            $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
            $value->code_date_format = $formattedDate;
        }
    }

    return response()->json($data);
}

// get powebank list

public function getDeviceListApi(Request $request)
{
    // Process pagination inputs from the request
    $start  = (int)$request->input('start', 0);
    $length = (int)$request->input('length', 10);

    // Get the base query for the device records
    $Records = Device::select('*')->with(['company', 'cluster']);

    // Check if the user is a Master Admin
    if (auth('api')->user()->role_id == \Config::get('constants.roles.Master_Admin')) {
        $company_login_id = session()->get('company_login_id');
        if ($company_login_id) {
            $Records->where(['company_id' => $company_login_id]);
        }
    } else {
        // Restrict the records to the user's company
        $Records->where(['company_id' => auth()->guard('admin')->user()->company_id]);
    }

    // Pagination logic
    $data = $Records->skip($start)->take($length)->get();

    // Prepare the records for the API response
    $result = [];
    foreach ($data as $row) {
        $rowData = [
            'id'              => $row->_id,
            'name'            => $this->getDeviceNameColumn($row),
            'verifed'         => $this->getVerifiedColumn($row),
            'company_name'    => isset($row->company->company_name) ? $row->company->company_name : '',
            'group_name'      => isset($row->cluster->name) ? $row->cluster->name : '',
            'address'         => isset($row->location['address']) ? $row->location['address'] : '',
            'connected'       => $this->deviceIsConnected($row->macid),
            'SOC'             => $this->getSOC($row->macid),
            'current_status'  => $this->getCurrentStatus($row->macid),
        ];
        $result[] = $rowData;
    }

    // Return the JSON response with the paginated data
    return response()->json([
        'data' => $result,
        'recordsTotal' => $Records->count(),
        'recordsFiltered' => $Records->count(),
    ]);
}

// Helper method to handle name column logic
private function getDeviceNameColumn($row)
{
    if (auth('api')->user()->role_id == \Config::get('constants.roles.Master_Admin')) {
        $id = $row['_id'];
        $company_id = $row['company_id'];
        $url = url("/company/$company_id/device_details/$id");
        return "<a href='$url'>" . (isset($row->name) && $row->name ? $row->name : '') . "</a>";
    } else {
        $id = $row['_id'];
        return "<a href='" . url("/device_details/$id") . "'>" . (isset($row->name) && $row->name ? $row->name : '') . "</a>";
    }
}

// Helper method to determine if the device is verified
private function getVerifiedColumn($row)
{
    if (isset($row->is_verified) && $row->is_verified == 1) {
        return '<span class="badge bg-status-verified text-white">Verified</span>';
    } else {
        return '<span class="badge bg-status-no text-white">Not Verified</span>';
    }
}

// Helper method to get SOC
private function getSOC($macid)
{
    $record = Data::where('macid', $macid)
        ->where('data.data.Contain', 'Battery')
        ->orderBy('created_at', 'desc')
        ->options(['allowDiskUse' => true])
        ->first();

    if (isset($record->data['data']['Status']['SOC(%)'])) {
        return $record->data['data']['Status']['SOC(%)'] . '%';
    } else {
        return '0%';
    }
}

// Helper method to get the current status of a device
private function getCurrentStatus($macid)
{
    if ($macid) {
        return deviceCurrentStatus($macid);
    }
    return null;
}

public function deviceIsConnected($macid='')
{
    if($macid)
    {
        $connected_minits =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
        $records = Data::where('macid',$macid)->where("created_at",">",Carbon::now()->subMinutes($connected_minits))->groupBy('macid')->get()->count();
        if($records > 0)
        {
            return '<span class="badge bg-status-yes text-white">Yes</span>';
        } else {
            return '<span class="badge bg-status-off text-white">No</span>';
        }
    }
}

// account summery 

public function getAccountSummery(Request $request)
{
    $admin_role       = \Config::get('constants.roles.Master_Admin');
    $connected_minits = \Config::get('constants.CONNECTED_TIME_IN_MINITS');
    $data = [];

    // Get the authenticated user
    $userData = JwtHelper::getUserData();
    $user = auth('api')->user();
    if ($user['role_id'] == \Config::get('constants.roles.Master_Admin')) {
        // Admin sections
        //$company_login_id = session()->get('company_login_id');
        $company_login_id = $user->company_id;
        if ($company_login_id) {
            $group_counts    = Cluster::where(['company_id' => $company_login_id, 'status' => 1])->get();
            $device_counts   = Device::where('company_id', $company_login_id)->count();
            $verified_device_count     = Device::where(['company_id' => $company_login_id, 'is_verified' => 1])->count();
            $unverified_device_count   = Device::where('company_id', $company_login_id)->where('is_verified', '!=', 1)->count();
        } else {
            $group_counts              = Cluster::where(['status' => 1])->count();
            $device_counts             = Device::count();
            $verified_device_count     = Device::where('is_verified', 1)->count();
            $unverified_device_count   = Device::where('is_verified', '!=', 1)->count();
        }

        $data['user_count_admin']    = User::where('role_id', '!=', \Config::get('constants.roles.Master_Admin'))->count();
        $data['device_count_admin']  = $device_counts;
        $data['cluster_count_admin'] = $group_counts ?? 0;
        $data['verified_device_count_admin'] = $verified_device_count;
        $data['unverified_device_count_admin'] =  $unverified_device_count;
        $data['connected_device_count_admin']  = Data::where("created_at", ">", Carbon::now()->subMinutes($connected_minits))->options(['allowDiskUse' => true])->groupBy('macid')->get()->count();

    } else {
        // Users Sections
        $macids           = Device::where(['company_id' => $user->company_id])->pluck('macid')->toArray();
        $company_login_id = $user->company_id;

        $device_counts             = Device::where('company_id', $company_login_id)->count();
        $verified_device_count     = Device::where(['company_id' => $company_login_id, 'is_verified' => 1])->count();
        $unverified_device_count   = Device::where('company_id', $company_login_id)->where('is_verified', '!=', 1)->count();

        $data['user_count']           = User::where(['company_id' => $user->company_id])->where('role_id', '!=', \Config::get('constants.roles.Master_Admin'))->count();
        $data['device_count_admin']   = $device_counts ?? 0;
        $data['cluster_count_admin']  = Cluster::where(['company_id' => $user->company_id, 'status' => 1])->count();
        $data['verified_device_count_admin']   = $verified_device_count ?? 0;
        $data['unverified_device_count_admin'] = $unverified_device_count ?? 0;
        $data['connected_device_count_admin']  = Data::whereIn('macid', $macids)->where("created_at", ">", Carbon::now()->subMinutes($connected_minits))->options(['allowDiskUse' => true])->groupBy('macid')->get()->count();
    }

    return response()->json($data);
}




public function getPowerBankUsageNew(Request $request)
{
    $filter_id = $request->filter_id ?? ''; // Default to empty string if not set

    // Check if the user is authenticated
    $user = auth('api')->user();

    // Initialize data
    $data = [];

    if ($user->role_id == \Config::get('constants.roles.Master_Admin')) {
        // Admin role
        $company_login_id = $user->company_id;
        if ($company_login_id) {
            $data = $this->getDeviceUsageNew($company_login_id, $filter_id);
        } else {
            $data = $this->getDeviceUsageNew('', $filter_id); // No company ID
        }
    } else {
        // Non-admin role
        $company_id = $user->company_id;
        $data = $this->getDeviceUsageNew($company_id, $filter_id);
    }

    return response()->json($data);
}



public function getDeviceUsageNew($company_id='',$filter_id='')
{
    $filterMacIds = [];
    if($filter_id){
        $filterMacIds = getFilterMacIds($filter_id);
    }

    $data = [];
    if($company_id)
    {
        if($filterMacIds){
            // $macIds = Device::select('_id','name','macid','created_at')->where('company_id',$company_id)->whereIn('macid',$filterMacIds)->get();
            $macIds = Device::where('company_id', $company_id)->whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
        } else {
            $macIds = Device::where('company_id', $company_id)->pluck('macid')->toArray();
        }
    } else {
        if($filterMacIds){
            $macIds = Device::whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
        } else {
            $macIds = Device::pluck('macid')->toArray();
        }
    }

    if($macIds){
        $powrbank_runtime = 0;
        $charged_with_solar = 0;
        $charged_with_genset = 0;
        $total_fuel_used = 0;
        foreach ($macIds as $LRkey => $macid) {

            $latestData = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Battery_Discharge_Time(s)')
                ->orWhereNotNull('data.data.AC_Solar_Tot_Energy(Wh)')
                ->orWhereNotNull('data.data.DC_Solar_Energy(Wh)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($latestData){
                $AC_Solar_Tot_Energy = (int)$latestData->data['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0;
                $DC_Solar_Energy     = (int)$latestData->data['data']['DC_Solar_Energy(Wh)'] ?? 0;

                $powrbank_runtime   +=  (int)$latestData->data['data']['Battery_Discharge_Time(s)'] ?? 0;
                $charged_with_solar +=  $AC_Solar_Tot_Energy + $DC_Solar_Energy;
            }

            $ChargedWithGenset = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Gen_Tot_Energy(Wh)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($ChargedWithGenset){
                $charged_with_genset   +=  (int)$ChargedWithGenset->data['data']['Gen_Tot_Energy(Wh)'] ?? 0;
            }


            $FuelUsed = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Gen_Fuel_Utilized(L)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($FuelUsed){
                $total_fuel_used   +=  (int)$FuelUsed->data['data']['Gen_Fuel_Utilized(L)'] ?? 0;
            }


        }

        if($powrbank_runtime){
            $powrbank_runtime = $powrbank_runtime / 3600;
            $powrbank_runtime = number_format($powrbank_runtime, 1);
        }

        if($charged_with_solar){
            $charged_with_solar = $charged_with_solar / 1000;
            $charged_with_solar = number_format($charged_with_solar, 1);
        }

        if($charged_with_genset){
            $charged_with_genset = $charged_with_genset / 1000;
            $charged_with_genset = number_format($charged_with_genset, 1);
        }

        $total_fuel_used = (int)$total_fuel_used * config('constants.CONVERT_TO_GALLONS');
        $total_fuel_used = number_format($total_fuel_used, 1);
        $data = [
            'powrbank_runtime'    => $powrbank_runtime ?? 0,
            'charged_with_solar'  => $charged_with_solar ?? 0,
            'charged_with_genset' => $charged_with_genset ?? 0,
            'total_fuel_used'     => $total_fuel_used ?? 0,
        ];
        return $data;

    }
}

}
