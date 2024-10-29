<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validators\ApiValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Data;
use App\Models\Inverter;
use App\Models\Cms;
use Carbon\Carbon;
use Auth;
use App\Mail\SendOtpNew;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiValidator;
    public function test(Request $request)
    {
        $data = Data::all();
        $response = ['status' => 'true', 'response_msg' => 'Invalid Code.', 'data' => $data ];
        return response()->json($response);
    }
    public function termsAndConditions()
    {
        $Cms = Cms::where('key','terms_conditions')->first();
        $html = $Cms->value ?? '';
        return response($html, 200)->header('Content-Type', 'text/html');
    }
    public function privacyPolicy()
    {
        $Cms = Cms::where('key','privacy_policy')->first();
        $html = $Cms->value ?? '';
        return response($html, 200)->header('Content-Type', 'text/html');
    }
    public function signup(Request $request)
    {
        $response = [];
        $validate = $this->signupValidate($request);
        if($validate['status'] == 'false'){
            return response()->json($validate);
        }

        $userEmailExist = User::where('email',$request->email)->first();
        
        if($userEmailExist && $userEmailExist->status == 1){
            $response = ['status' => 'false', 'screen_code' => '777', 'response_msg' => 'Your account already exists please login.'];
            return response()->json($response);
        } else if($userEmailExist && $userEmailExist->status == 0){
            $userEmailExist->otp = rand(1000,9999);
            $userEmailExist->save();
            try{
                // send email otp
                Mail::to($userEmailExist)->send(new SendOtpNew($userEmailExist));
            }
            catch(\Exception $e){}
            $userEmailExist->otp = config('constants.OTP_IN_RESPONSE') == 1 ? $userEmailExist->otp : null;

            $response = [
                            'status' => 'true', 
                            'user_id' => $userEmailExist->_id,
                            'screen_code' => '1003',  
                            'response_msg' => 'Thank you for registration. Please verify your number to login.',
                            'otp' => $userEmailExist->otp
                        ];
            return response()->json($response);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->roll_id = config('constants.roles.user');
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->status = 0;
        $user->otp = rand(1000,9999);
        if($user->save()){
            try{
                // send email otp
                Mail::to($user)->send(new SendOtpNew($user));
            }
            catch(\Exception $e){}
            $user->otp = config('constants.OTP_IN_RESPONSE') == 1 ? $user->otp : null;

            $response = [
                            'status' => 'true', 
                            'screen_code' => '1003', 
                            'user_id' => $user->_id,
                            'response_msg' => 'Thank you for registration.', 
                            'otp' => $user->otp
                        ];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Not able to signup at the moment.'];
        }
        return response()->json($response);
    }
    public function resendOtp(Request $request)
    {
        if($request->has('user_id')){
            $user = User::find($request->user_id);
            if(!$user){
                $response = ['status' => 'false', 'response_msg' => 'No user register with given Email.'];
                return response()->json($response);
            }

            $user->otp = rand(1000,9999);
            $user->save();
            try{
                 // send email otp
                 Mail::to($user)->send(new SendOtpNew($user));
            }
            catch(\Exception $e){}
            $user->otp = config('constants.OTP_IN_RESPONSE') == 1 ? $user->otp : null;

            $response = [
                            'status' => 'true', 
                            'screen_code' => '1003', 
                            'response_msg' => 'OTP sent successfully.', 
                            'otp' => $user->otp 
                        ];
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Email Address.' ];
            return response()->json($response);
        }
    }
    public function verifyOtp(Request $request)
    {
        if($request->has('otp') && $request->has('user_id') && $request->otp){

            $userData = User::find($request->user_id);
            if(!$userData){
                $response = ['status' => 'false', 'response_msg' => 'User not found.' ];
                return response()->json($response);
            }
            
            if($userData && !empty($userData->otp) && $userData->otp == $request->otp){
                //verify User. 
                if($userData->status == 0){
                    $userData->otp = '';
                    $userData->status = 1;
                    $userData->verified_at = now();
                    $userData->save();
                } else if($userData->status == 2){
                    $response = ['status' => 'false', 'response_msg' => 'Your account is deactivated.' ];
                    return response()->json($response);
                }

                $userData->tokens()->delete(); // remove previous all tokens
                $token = $userData->createToken($userData->email . ' ' . $userData->id)->plainTextToken;

                $response = [
                                'status' => 'true', 
                                'email' => $userData->email, 
                                'user_id'=>$userData->id,
                                'token' => $token, 
                                'screen_code' => '1002', 
                                'response_msg' => 'OTP verified successfully.' 
                            ];
                return response()->json($response);
            } else {
                $response = ['status' => 'false', 'response_msg' => 'Invalid OTP.' ];
                return response()->json($response);
            }
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing required params.' ];
            return response()->json($response);
        }
    }
    public function login(Request $request)
    {
        $response = [];
        $validate = $this->loginValidate($request);
        if($validate['status'] == 'false'){
            return response()->json($validate);
        }

        if ( Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'roll_id' => config('constants.roles.user')] )) {
            if(Auth::user()->status == 1) {
                $user = Auth::user();
                //$user->tokens()->delete(); // remove previous all tokens
                $token = $user->createToken($request->input('email') . ' ' . $user->id)->plainTextToken;
                $inverter = Inverter::where('user_id_str',$user->id)->where('status',1)->where('deleted','!=',1)->first();
                $response = [
                                'status' => 'true', 
                                'screen_code' => $inverter ? '000' : '1004',
                                'user_id'=>$user->id, 
                                'token' => $token, 
                                'response_msg' => 'Login successfully.'
                            ];
                return response()->json($response);
               
            } else if(Auth::user()->status == 0) {
                $user = Auth::user();
                $user->otp = rand(1000,9999);
                $user->save();
                try{
                    // send email otp
                    Mail::to($user)->send(new SendOtpNew($user));
                } catch(\Exception $e){}
                $user['otp'] = config('constants.OTP_IN_RESPONSE') == 1 ? $user['otp'] : null;

                $response = [
                                'status' => 'true', 
                                'screen_code' => '1003', 
                                'user_id' => $user->id,
                                'response_msg' => 'Your account is not verified.',
                                'otp' => $user['otp']
                            ];
                return response()->json($response);
            }
        } else {
           $response = ['status' => 'false', 'response_msg' => 'Invalid Credentials.'];
           return response()->json($response);
        }
        return response()->json($response);
    }
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();
        $response = ['status' => 'true', 'response_msg' => 'Successfully logged out.' ];
        return response()->json($response);
    }
    public function changePassword(Request $request)
    {
        $response = [];
        $validate = $this->changePassValidate($request);
        if($validate['status'] == 'false'){
            return response()->json($validate);
        }

        if($request->has('old_password') && $request->old_password){
            if (!Hash::check($request->input('old_password'), Auth::user()->password)) {
                $response = ['status' => 'false', 'response_msg' => 'Your current password does not match!.' ];
                return response()->json($response);
            }
        }

        $user = User::where(['_id' => Auth::user()->id])->update([
            'password' => Hash::make($request->input('new_password')),

        ]);

        $response = ['status' => 'true', 'response_msg' => 'Your password changed successfully.' ];
        return response()->json($response);
        
    }
    public function deleteAccount(Request $request)
    {
        $request->user()->inverter()->delete();
        $request->user()->currentAccessToken()->delete();
        $request->user()->delete();
        $response = ['status' => 'true', 'response_msg' => 'Account deleted successfully.'];
        return response()->json($response);
    }
    public function myProfile(Request $request)
    {
        $user = $request->user()->only(['_id', 'name', 'email', 'roll_id', 'status']);
        $response = ['status' => 'true', 'response_msg' => 'Profile.', 'user' => $user];
        return response()->json($response);
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        if($request->has('name')){
            $user->name = $request->name;
        }
        $user->save();
        $userData = $user->only(['_id', 'name', 'email', 'roll_id', 'status']);
        $response = ['status' => 'true', 'response_msg' => 'Profile updated successfully.' , 'data' => $userData];
        return response()->json($response);
    }
    public function forgotPassword(Request $request)
    {
        if($request->has('email')){
            $userEmailExist = User::where('email',$request->email)->first();
            if(!$userEmailExist){
                $response = ['status' => 'false', 'response_msg' => 'This email is not registered with system. Please check re-check your email or signup.'];
                return response()->json($response);
            } else if($userEmailExist && $userEmailExist->status == 0){
                $response = ['status' => 'false', 'response_msg' => 'Your account is not activated.'];
                return response()->json($response);
            } else if($userEmailExist && $userEmailExist->status == 1) {
                $userEmailExist->otp = rand(1000,9999);
                $userEmailExist->save();
                // send email otp
                Mail::to($userEmailExist)->send(new SendOtpNew($userEmailExist));
                $response = ['status' => 'true', 
                            'id' => $userEmailExist->id, 
                            'email' => $userEmailExist->email, 
                            'otp' => config('constants.OTP_IN_RESPONSE') == 1 ? $userEmailExist->otp : null,
                            'response_msg' => 'Your verification code sent on your email.' ];
                return response()->json($response);
            }
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Email ID.' ];
            return response()->json($response);
        }
    }
    public function verifyOtpFogotPass(Request $request)
    {
        if($request->has('otp') && $request->has('id') && $request->otp){
            $user = User::where('_id',$request->id)->where('otp',(int)$request->otp)->first();
            if($user && $user->otp){
                //create a new token to be sent to the user. 
                \DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => Str::random(60), //change 60 to any length you want
                    'created_at' => Carbon::now()
                ]);
                $tokenData = \DB::table('password_resets')
                ->where('email', $user->email)->first();
                // Remove verified OTP
                $user->otp = '';
                $user->save();

                $response = ['status' => 'true', 'email' => $user->email, 'token' => $tokenData['token'], 'screen_code' => '1002', 'response_msg' => 'OTP verified successfully.' ];
                return response()->json($response);
            } else {
                $response = ['status' => 'false', 'response_msg' => 'Invalid OTP.' ];
                return response()->json($response);
            }
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing required params.' ];
            return response()->json($response);
        }
    }
    public function resetPassword(Request $request)
    {
        if($request->has('password') && $request->password && $request->has('token') && $request->token){
            $tokenData = \DB::table('password_resets')
                ->where('token', $request->token)->first();
            if($tokenData){
                $user = User::where('email', $tokenData['email'])->first();
                if(!$user){
                    $response = ['status' => 'false', 'response_msg' => 'Opps.!! Something went wrong. No user found.' ];
                    return response()->json($response);
                }

                $user->password = Hash::make($request->password);
                $user->save();

                \DB::table('password_resets')->where('email', $user->email)->delete();

                $response = ['status' => 'true', 'screen_code' => '1001', 'response_msg' => 'Your password reset successfully.' ];
                return response()->json($response);
            } else {
                $response = ['status' => 'false', 'response_msg' => 'Opps.!! Something went wrong. Invalid reset password token.' ];
                return response()->json($response);
            }
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing required params.' ];
            return response()->json($response);
        }
    }
}
