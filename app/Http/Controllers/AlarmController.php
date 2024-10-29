<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DataTables;
use App\Models\Inverter;
use App\Models\InverterWarningCode;
use App\Models\Data;
use App\Http\Traits\UserTrait;
use Carbon\Carbon;
use  Illuminate\Support\Facades\Log;

class AlarmController extends Controller
{
    use UserTrait;
    public function index(Request $request)
    {
        try{
            if ($request->ajax())
            {
                $request->merge(array( 
                    'start' => (int)$request->input('start'), 
                    'length' => (int)$request->input('length') 
                ));
    
                $data        = Data::select('*')->where('data.content','alarm_warning_details')->where('data.alarm_warning','>',0);
                $seacrh_name = $request->get('seacrh_name');
                $control_card_no = $request->get('control_card_no');
                $serial_no = $request->get('serial_no');
                $user_name = $request->get('user_name');
                $startDate = $request->get('startDate');
                $endDate = $request->get('endDate');
                if($seacrh_name) {
                    $data->where('site_name', 'like', "%{$seacrh_name}%");
                }
                if($control_card_no) {
                    $data->where('data.Control_card_sn', $control_card_no);
                }
                if($serial_no) {
                    $data->where('serial_no', 'like', "%{$serial_no}%");
                }
                if($user_name) {
                    $data->whereHas('user', function ($query) use($user_name) {
                        $query->where('name', 'like', "%{$user_name}%");
                    });
                }
                if($startDate && $endDate){
                    $endDate = $endDate . ' 23:59:59';
                    $stDate = new Carbon($startDate);
                    $edDate = new Carbon($endDate);
                    $data->whereBetween('created_at',[$stDate, $edDate]);
                }
    
                $data = $data->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function($row){
                        $startDate = date_create($row->created_at);
                        return date_format($startDate,"Y-m-d h:m A");
                    }) 
                    ->addColumn('updated_at', function($row){
                        $startDate = date_create($row->updated_at);
                        return date_format($startDate,"Y-m-d h:m A");
                    })
                    ->addColumn('control_card_sn', function($row){
                        return $row['data']['Control_card_sn'] ?? '';
                    })
                    ->addColumn('warning_code', function($row){
                        return $row['data']['alarm_warning_code'] ?? '';
                    })
                    ->addColumn('warning_Text', function($row){
                        if( isset($row['data']['alarm_warning_code']) && $row['data']['alarm_warning_code'] ){
                            $code = InverterWarningCode::select('code','msg')->whereIn('code',$row['data']['alarm_warning_code'])->get();
                            //return $code ? $code->pluck('msg') : '' ;
                            if($code){
                                $alrm = '';
                                foreach ($code as $key => $value) {
                                    $alrm .= $value->code  . ' : ' . $value->msg . '<br>';
                                }
                                return $alrm;
                            } else {
                                return '';
                            }
                        } else {
                            return '';
                        }
                        
                    })
                    ->rawColumns(['warning_Text'])
                    ->make(true);
            }
            $data           = [];
            $data['title']  = 'Alarm Warning';
            Log::info('the alarm page is retrived by user ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('alarm.index',$data);
        }
        catch(Exception $e){
            Log::error('error occured in alarm page ', ['message'=>$e->gwtMessage()]);
        }
       
    }
}
