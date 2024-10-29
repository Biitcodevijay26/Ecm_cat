<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'middleware' => [],
    ], 
    function () {
        Route::post('/test', 'AuthController@test');
        Route::any('/terms-and-conditions', 'AuthController@termsAndConditions');
        Route::any('/privacy-policy', 'AuthController@privacyPolicy');
        Route::post('/signup', 'AuthController@signup')->middleware('log.route');
        Route::post('/login', 'AuthController@login');
        Route::post('/resend-otp', 'AuthController@resendOtp')->middleware(['throttle:3,1']);
        Route::post('/verify-otp', 'AuthController@verifyOtp')->middleware(['throttle:10,1']);
        Route::post('/forgot-password', 'AuthController@forgotPassword')->middleware(['throttle:10,1']);
        Route::post('/verify-otp-forgot-passsword', 'AuthController@verifyOtpFogotPass')->middleware(['throttle:10,1']);
        Route::post('/reset-password', 'AuthController@resetPassword')->middleware(['throttle:10,1']);

        Route::group(['middleware' => ['auth:sanctum','log.route']], function () { 

            Route::post('/logout', 'AuthController@logout');
            Route::post('/delete-account', 'AuthController@deleteAccount');
            Route::post('/my-profile', 'AuthController@myProfile');
            Route::post('/update-profile', 'AuthController@updateProfile');
            Route::post('/change-password', 'AuthController@changePassword');
            Route::post('/update-device-token', 'AuthController@updateDeviceToken');

            Route::post('/inverter-list', 'UserController@inverterList');
            Route::post('/my-default-inverter', 'UserController@getDefaultInverter');
            Route::post('/get-inverter-settings', 'UserController@getInverterSettings');
            Route::post('/get-inverter-data-by-content', 'UserController@getDataByContentType');
            Route::post('/get-inverter-warning-message', 'UserController@getInverterWarningMsg');
            Route::post('/get-inverter-warning-list', 'UserController@getAlarmWarningData');
            Route::post('/delete-inverter', 'UserController@deleteInverter');

            Route::post('/chart-data-power', 'UserController@getPowerChartData');
            Route::post('/chart-data-energy', 'UserController@getEnergyChartData');
            Route::post('/chart-data-battery-status', 'UserController@getBatteryStatusChartData');

        });
    }
);
