<?php
namespace App\Validators;

use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Validator;
use App\Validators\BaseValidator;
use Illuminate\Validation\Rule;

trait ApiValidator
{
    use BaseValidator;

    public $response;

    /**
     * @param   : Request $request
     * @return  : \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @method  : addMusicFileValidations
     * @purpose : Validation rule for add page
     */
    public function referralCodeValidate(Request $request)
    {
        try{
            $validations = array(
                'code' => 'required',
            );
            $validator = Validator::make($request->all(),$validations);
            $this->response = $this->apiValidateData($validator);
        }catch(\Exception $e){
            $this->response = $e->getMessage();
        }
        return $this->response;
    }
    public function signupValidate(Request $request){
        try{
            $validations = array(
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            );
            $validator = Validator::make($request->all(),$validations);
            $this->response = $this->apiValidateData($validator);
        }catch(\Exception $e){
            $this->response = $e->getMessage();
        }
        return $this->response;
    }
    
    public function loginValidate(Request $request){
        try{
            $validations = array(
                'email' => 'required|email',
                'password' => 'required',
            );
            $validator = Validator::make($request->all(),$validations);
            $this->response = $this->apiValidateData($validator);
        }catch(\Exception $e){
            $this->response = $e->getMessage();
        }
        return $this->response;
    }

    public function inverterSettingValidate(Request $request){
        try{
            $validations = array(
                'inverter_id' => 'required',
            );
            $validator = Validator::make($request->all(),$validations);
            $this->response = $this->apiValidateData($validator);
        }catch(\Exception $e){
            $this->response = $e->getMessage();
        }
        return $this->response;
    }
    public function changePassValidate(Request $request){
        try{
            $validations = array(
                'new_password' => 'required|min:6',
            );
            $validator = Validator::make($request->all(),$validations);
            $this->response = $this->apiValidateData($validator);
        }catch(\Exception $e){
            $this->response = $e->getMessage();
        }
        return $this->response;
    }

    
}
