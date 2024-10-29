<?php

namespace App\Validators;
Use Illuminate\Support\Facades\Validator;

trait BaseValidator
{
    public $response;

    /**
     * @param   : $validator
     * @return  : response
     * @method  : validateData
     * @purpose : Check validation rules and return response
     */
    public function validateData($validator){
        $this->response['status'] = true;
        if($validator->fails()){
            $errors = $validator->errors();
            foreach ($errors->messages() as $key => $message) {
                $error[$key] = $message[0];
            }
            $this->response['status'] = false;
            $this->response['errors'] = $error;
        }
        return $this->response;
    }
    public function apiValidateData($validator){
        $this->response['status'] = 'true';
        if($validator->fails()){
            // $errors = $validator->errors();
            // foreach ($errors->messages() as $key => $message) {
            //     $error[$key] = $message[0];
            // }
            $this->response['status'] = 'false';
            $this->response['response_msg'] = $validator->errors()->first();
            //$this->response['errors'] = $error;
        }
        return $this->response;
    }
}
