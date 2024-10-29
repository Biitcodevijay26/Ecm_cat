<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtHelper
{
    public static function getUserData()
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = JWTAuth::getPayload();

            return [
                'user_id' => $payload->get('sub'),
                'name' => $payload->get('first_name'),
                'email' => $payload->get('email'),
                'company_id' => $payload->get('company_id'),
                'role_id' => $payload->get('role_id'),
                'is_active'=>$payload->get('is_active'),
            ];
        } catch (JWTException $e) {
            return null; // Or handle the exception as needed
        }
    }
}

