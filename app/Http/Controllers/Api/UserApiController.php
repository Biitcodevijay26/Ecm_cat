<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserPin;
use App\Models\Company;
use App\Models\Country;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Hash;
use Gate;
class UserApiController extends Controller
{
    // 

    // public function saveUserApi(Request $request) 
    // {
    //     // Validate the JWT token and extract the authenticated user
    //     $authenticatedUser = auth('api')->user();  // Using 'api' as the JWT guard
    
    //     if (!$authenticatedUser) {
    //         return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    //     }
    
    //     // Validation for the request data
    //     $validator = Validator::make($request->all(), [
    //         'first_name'   => 'required',
    //         'last_name'    => 'required',
    //         'country_id'   => 'required',
    //         'city_name'    => 'required',
    //         'role_id'      => 'required',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
    //     }
    
    //     // Retrieve the company ID from JWT token or from request
    //     $company_login_id = $authenticatedUser->company_id;
    
    //     // Check if the user is updating an existing user
    //     if ($request->has('user_id') && $request->user_id) {
    //         $permissionCode = $request->permisionCode ?? [];
    
    //         // Save user permissions
    //         $this->saveUserPermission($request->user_id, $permissionCode);
    
    //         $user = User::find($request->user_id);
    //         if (!$user) {
    //             return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    //         }
    
    //         // Update user details
    //         $user->first_name = $request->first_name ?? '';
    //         $user->last_name = $request->last_name ?? '';
    //         $user->city_name = $request->city_name ?? '';
    //         $user->weight_unit = $request->weight_unit ?? '';
    //         $user->liquid_unit = $request->liquid_unit ?? '';
    //         $user->currency = $request->currency ?? '';
    
    //         // Update company_id if provided
    //         if ($request->has('company_id') && $request->company_id) {
    //             $user->company_id = $request->company_id ?? '';
    //         }
    
    //         $user->role_id = $request->role_id ?? '';
    //         $user->is_active = $request->is_active ? 1 : 0;
    
    //         // Update country information if country_id is provided
    //         if ($request->has('country_id')) {
    //             $country = Country::find($request->country_id);
    //             $countryData = [
    //                 'id' => $country->id,
    //                 'name' => $country->name ?? '',
    //                 'code' => $country->code ?? '',
    //                 'dial_code' => $country->dial_code ?? '',
    //             ];
    //             $user->country = $countryData;
    //         }
    
    //         // Hash the password if provided
    //         if ($request->has('password') && $request->password) {
    //             $user->password = Hash::make($request->password);
    //         }
    
    //         // Save the updated user
    //         $user->save();
    
    //         return response()->json(['status' => 'true']);
    //     } 
    //     // If no user_id, create a new user
    //     else {
    //         $adminRoleId = \Config::get('constants.roles.Master_Admin');
    //         $user = new User();
    
    //         // Set user details
    //         $user->first_name = $request->first_name ?? '';
    //         $user->last_name = $request->last_name ?? '';
    //         $user->email = $request->email ?? '';
    //         $user->city_name = $request->city_name ?? '';
    //         $user->weight_unit = $request->weight_unit ?? '';
    //         $user->liquid_unit = $request->liquid_unit ?? '';
    //         $user->currency = $request->currency ?? '';
    
    //         // Determine company_id based on the current user's role
    //         if ($request->has('company_id') && $request->company_id && $authenticatedUser->role_id == $adminRoleId) {
    //             $user->company_id = $request->company_id;
    //         } else {
    //             $user->company_id = $company_login_id ?? $authenticatedUser->company_id;
    //         }
    
    //         $user->role_id = $request->role_id ?? '';
    //         $user->status = 1;
    //         $user->is_active = $request->is_active ? 1 : 0;
    
    //         // Set country data
    //         if ($request->has('country_id')) {
    //             $country = Country::find($request->country_id);
    //             $countryData = [
    //                 'id' => $country->id,
    //                 'name' => $country->name ?? '',
    //                 'code' => $country->code ?? '',
    //                 'dial_code' => $country->dial_code ?? '',
    //             ];
    //             $user->country = $countryData;
    //         }
    
    //         // Hash the password if provided
    //         if ($request->has('password') && $request->password) {
    //             $user->password = Hash::make($request->password);
    //         }
    
    //         // Save the new user
    //         $user->save();
    
    //         // Save daily activity
    //         $dataActivity = [
    //             'user_id' => $user->id,
    //             'device_id' => '',
    //             'company_id' => $user->company_id ?? '',
    //             'macid' => '',
    //             'status' => 'new_user_added',
    //         ];
    //         saveDailyActivity($dataActivity);
    
    //         // Save user permissions
    //         $permissionCode = $request->permisionCode ?? [];
    //         $this->saveUserPermission($user->id, $permissionCode);
    
    //         return response()->json(['status' => 'true']);
    //     }
    // } 
 
    
    
    //   edit the users 
    public function editUser($id = '', $companyId = '')
    {
        // Retrieve user payload data from JWT using JWTHelper
        $jwtPayload = JwtHelper::getUserData();
        $authenticatedUser = auth('api')->user();  // Get the authenticated user
    
        if (!$authenticatedUser) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
    
        // If $companyId is provided, use it as the user ID to edit
        if ($companyId) {
            $id = $companyId;
        }
    
        // Fetch user data by ID
        $user = User::where('_id', $id)->first();
    
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
    
        // Fetch permission list and group by 'permission_group'
        $permissionList = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')
            ->orderBy('is_sequence')
            ->get()
            ->groupBy('permission_group')
            ->toArray();
    
        // Fetch user permissions by user_id
        $userPermissions = UserPermission::where('user_id', $id)->pluck('permission_code')->toArray();
    
        // Predefined color list
        $colors = ['bg-primary br-tr-4 br-tl-4', 'bg-info br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4', 
                   'bg-warning br-tr-4 br-tl-4', 'bg-secondary br-tr-4 br-tl-4', 'bg-danger br-tr-4 br-tl-4'];
    
        // Fetch roles based on authenticated user's role
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        $roles = ($authenticatedUser->role_id == $adminRoleId)
            ? Role::where('_id', '!=', $adminRoleId)->get()
            : Role::whereNotIn('_id', [$adminRoleId])->get();
    
        // Fetch available countries and companies
        $countries = Country::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();
    
        // Prepare response data
        $responseData = [
            'user'             => [
                'id'              => $user->_id,
                'first_name'      => $user->first_name,
                'last_name'       => $user->last_name,
                'email'           => $user->email,
                'city_name'       => $user->city_name,
                'country'         => $user->country,
                'company'         => $user->company,
                'role_id'         => $user->role_id,
                'is_active'       => $user->is_active,
                'permissions'     => $userPermissions,
            ],
            'permissions_list' => $permissionList,
            'roles'            => $roles,
            'countries'        => $countries,
            'companies'        => $companies,
            'colors'           => $colors,
            'currency_list'    => getCurrency(),
            'mode'             => 'edit',
        ];
    
        // Return the data as JSON
        return response()->json([
            'status' => 'success',
            'data'   => $responseData
        ]);
    }
 
    // Super Admin tab manage user list 
    public function listUsers(Request $request){
        
        $data = [
            'title'   => 'Home',
            'module'  => "Users",
            'heading'     => "Users",
            'companies'   => Company::where('status',1)->get(),
            'is_company_login' => isCompanyLogin()
        ];
        return response()->json($data);
    }
    public function getUserlists(Request $request)
    {
        $authenticatedUser = auth('api')->user();  // Using 'api' as the JWT guard
    
        if (!$authenticatedUser) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
    
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
    
        // Fetch users excluding Master Admin and the authenticated user
        $usersQuery = User::whereNotNull('role_id')
            ->where('role_id', '!=', $adminRoleId)
            ->where('_id', '!=', $authenticatedUser->id);
    
        // Filter by company_id based on the authenticated user's role
        if ($authenticatedUser->role_id != $adminRoleId) {
            $usersQuery->where('company_id', $authenticatedUser->company_id);
        }
    
        // Apply search filters
        if ($request->has('search_name')) {
            $searchName = $request->input('search_name');
            $usersQuery->where(function ($query) use ($searchName) {
                $query->where('first_name', 'like', "%{$searchName}%")
                      ->orWhere('last_name', 'like', "%{$searchName}%");
            });
        }
    
        // Filter by company_id if provided
        if ($request->has('search_company_id')) {
            $searchCompanyId = $request->input('search_company_id');
            $usersQuery->where('company_id', $searchCompanyId);
        }
    
        // Apply ordering by first_name in ascending order
       // $usersQuery->orderBy('first_name', 'asc'); // You can change 'first_name' to any other column if needed
       $usersQuery->orderBy('created_at', 'desc')
                   ->orderBy('first_name', 'asc');
        // Get the total count before applying pagination
        $totalUsers = $usersQuery->count();
    
        // Handle pagination (skip and take values)
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
       // $users = $usersQuery->skip($start)->take($length)->get();
       $users=$usersQuery->get();
    
        // Structure the data for response
        $userData = $users->map(function ($user) {
            return [
                'id' => $user->_id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'company' => $user->company->company_name ?? '',
                'country' => $user->country['name'] ?? '',
                'city_name' => $user->city_name ?? '',
                'status' => $user->status == 1 ? 'Verified' : 'Not Verified',
                'is_active' => $user->is_active == 1 ? 'Active' : 'Inactive',
                'created_at' => $user->created_at->format('Y-m-d h:i A'),
                'action' => [
                    'edit_url' => url('user-edit/' . $user->_id),
                    'toggle_active' => $user->is_active ? 'Deactivate' : 'Activate'
                ]
            ];
        });
    
        // Return the JSON response with pagination metadata
        return response()->json([
            'status' => 'success',
            'total_users' => $totalUsers,
            'users' => $userData
        ]);
    }
  

    // add user page 
 

    // 
    public function addUser(Request $request)

    {  
        $userData=JwtHelper::getUserData();
        if(!$userData){
            return response()->json(['status'=>'error','message'=>'Unauthorized'],401);

        }
        $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
        $grouplist = [];
        if($permissionlist)
        {
            $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
        }
        $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
        if(auth('api')->user()->role_id == $adminRoleId)
        {
            $roles = Role::where('_id','!=',$adminRoleId)->get();
        } 
        else {
            // $roles = Role::whereNotIn('_id',[$adminRoleId,$comAdminRoleId])->get();
            $roles = Role::whereNotIn('_id',$adminRoleId)->get();
        }
        $data = [
            'title'         => 'Home',
            'module'        => "Users",
            'heading'       => "Add Users",
            'countries'     => Country::where('status',1)->get(),
            'companies'     => Company::where('status',1)->get(),
            'Roles'         => $roles,
            'Permissions'   => $grouplist,
            'card_colors'   => $colors,
            'user_permissions' => ['CompanyFleetDashboard'],
            'mode'           => 'add',
            'is_company_login' => isCompanyLogin(),
            'currency_list' => getCurrency(),
        ];
       // return view('users.users.add_edit_user', $data);
       return response()->json(['data'=>'workiong ',$data]);
    }
    
    // save the user api 
    public function saveUser(Request $request)
    {
        // Get payload data from JWT token
        // $userData = JwtHelper::getUserData();
        // if (!$userData) {
        //     return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        // }
        // $company_login_id =$userData['company_id'];  // Use payload data
    
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required',
            'last_name'    => 'required',
            'country_id'   => 'required',
            'city_name'    => 'required',
            'role_id'      => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
        }
    
        // Check if user exists (update) or create a new user
        if ($request->has('user_id') && $request->user_id) {
            $permissionCode = $request->permisionCode ?? [];
            $this->saveUserPermission($request->user_id, $permissionCode);
    
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
            }
    
            // Update user information
            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->city_name  = $request->city_name;
            $user->weight_unit = $request->weight_unit ?? '';
            $user->liquid_unit = $request->liquid_unit ?? '';
            $user->currency    = $request->currency ?? '';
    
            // Optional company_id update
            if ($request->has('company_id') && $request->company_id) {
                $user->company_id = $request->company_id;
            }
    
            // Set role_id and active status
            $user->role_id = $request->role_id;
            $user->is_active = $request->is_active ? 1 : 0;
    
            // Save country data if present
            if ($request->has('country_id')) {
                $country = Country::find($request->country_id);
                if ($country) {
                    $user->country = [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                }
            }
    
            // Update password if present
            if ($request->has('password') && $request->password) {
                $user->password = Hash::make($request->password);
            }
    
            $user->save();
            return response()->json(['status' => 'true' ,'message'=>'update  user successfully']);

        } else {
            // If user doesn't exist, create a new one
            $adminUser = auth('api')->user();
            if (!$adminUser) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
            }
    
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $user = new User();
            $user->first_name  = $request->first_name;
            $user->last_name   = $request->last_name;
            $user->email       = $request->email ?? '';
            $user->city_name   = $request->city_name;
            $user->weight_unit = $request->weight_unit ?? '';
            $user->liquid_unit = $request->liquid_unit ?? '';
            $user->currency    = $request->currency ?? '';
    
            // Check company ID
            if ($request->has('company_id') && $request->company_id && $adminUser->role_id == $adminRoleId) {
                $user->company_id = $request->company_id;
            } else {
                $user->company_id = $company_login_id ?? $adminUser->company_id;
            }
    
            $user->role_id = $request->role_id;  // Set role_id
            $user->status = 1;
            $user->is_active = $request->is_active ? 1 : 0;
    
            // Add country information
            if ($request->has('country_id')) {
                $country = Country::find($request->country_id);
                if ($country) {
                    $user->country = [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                }
            }
    
            // Hash and save password
            if ($request->has('password') && $request->password) {
                $user->password = Hash::make($request->password);
            }
    
            $user->save();
    
            // Log activity
            $dataActivity = [
                'user_id'    => $user->id,
                'device_id'  => '',
                'company_id' => $user->company_id ?? '',
                'macid'      => '',
                'status'     => 'new_user_added',
            ];
    
            saveDailyActivity($dataActivity);
    
            // Save permissions
            $permissionCode = $request->permisionCode ?? [];
            $this->saveUserPermission($user->id, $permissionCode);
    
            return response()->json(['status' => 'true']);
        }
    }
    
// save userpermission
public function saveUserPermission($user_id = '', $permissionCode = [])
{
    if ($user_id) {
        try {
            // Delete existing permissions for the user
            \DB::table('user_permission')->where('user_id', $user_id)->delete();

            if (!empty($permissionCode)) {
                // Prepare the current timestamp
                $now_time = Carbon::now()->toDateTimeString();

                // Prepare the data for batch insert
                $permissions = [];
                foreach ($permissionCode as $code) {
                    $permissions[] = [
                        'user_id'          => $user_id,
                        'permission_code'  => $code ?? '',
                        'created_at'       => $now_time,
                    ];
                }

                // Perform batch insert
                \DB::table('user_permission')->insert($permissions);
            }
        } catch (\Exception $e) {
            // Handle any errors during database operations
            \Log::error("Failed to save user permissions: " . $e->getMessage());
            // You can choose to throw an exception or return an error response
        }
    } else {
        // Optionally, log or handle the case where user_id is not provided
        \Log::warning("No user ID provided for saving permissions.");
    }
}

// active inactive 

public function activeInactiveUsers(Request $request){
    // pass the user id
    $user = User::where('_id',$request->id)->first();
    if($user){

        $is_active = 1; 
        if($user->is_active == 1){
            $is_active = 0;
        } else {
            $is_active = 1;
        }

        $user->is_active = $is_active;
        $user->save();

        return response()->json([$is_active]);
    } else {
        return '0';
    }
}



// the new user tab  
public function listNewUsers()
{

    $data = [
        'title'            => 'Home',
        'module'           => "New Users",
        'heading'          => "New Users",
        'companies'        => Company::where('status',1)->get(),
        'is_company_login' => isCompanyLogin()
    ];
   // return view('users.new_users.list', $data);
   return response()->json([$data]);
}


public function getListNewUsers(Request $request)
{
    // Check if it's an AJAX or API request
    if ($request->ajax() || $request->is('api/*')) { 

        $authenticatedUser = auth('api')->user(); // Get authenticated user
        if (!$authenticatedUser) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $adminRoleId = \Config::get('constants.roles.Master_Admin');

        // Query to fetch users excluding Master Admin and authenticated user
        $usersQuery = User::whereNull('role_id')
                          ->where('_id', '!=', $authenticatedUser->id)
                          ->select('*');
        
        // Filter by company_id based on the authenticated user's role
        if ($authenticatedUser->role_id != $adminRoleId) {
            $usersQuery->where('company_id', $authenticatedUser->company_id);
        } else {
            $company_login_id = session()->get('company_login_id');
            if ($company_login_id) {
                $usersQuery->where('company_id', $company_login_id);
            }
        }

        // Apply search filters
        if ($request->has('search_name')) {
            $searchName = $request->input('search_name');
            $usersQuery->where(function ($query) use ($searchName) {
                $query->where('first_name', 'like', "%{$searchName}%")
                      ->orWhere('last_name', 'like', "%{$searchName}%");
            });
        }

        // Filter by company_id if provided
        if ($request->has('search_company_id')) {
            $searchCompanyId = $request->input('search_company_id');
            $usersQuery->where('company_id', $searchCompanyId);
        }

        // Apply ordering by created_at descending and first_name ascending
        $usersQuery->orderBy('created_at', 'desc')
                   ->orderBy('first_name', 'asc');

        // Get the total count before applying pagination
        $totalRecords = $usersQuery->count();

        // Handle pagination (skip and take values)
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $users = $usersQuery->skip($start)->take($length)->get();

        // Structure the data for response
        $userData = $users->map(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->first_name . ' ' . $row->last_name,
                'company' => $row->company->company_name ?? '',
                'country' => $row->country['name'] ?? '',
                'city_name' => $row->city_name ?? '',
                'status' => $row->status == 1 ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-danger">Not Verified</span>',
                'is_active' => $row->is_active == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>',
                'created_at' => $row->created_at->format('Y-m-d h:i A'),
                'action' => $this->getActionButtons($row)
            ];
        });

        // Return the JSON response with pagination metadata
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $userData
        ]);
    }

    return response()->json(['status' => 'error', 'message' => 'Invalid request.'], 400);
}

// Separate method to get action buttons
private function getActionButtons($row)
{
    $actionBtn = '';
    if (Gate::allows('UserManagementEdit')) {
        $actionBtn .= '<a href="new-user-edit/'.$row->id.'" class="btn btn-icon btn-primary me-2 editUsers" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';
        if ($row->is_active == 1) {
            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-info activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
        } else {
            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
        }
    }
    return $actionBtn;
}

//add new user 
public function  addNewUser(Request $request){
    $userData=JwtHelper::getUserData();
    if(!$userData){
        return response()->json(['status'=>'error','message'=>'Unauthorized'],401);

    }
    $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
    $grouplist = [];
    if($permissionlist)
    {
        $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
    }
    $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
    if(auth('api')->user()->role_id == $adminRoleId)
    {
        $roles = Role::where('_id','!=',$adminRoleId)->get();
    } 
    else {
        // $roles = Role::whereNotIn('_id',[$adminRoleId,$comAdminRoleId])->get();
        $roles = Role::whereNotIn('_id',$adminRoleId)->get();
    }
    $data = [
        'title'         => 'Home',
        'module'        => "New Users",
        'heading'       => "Add Users",
        'countries'     => Country::where('status',1)->get(),
        'companies'     => Company::where('status',1)->get(),
        'Roles'         => $roles,
        'Permissions'   => $grouplist,
        'card_colors'   => $colors,
        'user_permissions' => ['CompanyFleetDashboard'],
        'mode'           => 'add',
        'is_company_login' => isCompanyLogin(),
        'currency_list' => getCurrency(),
    ];
   // return view('users.users.add_edit_user', $data);
   return response()->json(['data'=>'workiong ',$data]);
}


public function saveNewUser(Request $request)
{
    $userData = JwtHelper::getUserData();

    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'first_name'   => 'required',
        'last_name'    => 'required',
        'country_id'   => 'required',
        'city_name'    => 'required',
        'role_id'      => 'required',
    ]);

    if ($validator->fails()) {
        // Return validation errors as a JSON response
        return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()], 422);
    } else {
       // $company_login_id = session()->get('company_login_id');
             $company_login_id = $userData['company_id'];
        if ($request->has('user_id') && $request->user_id) {
            // Update existing user
            $permissionCode = $request->permisionCode ?? [];
            $this->saveUserPermission($request->user_id, $permissionCode);

            $user = User::find($request->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->role_id = $request->role_id;
            $user->is_active = $request->is_active ? 1 : 0;
            $user->city_name = $request->city_name;
            $user->weight_unit = $request->weight_unit;
            $user->liquid_unit = $request->liquid_unit;
            $user->currency = $request->currency;

            if ($request->has('company_id') && $request->company_id) {
                $user->company_id = $request->company_id;
            }

            if ($request->has('country_id')) {
                $country = Country::find($request->country_id);
                $countryData = [
                    'id'        => $country->id,
                    'name'      => $country->name ?? '',
                    'code'      => $country->code ?? '',
                    'dial_code' => $country->dial_code ?? '',
                ];
                $user->country = $countryData;
            }

            if ($request->has('password') && $request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['status' => 'success', 'message' => 'User updated successfully', 'page_redirect' => false], 200);
        } else {
            // Create a new user
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->city_name = $request->city_name;
            $user->weight_unit = $request->weight_unit;
            $user->liquid_unit = $request->liquid_unit;
            $user->currency = $request->currency;

            if ($request->has('company_id') && $request->company_id && auth('api')->user()->role_id == $adminRoleId) {
                $user->company_id = $request->company_id;
            } else {
                $user->company_id = $company_login_id ?: auth('api')->user()->company_id;
            }

            $user->role_id = $request->role_id;
            $user->status = 1;  // Default status to verified
            $user->is_active = $request->is_active ? 1 : 0;

            if ($request->has('country_id')) {
                $country = Country::find($request->country_id);
                $countryData = [
                    'id'        => $country->id,
                    'name'      => $country->name ?? '',
                    'code'      => $country->code ?? '',
                    'dial_code' => $country->dial_code ?? '',
                ];
                $user->country = $countryData;
            }

            if ($request->has('password') && $request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Save user activity
            $dataActivity = [
                'user_id'    => $user->id,
                'device_id'  => '',
                'company_id' => $user->company_id,
                'macid'      => '',
                'status'     => 'new_user_added',
            ];

            saveDailyActivity($dataActivity);

            // Save user permissions
            $permissionCode = $request->permisionCode ?? [];
            $this->saveUserPermission($user->id, $permissionCode);

            return response()->json(['status' => 'success', 'message' => 'New user created successfully', 'page_redirect' => true], 201);
        }
    }
}


// edit the user (new)
public function getEditNewUser($id = '', $companyId = '')
{
    // If companyId is provided, replace the user ID with company ID
    if ($companyId) {
        $id = $companyId;
    }

    // Find the user by ID
    $dataOne = User::where('_id', $id)->first();
    if (!$dataOne) {
        return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }

    // Retrieve the list of permissions and group them by 'permission_group'
    $permissionList = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')
        ->orderBy('is_sequence')
        ->get()
        ->toArray();

    $groupList = [];
    if ($permissionList) {
        $groupList = collect($permissionList)->groupBy('permission_group')->toArray();
    }

    // Get the user's assigned permissions
    $userPermissions = UserPermission::where('user_id', $id)->pluck('permission_code')->toArray();

    // Define card colors
    $colors = ['bg-primary br-tr-4 br-tl-4', 'bg-info br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4',
               'bg-warning br-tr-4 br-tl-4', 'bg-secondary br-tr-4 br-tl-4', 'bg-danger br-tr-4 br-tl-4'];

    // Get the Master Admin Role ID from the configuration
    $adminRoleId = \Config::get('constants.roles.Master_Admin');

    // Prepare the data to return in the response
    $response = [
        'status'       => 'success',
        'user_data'    => $dataOne,
        'countries'    => Country::where('status', 1)->get(),
        'companies'    => Company::where('status', 1)->get(),
        'roles'        => Role::where('_id', '!=', $adminRoleId)->get(),
        'permissions'  => $groupList,
        'user_permissions' => $userPermissions,
        'card_colors'  => $colors,
        'currency_list' => getCurrency(),
    ];

    return response()->json($response, 200);
}



}
