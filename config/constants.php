
<?php

return [
    'APP_NAME' => env('APP_NAME'),
    'GOOGLE_MAP_KEY' => 'AIzaSyAXgn1b-y0lC11QjFve-kP6oCFxKG2sHfo',
    'roles' => [
        'Master_Admin'            => "6440caeb7f86dd3c207e508f",
        'Company_Admin'           => "6440cb4c7f86dd3c207e5090",
        'Manager'                 => "6440cb617f86dd3c207e5091",
        'Restricted_Manager'      => "6440cb6e7f86dd3c207e5092",
        'Technician'              => "6440cb837f86dd3c207e5093",
        'Restricted_Technician'   => "6440cb917f86dd3c207e5094",
        'View_Only'               => "6440cb9f7f86dd3c207e5095",
    ],
    'CONVERT_TO_GALLONS'  => 0.264172,
    'CONNECTED_TIME_IN_MINITS'=> 5,
    'DAILY_SMS_LIMIT'=> '30',
    'EMAIL_OTP_AUTO_FILL'=> '0',
    'OTP_IN_RESPONSE' => 1,
    'MQTT_IP' => '3.108.245.156',
    'MQTT_PORT' => 9001,
    'MQTT_USERNAME' => 'ecmmqqt',
    'MQTT_PASSWORD' => 'AsmEcmssdqhR',
    'BASE_URL_NODE' => 'http://ecm.co.in:3000/',
    'API_KEY_NODE' => '9bc5e66c-7261-12ed-a1eb-0242ac120005',
    'permissions' => [
        'CompanyFleetDashboard'   => "CompanyFleetDashboard",
        'DeviceManagementList'    => "DeviceManagementList",
        'DeviceManagementAdd'     => "DeviceManagementAdd",
        'DeviceManagementEdit'    => "DeviceManagementEdit",
        'DeviceManagementDelete'  => "DeviceManagementDelete",
        'UserManagementList'      => "UserManagementList",
        'UsereManagementAdd'      => "UsereManagementAdd",
        'UserManagementEdit'      => "UserManagementEdit",
        'UserManagementDelete'    => "UserManagementDelete",
        'LiveView'                => "LiveView",
    ],

   // 'MAIL_HOST'             => 'email-smtp.ap-south-1.amazonaws.com',
   // 'MAIL_USERNAME'         => 'AKIARDLJA4KI2YZPN4UU',
    // 'MAIL_PASSWORD'         => 'BN37p4hxIAFaSZpcQRf/UsIN37WELhExSf7lbD2d95xo',
    // 'MAIL_FROM_ADDRESS'     => 'support@vdpsolution.com',
    'MAIL_HOST' =>'smtp.office365.com',
      'MAIL_PASSWORD' => 'Powr22024',
      'MAIL_FROM_ADDRESS' =>'ecmalerts@powr2.com',
    //'AWS_DEFAULT_REGION'    => 'ap-south-1',
    'BASE_URL_NODE_CURL'    => 'http://3.108.245.156:3000/',
    'API_KEY_NODE_CURL'     => '1b766690-cacf-4953-be64-be34d4175582',
    'LOGO_IMG_WIDTH' => '200',
    'LOGO_IMG_HEGHT' => '55',
    'DWS_KEY' => '2E2047T8Zp3b4NhP0H2049X3S0GxRZ',
    'DWS_SECRET' => 'AV926dDT57dG535PPc1vUa3Jj34Poz1E249Xn569',
    'DWS_URL' => 'https://www.apiremoteaccess.com/en/api',
    'DWS_ALLOWD_ORIGIN'   => "http://3.108.245.156",

    'DWS_ALLOWD_ORIGIN'   => "http://3.108.245.156",
    'BASE_URL_AGENT_CURL' => 'https://www.apiremoteaccess.com/en/api',
    'AGENT_BARRIER_CODE'  => 'Basic MkUyMDQ3VDhacDNiNE5oUDBIMjA0OVgzUzBHeFJaOkFWOTI2ZERUNTdkRzUzNVBQYzF2VWEzSmozNFBvejFFMjQ5WG41Njk='
];

