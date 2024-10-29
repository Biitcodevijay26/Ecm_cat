<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Models\Data;
use App\Models\Permission;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use App\Mail\SendOtp;
use App\Models\Cluster;
use App\Models\CompanyAgent;
use App\Models\CompanyAgentDetail;
use App\Models\CompanyChannel;
use App\Models\DailyActivity;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use App\Models\Error;
use App\Models\IconSetting;
use App\Models\Inverter;
use App\Models\ModuleAccess;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\UserChart;
use App\Models\UserPermission;
use App\Models\Warning;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use MongoDB\BSON\UTCDateTime;
use Auth;
use Validator;
class AuthController extends Controller
{
    public function __construct()
    {
        //
      //  $this->token=$token;
        $this->middleware('multi-guard',['except'=>['login','register']]);
    }

    public function showLogin()
    {
        if (auth()->guard('admin')->check()) {
            return redirect('/dashboard');
        }
        $this->middleware('guest');
        return view('login');
    }

    public function showRegister()
    {
        $this->middleware('guest');
        $data = [
            'countries' => Country::where('status',1)->get(),
            'companies' => Company::where('status',1)->get(),
        ];
        return view('register',$data);
    }
    public function showForgotPassword()
    {
        $this->middleware('guest');
        return view('forgot-password');
    }

    public function showAdminLogin()
    {
        $this->middleware('guest');
        return view('admin-login');
    }

    public function attemptLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
        } else {

            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $is_send_otp = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
            if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], ($request->has('remember')))) {
                if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id != $adminRoleId)
                {
                    if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->status == "1")
                    {
                        auth()->guard('admin')->user();
                        return response()->json(['status' => 'success']);

                        // return redirect('/dashboard');
                    } else {

                        $check_limit_msg = sms($request->email,'',auth()->guard('admin')->user()->email_otp);
                        $msg             = 'OTP has been Sent to Your Email.';
                        $error_class     = 'success';
                        if($check_limit_msg == 'false')
                        {
                            $msg = "Your SMS OTP limit got over for today.";
                            $error_class = "error";
                        }
                        $data = [
                            'user_id'   => auth()->guard('admin')->user()->id,
                            'msg'       => $msg,
                            'otp'       => '',
                        ];
                        if($is_send_otp == 1 && $check_limit_msg == 'true')
                        {
                            $data['otp'] = auth()->guard('admin')->user()->email_otp;
                        }

                        if($check_limit_msg  == 'true')
                        {
                            // Send Mail OTP
                            try{
                                $user = User::where('_id',auth()->guard('admin')->user()->id)->first();
                                mail::to($request->email)->send(new SendOtp($user));
                            }
                            catch(\Exception $e){}
                        }
                        return response()->json(['status' => 'open_veri_screen','data' => $data, 'error_class' => $error_class]);

                        // return back()->withErrors([
                        //     'password' => 'Your account is deactivated, Please contact your administrator.',
                        // ])->onlyInput('password');
                    }
                } else {
                    Auth::guard('admin')->logout();
                    return response()->json(['status' => 'user_login','msg' => "Invalid credentials."]);
                }

            } else {
                return response()->json(['status' => 'user_login','msg' => "The provided credentials do not match our records."]);

                // return back()->withErrors([
                //     'password' => 'The provided credentials do not match our records.',
                // ])->onlyInput('password');
            }
        }

    }

    public function oldattemptLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], ($request->has('remember')))) {
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id != $adminRoleId)
            {
                if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->status == "1")
                {
                    auth()->guard('admin')->user();
                    return redirect('/dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return back()->withErrors([
                        'password' => 'Your account is deactivated, Please contact your administrator.',
                    ])->onlyInput('password');
                }
            } else {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'password' => 'Only users can login.',
                ])->onlyInput('password');
            }

        } else {
            return back()->withErrors([
                'password' => 'The provided credentials do not match our records.',
            ])->onlyInput('password');
        }
    }

    public function attemptAdminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], ($request->has('remember')))) {
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId)
            {
                if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->status == "1")
                {
                    auth()->guard('admin')->user();
                    return redirect('/dashboard');
                } else {
                    Auth::guard('admin')->logout();
                    return back()->withErrors([
                        'password' => 'Your account is deactivated, Please contact your administrator.',
                    ])->onlyInput('password');
                }
            } else {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'password' => 'Only admin can login.',
                ])->onlyInput('password');
            }

        } else {
            return back()->withErrors([
                'password' => 'The provided credentials do not match our records.',
            ])->onlyInput('password');
        }
    }

    public function saveRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required',
            'last_name'    => 'required',
            'email'        => 'required|email',
            'company_id'   => 'required',
            'password'     => 'required|min:6',
            'cpassword'    => 'required|same:password|min:6',
            'country_id'   => 'required',
            'city_name'    => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
        }
        else
        {
            $is_send_otp = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
            $userExits = User::where('email',$request->email)->first();
            if($userExits)
            {
                if($userExits->status == 0 && $userExits->is_active == 0)
                {
                    $check_limit_msg = sms($request->email,'',$userExits->email_otp);
                    $msg             = 'OTP has been Sent to Your Email.';

                    $error_class     = 'success';
                    if($check_limit_msg == 'false')
                    {
                        $msg = "Your SMS OTP limit got over for today.";
                        $error_class = "error";
                    }
                    $data = [
                        'user_id'   => $userExits->id,
                        'msg'       => $msg,
                        'otp'       => '',
                    ];
                    if($is_send_otp == 1 && $check_limit_msg == 'true')
                    {
                        $data['otp'] = $userExits->email_otp;
                    }
                    if($check_limit_msg  == 'true')
                    {
                        // Send Mail OTP
                        try{
                            // send email otp
                            mail::to($request->email)->send(new SendOtp($userExits));
                        }
                        catch(\Exception $e){}
                    }
                    return response()->json(['status' => 'open_veri_screen','data' => $data,'error_class' => $error_class]);
                }
                else if($userExits->status == 1 && $userExits->is_active == 0)
                {
                    return response()->json(['status' => 'login_msg']);

                } else if($userExits->status == 1 && $userExits->is_active == 1){
                    return response()->json(['status' => 'login_msg']);
                }
            }
            else
            {

                $email_code = rand(1111, 9999);
                $now_date   = date('Y-m-d');

                $user = new User();
                $user->first_name  = $request->first_name ?? '';
                $user->last_name   = $request->last_name ?? '';
                $user->email       = $request->email ?? '';
                $user->company_id  = $request->company_id ?? '';
                if ($request->has('password') && $request->password) {
                    $user->password = Hash::make($request->password);
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
                $user->city_name       = $request->city_name ?? '';
                $user->role_id         = NULL;
                $user->email_otp       = $email_code;
                $user->email_otp_date  = $now_date;
                $user->email_otp_count = 1;
                $user->email_verify_at = NULL;
                $user->status          =  0;
                $user->is_active       =  0;

                if($user->save())
                {
                    $is_send_otp     = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
                    $check_limit_msg = sms($request->email,'',$email_code);
                    $data = [
                        'user_id'   => $user->id,
                        'otp'       => '',
                    ];
                    if($is_send_otp == 1 && $check_limit_msg == 'true')
                    {
                        $data['otp'] = $email_code;
                    }

                    if($check_limit_msg  == 'true')
                    {
                        // Send Mail OTP
                        try{
                            // send email otp
                            mail::to($request->email)->send(new SendOtp($user));
                        }
                        catch(\Exception $e){}
                    }

                    // Save Permission when new user create
                    \DB::table('user_permission')->where('user_id', $user->id)->delete();
                    $mytime   = Carbon::now();
                    $now_time = $mytime->toDateTimeString();
                    \DB::table('user_permission')->insert([
                        'user_id'              => $user->id,
                        'permission_code'      => 'CompanyFleetDashboard',
                        'created_at'           => $now_time
                    ]);

                    // Add Notification when new user create
                    $notify = new Notification();
                    $notify->user_id    = $user->id;
                    $notify->type       = 'NEW_REGISTER';
                    $notify->is_read    = 0;
                    $notify->read_at    = NULL;
                    $notify->created_at = $now_time;
                    $notify->updated_at = $now_time;
                    $notify->save();

                    return response()->json(['status' => 'success', 'success' =>'Register successfully','data' => $data]);
                } else {
                    return response()->json(['status' => 0, 'success' =>'can not saved.']);
                }
            }
        }
    }

    public function verifyOTP(Request $request)
    {

        if($request->has('user_id') && $request->user_id && $request->has('otp') && $request->otp){
            $user = User::where(['_id' => $request->user_id,'is_active' => 0])->first();
            if($user->email_otp == $request->otp)
            {
             

                $user->status = 1;
                $user->email_verify_at = date('Y-m-d');
                $user->save();
               
                Auth::guard('admin')->login($user);
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 0, 'success' =>'can not saved.']);
            }
        }
    }

    public function resendOTP(Request $request)
    {

        $checkUser = User::where('_id',$request->user_id)->first();

        if ($checkUser){
            $check_limit_msg = sms($checkUser->email,'',$checkUser->email_otp);

            // $name = $checkUser->first_name.' '.$checkUser->last_name;
            // $group_image = getPostImage();
            // $data = array('code'=>$checkUser->code,'name'=>$name,'group_image' => $group_image);
            // Mail::to($checkUser->email)->send(new NotifyMail($data));

            $msg = 'OTP has been Sent to Your Email.';
            $error_class     = 'success';
            if($check_limit_msg == 'false')
            {
                $msg = "Your SMS OTP limit got over for today.";
                $error_class = "error";
            }
            $is_send_otp = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
            $otp = '';
            if($is_send_otp == 1 && $check_limit_msg == 'true')
            {
                $otp = $checkUser->email_otp ?? '';
            }

            if($check_limit_msg  == 'true')
            {
                // Send Mail OTP
                try{
                    // send email otp
                    mail::to($checkUser->email)->send(new SendOtp($checkUser));
                }
                catch(\Exception $e){}
            }
            return response()->json(['status' => 1,'msg' => $msg, 'otp' => $otp, 'error_class' => $error_class]);
         }

    }

    public function forgotPassword(Request $request)
    {
        if($request->has('email'))
        {
            $user = User::where('email',$request->email)->first();
            if($user && $user->status == 0){
                return response()->json(['status' => 0, 'msg' => 'Your account is not verified.']);
            }else if($user && $user->is_active == 0){
                return response()->json(['status' => 0,'msg' => "Your account is under review  you can reset <br> your password after your account has been <br> approved by admin."]);
            }
            else if($user && $user->status == 1)
            {
                $email_code = rand(1111, 9999);
                $now_date   = date('Y-m-d');

                $user->email_otp      = $email_code;
                $user->email_otp_date = $now_date;
                $user->save();

                $check_limit_msg = sms($request->email,'',$email_code);

                // $name = $checkUser->first_name.' '.$checkUser->last_name;
                // $group_image = getPostImage();
                // $data = array('code'=>$checkUser->code,'name'=>$name,'group_image' => $group_image);
                // Mail::to($checkUser->email)->send(new NotifyMail($data));

                $msg = 'OTP has been Sent to Your Email.';
                $error_class     = 'success';

                $is_send_otp = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
                $data = [
                    'user_id'   => $user->id,
                    'msg'       => $msg,
                    'otp'       => '',
                ];
                if($is_send_otp == 1)
                {
                    $data['otp'] = $email_code;
                }

                if($check_limit_msg  == 'true')
                {
                    // Send Mail OTP
                    try{
                        // send email otp
                        mail::to($request->email)->send(new SendOtp($user));
                    }
                    catch(\Exception $e){}
                }

                return response()->json(['status' => "success",'msg' => $msg, 'data' => $data, 'error_class' => $error_class]);
            }
            else
            {
                return response()->json(['status' => 0,'msg' => "This email is not registered with system."]);
            }
        }
    }

    // Verify Forgot OTP
    public function verifyForgotOTP(Request $request)
    {
        if($request->has('otp') && $request->has('user_id') && $request->otp && $request->user_id){
            $user = User::where(['_id' => $request->user_id])->first();
            if($user && $user->email_otp && $user->email_otp == $request->otp){
                $mytime   = Carbon::now();
                $now_time = $mytime->toDateTimeString();

                //create a new token to be sent to the user.
                \DB::table('password_resets')->insert([
                    'email'      => $user->email,
                    'token'      => Str::random(60), //change 60 to any length you want
                    'created_at' => $now_time
                ]);
                $tokenData = \DB::table('password_resets')
                ->where('email', $user->email)->first();
                // Remove verified OTP
                $user->email_otp = '';
                $user->save();

                $response = ['status' => 1, 'email' => $user->email, 'token' => $tokenData['token'], 'msg' => 'OTP verified successfully.' ];
                return response()->json($response);
            } else {
                $response = ['status' => 0, 'msg' => 'Invalid OTP.' ];
                return response()->json($response);
            }
        }
    }

    public function resetPassword(Request $request)
    {
        if($request->has('password') && $request->password && $request->has('reset_token') && $request->reset_token){
            $tokenData = \DB::table('password_resets')
            ->where('token', $request->reset_token)->first();
            if($tokenData){
                $user = User::where('email', $tokenData['email'])->first();
                if(!$user){
                    $response = ['status' => 'false', 'msg' => 'Something went wrong. No user found.' ];
                    return response()->json($response);
                }

                $user->password = Hash::make($request->password);
                $user->save();

                \DB::table('password_resets')->where('email', $user->email)->delete();
                $user->tokens()->delete(); // remove prev all tokens


                $response = ['status' => 'true', 'msg' => 'Your password reset successfully.' ];
                return response()->json($response);
            } else {
                $response = ['status' => 'false', 'msg' => 'Opps.!! Something went wrong. <br> Invalid reset password token.' ];
                return response()->json($response);
            }
        }
    }

    // Check Module access password
    public function verifyModuleAccessPassword(Request $request){
        if($request->has('password') && $request->password && $request->has('session_name') && $request->session_name){
            $password  = $request->password;
            $sess_name = $request->session_name;
            $hashedPassword = ModuleAccess::pluck('password')->first();
            if (Hash::check($password, $hashedPassword)) {
                $is_login  = 'false';
                $page_name = '';
                if($sess_name == 'countries'){
                    session()->put('is_module_access_countries', 'true');
                    $is_login = 'true';
                    $page_name = 'countries';
                    $is_module_access_countries = session()->get('is_module_access_countries');
                    if($is_module_access_countries == 'true'){
                        $response = ['status' => 'true', 'msg' => "Password has been successfully verified.",'is_login' => $is_login, 'page_name' => $page_name];
                    }
                }
                if ($sess_name == 'logo') {
                    session()->put('is_module_access_logo', 'true');
                    $is_login = 'true';
                    $page_name = 'manage-logo';
                    $is_module_access_logo = session()->get('is_module_access_logo');
                    if($is_module_access_logo == 'true'){
                        $response = ['status' => 'true', 'msg' => "Password has been successfully verified.",'is_login' => $is_login, 'page_name' => $page_name];
                    }
                }
                if ($sess_name == 'agent') {
                    session()->put('is_module_access_agent', 'true');
                    $is_login = 'true';
                    $page_name = 'agent';
                    $is_module_access_agent = session()->get('is_module_access_agent');
                    if($is_module_access_agent == 'true'){
                        $response = ['status' => 'true', 'msg' => "Password has been successfully verified.",'is_login' => $is_login, 'page_name' => $page_name];
                    }
                }
                if ($sess_name == 'channel') {
                    session()->put('is_module_access_channel', 'true');
                    $is_login = 'true';
                    $page_name = 'channel';
                    $is_module_access_channel = session()->get('is_module_access_channel');
                    if($is_module_access_channel == 'true'){
                        $response = ['status' => 'true', 'msg' => "Password has been successfully verified.",'is_login' => $is_login, 'page_name' => $page_name];
                    }
                }

                return response()->json($response);
            } else {
                $response = ['status' => 'false', 'msg' => "Password is incorrect."];
                return response()->json($response);
            }
        } else {
            $response = ['status' => 'false', 'msg' => "Please enter Password."];
            return response()->json($response);
        }
    }

    public function userList(Request $request)
    {
        $mytime   = Carbon::now();
        $now_time = $mytime->toDateTimeString();
        $loginUserId = auth()->guard('admin')->user()->id;
        // $user = \DB::table('settings')->get()->toArray();
        // $user = \DB::table('device_notifications')->get()->toArray();
        // $user = \DB::table('user_permission')->get()->toArray();
        // $user = Permission::all()->toArray();
        // Notification::where('is_read', 1)->update(['is_read' => 0,'read_at' => NULL]);
        // $user = Company::all()->toArray();
        // $user = Cluster::all()->toArray();
        // $user = UserChart::all()->toArray();
        // $user = Notification::all()->toArray();
        // $user = User::where('company_id', auth()->guard('admin')->user()->company_id)
        // ->where('role_id', '!=', \Config::get('constants.roles.Master_Admin'))
        // ->where('id', '!=', $loginUserId)
        // ->get()->toArray();
        // $user = ModuleAccess::all()->toArray();
        // $user = Device::all()->toArray();
        // $user = CompanyAgent::all()->toArray();
        // $user = CompanyAgentDetail::all()->toArray();
        // echo "<pre>"; print_r($user); exit("CALL");
        // Permission::where('permission_group','=','RemoteAccessManagement')->update(['is_sequence' => 6]);
        // Permission::where('permission_group','=','LiveView')->update(['is_sequence' => 5]);
        // Permission::where('permission_group','=','NotificationMessageManagement')->update(['is_sequence' => 4]);
        // Permission::where('permission_group','=','ClusterManagement')->update(['is_sequence' => 7]);

        $user = Data::take(800)->orderBy('created_at','desc')->get()->toArray();

        // foreach($user as $key => $value){
        //     $test = mb_strlen(serialize((array)$value), '8bit');
        //     $user[$key]['size'] = $test;
        //     $user[$key]['KB'] = $test / 1024;
        // }
        // $macId = getDeviceMacids('6442771eaa6eb38964069033');
        // $startDate = Carbon::now('UTC')->startOfMonth(); // GMT start of today
        // $endDate   = Carbon::now('UTC')->endOfMonth();
        // $user = Data::whereBetween('created_at', [$startDate, $endDate])->wherein('macid',$macId)->where('data.data.AC_Solar_Tot_Energy(Wh)','>',0)->sum('data.data.AC_Solar_Tot_Energy(Wh)');
        // echo "<pre>"; print_r($user); exit("CALL");

        // $user = Device::all()->toArray();
        // $user = DeviceNotification::all()->toArray();
        // $user = Warning::all()->toArray();
        // $user = DailyActivity::all()->toArray();
        // $user = Error::all()->toArray();
        // $user = Company::all()->toArray();
        // echo "<pre>"; print_r($user); exit("CALL");
        // $user = User::orderBy('_id','desc')->get()->toArray();
        // $user = UserChart::all()->toArray();
        // mail::to("kanzariyaashvin50@gmail.com")->send(new SendOtp($user));

          // GMT end of today
        // $user = Data::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at','desc')->get()->toArray();
        // $data = [
        //     'device_id'  => '648057a8c6be756ed30caf4c',
        //     'company_id' => '6442771eaa6eb38964069033',
        //     'macid'      => '0c:dc:7e:87:a4:d0',
        //     'status'     => 'device_updated',
        // ];
        // saveDailyActivity($data);
        // $user = DailyActivity::all()->toArray();
        // $id     = "645497dbf51bdcf95c0af432";
        // $filterMacIds = getFilterMacIds($id);
        // $user = DailyActivity::with('device')->wherein('macid',$filterMacIds)->latest()->take(20)->get()->toArray();
        // $macids = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->pluck('macid')->toArray();
        // $data   = DeviceWarning::whereIn('macid', $macids)->with('warning')->latest()->take(20)->get()->toArray();
        // $datetime = Carbon::parse('2024-05-08T10:38:17.000000Z');
        // $formattedDatetime = $datetime->format('d M \'y H:i:s');

        // $user = DeviceNotification::with('notification')->latest()->take(20)->get()->toArray();
        // $user = Cluster::where(['company_id' => auth()->guard('admin')->user()->company_id])->get()->toArray();
        // $user = IconSetting::all()->toArray();
        // $filterMacIds = [
        //     '7c:9e:bd:e3:36:14',
        //     '7c:9e:bd:e3:36:11',
        //     '7c:9e:bd:e3:36:a0',
        //     '0c:dc:7e:87:a4:d0',
        //     'c0:49:ef:d7:40:3c',
        //     'c0:49:ef:d7:ac:48',
        //     'c0:49:ef:d7:40:7c',
        //     '24:dc:c3:a4:10:fc',
        //     '24:dc:c3:a4:10:e4',
        //     '24:dc:c3:a4:11:14',
        // ];
        // $user = DeviceWarning::all()->toArray();
        $latestData = Data::where('macid',"c0:49:ef:d7:40:7c")
            ->where(function ($query) {
                $query->whereNotNull('data.data.DC_Solar_Energy(Wh)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();
        echo "<pre>"; print_r($latestData); exit("CALL");

    }

    public function TestQueryOld(Request $request)
    {

        $startDate = Carbon::now('UTC')->startOfDay(); // GMT start of today
        $endDate   = Carbon::now('UTC')->endOfDay();   // GMT end of today

        $hourlySumToday = Data::where(['data.MacId' => '0c:dc:7e:87:a4:d0','data.data.Contain' => "PV"])->whereBetween('created_at', [$startDate, $endDate])->get()->count();

        echo "Counts : ".$hourlySumToday;
        echo "<br>";

        $today = Carbon::today();

        // $hourlySum = Data::raw(function ($collection) use ($today) {
        //     return $collection->aggregate([
        //         [
        //             '$match' => [
        //                 'data.MacId'        => "0c:dc:7e:87:a4:d0",
        //                 'data.data.Contain' => "PV",
        //                 'created_at' => [
        //                     '$gte' => new UTCDateTime($today->startOfDay()),
        //                     '$lt'  => new UTCDateTime($today->endOfDay()),
        //                 ],
        //             ],
        //         ],
        //         [
        //             '$group' => [
        //                 "_id" => [ '$dateToString' => [ "format" => "%H", "date" => '$created_at' ] ],
        //                 'sum' => ['$avg' => ['$toDouble' => '$data.data.Power.PV_AC_O/L1(W)']],
        //             ]
        //         ],
        //         [
        //             '$sort' => ['_id' => 1],
        //         ],
        //     ]);
        // });

        $hourlySum = Data::raw(function ($collection) use ($today) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'data.MacId'        => "0c:dc:7e:87:a4:d0",
                        'data.data.Contain' => "PV",
                        'created_at' => [
                            '$gte' => new UTCDateTime($today->startOfDay()),
                            '$lt' => new UTCDateTime($today->endOfDay()),
                        ],
                    ],
                ],
                [
                    '$group' => [
                        // '_id' => ['$hour' => '$created_at'],
                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at'] ],
                        'sum_value' => ['$sum' => 1],
                    ],
                ],
                [
                    '$sort' => ['_id' => 1],
                ],
            ]);
        });
        echo "<pre>"; print_r($hourlySum->toArray()); exit("CALL");
    }

    public function TestQuery(Request $request)
    {
        // $user = Cluster::all()->toArray();
        // $user = CompanyChannel::all()->toArray();
        $user = CompanyAgentDetail::all()->toArray();
        // $user = Inverter::all()->toArray();
        echo "<pre>"; print_r($user); exit("CALL");
        $now          = Carbon::now();
        $last5Minutes = $now->subMinutes(5);
        $data = Data::where('macid','c0:49:ef:d7:40:7c')->where('created_at', '>=', $last5Minutes)->orderBy('created_at','desc')->get()->toArray();

        $current_date = '';
        if($request->has('current_date') && $request->current_date)
        {
            $current_date = date('Y-m-d', strtotime($request->current_date));
        }
        $timezone      = config('app.timezone');

        $dt  = $current_date . ' ' . '00:00:00';
        $dt1 = $current_date . ' ' . '23:59:59';
        $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
        $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);


        $hourlySumToday = Data::where(['data.MacId' => '0c:dc:7e:87:a4:d0','data.data.Contain' => "PV"])->whereBetween('created_at', [$start, $end])->get()->count();

        echo "Counts : ".$hourlySumToday;
        echo "<br>";

        $data = Data::raw(function ($collection) use($start,$end,$timezone) {


                return $collection->aggregate([


                        [
                            '$match' => [
                                'data.MacId'        => "0c:dc:7e:87:a4:d0",
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.data.Contain' => "PV",
                               // 'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                               // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                               'total' => ['$sum' => 1]


                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => -1 ]
                        ]

                ]);

        });
        echo "<pre>"; print_r($data->toArray()); exit("CALL");
    }

    public function changePassword()
    {
        $user_id           = Auth::user()->id;
        $profile_data      = User::find($user_id);
        $data = [
            'heading'      => 'Change Password',
            'title'        => 'Home',
            'tab'          => 'password',
            'profile_data' => $profile_data,

        ];
        return view('change_password', $data);
    }

    public function updatePassword(Request $request)
    {
        $user_id       = Auth::user()->id;
        $validate_data = $request->validate([
            'current_password'   => 'required',
            'new_password'       => 'required|same:new_password|min:6',
            'confrim_password'   => 'required|same:new_password',

        ]);
        if (!Hash::check($request->input('current_password'), Auth::user()->password)) {
            return back()->withErrors(['current_password' => ['current password does not match!.']]);
        }

        $user = User::where(['_id' => $user_id])->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return redirect('change-password')->with('message','Password Updated Successfully');

    }

    public function logout(Request $request) {
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId)
        {

            session()->put('is_module_access_countries','false');
            session()->put('is_module_access_logo','false');
            session()->put('is_module_access_agent','false');
            session()->put('is_module_access_channel','false');

            Auth::guard('admin')->logout();
            return redirect('admin');

        } else {
            Auth::guard('admin')->logout();
            return redirect('login');
        }
    }

    // Save Permission
    public function savePermission()
    {
        $permission_data = [
            // [
            //     'permission_name'  => 'Company Fleet Dashboard',
            //     'permission_code'  => 'CompanyFleetDashboard',
            //     'permission_group' => 'CompanyFleetDashboard',
            //     'order'            => 1,
            // ],
            // [
            //     'permission_name'  => 'Device Management List',
            //     'permission_code'  => 'DeviceManagementList',
            //     'permission_group' => 'DeviceManagement',
            //     'order'            => 3,

            // ],
            // [
            //     'permission_name'  => 'Device Management Add',
            //     'permission_code'  => 'DeviceManagementAdd',
            //     'permission_group' => 'DeviceManagement'
            // ],
            // [
            //     'permission_name'  => 'Device Management Edit',
            //     'permission_code'  => 'DeviceManagementEdit',
            //     'permission_group' => 'DeviceManagement'
            // ],
            // [
            //     'permission_name'  => 'Device Management Delete',
            //     'permission_code'  => 'DeviceManagementDelete',
            //     'permission_group' => 'DeviceManagement'
            // ],
            // [
            //     'permission_name'  => 'User Management List',
            //     'permission_code'  => 'UserManagementList',
            //     'permission_group' => 'UserManagement',
            //     'order'            => 4,
            // ],
            // [
            //     'permission_name'  => 'User Management Add',
            //     'permission_code'  => 'UsereManagementAdd',
            //     'permission_group' => 'UserManagement'
            // ],
            // [
            //     'permission_name'  => 'User Management Edit',
            //     'permission_code'  => 'UserManagementEdit',
            //     'permission_group' => 'UserManagement'
            // ],
            // [
            //     'permission_name'  => 'User Management Delete',
            //     'permission_code'  => 'UserManagementDelete',
            //     'permission_group' => 'UserManagement'
            // ],
            // [
            //     'permission_name'  => 'Live View',
            //     'permission_code'  => 'LiveView',
            //     'permission_group' => 'LiveView',
            //     'order'            => 2,
            // ],
            [
                'permission_name'  => 'Device Notification View',
                'permission_code'  => 'DeviceNotificationView',
                'permission_group' => 'DeviceNotificationManagement',
                'order'            => 1,
                'is_sequence'      => 8,
            ],
            [
                'permission_name'  => 'Device Warning View',
                'permission_code'  => 'DeviceWarningView',
                'permission_group' => 'DeviceNotificationManagement',
                'order'            => 2,
                'is_sequence'      => 8,
            ],
            // [
            //     'permission_name'  => 'Cluster Management Add',
            //     'permission_code'  => 'ClusterManagementAdd',
            //     'permission_group' => 'ClusterManagement',
            //     'order'            => 2,
            // ],
        ];
        foreach ($permission_data as $key => $permission) {
            if(isset($permission['permission_code']) && $permission['permission_code'])
            {
                $permission_list = Permission::where(['permission_code' => $permission['permission_code']])->first();
                if($permission_list)
                {
                    $permission_list->permission_name  = $permission['permission_name'] ?? '';
                    $permission_list->permission_code  = $permission['permission_code'] ?? '';
                    $permission_list->permission_group = $permission['permission_group'] ?? '';
                    $permission_list->order            = $permission['order'] ?? '';
                    $permission_list->is_sequence      = $permission['is_sequence'] ?? '';
                    $permission_list->save();
                } else {
                    $permission_list = new Permission();
                    $permission_list->permission_name  = $permission['permission_name'] ?? '';
                    $permission_list->permission_code  = $permission['permission_code'] ?? '';
                    $permission_list->permission_group = $permission['permission_group'] ?? '';
                    $permission_list->order            =  $permission['order'] ?? '';
                    $permission_list->is_sequence      =  $permission['is_sequence'] ?? '';
                    $permission_list->save();
                }
            }
        }

        $listData = Permission::all()->toArray();
    }

    public function saveIconSettings(){
        $icons_data = [
            [
                'company_id' => '65e0b1e404c5193b7209af33',
                'icon_label' => 'PowerBank Details',
                'icon_name'  => 'powerbank-details.svg',
                'status'     => 'active',
            ],
            [
                'company_id' => '65e0b1e404c5193b7209af33',
                'icon_label' => 'PowerBank Details',
                'icon_name'  => 'powerbank-details-off.svg',
                'status'     => 'inactive',
            ]
        ];
        foreach ($icons_data as $key => $value) {
            $mytime   = Carbon::now();
            $now_time = $mytime->toDateTimeString();
            $iconDatas = new IconSetting();
            $iconDatas->company_id  = $value['company_id'] ?? '';
            $iconDatas->icon_label  = $value['icon_label'] ?? '';
            $iconDatas->icon_name   = $value['icon_name'] ?? '';
            $iconDatas->status          =  $value['status'] ?? '';
            $iconDatas->created_at      =  $now_time;
            $iconDatas->updated_at      =  $now_time;
            // $iconDatas->save();
        }
        $listData = IconSetting::all()->toArray();
        echo "<pre>"; print_r($listData); exit("CALL");
    }
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'role_id'=>'required',
            'status'=>'required',
            'email'=>'required|string|email|unique:users',
            'password' =>'required|string|confirmed|min:6'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->tojson(),400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
        ));
        return response()->json([
            'message'=>'User successfully register',
            'user'=>$user
        ],201);
    }
    public function login(Request $request)
    {    
        
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ]
            ]);
        // $validator = Validator::make($request->all(),[            
        //     'email'=>'required|email',
        //     'password'=>'required|string'
        // ]);
        // if($validator->fails()){
        //     return response()->json($validator->errors(),422);
        // }
        // $token = Auth::attempt($validator);
        // if(!$token=auth()->attempt($validator->validated())){
        //   //  return response()->json($validator->errors()->tojson(),401);
        //    return response()->json(['error'=>'Unautharized'],401);
        //         // return response()->json([
        //         //     'error' => 'Unauthorized'
        //         // ], 401);
        // }
       // return $this->createToken($token);
    }
    public function logoutsession()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    // public function createToken($token)
    // {
    //         return response()->json([
    //             'access_token'=>$token,
    //             'token_type'=>'bearer',                
    //             //'expires_in' =>auth()->factory()->getTTL()*60,
    //             'expires_in' => config('jwt.ttl') * 60,
    //           //  'user'=>auth('api')->user()
    //         ]);
    // }
    

}
