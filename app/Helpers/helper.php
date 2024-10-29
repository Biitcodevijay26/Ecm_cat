<?php

use App\Models\Cluster;
use App\Models\Company;
use App\Models\CompanyAgentDetail;
use App\Models\DailyActivity;
use App\Models\Data;
use App\Models\Device;
use App\Models\IconSetting;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

function getLocalFileUrl($pic_url='',$location='') {
    if($pic_url){
        if($location)
        {
            return url('uploads/' .$location.'/'.$pic_url);
        } else {
            return url('uploads/' . $pic_url);
        }
    } else {
        return '';
    }
}

function sms($to, $sms,$otp) {

    $check_limit = User::where('email',$to)->where('email_otp_date','=', date('Y-m-d'))->first();
    if($check_limit)
    {
        if($check_limit->email_otp_count >= \Config::get('constants.DAILY_SMS_LIMIT')) {
            return 'false';
        } else {
            $check_limit->email_otp_count  = $check_limit->email_otp_count + 1;
            $check_limit->save();
            return 'true';
        }
    } else {
        User::where('email',$to)->update(['email_otp_count' => 1, 'email_otp_date' => date('Y-m-d')]);
        return 'true';
    }
    // if ($user) {
    //     $to = $user->country_code . $to;
    // } else {
    //     $to = \Config::get('constants.IND_COUNTRY_CODE') . $to;
    // }


    // $check_limit = DeliveryReceipt::where('phone',$to)
    // 			   ->where('created_at','>=',date('Y-m-d').' 00:00:00')
    // 			   ->where('created_at','<=',date('Y-m-d').' 23:59:59')
    // 			   ->count();


}

function parseCamelCase($camelCaseString){
    if($camelCaseString)
    {
        if($camelCaseString == "ClusterManagement"){
            $string = 'Group Management';
        } elseif ($camelCaseString == "DeviceNotificationManagement"){
            $string = 'POWRBANK Notification Management';
        } elseif ($camelCaseString == "DeviceManagement"){
            $string = 'POWRBANK Management';
        } else {
            $data   = preg_split('/(?=[A-Z])/',$camelCaseString);
            $string = implode(' ', $data);
        }
        return ucwords($string);
    }
}

function periodDates($startDate,$endDate)
{
    $period = CarbonPeriod::create($startDate, $endDate);

    // Iterate over the period
    $dates = [];
    foreach ($period as $date) {
        $dates[] = $date->format('Y-m-d');
    }

    // Convert the period to an array of dates
    // $dates = $period->toArray();
    return $dates;
}

function isChecked($value,$data=[])
{
    if($value && $data)
    {
        foreach ($data as $key => $option_value) {
            if($option_value['data_value'] == $value)
            {
                return 'checked';
            }
        }
    }
}

// function machineDatas()
// {
//     $url = asset('/theme-asset/file/data_list_new.php');
//     $data = file_get_contents($url, true);
//     $datas = json_decode($data,true);
//     return $datas;
// }


    function machineDatas()
    {
        $filePath = public_path('theme-asset/file/data_list_new.php');
    
        // Check if the file exists
        if (!file_exists($filePath)) {
            // Log an error or return an empty array
            \Log::error('Data file not found: ' . $filePath);
            return [];
        }
    
        // Read and decode the data
        $data = file_get_contents($filePath);
        $datas = json_decode($data, true);
    
        // Check if data is in expected format
        if (!is_array($datas)) {
            \Log::error('Invalid data format: ' . print_r($datas, true));
            return [];
        }
    
        return $datas;
    }
    



// Rendom Colors

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return '#'.random_color_part() . random_color_part() . random_color_part();
}

function getColorFromChartKey($key) {
    $colorArray = [
        "PV_AC_O/L1(W)"     => '#000000',
        "PV_AC_O/L2(W)"     => '#FFC300',
        "PV_AC_O/L3(W)"     => '#65242e',
        "PV_AC_I/L1(W)"     => '#B6B6B4',
        "PV_AC_I/L2(W)"     => '#2C3539',
        "PV_AC_I/L3(W)"     => '#FFFF00',
        "PV_AC_G/L1(W)"     => '#6D7B8D',
        "PV_AC_G/L2(W)"     => '#728FCE',
        "PV_AC_G/L3(W)"     => '#55FFFF',
        "PV_DC(W)"          => '#00FF00',
        "OV_L1(W)"          => '#00008B',
        "OV_L2(W)"          => '#368BC1',
        "OV_L3(W)"          => '#FFFACD',
        "PV_DC(A)"          => '#C9C0BB',
        "PV(A)"             => '#A0CFEC',
        "E_AC-AC1(KWh)"     => '#0D9494',
        "E_AC-AC2(KWh)"     => '#FFDDAA',
        "E_B_AC(KWh)"       => '#4D5D53',
        "PV(V)"             => '#F8F8FF',
        "Feed_DC"           => '#66CDAA',
        "Grid_P_L1(W)"      => '#43C6DB',
        "Grid_P_L2(W)"      => '#01F9C6',
        "Grid_P_L3(W)"      => '#00827F',
        "Gen_P_L1(W)"       => '#E5E4E2',
        "Gen_P_L2(W)"       => '#2E8B57',
        "Gen_P_L3(W)"       => '#50C878',
        "O_P_L1(W)"         => '#3EB489',
        "O_P_L2(W)"         => '#617C58',
        "O_P_L3(W)"         => '#728C00',
        "SS_IN"             => '#4E5B31',
        "O_V_L1(V)"         => '#801818',
        "O_V_L2(V)"         => '#4A9976',
        "O_V_L3(V)"         => '#8FBABC',
        "Grid_V_L1(V)"      => '#E48400',
        "Grid_V_L2(V)"      => '#738678',
        "Grid_V_L3(V)"      => '#E52B50',
        "Gen_V_L1(V)"       => '#000100',
        "Gen_V_L2(V)"       => '#BB1111',
        "Gen_V_L3(V)"       => '#0014A8',
        "O_C_L1(A)"         => '#CC8899',
        "O_C_L2(A)"         => '#003399',
        "O_C_L3(A)"         => '#DEB887',
        "Grid_C_L1(A)"      => '#42B3AE',
        "Grid_C_L2(A)"      => '#B05923',
        "Grid_C_L3(A)"      => '#E49B0F',
        "Gen_C_L1(A)"       => '#AD4D8C',
        "Gen_C_L2(A)"       => '#AA0022',
        "Gen_C_L3(A)"       => '#9CC2BF',
        "In_F_L1(Hz)"       => '#CBC5B6',
        "In_F_L2(Hz)"       => '#D3A18E',
        "In_F_L3(Hz)"       => '#8FBDD2',
        "Out_F(Hz)"         => '#85AAAB',
        "Gen_F_L1(Hz)"      => '#8CBEDD',
        "Gen_F_L2(Hz)"      => '#0014A8',
        "Gen_F_L3(Hz)"      => '#2D5455',
        "V_DC(V)"           => '#4E9258',
        "I_DC(A)"           => '#347C2C',
        "DVCC"              => '#006400',
        "P_DC(W)"           => '#98AFC7',
        "CH_DC(W)"          => '#6AA121',
        "CH_DC1(W)"         => '#6CC417',
        "SOC(%)"            => '#54C571',
        "SOH(%)"            => '#8FBC8F',
        "E1(KWH)"           => '#A0D6B4',
        "E2(KWH)"           => '#8FB31D',
        "E_DIS(KWH)"        => '#57E964',
        "E_CHR(KWH)"        => '#5FFB17',
        "E_OUT(KWH)"        => '#004225',
        "DISCH(AH)"         => '#808000',
        "CAPACITY(AH)"      => '#E3F9A6',
        "CONSUM(AH)"        => '#354949',
        "COND"              => '#B1FB17',
        "TOG(sec)"          => '#F0FFF0',
        "COUNT"             => '#FFE5B4',
        "L_C(sec)"          => '#8A9A5B',
        "CH_ST"             => '#FDBD01',
        "CH_ST_1"           => '#EE9A4D',
        "No_of_Batt"        => '#C8B560',
        "No_Batt_Para"      => '#C7A317',
        "No_Batt_Series"    => '#B8860B',
        "B_Temp1?"          => '#FF9215',
        "B_Temp2?"          => '#5F676E',
        "Act_Inp"           => '#806517',
        "Bus_State"         => '#AF9B60',
        "Bus_Error"         => '#493D26',
        "Switch_Post"       => '#804A00',
        "Inv_State"         => '#EB5406',
        "Post"              => '#FF8C00',
        "Syst_Switch"       => '#FF7F50',
        "Inv_Switch"        => '#E8D849',
        "TEMP_ALR"          => '#B21807',
        "TEMP_ALR1"         => '#835C3B',
        "TEMP_ALR2"         => '#3D0C02',
        "TEMP_ALR3"         => '#7D0552',
        "LOW_TEMP"          => '#C48793',
        "HIGH_TEMP"         => '#E8ADAA',
        "BATT_TEMP"         => '#FFCBA4',
        "MIN_CELL_TEMP"     => '#FDD7E4',
        "MAX_CELL_TEMP"     => '#FFB2D0',
        "HI_INT_TEMP"       => '#DC143C',
        "TEMP_SEN"          => '#CC338B',
        "HI_CHR_TEMP"       => '#DA70D6',
        "HIGH_ALR"          => '#FFEBCD',
        "BUS_SOC(%)"        => '#915F6D',
        "LW_ALR"            => '#5865F2',
        "LW_BAT_ALRM"       => '#36013F',
        "HI_BAT_ALRM"       => '#DCD0FF',
        "LW_BAT_ALRM1"      => '#E1D9D1',
        "LW_BAT_ALRM2"      => '#8F0B0B',
        "LW_BAT_ALRM3"      => '#FF6700',
        "OVR_ALM"           => '#7F5217',
        "OVR_ALM(L1)"       => '#8E7618',
        "OVR_ALM(L2)"       => '#FFDF00',
        "OVR_ALM(L3)"       => '#FFFFC2',
        "VOL_ALRM"          => '#7CFC00',
        "LOW_VOL_ALRM"      => '#36F57F',
        "HIGH_VOL_ALRM"     => '#3F9B0B',
        "LOW_SOC_ALRM"      => '#355E3B',
        "LW_VOL_COUNT"      => '#033E3E',
        "HI_VOL_COUNT"      => '#93FFE8',
        "CELL_IMB"          => '#F0FFFF',
        "BMS_BAT_CH"        => '#3B3131',
        "BMS_BAT_DH"        => '#4b0082',
        "BMS_ERR"           => '#228b22',
        "INT_FAIL"          => '#eb5e00',
        "PH_ROT"            => '#FF69B4',
        "AC1_IGN"           => '#ffe4c4',
        "AC2_IGN"           => '#9bb9ca',
        "ALRM"              => '#9bb9ca',
        "FUSE_ALR"          => '#E3319D',
        "CH_CURR_ALR"       => '#8B008B',
        "DH_CURR_ALR"       => '#C35817',
        "OVR_ALR"           => '#FA2A55',
        "LOW_SOC_AR"        => '#9ACD32',
        "PV_PWR(W)"         => '#F08080',
        "MAX_CHR0(W)"       => '#8B8000',
        "MAX_CHR1(W)"       => '#FFDB58',
        "MAX_CHR2(W)"       => '#FFE87C',
        "MAX_CHR3(W)"       => '#FF77FF',
        "MAX_CHR_0(W)"      => '#191970',
        "MAX_CHR_1(W)"      => '#353935',
        "MAX_CHR_2(W)"      => '#DAF7A6',
        "MAX_CHR_3(W)"      => '#F08080',
        "ESS_SET1(W)"       => '#8B0000',
        "ESS_SET2(W)"       => '#FF4500',
        "ESS_SET3(W)"       => '#8A2BE2',
        "MAX_PWR_CHR0(W)"   => '#555555',
        "MAX_PWR_CHR1(W)"   => '#FFFF55',
        "MAX_PWR_CHR2(W)"   => '#00AAAA',
        "MAX_PWR_CHR3(W)"   => '#5555FF',
        "MAX_PWR_CHR_0(W)"  => '#00FA9A',
        "MAX_PWR_CHR_1(W)"  => '#008080',
        "MAX_PWR_CHR_2(W)"  => '#00FFFF',
        "MAX_PWR_CHR_3(W)"  => '#0000CD',
        "MPPT_MODE"         => '#FFF8DC',
        "ESS_MODE"          => '#DAA520',
        "ESS_MODE1"         => '#A52A2A',
        "LW_ALRM"           => '#696969',
        "PV_VOL_0(V)"       => '#2F4F4F',
        "PV_VOL_1(V)"       => '#BC8F8F',
        "PV_VOL_2(V)"       => '#98FB98',
        "PV_VOL_3(V)"       => '#413839',
        "SOL_VOL(V)"        => '#99FF33',
        "SOL_CUR(A)"        => '#9999FF',
        "ENR_S_OUT(KWH)"    => '#FFFF33',
        "ENR_S_BAT(KWH)"    => '#FF3333',
        "YLD0(KWH)"         => '#000033',
        "YLD1(KWH)"         => '#E37284',
        "YLD2(KWH)"         => '#5C453F',
        "YLD3(KWH)"         => '#D0AE00',
        "YLD_0(KWH)"        => '#8DD59F',
        "YLD_1(KWH)"        => '#FFFF99',
        "YLD_2(KWH)"        => '#33FFFF',
        "YLD_3(KWH)"        => '#B22222',
        "AC_Solar_Tot_Pow(W)"     => '#77523B',
        "Battery_Charge_Time(s)"  => '#FFE8C2',
        "Battery_Discharge_Time(s)"  => '#49473E',
        "Battery_Idle_Time(s)"  => '#685558',
        "Battery_Off_Time(s)"   => '#DAECD6',
        "Battery_Total_Energy(Wh)"  => '#A7867B',
        "Cooling_Mode(On/Off)"  => '#8A8E7D',
        "DC_Solar_Energy(Wh)"   => '#C4C0E2',
        "DC_Solar_Power(W)"     => '#907366',
        "Gen_Freq(Hz)"          => '#378F85',
        "Gen_Fuel_Utilized(L)"  => '#D2D0AE',
        "Gen_L2L_Voltage(V)"    => '#C7BEA7',
        "Gen_RunTime_total(s)"  => '#5A5A59',
        "Gen_Total_Pow(W)"      => '#DDDACC',
        "Generator_Auto_Flag(On/Off)"    => '#A14F34',
        "Generator_Manual_Flag(On/Off)"  => '#CE94B5',
        "Grid_RunTime(s)"           => '#CFDFE6',
        "Grid_Tot_Energy(Wh)"       => '#D8D5CC',
        "Grid_Tot_Pow(W)"           => '#382A34',
        "Grid_to_Battery_Power(W)"  => '#BFC4C4',
        "Heater_Mode(On/Off)"       => '#FFEA8E',
        "Immediate_Start_Time_Count_s(s)"  => '#659ACE',
        "Load_Auto_Flag(On/Off)"   => '#463A39',
        "Load_Manual_Flag(On/Off)" => '#FF8696',
        "Load_RunTime_Total(s)"    => '#FAB836',
        "Load_Timer_Flag(On/Off)"  => '#857D7F',
        "Out_L2L_Volt(V)"        => '#47B1B7',
        "Out_Tot_Energy(Wh)"     => '#FFCA42',
        "Out_Tot_Pow(W)"         => '#656264',
        "Solar_To_Grid(kWH)"     => '#846c5b',
        "Standby_Time_Count(s)"  => '#91785d',
        "Sys_Runtime(s)"     => '#f5dd90',
        "System(On/Off)"     => '#ff8200',
        "Temperature_1(°C)"  => '#5f464b',
        "Temperature_2(°C)"  => '#9899a6',
        "Temperature_3(°C)"  => '#f3eff5',
        "AC_Solar_Tot_Energy(Wh)"       => '#fff3b0',
        "Delayed_Start_Time_Count_s(s)" => '#08415c',
        "Gen_Tot_Energy(Wh)"            => '#faf4d3',
        "Genrator_to_battery_Power(W)"  => '#d2b48c',
        "Load_OUT_1_flag(On/Off)"  => '#f7f06d',
        "Load_OUT_2_flag(On/Off)"  => '#ada0a6',
        "Load_OUT_3_flag(On/Off)"  => '#f4b942',
        "Load_OUT_4_flag(On/Off)"  => '#abab98',
    ];

    if(array_key_exists($key,$colorArray))
    {
        return $colorArray[$key];
    } else {
        return random_color();
    }
}

// Admin access to company panel
function companyLoginByAdmin($company_id)
{
    session()->forget('company_login_id');
    session()->put('company_login_id', $company_id);
    $company = Company::find($company_id);
    if($company)
    {
        session()->put('company_login_name', $company->company_name);
    }
}
function isCompanyLogin()
{
    $segment     = Request::segment(1);
    $segment1    = Request::segment(2);
    $is_session = session()->get('company_login_id');
    if($is_session && $segment1 && $segment == 'company')
    {
        return 'true';
    } else {
        session()->forget('company_login_id');
        return 'false';
    }
}

function userProfile($user_id){
    $userProfile = User::select('_id','first_name','last_name')->where('_id',$user_id)->first();
    if($userProfile){
        return $userProfile->first_name.' '.$userProfile->last_name;
    }else{
        return '';
    }
}

function deviceCurrentStatus($macid)
{
    $data = Data::where('macid',$macid)->orderBy('created_at','desc')->options(['allowDiskUse' => true])->first();

    if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Device Disconnected")
    {
        return "<span class='badge bg-status-off text-white'>OFF</span>";
    }
    else if(isset($machineData['data']['data']['Contain']) && $machineData['data']['data']['Contain'] == "Inverter_disconnected")
    {
        return "<span class='badge bg-info text-white'>Inverter Disconnected</span>";
    } else {
        $data = Data::where('macid',$macid)->where('data.data.Contain','Battery')->orderBy('created_at','desc')->options(['allowDiskUse' => true])->first();
        if (isset($data->data['data']) && $data->data['data']['Power']['P_DC(W)']) {
            $P_DC_BTN = $data->data['data']['Power']['P_DC(W)'];

            // if($P_DC_BTN >= 1000)
            // {
            //     return "<span class='badge bg-danger text-white'>Discharge</span>";
            // }else if($P_DC_BTN < 0){
            //     if(abs($P_DC_BTN) <= 1000) // Convert nagative valut to possitive value(-1000)
            //     {
            //         return  "<span class='badge bg-status-charging text-white'>Charging</span>";
            //     } else {
            //         return  "<span class='badge bg-status-off text-white'>OFF</span>";
            //     }
            // } else if($P_DC_BTN < 1){
            //     return  "<span class='badge bg-status-off text-white'>OFF</span>";
            // } else {
            //     return  "<span class='badge bg-primary text-white'>Ideal</span>";
            // }

            if($P_DC_BTN <= -1000){
                return "<span class='badge bg-danger text-white'>Discharge</span>";
            } else  if($P_DC_BTN >= 1000){
                return  "<span class='badge bg-status-charging text-white'>Charging</span>";
            } else if($P_DC_BTN < 1 ){
                return  "<span class='badge bg-status-off text-white'>OFF</span>";
            } else {
                return  "<span class='badge bg-primary text-white'>Ideal</span>";
            }
        } else {
            return  "<span class='badge bg-status-off text-white'>OFF</span>";
        }
    }
}


function saveDailyActivity($data = [])
{
    if($data && count($data) > 0){

        $record = new DailyActivity;
        $record->user_id    = (isset($data['user_id']) && $data['user_id'] ? $data['user_id'] : '');
        $record->device_id  = (isset($data['device_id']) && $data['device_id'] ? $data['device_id'] : '');
        $record->company_id = (isset($data['company_id']) && $data['company_id'] ? $data['company_id'] : '');
        $record->macid      = (isset($data['macid']) && $data['macid'] ? $data['macid'] : '');
        $record->status     = (isset($data['status']) && $data['status'] ? $data['status'] : '');
        $record->save();
    }
}

function getDailyActivity($company_id='',$filter_id='')
{
    $bgArray = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
    $filterMacIds = [];
    if($filter_id){
        $filterMacIds = getFilterMacIds($filter_id);
    }
    if($company_id)
    {
        if($filter_id){
            $latestRecords = DailyActivity::with('device')->whereIn('macid',$filterMacIds)->where('company_id',$company_id)->latest()->take(20)->get()->toArray();
        } else {
            $latestRecords = DailyActivity::with('device')->where('company_id',$company_id)->latest()->take(20)->get()->toArray();
        }
    } 
    
    else {
        if($filter_id){
            $latestRecords = DailyActivity::with('device')->whereIn('macid',$filterMacIds)->latest()->take(20)->get()->toArray();
        }else{
            $latestRecords = DailyActivity::with('device')->latest()->take(20)->get()->toArray();
        }
    }

    foreach ($latestRecords as $key => $record) {
        $date = Carbon::parse($record['created_at']);
        $latestRecords[$key]['created'] = $date->format('d M Y H:i:s');
        $randomKey = array_rand($bgArray);
        $latestRecords[$key]['bg_color'] = $bgArray[$randomKey];
        if($record['status'])
        {
            $device_name = '';
            $user_name   = '';
            if(isset($record['device']['name']) && $record['device']['name'])
            {
                $device_name = $record['device']['name'];
            }

            if(isset($record['user_id']) && $record['user_id'])
            {
                $user_name = userProfile($record['user_id']);
            }
            if($record['status'] == 'descharging' || $record['status'] == 'discharging'){
                $record['status'] = 'discharging';
            }
            $latestRecords[$key]['conv_status'] = ucwords(str_replace('_', ' ', $record['status']));
            if($record['status'] == "remote_access_updated")
            {
                $latestRecords[$key]['message'] = 'Remote Access updated for ' .$device_name.' by '.$user_name;
            } else if($record['status'] == "new_user_added"){
                $latestRecords[$key]['message'] = ' New User Added by '.$user_name;
            } else if($record['status'] == "device_location_updated"){
                $latestRecords[$key]['message'] = 'Power Bank Location of '.$device_name.' Updated by '.$user_name;
            } else if($record['status'] == "device_updated"){
                $latestRecords[$key]['message'] = 'Power Bank Details of '.$device_name.' Updated by '.$user_name;
            } else if($record['status'] == "off"){
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name.' Current Status is OFF';
            } else if($record['status'] == "new_device_add"){
                $latestRecords[$key]['conv_status'] = 'New Power Bank Added';
                $latestRecords[$key]['message'] = 'New Power Bank '.$device_name.' Added by '.$user_name;
            } else if ($record['status'] == "descharging" || $record['status'] == "discharging") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Discharging';
            } else if ($record['status'] == "idle" || $record['status'] == "Idle ") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Idle';
            } else if ($record['status'] == "charging") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Charging';
            }
        }
    }
    return $latestRecords;
}

// dailyactivity based on user id

function getDailyActivitybyUser($user_id = '', $filter_id = '')
{
    $bgArray = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
    $filterMacIds = [];
    if($filter_id){
        $filterMacIds = getFilterMacIds($filter_id);
    }
  
    if($user_id)
    {

        if($filter_id) {
    $latestRecords = DailyActivity::with('device')
        ->whereIn('macid', $filterMacIds)
       // ->where('company_id', $company_id)
        ->where('user_id', $user_id) // Filter by user_id
        ->latest()
        ->take(20)
        ->get()
        ->toArray();
} else {
    $latestRecords = DailyActivity::with('device')
      //
     // ->where('company_id', $company_id)
        ->where('user_id', $user_id) // Filter by user_id
        ->latest()
        ->take(20)
        ->get()
        ->toArray();
}
    } 
    
    else {
        if($filter_id){
            $latestRecords = DailyActivity::with('device')->whereIn('macid',$filterMacIds)->latest()->take(20)->get()->toArray();
        }else{
            $latestRecords = DailyActivity::with('device')->latest()->take(20)->get()->toArray();
        }
    }

    foreach ($latestRecords as $key => $record) {
        $date = Carbon::parse($record['created_at']);
        $latestRecords[$key]['created'] = $date->format('d M Y H:i:s');
        $randomKey = array_rand($bgArray);
        $latestRecords[$key]['bg_color'] = $bgArray[$randomKey];
        if($record['status'])
        {
            $device_name = '';
            $user_name   = '';
            if(isset($record['device']['name']) && $record['device']['name'])
            {
                $device_name = $record['device']['name'];
            }

            if(isset($record['user_id']) && $record['user_id'])
            {
                $user_name = userProfile($record['user_id']);
            }
            if($record['status'] == 'descharging' || $record['status'] == 'discharging'){
                $record['status'] = 'discharging';
            }
            $latestRecords[$key]['conv_status'] = ucwords(str_replace('_', ' ', $record['status']));
            if($record['status'] == "remote_access_updated")
            {
                $latestRecords[$key]['message'] = 'Remote Access updated for ' .$device_name.' by '.$user_name;
            } else if($record['status'] == "new_user_added"){
                $latestRecords[$key]['message'] = ' New User Added by '.$user_name;
            } else if($record['status'] == "device_location_updated"){
                $latestRecords[$key]['message'] = 'Power Bank Location of '.$device_name.' Updated by '.$user_name;
            } else if($record['status'] == "device_updated"){
                $latestRecords[$key]['message'] = 'Power Bank Details of '.$device_name.' Updated by '.$user_name;
            } else if($record['status'] == "off"){
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name.' Current Status is OFF';
            } else if($record['status'] == "new_device_add"){
                $latestRecords[$key]['conv_status'] = 'New Power Bank Added';
                $latestRecords[$key]['message'] = 'New Power Bank '.$device_name.' Added by '.$user_name;
            } else if ($record['status'] == "descharging" || $record['status'] == "discharging") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Discharging';
            } else if ($record['status'] == "idle" || $record['status'] == "Idle ") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Idle';
            } else if ($record['status'] == "charging") {
                $device_name = getDeviceNameByMacId($record['macid']);
                $latestRecords[$key]['message'] = $device_name . ' Current Status is Charging';
            }
        }
    }
    return $latestRecords;
}
  




// function getDeviceMacids($company_id='')
// {
//     if($company_id)
//     {
//         $macids = Device::where('company_id',$company_id)->pluck('macid')->toArray();
//     } else {
//         $macids = Device::pluck('macid')->toArray();
//     }
//     if($macids){
//         $macids = array_unique($macids);
//     }
//     return $macids;
// }
function getDeviceMacids($user_id='',$company_id='')

{
    if($company_id)
    {
        $macids = Device::where('company_id',$company_id)->pluck('macid')->toArray();
    }
    elseif ($user_id) {
        // Fetch MAC IDs based on the user_id
        $macids = Device::where('user_id_str', $user_id)->pluck('macid')->toArray();
    } else {
       
        $macids = Device::pluck('macid')->toArray();
    }
 
    if($macids){
        $macids = array_unique($macids);
    }
    return $macids;
}

function getDeviceNameByMacId($macid='')
{
    if($macid)
    {
        $name = Device::where('macid',$macid)->pluck('name')->first();
        return $name;
    } else {
        return '';
    }
}

function getDeviceUsageTest($company_id='',$filter_id='')
{
    $filterMacIds = [];
    if($filter_id){
        $filterMacIds = getFilterMacIds($filter_id);
    }
    $startDate = Carbon::now('UTC')->startOfDay(); // GMT start of today
    $endDate   = Carbon::now('UTC')->endOfDay();   // GMT end of today
    $Battery_Discharge_Time = [];

    $data = [];
    if($company_id)
    {
        if($filterMacIds){
            $latestRecords = Device::select('_id','name','macid','created_at')->where('company_id',$company_id)->whereIn('macid',$filterMacIds)->get();
        } else {
            $latestRecords = Device::select('_id','name','macid','created_at')->where('company_id',$company_id)->get();
        }
    } else {
        if($filterMacIds){
            $latestRecords = Device::select('_id','name','macid','created_at')->whereIn('macid',$filterMacIds)->get();
        } else {

            $latestRecords = Device::select('_id','name','macid','created_at')->get();
        }
    }

    if($latestRecords){
        foreach ($latestRecords as $LRkey => $value) {
            // OLD Logic (06-03-2024)
            $date = Carbon::parse($value['created_at']);
            $data[$LRkey]['device_name'] = $value['name'] ?? '';
            $data[$LRkey]['created']     = $date->format('d M Y H:i:s');
            // OLD Logic
            // $high_run_time                   = Data::whereNotNull('data.data.Battery_Discharge_Time(s)')->where('macid',$value->macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.Battery_Discharge_Time(s)')->first();
            // $data[$LRkey]['high_run_time']   = (isset($high_run_time) && $high_run_time ? $high_run_time : 0);
            // $AC_Solar_Tot_Energy     = Data::whereNotNull('data.data.AC_Solar_Tot_Energy(Wh)')->where('macid',$value->macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.AC_Solar_Tot_Energy(Wh)')->first();
            // $DC_Solar_Energy         = Data::whereNotNull('data.data.DC_Solar_Energy(Wh)')->where('macid',$value->macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.DC_Solar_Energy(Wh)')->first();
            // $data[$LRkey]['solar_generated_power']   = $AC_Solar_Tot_Energy + $DC_Solar_Energy;
            // $fule_saved                    = Data::whereNotNull('data.data.Total_saving($)')->where('macid',$value->macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.Total_saving($)')->first();
            // $data[$LRkey]['fule_saved']    = (isset($fule_saved) && $fule_saved ? $fule_saved : 0);

            // New Logic
            $latestData = Data::where('macid', $value->macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Battery_Discharge_Time(s)')
                    ->orWhereNotNull('data.data.AC_Solar_Tot_Energy(Wh)')
                    ->orWhereNotNull('data.data.DC_Solar_Energy(Wh)')
                    ->orWhereNotNull('data.data.Total_saving($)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if ($latestData) {
                $data[$LRkey]['high_run_time'] = $latestData->data['data']['Battery_Discharge_Time(s)'] ?? 0;
                $data[$LRkey]['solar_generated_power'] = ($latestData->data['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0) + ($latestData->data['data']['DC_Solar_Energy(Wh)'] ?? 0);
                $data[$LRkey]['fule_saved'] = $latestData->data['data']['Total_saving($)'] ?? 0;
            } else {
                $data[$LRkey]['high_run_time'] = 0;
                $data[$LRkey]['solar_generated_power'] = 0;
                $data[$LRkey]['fule_saved'] = 0;
            }

        }
    }
    $highestRunTimeValue      = null;
    $solarGeneratedPowerValue = null;
    $fuleSavedValue           = null;
    $high_run_time          = [];
    $solar_generatedpower   = [];
    $fule_saved             = [];
    if($data && count($data) > 0){
        foreach ($data as $key => $value) {
            if ($highestRunTimeValue === null || $value['high_run_time'] > $highestRunTimeValue) {
                $highestRunTimeValue = $value['high_run_time'];
                $high_run_time       = $value;
            }

            if($solarGeneratedPowerValue === null || $value['solar_generated_power'] > $solarGeneratedPowerValue){
                $solarGeneratedPowerValue = $value['solar_generated_power'];
                $solar_generatedpower    = $value;
            }

            if($fuleSavedValue === null || $value['fule_saved'] > $fuleSavedValue){
                $fuleSavedValue = $value['fule_saved'];
                $fule_saved     = $value;
            }
        }
        $return = [
            'high_run_time'          => $high_run_time,
            'solar_generated_power'  => $solar_generatedpower,
            'fule_saved'             => $fule_saved,

        ];
        return $return;
    } else {
        return [];
    }
}
function getDeviceUsageTestings($company_id='',$filter_id='')
{
    $filterMacIds = $filter_id ? getFilterMacIds($filter_id) : [];

    $latestRecordsQuery = Device::select('_id', 'name', 'macid', 'created_at')
    ->when($company_id, function($query) use ($company_id) {
        return $query->where('company_id', $company_id);
    })
    ->when($filterMacIds, function($query) use ($filterMacIds) {
        return $query->whereIn('macid', $filterMacIds);
    })
    ->get();


    // Remove duplicate macids
    $macids = $latestRecordsQuery->pluck('macid')->unique()->toArray();

    $data = Data::whereIn('macid', $macids)
        ->whereIn('data.data.Battery_Discharge_Time(s)', ['$', null])
        ->orWhereIn('data.data.AC_Solar_Tot_Energy(Wh)', ['$', null])
        ->orWhereIn('data.data.DC_Solar_Energy(Wh)', ['$', null])
        ->orWhereIn('data.data.Total_saving($)', ['$', null])
        ->orderByDesc('created_at_timestamp')
        ->options(['allowDiskUse' => true])
        ->get();

    $result = [
        'high_run_time'         => 0,
        'solar_generated_power' => 0,
        'fule_saved'            => 0,
    ];

    foreach ($data as $record) {
        $high_run_time = $record->data['data']['Battery_Discharge_Time(s)'] ?? 0;
        $AC_Solar_Tot_Energy = $record->data['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0;
        $DC_Solar_Energy = $record->data['data']['DC_Solar_Energy(Wh)'] ?? 0;
        $fule_saved = $record->data['data']['Total_saving($)'] ?? 0;

        $result['high_run_time'] += $high_run_time;
        $result['solar_generated_power'] += ($AC_Solar_Tot_Energy + $DC_Solar_Energy);
        $result['fule_saved'] += $fule_saved;
    }

    $highestRunTimeValue = $data->max('data.data.Battery_Discharge_Time(s)');
    $highestSolarPowerValue = $data->max('data.data.AC_Solar_Tot_Energy(Wh)') + $data->max('data.data.DC_Solar_Energy(Wh)');
    $highestFuelSavedValue = $data->max('data.data.Total_saving($)');

    $highestRunTimeRecord = $data->where('data.data.Battery_Discharge_Time(s)', $highestRunTimeValue)->first();
    $highestSolarPowerRecord = $data->where('data.data.AC_Solar_Tot_Energy(Wh)', $highestSolarPowerValue - $highestRunTimeRecord->data['data']['AC_Solar_Tot_Energy(Wh)'])->first();
    $highestFuelSavedRecord = $data->where('data.data.Total_saving($)', $highestFuelSavedValue - $highestSolarPowerRecord->data['data']['Total_saving($)'])->first();

    $results = [
        'highestRunTime' => $highestRunTimeRecord,
        'highestSolarPower' => $highestSolarPowerRecord,
        'highestFuelSaved' => $highestFuelSavedRecord,
    ];

    echo "<pre>"; print_r($results); exit("CALL");
}


function unitConverter($val,$decimal = 2)
{
    if($val > -1000 && $val < 1000 )
    {
        $val = number_format($val, $decimal);
        $val." ";
        return $val;

    }   else if($val <= -1000 && $val > -1000000){
            $val = $val /1000;
            $val = number_format($val, $decimal);
            $str = $val." K";
            return $str;
        }
        else if($val <= -1000000 &&  $val > -1000000000)
        {
            $val = $val /1000000;
            $val = number_format($val, $decimal);
            $str = $val." M";
            return $str;
        }
        else if($val <= -1000000000 &&  $val > -1000000000000)
        {
            $val = $val /1000000000;
            $val = number_format($val, $decimal);
            $str = $val." G";
            return $str;
        }
        else if($val >= 1000 && $val <1000000)
        {
            $val = $val /1000;
            $val = number_format($val, $decimal);
            $str = $val." K";
            return $str;
        }
        else if($val >= 1000000 &&  $val <1000000000)
        {
            $val = $val /1000000;
            $val = number_format($val, $decimal);
            $str = $val." M";
            return $str;
        }
        else if($val >= 1000000000 &&  $val <1000000000000)
        {
            $val = $val /1000000000;
            $val = number_format($val, $decimal);
            $str = $val." G";
            return $str;
        }
        return "NOT Valid";

}

function getCurrentIpAddress()
{
    $ipAddress = request()->ip();
    return $ipAddress;
}

function saveLogs($type)
{
    if($type)
    {
        $data = [
            'user_id'    => auth()->guard('admin')->user()->id,
            'user_name'  => auth()->guard('admin')->user()->first_name .' '.auth()->guard('admin')->user()->last_name,
            'ip address' => getCurrentIpAddress(),
            'system date and time' => Carbon::now(),
            'type'      => $type
        ];
        Log::info($type,['data' => $data]);
    }
}

function getCompanyName($id){
    if($id){
        $comp_name = Company::where('_id',$id)->pluck('company_name')->first();
        return $comp_name;
    }
}

function getAgentId($company_id,$install_id){
    if($company_id && $install_id){
        $agent_id = CompanyAgentDetail::where('company_id',$company_id)->where('install_code',(int)$install_id)->pluck('agent_id')->first();
        return $agent_id;
    }
}

function getAssignCounts($companyId, $channel_id){
    if ($companyId) {
        $counts = Device::where('company_id', $companyId)->where('channel_id', (string)$channel_id)->count();
        return $counts;
    }
}

function getChartYearRange()
{
    $startYear   = 2022;
    $currentYear = date('Y');
    $years       = range($startYear, $currentYear);

    return $years;
}

function getChartMonthRange() {
    $monthNames = [
        'January'  => 1,
        'February' => 2,
        'March'    => 3,
        'April'    => 4,
        'May'      => 5,
        'June'     => 6,
        'July'     => 7,
        'August'   => 8,
        'September'=> 9,
        'October'  => 10,
        'November' => 11,
        'December' => 12,
    ];

    return $monthNames;

}

// Get Notofications
function getNotification(){
    $unreadNotifications = Notification::with('user')->where(['is_read' => 0, 'read_at' => NULL, 'type' => 'NEW_REGISTER'])
    ->latest()
    ->limit(3)
    ->get();

    return $unreadNotifications;
}

function getNotificationCounts(){
    $unread_count   = Notification::where(['is_read' => 0, 'read_at' => NULL, 'type' => 'NEW_REGISTER'])->count();
    return $unread_count;
}

function getClusters(){
    $company_login_id = session()->get('company_login_id');
    if($company_login_id)
    {
        $company_id = $company_login_id;
    } else {
        $company_id = auth()->guard('admin')->user()->company_id;
    }

    $groups = Cluster::where(['status' => 1,'company_id' => $company_id])->get();
    return $groups;
}

function getFilterMacIds($id){
    if($id){
        $clusterRecords = Cluster::where('_id', $id)->count();
        $deviceRecords  = Device::where('_id', $id)->count();
        $macIds = [];
        if ($clusterRecords > 0) {
            $macIds = Device::where('cluster_id', $id)->pluck('macid')->toArray();
        } else {
            $macIds = Device::where('_id', $id)->pluck('macid')->toArray();
        }
        return $macIds;
    }
}

function getIconSettings($company_id){
    if($company_id){
        $dataOne = IconSetting::where('company_id',$company_id)->get()->toArray();
        return $dataOne;
    }
}
function generateIconSettings($company_id){
    if($company_id){
        $icons_data = [
            [
                'icon_label' => 'Generator',
                'icon_name'  => 'charging.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Grid',
                'icon_name'  => 'tower.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Unit',
                'icon_name'  => 'unit.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'AC Solar',
                'icon_name'  => 'acsolar.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Load',
                'icon_name'  => 'load.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'DC Solar',
                'icon_name'  => 'dcsolar.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Battery',
                'icon_name'  => 'battery_full_icon.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Solar',
                'icon_name'  => 'solar.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'PowerBank Details',
                'icon_name'  => 'powerbank-details.svg',
                'status'     => 'active',
            ],
            [
                'icon_label' => 'Generator',
                'icon_name'  => 'charging-off.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'Grid',
                'icon_name'  => 'tower-off.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'Unit',
                'icon_name'  => 'unit-off.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'AC Solar',
                'icon_name'  => 'acsolar-xl-off-icon.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'Load',
                'icon_name'  => 'load-xl-off-icon.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'DC Solar',
                'icon_name'  => 'dcsolar-xl-off-icon.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'Battery',
                'icon_name'  => 'battery_full_icon_off.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'Solar',
                'icon_name'  => 'solar-xl-off-icon.svg',
                'status'     => 'inactive',
            ],
            [
                'icon_label' => 'PowerBank Details',
                'icon_name'  => 'powerbank-details-off.svg',
                'status'     => 'inactive',
            ],
        ];
        foreach ($icons_data as $key => $value) {
            $mytime    = Carbon::now();
            $now_time  = $mytime->toDateTimeString();
            $iconDatas = new IconSetting();
            $iconDatas->company_id   = $company_id ?? '';
            $iconDatas->icon_label   = $value['icon_label'] ?? '';
            $iconDatas->icon_name    = $value['icon_name'] ?? '';
            $iconDatas->status       = $value['status'] ?? '';
            $iconDatas->created_at   = $now_time;
            $iconDatas->updated_at   = $now_time;
            $iconDatas->save();
        }
    }
}

// function getCurrency(){
//     $url = asset('/theme-asset/file/Common-Currency.json');
//     $data = file_get_contents($url, true);
//     $datas = json_decode($data);
//     return $datas;
// }


function getCurrency() {
    // Get the full path of the JSON file using public_path()
    $filePath = public_path('theme-asset/file/Common-Currency.json');
    
    // Ensure the file exists before trying to get its contents
    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
        $datas = json_decode($data);
        return $datas;
    }

    // Handle the case where the file doesn't exist
    return null; // or return an empty array [] or some error message
}