<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Validators\ApiValidator;
use Illuminate\Support\Str;
use App\Models\Inverter;
use App\Models\InverterSetting;
use App\Models\Data;
use App\Models\InverterWarningCode;
use App\Http\Traits\UserTrait;
use Carbon\Carbon;
use Auth;
use MongoDB\BSON\UTCDateTime;
use DateTime;

class UserController extends Controller
{
    use ApiValidator;
    use UserTrait;
    public function inverterList(Request $request)
    {
        $user = $request->user();
        $inverter = Inverter::where('user_id_str',$user->id)->where('status',1)->where('deleted','!=','1')->get();
        if($inverter){
            $response = ['status' => 'true', 'response_msg' => 'Inverter list.', 'inverter' => $inverter];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'No inverter found.', 'inverter' => $inverter];
        }
        return response()->json($response);
    }
    public function getDefaultInverter(Request $request)
    {
        $user = $request->user();
        $inverter = Inverter::where('user_id_str',$user->id)->where('status',1)->where('deleted','!=','1')->first();
        if($inverter){
            $response = ['status' => 'true', 'response_msg' => 'Inverter detail.', 'inverter' => $inverter];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'No inverter found.', 'inverter' => $inverter];
        }
        return response()->json($response);
    }
    public function getDataByContentType(Request $request)
    {
        if($request->has('control_card_no') && $request->has('content')){
            $user = $request->user();
            $data = Data::where('data.Control_card_sn',$request->control_card_no)->where('data.content',$request->content)->orderBy('created_at_timestamp','desc')->first();
            if($data){
                $response = ['status' => 'true', 'response_msg' => 'Inverter detail.', 'data' => $data];
            } else {
                $response = ['status' => 'false', 'response_msg' => 'No data found.', 'data' => $data];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
    public function deleteInverter(Request $request)
    {
        if($request->has('inverter_id') && $request->inverter_id){
            $user = $request->user();
            $inverter = Inverter::where('_id',$request->inverter_id)->first();
            if(!$inverter){
                $response = ['status' => 'false', 'response_msg' => 'Invalid inverter ID.'];
            } else {
                $inverter->deleted = '1';
                $inverter->deleted_at = date('Y-m-d H:i:s');
                $inverter->save();
                $response = ['status' => 'true', 'response_msg' => 'Inverter deleted successfully.'];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
    public function getInverterSettings(Request $request)
    {
        $response = [];
        $validate = $this->inverterSettingValidate($request);
        if($validate['status'] == 'false'){
            return response()->json($validate);
        }
        
        //$setting = InverterSetting::where('inverter_id',$request->inverter_id)->first();
        $setting = [];
        if(!$setting){
            // store default setting when setting is empty
            $inverter = Inverter::where('_id',$request->inverter_id)->first();
            $setting = $this->getInverterSettingDetails($inverter->control_card_no,$request->inverter_id);

        }

        if($setting){
            $response = ['status' => 'true', 'response_msg' => 'Inverter data.', 'data' => $setting];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Not able to save setting.', 'data' => $setting];
        }
        return response()->json($response);
    }
    public function getInverterWarningMsg(Request $request)
    {
        $response = [];
        $codes = ($request->has('warning_codes') && $request->warning_codes) ? array_map('intval', explode(',', $request->warning_codes)) : [];
        $warningData = $this->getInverterWaningCodeByCodeOrccn($request->control_card_no ?? '',$codes);

        if($warningData){
            $response = ['status' => 'true', 'response_msg' => 'Inverter warnings.', 'data' => $warningData];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Not warning found.', 'data' => $warningData];
        }
        return response()->json($response);
    }
    public function getPowerChartData(Request $request)
    {
        if($request->has('control_card_no')){
            $reqData = [
                'startDate' => $request->startDate ?? date('Y-m-d'),
                'endDate' => $request->endDate ?? date('Y-m-d'),
                'control_card_no' => $request->control_card_no
            ];
            $mainData = $this->getPowerGraphData($reqData);

            if($mainData){
                $response = ['status' => 'true', 'response_msg' => 'Graph detail.', 'data' => $mainData];
            } else {
                $response = ['status' => 'false', 'response_msg' => 'No data found.', 'data' => $mainData];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
    public function getEnergyChartData(Request $request)
    {
        if($request->has('control_card_no')){
            $reqData = [
                'control_card_no' => $request->control_card_no,
                'enchartFilter' => $request->enchartFilter ?? 'month',
                'enDay' => $request->enDay ?? date('d'),
                'enMonth' => $request->enMonth ?? date('m'),
                'enYear' => $request->enYear ?? date('Y')
            ];
            $mainData = $this->getEnergyGraphData($reqData);

            if($mainData){
                $response = ['status' => 'true', 'response_msg' => 'Graph detail.', 'data' => $mainData];
            } else {
                $response = ['status' => 'false', 'response_msg' => 'No data found.', 'data' => $mainData];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
    public function getBatteryStatusChartData(Request $request)
    {
        if($request->has('control_card_no')){
            $reqData = [
                'startDate' => $request->startDate ?? date('Y-m-d'),
                'endDate' => $request->endDate ?? date('Y-m-d'),
                'control_card_no' => $request->control_card_no
            ];
            $mainData = $this->getBatteryStatusGraphData($reqData);

            if($mainData){
                $response = ['status' => 'true', 'response_msg' => 'Graph detail.', 'data' => $mainData];
            } else {
                $response = ['status' => 'false', 'response_msg' => 'No data found.', 'data' => $mainData];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
    public function getAlarmWarningData(Request $request)
    {
        if($request->has('inverter_id')){
            $warningData = [];
            $limit = 20;
            $offset = $request->offset ?? 0;
            $inverter = Inverter::where('_id',$request->inverter_id)->first();
            $data        = Data::select('*')
                        ->where('data.content','alarm_warning_details')
                        ->where('data.alarm_warning','>',0)
                        ->where('data.Control_card_sn',$inverter->control_card_no)
                        ->orderByDesc('created_at_timestamp')
                        ->skip($offset)
                        ->take($limit)
                        ->get();

            if($data && count($data) > 0){
                $i=0;
                foreach ($data as $key => $value) {
                    $alarm_warning_code = $value['data']['alarm_warning_code'] ?? [];
                    if($alarm_warning_code){
                        foreach ($alarm_warning_code as $key1 => $value1) {
                            $code = InverterWarningCode::select('code','msg')->where('code',$value1)->first();
                            $warningData[$i]['control_card_sn'] = $value['data']['Control_card_sn'];
                            $warningData[$i]['serial_no'] = $inverter->serial_no ?? '';
                            $warningData[$i]['code'] = $value1;
                            $warningData[$i]['code_msg'] = $code->msg ?? '';
                            $warningData[$i]['created_at'] = $value->created_at;
                            $warningData[$i]['created_at_timestamp'] = $value->created_at_timestamp;
                            $warningData[$i]['created_at_format'] = Carbon::parse($value->created_at)->format('Y-m-d g:i A');
                            $i++;
                        }
                    }
                }
                $response = ['status' => 'true', 'response_msg' => 'Alarm detail.', 'offset' => $offset + $limit , 'data' => $warningData];
            } else {
                $response = ['status' => 'false', 'response_msg' => 'No data found.', 'data' => $warningData];
            }
            return response()->json($response);
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Missing Required Param.' ];
            return response()->json($response);
        }
    }
}
