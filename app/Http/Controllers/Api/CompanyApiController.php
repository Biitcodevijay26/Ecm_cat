<?php

namespace App\Http\Controllers\Api;
use App\Models\Cluster;
use App\Models\Company;
use App\Models\Data;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \DataTables;
use App\Helpers\JwtHelper;

class CompanyApiController extends Controller
{
    // get the of company list 
    public function list()
    {
        isCompanyLogin();
        $data = [
            'title'   => 'Home',
            'module'  => "Company",
            'heading' => "Company",
        ];

        return view('master.company.list', $data);
    }
// get list of company 



public function listCompany(Request $request)
{
    

    // Normalize input for pagination
    $request->merge(array(
        'start' => (int)$request->input('start', 0),  // Default to 0 if not set
        'length' => (int)$request->input('length', 10) // Default to 10 if not set
    ));

    // Eloquent query to get companies with their owners
    $company = Company::with(['companyOwner' => function($query) {
        $company_admin = \Config::get('constants.roles.Company_Admin');
        $query->where([
            'role_id' => $company_admin, 
            'status' => 1, 
            'is_active' => 1
        ]);
    }])->select('*');

    
    $search_name = $request->get('search_name');
    if ($search_name) {
        $company->where('company_name', 'like', "%{$search_name}%");
    }

    // Use Datatables to format the result
    $data = Datatables::of($company)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            // Define action buttons
            $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-company-name="'.$row->company_name.'" data-status="'.$row->status.'" class="btn btn-icon  btn-primary me-2 editCompany" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';

            if ($row->status == 1) {
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompany" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
            } else {
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompany" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
            }
            return $actionBtn;
        })
        ->addColumn('status', function ($row) {
            return $row->status == 1 
                ? '<span class="badge bg-success">Active</span>' 
                : '<span class="badge bg-danger">Inactive</span>';
        })
        ->addColumn('company_login', function ($row) {
            return '<a href="/company/'.$row->id.'/dashboard" class="btn btn-icon  btn-primary me-2" title="Click to View Company"> <i class="fe fe-eye" aria-hidden="true"></i></a>';
        })
        ->addColumn('created_at', function ($row) {
            $startDate = date_create($row->created_at);
            return date_format($startDate, "Y-m-d h:m A");
        })
        ->addColumn('company_owner', function ($row) {
            $owner_name = [];
            if (count($row->companyOwner) > 0) {
                foreach ($row->companyOwner as $key => $value) {
                    if ($key == 0) {
                        $owner_name[] = $value['first_name'] . ' ' . $value['last_name'];
                    }
                }
            }
            return implode(', ', $owner_name);
        })
        ->orderColumn('company_name', function ($query, $order) {
            $query->orderBy('company_name', $order);
        })
        ->orderColumn('created_at', function ($query, $order) {
            $query->orderBy('created_at', $order);
        })
        ->rawColumns(['action', 'status', 'company_login'])
        ->make(true);
      
    // Format the response in a standardized JSON API format
    return response()->json([
        'status' => 'success',
        'message' => 'Company list retrieved successfully',
        'data' => $data->getData(), // Fetch the Datatables object and its data
    ], 200);
}

// add new company record
public function saveCompany(Request  $request)
{
     $userdata=JwtHelper::getUserData();
     
     $userId = $userdata['user_id'] ?? null;
     $userRole = $userdata['role'] ?? null;
     if (!$userId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized access. Invalid token.'
        ], 401);
    }
    $validatedData = $request->validate([
        'company_name' => 'required|string|max:255',
        'status' => 'sometimes|boolean',
        'company_id' => 'sometimes|integer' // For updating the company
    ]);

    $input = $request->all();
    if (isset($input['company_name']) && $input['company_name']) {
        if (isset($input['company_id']) && $input['company_id']) {
            $company = Company::find($input['company_id']);
            if ($company) {
                $company->company_name = $input['company_name'] ?? '';
                $company->status = isset($input['status']) && $input['status'] == 1 ? 1 : 0;
                $company->save();
                
                $response['status'] = 1;
                $response['message'] = 'Company updated successfully';
            } else {
                // If company not found
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company not found.'
                ], 404);
            }
        } else {
            // Create a new company record
            $company = new Company();
            $company->company_name = $input['company_name'] ?? '';
            $company->status = isset($input['status']) && $input['status'] == 1 ? 1 : 0;
            $company->save();
            
            $response['status'] = 1;
            $response['message'] = 'Company created successfully';
        }

        // Return success response
        return response()->json($response, 200);

    } else {
        // If company_name is not provided
        return response()->json([
            'status' => 0,
            'message' => 'Company name is required.'
        ], 400);
    }
}

// active inactive status 
public function getActiveInactive(Request $request){
    $userdata=JwtHelper::getUserData();
    $company_id=$userdata['company_id'];
    
    $company = Company::where('_id',$company_id)->first();
    if($company){

        $status = 1;
        if($company->status == 1){
            $status = 0;
        } else {
            $status = 1;
        }

        $company->status = $status;
        $company->save();

        return response()->json(['status'=>$status]);
    } else {
        return '0';
    }
}


// company page login 
public function  companyLogin(Request $request,$company_id,$id=''){
    $userdata=JwtHelper::getUserData();

    // if()
   
    $company = Company::find($company_id);
    if(!$company)
    {
       // abort(404);
       return response()->json(['message'=>'company not found',404]);
    }
    companyLoginByAdmin($company_id);
    $filterMacIds = [];
    if($id){
        $filterMacIds = getFilterMacIds($id);
    }
    $connected_minits   =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
    $comAdminRoleId     = \Config::get('constants.roles.Company_Admin');
    $macids             = Device::where(['company_id' => $company->id])->pluck('macid')->toArray();

    $adminrole = \Config::get('constants.roles.Master_Admin');
     if($filterMacIds){
        $device_notification = DeviceNotification::whereIn('macid', $filterMacIds)->with('notification')->latest()->take(20)->get();
    } else {
        $device_notification = DeviceNotification::whereIn('macid', $macids)->with('notification')->latest()->take(20)->get();
    }

    $data=[];

    $data[]=[
        'admin_role' =>  $adminrole ,
       // 'daily_activities' =>  getDailyActivities($company->id),
        'device_notification'=> $device_notification,
        'group_dropdown'=> Cluster::where(['company_id' => $company->id, 'status' => 1])->get(),
        'powerbank_dropdown'=> Device::where(['company_id' => $company->id])->get(),
        'filter_id'=> $id,
        'is_company_login' => 'true'

    ];
     
    return response()->json($data);

   

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


// 

}