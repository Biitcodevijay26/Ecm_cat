<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth0\SDK\Auth0;

use Auth0\SDK\Configuration\SdkConfiguration;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\JwtHelper;
use Auth;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    //
    public $auth0;
    public function __construct(){
     
    
      $configuration = new SdkConfiguration(
        domain: 'dev-hldzq5hbrub0deck.us.auth0.com',
        clientId: 'XP55JuX8fAPAQV6VahwZcIRW9YJaPAP4',
        clientSecret: 'y-B-nc2gh4YWyfmFTgRoH2ZrcAabhOoQvtIzJSaaIuSYT_kRimOk_yXXYU_A4VJM',
        cookieSecret: 'test-laravel-10-auth-0',
        redirectUri:'http://127.0.0.1:8000/callback'
    );
    $this->auth0 = new Auth0($configuration);
    $this->middleware('multi-guard')->except(['login', 'register','auth_login']);
 
     }
    public function test(){

        return response()->json(['data'=>'submitted']);
    }

    public function login(Request $request)
{
    $email = $request->input('email');
    $password = $request->input('password');
   
       
    //$credentials = $request->only('email', 'password');
   
    $credentials = ['email' => $email, 'password' => $password];

    $encryptedData = encrypt($password);
    $decryptedData = decrypt($encryptedData);


    // Use the 'api' guard explicitly
    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized credentials'], 401);
    }

    return $this->createToken($token);
}
    public function createToken($token)
    {

      //  $password=auth('api')->user()->password;

        //$password_show= $password;
        // Decrypting the string
 


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
          'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(), // Return the authenticated user
            // 'user_password'=>$decrypted,
        ]);
    }


    public function register(Request $request){
        // add validations for checking validates data is coming or not
        $validator= validator::make($request->all(),[
           'name'=>'required',
           'email'=>'required|email|unique:users',
           'password'=>'required|string|min:6',
        ]);
        if($validator->fails()){
           return response()->json($validator->errors()->toJson(),400);
   }
   //create user
   $user= new User();
   $user->name=$request->name;
   $user->email=$request->email;
   $user->password=bcrypt($request->password);
   
   $user->save();
   return response()->json([
'message'=> 'user register successfully',
       'user'=>$user,
    ] , 201
   );

}


// super admin 

public function superAdminLogin(Request $request){
    
    $credentials = $request->only('email', 'password');
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    try {
        // Attempt to log in the user and issue a token
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid credentials.'
            ], 401);
        }
        $user = auth('api')->user();
        if ($user->role_id != $adminRoleId) {
            return response()->json([
                'error' => 'Only admin users can log in.'
            ], 403);
        }
        if ($user->status != "1") {
            return response()->json([
                'error' => 'Your account is deactivated, please contact the administrator.'
            ], 403);
        }
        return $this->createToken($token);

}
catch (JWTException $e) {
    return response()->json([
        'error' => 'Could not create token.',
    ], 500);
}

}

// logout  functions 

public function logout(Request $request) 

{
      $userdata= JwtHelper::getUserData();
       if(!$userdata){

        return response()->json(['message'=>'invalid  token or user data'], 401);
       }
       

   
     $adminRoleId = \Config::get('constants.roles.Master_Admin');
     if($userdata['role_id']==$adminRoleId){
              session()->put('is_module_access_countries', 'false');
        session()->put('is_module_access_logo', 'false');
        session()->put('is_module_access_agent', 'false');
        session()->put('is_module_access_channel', 'false');

         $userdata=auth('api')->logout();
         return response()->json([
            'message' => 'Logged out successfully',
            'redirect' => 'admin'
        ], 200);
     }
    else{
        $userdata=auth('api')->logout();
              return response()->json([
            'message' => 'Logged out successfully',
            'redirect' => 'login'
        ], 200);
    }

    
}


// login auth0 

public function auth_login(Request $request){
    try{
        $loginUrl = $this->auth0->login();
        return response()->json(['login_url' => $loginUrl]);
        // $email = $request->input('email');
        // $session=$this->auth0->getCredentials();
    
        // $userInfo = $session->user;
        // $email = $userInfo['email'] ?? null;
        // $name = $userInfo['name'] ?? null;
        // $name_user = $userInfo['nickname'] ?? null;
        // return  response()->json(['user'=>$userInfo], 401);

    }
     catch(Exception $e){
        return  response()->json(['message'=>'invalid  token or user data'], 401);

     }
    }

     public function handleAuth0Callback(Request $request)
     {
        $this->auth0->exchange();
         $session = $this->auth0->getCredentials();
 
         if (null === $session || $session->accessTokenExpired) {
             return response()->json(['error' => 'Access token expired or no valid session.'], 401);
         }
 
         // Retrieve user information from the session
         $userInfo = $session->user;
         $email = $userInfo['email'] ?? null;
         $name = $userInfo['name'] ?? null;
 
         return response()->json([
             'message' => 'Login successful',
             'user' => [
                 'name' => $name,
                 'email' => $email,
             ],
         ]);
     }
  

// DASHBOARD login  by auth0
public function dashboard_login(Request $request)
{
   
    $email =  $request->input('email');
    $session = $this->auth0->getCredentials();

}



}




//}



