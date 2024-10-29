<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Device;
use Illuminate\Http\Request;
use \DataTables;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserPin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $data        = User::where(['roll_id' => 2]);
            // $data        = User::where('roll_id',2);
            // $data->where('roll_id', 2);
            $seacrh_name = $request->get('seacrh_name');

            if($seacrh_name) {
                $data->where('name', 'like', "%{$seacrh_name}%");
            }
            $data = $data->get();
            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('status', function($row){
                    $statusBtn = '';
                    if($row->status == 0){
                        $statusBtn .= "Inactive";
                    }
                    else if($row->status == 1){
                        $statusBtn .= "Active";
                    } else if($row->status == 2){
                        $statusBtn .= "Deactive";
                    }
                    return $statusBtn;
                })
                ->addColumn('created_at', function($row){
                    $startDate = date_create($row->created_at);
                    return date_format($startDate,"Y-m-d h:m A");
                })
                ->addColumn('updated_at', function($row){
                    $startDate = date_create($row->updated_at);
                    return date_format($startDate,"Y-m-d h:m A");
                })
                ->addColumn('verified_at', function($row){
                    $startDate = date_create($row->verified_at);
                    return date_format($startDate,"Y-m-d h:m A");
                })
                // // ->addColumn('action', function($row){
                // //     $clientCount =  0;
                // //     $actionBtn  = '';
                // //     $actionBtn .= '<div class="btn-group" role="group" aria-label="Action button">';

                // //         $actionBtn .= '<a href="contact/'.$row->id.'/edit" data-id="'.$row->id.'" data-name="" data-status="'.$row->status.'" class="editCategory editRx btn btn-info btn-sm mr5" title="Click to Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                // //         if($row->is_active == 1){
                // //             $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveContact btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                // //         } else {
                // //             $actionBtn .= '<a href="javascript:void(0)" title="Click to Active" class="activeInactiveContact btn btn-warning btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                // //         }
                // //         $actionBtn .= '<a href="javascript:void(0)" class="removContact btn btn-danger btn-sm" title="Click to Delete" data-id="'.$row->id.'"><i class="fa fa-trash " aria-hidden="true"></i></a>';
                // //     $actionBtn .= '</div>';
                // //     return $actionBtn;
                // // })
                // ->rawColumns(['action'])
                ->make(true);
        }
        $data           = [];
        $data['title']  = 'Users';
        return view('users.index',$data);
    }

    public function savePin(Request $request)
    {
        $user = $request->user();
        $userpin = UserPin::where('user_id',$user->id)->first();
        if($userpin){
            $userpin->pin = $request->pin1;
        } else {
            $userpin = new UserPin;
            $userpin->user_id = $user->id;
            $userpin->pin = $request->pin1;
            $userpin->save();
        }
        $response = ['status' => 'true', 'success' => true, 'response_msg' => 'Pin saved.'];
        return response()->json($response);
    }

    public function verifyPin(Request $request)
    {
        $user = $request->user();
        $userpin = UserPin::where('user_id',$user->id)->first();
        if($userpin){
            if($userpin->pin == $request->pin_verify ){
                Cache::put('myPin', $userpin->pin, $seconds = 60*60);
                $response = ['status' => 'true', 'success' => true, 'response_msg' => 'PIN verified.'];
            } else {
                $response = ['status' => 'false', 'success' => false, 'response_msg' => 'Invalid PIN.'];
            }
        } else {
            $response = ['status' => 'false', 'success' => false, 'response_msg' => 'PIN not set.'];
        }

        return response()->json($response);
    }


//======================================== New Users =========================================//

    public function listNewUsers(Request  $request)

    {
         try{
            $data = [
                'title'            => 'Home',
                'module'           => "New Users",
                'heading'          => "New Users",
                'companies'        => Company::where('status',1)->get(),
                'is_company_login' => isCompanyLogin()
            ];
            Log::info('the new users list page retrived successfully by user',['ip_address' => $request->ip(),
            'user_id' => auth()->user()->email]);
            return view('users.new_users.list', $data);
         }
         catch(Expection  $e){
              Log::error('Error occured while  listing new users page'.$e->getMessage());


         }
        
    }

    public function getListNewUsers(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $users = User::whereNull('role_id')->where('_id','!=',auth()->guard('admin')->user()->id)->select('*');
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id != $adminRoleId)
            {
                $users->where('company_id',auth()->guard('admin')->user()->company_id);
            } else {

                $company_login_id = session()->get('company_login_id');
                if($company_login_id)
                {
                    $users->where('company_id',$company_login_id);
                }
            }

            $seacrh_name       = $request->get('seacrh_name');
            $seacrh_company_id = $request->get('seacrh_company_id');
            if($seacrh_name) {
                $users->where(function($query) use ($seacrh_name) {
                    $query->where('first_name', 'like', "%{$seacrh_name}%");
                    $query->orWhere(function($query) use ($seacrh_name) {
                        $query->where('last_name', 'like', "%{$seacrh_name}%");
                    });
                });
                // $users->where('first_name', 'like', "%{$seacrh_name}%");
            }

            if($seacrh_company_id) {
                $users->where('company_id',$seacrh_company_id);
            }

            $data = $users;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $clientCount =  0;
                $actionBtn = '';
                if (Gate::allows('UserManagementEdit')) {
                    $actionBtn .= '<a href="new-user-edit/'.$row->id.'" class="btn btn-icon btn-primary me-2 editUsers" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                    if($row->is_active == 1){
                        // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveByAdmin btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                        $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                    } else {
                        $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                    }
                }
                return $actionBtn;
            })
            ->addColumn('name', function($row){
                $name = $row->first_name .' '. $row->last_name;
                return $name;
            })
            ->addColumn('company', function($row){
                $name = (isset($row->company->company_name) && $row->company->company_name ? $row->company->company_name : '');
                return $name;
            })
            ->addColumn('country', function($row){
                $country = (isset($row->country['name']) && $row->country['name'] ? $row->country['name'] : '');
                return $country;
            })
            ->addColumn('city_name', function($row){
                $city_name = (isset($row->city_name) && $row->city_name ? $row->city_name : '');
                return $city_name;
            })
            ->addColumn('status', function($row){
                if($row->status == 1){
                    return  '<span class="badge bg-success">Verified</span>';
                } else {
                    return  '<span class="badge bg-danger">Not Verified</span>';
                }
            })
            ->addColumn('is_active', function($row){
                if($row->is_active == 1){
                    return  '<span class="badge bg-success">Active</span>';
                } else {
                    return  '<span class="badge bg-danger">InActive</span>';
                }
            })
            ->addColumn('created_at', function($row){
                $startDate = date_create($row->created_at);
                return date_format($startDate,"Y-m-d h:m A");
            })
            // ->orderColumn('name', function ($query, $order) {
            //     $query->orderBy('first_name', $order);
            // })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action','status','is_active'])
            ->make(true);
        }
    }


    public function activeInactiveUsers(Request $request){
        try{
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
                Log::info('active InactiveUsers status', [ 'status'=>$is_active  ,'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

                return $is_active;
            } else {
                return '0';
            }
        }
        catch(Execption  $e){
             Log::error('Error  in active InactiveUsers status',$e->getMessage());

        }

        
	}

    public function addUserNew(Request $request)
    {
        try{
            $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
            $grouplist = [];
            if($permissionlist)
            {
                $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
            }
            $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
            if(auth()->guard('admin')->user()->role_id == $adminRoleId)
            {
                $roles = Role::where('_id','!=',$adminRoleId)->get();
            } else {
                $roles = Role::whereNotIn('_id',[$adminRoleId])->get();
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
                'mode'             => 'add',
                'is_company_login' => isCompanyLogin(),
                'currency_list' => getCurrency(),
            ];
            Log::info('the new user addpage retrived successfully by user',['ip_address' => $request->ip(),
            'user_id' => auth()->user()->email]);
            return view('users.new_users.add_edit_user', $data);
        }
        catch(Execption  $e){
            Log::error('Error  in add user',$e->getMessage());
        }
       
    }

    public function editNewUser( Request $request,$id = '',$companyId = '')
    {
        try{
            if($companyId)
            {
                $id = $companyId;
            }
            $dataOne = User::where('_id', $id)->first();
            if(!$dataOne)
            {
                abort(404);
            }
            $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
            $grouplist = [];
            if($permissionlist)
            {
                $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
            }
            $user_permissions = UserPermission::where('user_id', $id)->pluck('permission_code');
    
            if($user_permissions)
            {
                $user_permissions = $user_permissions->toArray();
            }
            $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $data = [
                'title'       => 'Home',
                'module'      => 'New Users',
                'heading'     => 'Edit New User',
                'data'        => $dataOne,
                'countries'   => Country::where('status',1)->get(),
                'companies'   => Company::where('status',1)->get(),
                'Roles'       => Role::where('_id','!=',$adminRoleId)->get(),
                'Permissions' => $grouplist,
                'user_permissions' => $user_permissions,
                'card_colors' => $colors,
                'is_company_login' => isCompanyLogin(),
                'mode'           => 'edit',
                'currency_list' => getCurrency(),
    
            ];
            Log::info('the editnewuser page  is accessed by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

            return view('users.new_users.add_edit_user',$data);
        }
        catch(Expection  $e){
              log::error('error  in editnewuser page',['error' => $e->getMessage()]);

        }

       
    }

    public function saveNewUser(Request $request)
    {
  try{
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required',
            'last_name'    => 'required',
            // 'company_id'   => 'required',
            'country_id'   => 'required',
            'city_name'    => 'required',
            'role_id'      => 'required',
        ]);
        if ($validator->fails()) {
            Log::error('validated data not present');
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
        }
        else
        {
            $company_login_id = session()->get('company_login_id');
            if($request->has('user_id') && $request->user_id)
            {
                $permissionCode = (isset($request->permisionCode) && $request->permisionCode ? $request->permisionCode : []);
                $this->saveUserPermission($request->user_id,$permissionCode);

                $user = User::find($request->user_id);
                $user->first_name     = $request->first_name ?? '';
                $user->last_name      = $request->last_name ?? '';
                // $user->company_id     = $request->company_id ?? '';
                $user->role_id        = $request->role_id ?? '';
                $user->is_active      = (isset($request->is_active) && $request->is_active ? 1 : 0);
                $user->city_name      = $request->city_name ?? '';
                $user->weight_unit      = $request->weight_unit ?? '';
                $user->liquid_unit      = $request->liquid_unit ?? '';
                $user->currency         = $request->currency ?? '';
                if($request->has('company_id') && $request->company_id)
                {
                    $user->company_id = $request->company_id ?? '';
                }
                if($request->has('country_id')){
                    $country = Country::find($request->country_id);
                    $contryData= [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                    $user->country = $contryData;
                }
                if ($request->has('password') && $request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
                Log::info('update new userdata by user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return response()->json(['status' => 'true','page_redirect' => false]);
            }
            else
            {
                $adminRoleId    = \Config::get('constants.roles.Master_Admin');
                $user = new User();
                $user->first_name     = $request->first_name ?? '';
                $user->last_name      = $request->last_name ?? '';
                $user->email          = $request->email ?? '';
                $user->city_name      = $request->city_name ?? '';
                $user->weight_unit      = $request->weight_unit ?? '';
                $user->liquid_unit      = $request->liquid_unit ?? '';
                $user->currency         = $request->currency ?? '';
                if($request->has('company_id') && $request->company_id && auth()->guard('admin')->user()->role_id == $adminRoleId)
                {
                    $user->company_id = $request->company_id ?? '';
                } else {
                    if($company_login_id)
                    {
                        $user->company_id = $company_login_id;
                    } else {
                        $user->company_id = auth()->guard('admin')->user()->company_id;
                    }
                }
                $user->role_id        = $request->role_id ?? '';
                $user->status         = 1;
                $user->is_active      = (isset($request->is_active) && $request->is_active ? 1 : 0);
                if($request->has('country_id')){
                    $country    = Country::find($request->country_id);
                    $contryData = [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                    $user->country = $contryData;
                }
                if ($request->has('password') && $request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();

                $dataActivity = [
                    'user_id'    => $user->id,
                    'device_id'  => '',
                    'company_id' => $user->company_id ?? '',
                    'macid'      => '',
                    'status'     => 'new_user_added',
                ];

                // Save Daily Activity Reports
                saveDailyActivity($dataActivity);

                $permissionCode = (isset($request->permisionCode) && $request->permisionCode ? $request->permisionCode : []);
                $this->saveUserPermission($user->id,$permissionCode);
                Log::info('new user is saved successfully by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return response()->json(['status' => 'true','page_redirect' => true]);
            }

        }
    }
    catch(Expection $e){
       Log::error('error occured  while saving new user',['error'=>$e->getMessage()]);


    }
}


// ===================================== Start Users Listing =======================================//

    public function listUsers(  Request $request ){

    
        try{
            $data = [
                'title'   => 'Home',
                'module'  => "Users",
                'heading'     => "Users",
                'companies'   => Company::where('status',1)->get(),
                'is_company_login' => isCompanyLogin()
            ];
            Log::info('user list page retrived by user successfully', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('users.users.list', $data);
        }
        catch(Expection $e){
            Log::error('error occured  while listing users',['error'=>$e->getMessage()]);
        }
    }
        
    

    public function getListUsers(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $users = User::whereNotNull('role_id')->where('role_id','!=',$adminRoleId)->where('_id','!=',auth()->guard('admin')->user()->id)->select('*');
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id != $adminRoleId)
            {
                $users->where('company_id',auth()->guard('admin')->user()->company_id);
            } else {

                $company_login_id = session()->get('company_login_id');
                if($company_login_id)
                {
                    $users->where('company_id',$company_login_id);
                }
            }
            $seacrh_name = $request->get('seacrh_name');
            if($seacrh_name) {
                $users->where(function($query) use ($seacrh_name) {
                    $query->where('first_name', 'like', "%{$seacrh_name}%");
                    $query->orWhere(function($query) use ($seacrh_name) {
                        $query->where('last_name', 'like', "%{$seacrh_name}%");
                    });
                });
                // $full_name = explode(" ", $seacrh_name);
                // $last_name  = $full_name[1] ?? '';
                // $first_name = $full_name[0] ?? '';
                // $users->orWhere(function($query) use($last_name,$first_name){
                //    $query->where('last_name','like', $last_name)->where('first_name','like',$first_name);
                // });
                // $users->where('first_name', 'like', "%{$seacrh_name}%");
            }
            $seacrh_company_id = $request->get('seacrh_company_id');
            if($seacrh_company_id) {
                $users->where('company_id',$seacrh_company_id);
            }
            $data = $users;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $clientCount =  0;
                $actionBtn = '';
                if (Gate::allows('UserManagementEdit')) {
                    $actionBtn .= '<a href="user-edit/'.$row->id.'" class="btn btn-icon btn-primary me-2 editUsers" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';

                    if($row->is_active == 1){
                        // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveByAdmin btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                        $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                    } else {
                        $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                    }
                }
                return $actionBtn;
            })
            ->addColumn('name', function($row){
                $name = $row->first_name .' '. $row->last_name;
                return $name;
            })
            ->addColumn('company', function($row){
                $name = (isset($row->company->company_name) && $row->company->company_name ? $row->company->company_name : '');
                return $name;
            })
            ->addColumn('country', function($row){
                $country = (isset($row->country['name']) && $row->country['name'] ? $row->country['name'] : '');
                return $country;
            })
            ->addColumn('city_name', function($row){
                $country = (isset($row->city_name) && $row->city_name ? $row->city_name : '');
                return $country;
            })
            ->addColumn('status', function($row){
                if($row->status == 1){
                    return  '<span class="badge bg-success">Verified</span>';
                } else {
                    return  '<span class="badge bg-danger">Not Verified</span>';
                }
            })
            ->addColumn('is_active', function($row){
                if($row->is_active == 1){
                    return  '<span class="badge bg-success">Active</span>';
                } else {
                    return  '<span class="badge bg-danger">InActive</span>';
                }
            })
            ->addColumn('created_at', function($row){
                $startDate = date_create($row->created_at);
                return date_format($startDate,"Y-m-d h:m A");
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('first_name', $order);
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action','status','is_active'])
            ->make(true);
        }
    }


// public function addUser(Request $request){
   
//  $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')
//  ->orderBy('is_sequence')
//  ->paginate(100)
//  ->toArray();

//      $grouplist = [];
//     if ($permissionlist) {
//         $grouplist = collect($permissionlist)->groupBy('permission_group')->toArray();
//     }
    
//     $colors = ['bg-primary br-tr-4 br-tl-4', 'bg-info br-tr-4 br-tl-4', 'bg-success br-tr-4 br-tl-4', 
//                'bg-warning br-tr-4 br-tl-4', 'bg-secondary br-tr-4 br-tl-4', 'bg-danger br-tr-4 br-tl-4'];

//     $adminRoleId = \Config::get('constants.roles.Master_Admin');
//     $comAdminRoleId = \Config::get('constants.roles.Company_Admin');

//     // Paginate roles as well to improve performance
//     if (auth()->guard('admin')->user()->role_id == $adminRoleId) {
//         $roles = Role::where('_id', '!=', $adminRoleId)->paginate(50); 
//     } else {
//         $roles = Role::whereNotIn('_id', [$adminRoleId])->paginate(50); 
//     }

//     $data = [
//         'title'          => 'Home',
//         'module'         => "Users",
//         'heading'        => "Add Users",
//         'countries'      => Country::where('status', 1)->paginate(50), // Paginate countries
//         'companies'      => Company::where('status', 1)->paginate(50), // Paginate companies
//         'Roles'          => $roles,
//         'Permissions'    => $grouplist,
//         'card_colors'    => $colors,
//         'user_permissions' => ['CompanyFleetDashboard'],
//         'mode'           => 'add',
//         'is_company_login' => isCompanyLogin(),
//         'currency_list'  => getCurrency(),
//     ];

//     return view('users.users.add_edit_user', $data);
// }

public function addUser(Request $request)
{
 try{
    $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
    $grouplist = [];
    if($permissionlist)
    {
        $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
    }
    $colors      = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
    $company_id = auth()->guard('admin')->user()->company_id;
   
    if(auth()->guard('admin')->user()->role_id == $adminRoleId)
    {
        $roles = Role::where('_id','!=',$adminRoleId)->get();
            $powrbanklists = Device::where('status',1)->get();
    } 
    else {
        // $roles = Role::whereNotIn('_id',[$adminRoleId,$comAdminRoleId])->get();
        $roles = Role::whereNotIn('_id',[$adminRoleId])->get();
        $powrbanklists = Device::where('company_id',$company_id)->where('status',1)->get();
    }
    // $dataOne=  $dataOne = User::where('_id', $id)->first();
    $selectedPowerbanks =  [];
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
        'powrbanklists'  => $powrbanklists,
        'selectedPowerbanks' => $selectedPowerbanks,
      
    ];
    Log::info('adduser page retrived successfully by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
              
    return view('users.users.add_edit_user', $data);
}
catch(Expection  $e){
    Log::error('error occured while retrived by user',['error'=>$e->getMessage()]);
}

   
}



    






    public function editUser(Request $request,$id = '',$companyId = '')
    {
        try{
            if($companyId)
            {
                $id = $companyId;
            }
            $dataOne = User::where('_id', $id)->first();
            if(!$dataOne)
            {
                abort(404);
            }
            $permissionlist = Permission::where('_id', '!=', '644140fdcfcace244f0b6432')->orderBy('is_sequence')->get()->toArray();
            $grouplist = [];
            if($permissionlist)
            {
                $grouplist  = collect($permissionlist)->groupBy('permission_group')->toArray();
            }
            $user_permissions = UserPermission::where('user_id', $id)->pluck('permission_code');
    
            if($user_permissions)
            {
                $user_permissions = $user_permissions->toArray();
            }
            $colors         = ['bg-primary br-tr-4 br-tl-4','bg-info br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4','bg-warning br-tr-4 br-tl-4','bg-secondary br-tr-4 br-tl-4','bg-danger br-tr-4 br-tl-4','bg-primary br-tr-4 br-tl-4','bg-success br-tr-4 br-tl-4'];
            $adminRoleId    = \Config::get('constants.roles.Master_Admin');
            $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
            $company_id = auth()->guard('admin')->user()->company_id;
           $user_id=auth()->guard('admin')->user()->_id;
            if(auth()->guard('admin')->user()->role_id == $adminRoleId)
            {
                $roles = Role::where('_id','!=',$adminRoleId)->get();
                $powrbanklists = Device::where('status',1)->get();
            } else {
                $roles = Role::whereNotIn('_id',[$adminRoleId])->get();
            
                $powrbanklists = Device::where('company_id',$company_id)->where('status',1)->get();
               
            }
            $selectedPowerbanks = $dataOne->powrbank_details ? json_decode($dataOne->powrbank_details, true) : [];
              //  Log::info([ "data "=>$selectedPowerbanks]);
            $data = [
                'title'            => 'Home',
                'module'           => 'Users',
                'heading'          => 'Edit User',
                'data'             => $dataOne,
                'countries'        => Country::where('status',1)->get(),
                'companies'        => Company::where('status',1)->get(),
                'Roles'            => $roles,
                'Permissions'      => $grouplist,
                'user_permissions' => $user_permissions,
                'card_colors'      => $colors,
                'mode'           => 'edit',
                'currency_list' => getCurrency(),
                'powrbanklists'   => $powrbanklists,
                'selectedPowerbanks' => $selectedPowerbanks,
            ];
            Log::info('user edit page retrived successfuly by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('users.users.add_edit_user',$data);
        }
        catch (Exception $e) {
            Log::error('error  in user edit page retrived by user',$e->getMessage());

        }
       
    }

    public function saveUser(Request $request)
    {
      try{
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required',
            'last_name'    => 'required',
            // 'company_id'   => 'required',
            'country_id'   => 'required',
            'city_name'    => 'required',
            'role_id'      => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
        }
        else
        {
            $company_login_id = session()->get('company_login_id');
            if($request->has('user_id') && $request->user_id)
            {
                $permissionCode = (isset($request->permisionCode) && $request->permisionCode ? $request->permisionCode : []);
                $this->saveUserPermission($request->user_id,$permissionCode);

                $user = User::find($request->user_id);
                $user->first_name     = $request->first_name ?? '';
                $user->last_name      = $request->last_name ?? '';
                $user->city_name      = $request->city_name ?? '';
                $user->weight_unit      = $request->weight_unit ?? '';
                $user->liquid_unit      = $request->liquid_unit ?? '';
                $user->currency         = $request->currency ?? '';
                if($request->has('company_id') && $request->company_id)
                {
                    $user->company_id = $request->company_id ?? '';
                }
                $user->role_id        = $request->role_id ?? '';
                $user->is_active      = (isset($request->is_active) && $request->is_active ? 1 : 0);
                if($request->has('country_id')){
                    $country = Country::find($request->country_id);
                    $contryData= [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                    $user->country = $contryData;
                }
                // powrbank edit
                if ($request->has('powrbanklists')) {
                    $selectedPowerbanks = $request->powrbanklists;
                
                    $powerbankDetails = Device::whereIn('_id', $selectedPowerbanks)
                                         ->get(['_id', 'name'])
                                         ->toArray();
                
                    $user->powrbank_details = json_encode($powerbankDetails);
                }

                if ($request->has('password') && $request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
                Log::info('the user record update by user',[ 'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return response()->json(['status' => 'true']);
            }
            else
            {
                $adminRoleId    = \Config::get('constants.roles.Master_Admin');
                $user = new User();
                $user->first_name     = $request->first_name ?? '';
                $user->last_name      = $request->last_name ?? '';
                $user->email          = $request->email ?? '';
                $user->city_name      = $request->city_name ?? '';
                $user->weight_unit      = $request->weight_unit ?? '';
                $user->liquid_unit      = $request->liquid_unit ?? '';
                $user->currency         = $request->currency ?? '';

                if($request->has('company_id') && $request->company_id && auth()->guard('admin')->user()->role_id == $adminRoleId)
                {
                    $user->company_id = $request->company_id ?? '';
                } else {
                    if($company_login_id)
                    {
                        $user->company_id = $company_login_id;
                    } else {
                        $user->company_id = auth()->guard('admin')->user()->company_id;
                    }
                }
                $user->role_id        = $request->role_id ?? '';
                $user->status         = 1;
                $user->is_active      = (isset($request->is_active) && $request->is_active ? 1 : 0);
                if($request->has('country_id')){
                    $country    = Country::find($request->country_id);
                    $contryData = [
                        'id'        => $country->id,
                        'name'      => $country->name ?? '',
                        'code'      => $country->code ?? '',
                        'dial_code' => $country->dial_code ?? '',
                    ];
                    $user->country = $contryData;
                }
                if ($request->has('password') && $request->password) {
                    $user->password = Hash::make($request->password);
                }

            //   powrbank save 

           
            if ($request->has('powrbanklists')) {
                $selectedPowerbanks = $request->powrbanklists;
            
                $powerbankDetails = Device::whereIn('_id', $selectedPowerbanks)
                                     ->get(['_id', 'name'])
                                     ->toArray();
            
                $user->powrbank_details = json_encode($powerbankDetails);
            }
            
               
              // Log::info('selected powrbank list',  $user->powrbank_details);

                $user->save();

                $dataActivity = [
                    'user_id'    => $user->id,
                    'device_id'  => '',
                    'company_id' => $user->company_id ?? '',
                    'macid'      => '',
                    'status'     => 'new_user_added',
                ];

                // Save Daily Activity Reports
                saveDailyActivity($dataActivity);

                $permissionCode = (isset($request->permisionCode) && $request->permisionCode ? $request->permisionCode : []);
                $this->saveUserPermission($user->id,$permissionCode);
                Log::info('the user record save by user',[  'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return response()->json(['status' => 'true']);
            }
        }
      }
      catch(Expection $e){
           Log::error('Error Occured whiling  saving user record',[$e->getMessage()]);

      }
       
    }

// ============================ End User =======================================//

 public function saveUserPermission($user_id = '', $permissionCode = [])
 {
    if($user_id && $permissionCode)
    {
        \DB::table('user_permission')->where('user_id', $user_id)->delete();

        foreach ($permissionCode as $key => $code) {
            $mytime   = Carbon::now();
            $now_time = $mytime->toDateTimeString();
            \DB::table('user_permission')->insert([
                'user_id'              => $user_id,
                'permission_code'      => $code ?? '',
                'created_at'           => $now_time
            ]);
        }
    }
    else if($user_id)
    {
        \DB::table('user_permission')->where('user_id', $user_id)->delete();
    }

 }

}
