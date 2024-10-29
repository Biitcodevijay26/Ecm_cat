<?php

namespace App\Http\Controllers;
use Auth0\SDK\Auth0;

use Auth0\SDK\Configuration\SdkConfiguration;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Log; 

class CheckAuthController extends Controller
{
    //
    
    public $auth0;
    public $configuration;

    // Constructor for adding the configurations
    public function __construct(){
        $configuration = new SdkConfiguration(
            domain: 'dev-hldzq5hbrub0deck.us.auth0.com',
            clientId: 'XP55JuX8fAPAQV6VahwZcIRW9YJaPAP4',
            clientSecret: 'y-B-nc2gh4YWyfmFTgRoH2ZrcAabhOoQvtIzJSaaIuSYT_kRimOk_yXXYU_A4VJM',
            cookieSecret: 'test-laravel-10-auth-0',
            redirectUri:'http://127.0.0.1:8000/callback'
        );

        
         $this->auth0 = new Auth0($configuration);
          
        // dd($this->auth0);
       
       // $this->auth0 = new \Auth0\SDK\Auth0($configuration);
    }
    
    public function logout(Request $request) {
       try{
        Log::info('the user logout from  the application', ['ip_address' => $request->ip()]);
        session()->flush();
        $this->auth0->logout();
       $auth0Domain = env('AUTH0_DOMAIN');  // 
       $clientId = env('AUTH0_CLIENT_ID');  // 
       $returnTo = 'http://127.0.0.1:8000'; 

       $logoutUrl = sprintf(
        'http://%s/v2/logout?client_id=%s&returnTo=%s',
        $auth0Domain,
        $clientId,
        urlencode($returnTo) 
    );
     

    return redirect($logoutUrl);
       }
       catch(Exception  $e){
        Log::error('Error occurred while logout from application.', [
            'ip_address' => $request->ip(),
            'user_id' =>auth()->user()->email,
            'message' => $e->getMessage()]);
       }

         
    }

    public function auth0(){


$session = $this->auth0->getCredentials();
 //dd($session);

if (null === $session || $session->accessTokenExpired) {
    $message = "Logging in now";
    Log::info($message);
    return redirect($this->auth0->login());
       
}
 else{
    $userInfo = $session->user;
   // dd($userInfo);
   $email = $userInfo['email'] ?? null;


     return redirect('auth0-login');
 }
        }
       
   // }


    public function callback(Request $req){
        $input = $req->all();
        
        if (null !== $this->auth0->getExchangeParameters()) {
            $this->auth0->exchange();
        }
        
        $user = $this->auth0->getCredentials()?->user;
       // dd($user);  
       if ($user) {
       
       return redirect('auth0-login');
    } else {
        // If user doesn't exist, redirect to the login page or handle errors
        return "auth0 not present";
    }

    }



    // login function 
    public function LoginAuth0(){
 
        
$session = $this->auth0->getCredentials();
 //dd($session);

if (null === $session || $session->accessTokenExpired) {
    //$message = "user Login by auth0";

  //  Log::info($message);
   return redirect($this->auth0->login());
}
else{
    $m2=" Login by Auth0  ";

     $userInfo = $session->user;
   // dd($userInfo);
   $email = $userInfo['email'] ?? null;
   $name = $userInfo['name'] ?? null;
   $name_user = $userInfo['nickname'] ?? null;
   Log::info($m2, ['name'=>$name_user ,'email'=>$name]);
   return view('auth0-login',compact('email'));
}

    }

//     public  function storeLoginAuth0(Request  $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'email'    => 'required',     
//         ]);


//         if ($validator->fails()) {
//             return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()]);
//         } else {

//             $email = $request->input('email');

//             $adminRoleId = \Config::get('constants.roles.Master_Admin');
//            // $is_send_otp = \Config::get('constants.EMAIL_OTP_AUTO_FILL');
         
//            $user = User::where('email', $email)->first();
//         //dd( $email);
//         if ($user) {
           
//             Auth::guard('admin')->login($user);

//             return response()->json(['status' => 'success', 'redirect' => url('/dashboard')]);
//         } else {
          
//             return response()->json(['status' => 'error', 'msg' => 'Email not found. Please check and try again.']);
//         }
      
//     }
// }



public function auth0Login(Request $request)
{
    $email = $request->input('email');
    
    $user = User::where('email', $email)->first();
    if ($user) {

           $m3='the user present, go for dashboard ';
        Auth::guard('admin')->login($user);

        Log::info($m3, ['user'=>$email]);
        return response()->json(['status' => 'success', 'redirect' => url('/dashboard')]);
    }


    else {


        session()->flush();
        $this->auth0->logout();

        $auth0Domain = env('AUTH0_DOMAIN');
        $clientId = env('AUTH0_CLIENT_ID');
        $returnTo = url('http://127.0.0.1:8000');  

        $logoutUrl = sprintf(
            'https://%s/v2/logout?client_id=%s&returnTo=%s',
            $auth0Domain,
            $clientId,
            urlencode($returnTo)
        );
    //    log message 
         Log::warning('User not Present in DB , redirecting to auth0 logout page', ['user'=>$email,'logout_url'=>$logoutUrl]);

        return response()->json([
            'status' => 'logout',  // Mark the status for frontend
            'logoutUrl' => $logoutUrl
        ]);
    }
}




}
 


