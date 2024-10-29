<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \DataTables;
use App\Models\Inverter;
use App\Models\Data;
use App\Models\UserPin;
use App\Models\InverterWarningCode;
use App\Http\Traits\UserTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use DateTime;
use Illuminate\Support\Facades\Log;

class InverterController extends Controller
{
    use UserTrait;
    public function index(Request $request)
    {
        try{
            Cache::forget('myPin');
            if ($request->ajax())
            {
                $request->merge(array( 
                    'start' => (int)$request->input('start'), 
                    'length' => (int)$request->input('length') 
                ));
    
                $data        = Inverter::select('*');
                $seacrh_name = $request->get('seacrh_name');
                $control_card_no = $request->get('control_card_no');
                $serial_no = $request->get('serial_no');
                $user_name = $request->get('user_name');
                if($seacrh_name) {
                    $data->where('site_name', 'like', "%{$seacrh_name}%");
                }
                if($control_card_no) {
                    $data->where('control_card_no', 'like', "%{$control_card_no}%");
                }
                if($serial_no) {
                    $data->where('serial_no', 'like', "%{$serial_no}%");
                }
                if($user_name) {
                    $data->whereHas('user', function ($query) use($user_name) {
                        $query->where('name', 'like', "%{$user_name}%");
                    });
                }
    
                $data = $data->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($row){
                        $statusBtn = '';
                        if($row->status == 0){
                            $statusBtn .= "Inactive";
                        } else if($row->status == 1){
                            $statusBtn .= "Active";
                        }
                        return $statusBtn;
                    })
                    ->addColumn('created_at', function($row){
                        $startDate = date_create($row->created_at);
                        return date_format($startDate,"Y-m-d h:m A");
                    }) 
                    ->addColumn('updated_at', function($row){
                        $startDate = date_create($row->updated_at);
                        return date_format($startDate,"Y-m-d h:m A");
                    })
                    ->addColumn('user_name', function($row){
                        return $row->user->name ?? '';
                    })
                    ->addColumn('verified', function($row){
                        return $row->verified ?? '';
                    })
                    ->addColumn('action', function($row){
                        $url =  url('admin/inverter-detail').'/'.$row->id;
                        $urlSettings =  url('admin/inverter-settings').'/'.$row->id;
                        $urlPGraph =  url('admin/graph-power').'/'.$row->id;
                        $actionBtn  = '';
                        $actionBtn .= '<div class="btn-group" role="group" aria-label="Action button">';
                        $actionBtn .= '<a href="'.$url.'" class=" btn btn-info btn-sm mr5" title="Click to View"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                       // $actionBtn .= '<a href="'.$urlSettings.'" class=" btn btn-warning btn-sm mr5" title="Settings"><i class="fa fa-cog" aria-hidden="true"></i></a>';
                       // $actionBtn .= '<a href="'.$urlPGraph.'" class=" btn btn-warning btn-sm mr5" title="Power Graph"><i class="fa fa-line-chart" aria-hidden="true"></i></a>';
                       if($row->deleted && $row->deleted == '1'){
                        $actionBtn .= '<span class="delParentSpn"> <span class="spn-deleted pl-2"> DELETED </span> <span class="spn-deleted-at pl-2"> '. $row->deleted_at .' </span> </span>';
                       }
                       
                       $actionBtn .= '</div>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $data           = [];
            $data['title']  = 'Inverters';
            Log::info('the inverter page retrived by user',[
                'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email
            ]);
            return view('inverters.index',$data);
        }
        catch(Exception $e){
        Log::error('error occured  in inverter page',[$e->getMessage()]);

        }
       
    }
    public function detail(Request $request,$id)
    {
        try{
            $inverter = Inverter::where('_id',$id)->first();
            if(!$inverter){
                return redirect()->back();
            }
    
            $type = request()->query('type');
    
            $data           = [];
            $data['title']  = 'Inverter';
            if($type == 'batteryAnalysis'){
                $battery_details = Data::where('data.Control_card_sn',$inverter->control_card_no)->where('data.content','battery_details')->orderBy('created_at_timestamp','desc')->first();
                $data['title_sub']  = 'Battery Analysis';
                $data['inverter']  = $inverter;
                $data['battery_details']  = $battery_details;
                return view('inverters.detail_battery_analysis',$data);
            } else if($type == 'alarmHistory'){
                $data['title_sub']  = 'Alarm History';
                $data['inverter']  = $inverter;
                return view('inverters.detail_alarm_history',$data);
            } else if($type == 'statisticReport'){
                $data['title_sub']  = 'Statistic Report';
                $data['inverter']  = $inverter;
                return view('inverters.detail_statistic_report',$data);
            } else {
                $data['title_sub']  = 'Inverter Details';
                $data['inverter']  = $inverter;
                return view('inverters.detail',$data);
            }
            Log::info('the inverte detail page is retrived by user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        }
        catch(Exception $e){
            Log::error('error occured  in inverter detail page',[$e->getMessage()]);
        }
        
        
    }
    public function getTmpContentData(Request $request)
    {
        try{
        $data = Data::where('data.Control_card_sn',$request->control_card_no)->where('data.content',$request->content)->orderBy('created_at_timestamp','desc')->first();
        $viewName = '';
        if($request->content == 'pv_details'){
            $viewName = 'tmp_pv_details';
        } else if($request->content == 'inverter_details'){
            $viewName = 'tmp_inverter_details';
        } else if($request->content == 'battery_details'){
            $viewName = 'tmp_battery_details';
        } else if($request->content == 'grid_details'){
            $viewName = 'tmp_grid_details';
        }
        Log::info('tmp content data of inverted by  user', ['ip_address' => $request->ip(), 
        'user_id' => auth()->user()->email]);

        return view('inverters.'.$viewName, compact('data'))->render();
    }
    catch(Exception $e){
        Log::error('error occured  in tmp content data',[$e->getMessage()]);
        }
        }
    public function settings($id)
    {
        try{
            $inverter = Inverter::where('_id',$id)->first();
            if(!$inverter){
                return redirect()->back();
            }
            $userpin = UserPin::where('user_id',auth()->user()->id)->first();
            $setting = $this->getInverterSettingDetails($inverter->control_card_no,$id);
            $data           = [];
            $data['title']  = 'Inverter';
            $data['title_sub']  = 'Inverter Settings';
            $data['inverter']  = $inverter;
            $data['userpin']  = $userpin ? true : false;
            $data['setting']  = $setting;
            Log::info('inverter setting retrived by user ', ['ip_address' => $request->ip(),
            'user_id' => auth()->user()->email]);

            return view('inverters.settings',$data);

        }
        catch(Exception  $e){
            Log::error('error occured  in inverter settings',[$e->getMessage()]);
            }


       
    }
    public function saveInverterSetting(Request $request)
    {
        try{
        $data = $request->post();
        $route = 'save_inverter_settings';
        $setting = $this->invokeNodeApi($route,$data);
        Log::info('save  inverter setting by user', ['ip_address' => $request->ip(),
        'user_id' => auth()->user()->email]);

        return response($setting);
    }
    catch(Exception  $e){
        Log::error('error occured  in save inverter settings',[$e->getMessage()]);
        }}

    public function getInverterWarningMsg(Request $request)
    {
        try{

        $response = [];
        $codes = ($request->has('warning_codes') && $request->warning_codes) ? array_map('intval', $request->warning_codes) : [];
        $warningData = $this->getInverterWaningCodeByCodeOrccn($request->control_card_no ?? '',$codes);

        if($warningData){
            $response = ['status' => 'true', 'response_msg' => 'Inverter warnings.', 'data' => $warningData];
        } else {
            $response = ['status' => 'false', 'response_msg' => 'Not warning found.', 'data' => $warningData];
        }
        Log::info('inverter warning msg retrived by user ', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return response()->json($response);
    }
    catch(Exception   $e){
        Log::error('error occured  in get inverter warning msg',[$e->getMessage()
        ]);}
    }

    public function graphPower($id)
    {
        $inverter = Inverter::where('_id',$id)->first();
        if(!$inverter){
            return redirect()->back();
        }
        $data           = [];
        $data['title']  = 'Graph';
        $data['title_sub']  = 'Graph';
        $data['inverter']  = $inverter;
        return view('graph.power',$data);
    }
    public function getPowerChartData(Request $request)
    {
        $reqData = [
            'startDate' => $request->startDate ?? date('Y-m-d'),
            'endDate' => $request->endDate ?? date('Y-m-d'),
            'control_card_no' => $request->control_card_no
        ];
        $mainData = $this->getPowerGraphData($reqData);
        return response()->json($mainData);
    }
    public function createWarningCodeMaster()
    {
        dd('stop');
        $codes = [
            [ 'code' => 0, 'msg' => 'PV volt high' ],
            [ 'code' => 1, 'msg' => 'PV config Err' ],
            [ 'code' => 2, 'msg' => 'DCI OCP Fault' ],
            [ 'code' => 3, 'msg' => 'DCV OCP Fault' ],
            [ 'code' => 4, 'msg' => 'RC OCP Fault' ],
            [ 'code' => 5, 'msg' => 'SW OCP Fault' ],
            [ 'code' => 6, 'msg' => 'RCD fault' ],
            [ 'code' => 7, 'msg' => 'TZ protect' ],
            [ 'code' => 8, 'msg' => 'Isolation Fault' ],
            [ 'code' => 9, 'msg' => 'Temp Over' ],
            [ 'code' => 10, 'msg' => 'Grid Lost' ],
            [ 'code' => 11, 'msg' => 'V Grid Err' ],
            [ 'code' => 12, 'msg' => 'Grid Freq err' ],
            [ 'code' => 13, 'msg' => 'PLL lost' ],
            [ 'code' => 14, 'msg' => '10m Grid Volt' ],
            [ 'code' => 15, 'msg' => 'Bus Volt High' ],
            [ 'code' => 16, 'msg' => 'Grid Relay Faul' ],
            [ 'code' => 17, 'msg' => 'HCT AC Fault' ],
            [ 'code' => 18, 'msg' => 'P_Angle Fault' ],
            [ 'code' => 19, 'msg' => 'Parallel Fault' ],
            [ 'code' => 20, 'msg' => 'EPS Overload' ],
            [ 'code' => 21, 'msg' => 'EPS OCP Fault' ],
            [ 'code' => 22, 'msg' => 'EPS Bat P Low' ],
            [ 'code' => 23, 'msg' => 'EPS Relay Fault' ],
            [ 'code' => 24, 'msg' => 'Inv E2Prom Fault' ],
            [ 'code' => 25, 'msg' => 'Int Comm Fault' ],
            [ 'code' => 26, 'msg' => 'Fan fault' ],
            [ 'code' => 27, 'msg' => 'Hardware trip' ],
            [ 'code' => 28, 'msg' => 'F.W Vers Fault' ],
            [ 'code' => 29, 'msg' => 'SAMP Cons Fault' ],
            [ 'code' => 30, 'msg' => 'Bat Volt High' ],
            [ 'code' => 31, 'msg' => 'Bat Relay Err' ],
            [ 'code' => 32, 'msg' => 'Bat Condir Err' ],
            [ 'code' => 33, 'msg' => 'BMS Cellimbal' ],
            [ 'code' => 34, 'msg' => 'BMS charge OCP' ],
            [ 'code' => 35, 'msg' => 'BMS Dischar OCP' ],
            [ 'code' => 36, 'msg' => 'BMS Ext Err' ],
            [ 'code' => 37, 'msg' => 'BMS Int Err' ],
            [ 'code' => 38, 'msg' => 'BMS O.V.' ],
            [ 'code' => 39, 'msg' => 'BMS L.V.' ],
            [ 'code' => 40, 'msg' => 'BMS CurSen_Fault' ],
            [ 'code' => 41, 'msg' => 'BMS TemSen_Fault' ],
            [ 'code' => 42, 'msg' => 'BMS V_Sen Fault' ],
            [ 'code' => 43, 'msg' => 'BMS Temp High' ],
            [ 'code' => 44, 'msg' => 'BMS Temp Low' ],
            [ 'code' => 45, 'msg' => 'BMS ISO Fault' ],
            [ 'code' => 46, 'msg' => 'BMS H.W. Prot' ],
            [ 'code' => 47, 'msg' => 'BMS InLock Fault' ],
            [ 'code' => 48, 'msg' => 'BMS Relay Fault' ],
            [ 'code' => 49, 'msg' => 'BMS Type Unmatch' ],
            [ 'code' => 50, 'msg' => 'BMS Ver Unmathch' ],
            [ 'code' => 51, 'msg' => 'BMS Man_Unmatch' ],
            [ 'code' => 52, 'msg' => 'BMS SW&HW diff' ],
            [ 'code' => 53, 'msg' => 'BMS M&S Unmatch' ],
            [ 'code' => 54, 'msg' => 'BMS CR Unrespons' ],
            [ 'code' => 55, 'msg' => 'BMS S.W. Protect' ],
            [ 'code' => 56, 'msg' => 'BMS 536 Fault' ],
            [ 'code' => 57, 'msg' => 'BMS Temp. diff' ],
            [ 'code' => 58, 'msg' => 'BMS Break' ],
            [ 'code' => 59, 'msg' => 'BMS Flash Fault' ],
            [ 'code' => 60, 'msg' => 'Precharge Fault' ],
            [ 'code' => 61, 'msg' => 'BMS A.S. Break' ],
            [ 'code' => 62, 'msg' => 'Mgr E2Prom Error' ],
            [ 'code' => 63, 'msg' => 'DSP Ver Fault' ],
            [ 'code' => 64, 'msg' => 'NTC Sample diff' ],
            [ 'code' => 65, 'msg' => 'Int_Com_Err' ],
            [ 'code' => 66, 'msg' => 'Meter Fault' ],
            [ 'code' => 67, 'msg' => 'REV' ],
        ];
        if($codes){
            foreach ($codes as $key => $value) {
                $invCode = InverterWarningCode::where('code', $value['code'])->first();
                if($invCode){
                    $invCode->msg = $value['msg'];
                } else {
                    $invCode = new InverterWarningCode;
                    $invCode->code = $value['code'];
                    $invCode->msg = $value['msg'];
                }
                $invCode->save();
            }
        }
    }
    public function getEnergyChartData(Request $request)
    {
        $reqData = [
            'control_card_no' => $request->control_card_no,
            'enchartFilter' => $request->enchartFilter,
            'enDay' => $request->enDay,
            'enMonth' => $request->enMonth,
            'enYear' => $request->enYear
        ];
        $mainData = $this->getEnergyGraphData($reqData);
        return response()->json($mainData);
    }
    public function getBatteryStatusChartData(Request $request)
    {
        $reqData = [
            'startDate' => $request->startDate ?? date('Y-m-d'),
            'endDate' => $request->endDate ?? date('Y-m-d'),
            'control_card_no' => $request->control_card_no
        ];
        $mainData = $this->getBatteryStatusGraphData($reqData);
        return response()->json($mainData);
    }
    public function getStaticReport(Request $request)
    {
        if ($request->ajax())
        {
            $request->merge(array( 
                'start' => (int)$request->input('start'), 
                'length' => (int)$request->input('length') 
            ));
            
            $streportFilter = $request->get('streportFilter');

            $control_card_no = $request->get('control_card_no');
            $enDaily = $request->get('enDaily');
            $enMonthly = $request->get('enMonthly');
            $enYearly = $request->get('enYearly');
            $endDate = $enDaily . ' 23:59:59';
            $stDate = new Carbon($enDaily);
            $edDate = new Carbon($endDate);
            $timezone = config('app.timezone');

            if($streportFilter  == 'daily'){
                $data = Data::select('*')->where('data.content','pv_details')->where('data.Control_card_sn', $control_card_no);
                if($streportFilter  == 'daily'){
                    $data->whereBetween('created_at',[$stDate, $edDate]);
                }
                $data = $data->get();
                if($data){
                    foreach ($data as $key => $value) {
                        $data2 = Data::raw(function ($collection) use($control_card_no,$enDaily,$endDate,$value) {
                                $dt = $value['created_at']->format('Y-m-d H:i:00');
                                $dt1 = $value['created_at']->format('Y-m-d H:i:59');
                                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                
                                return $collection->aggregate([
                                        [
                                            '$match' => [
                                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                                'data.Control_card_sn' => $control_card_no,
                                                'data.content' => 'inverter_details'
                                            ],
                                        ]
                                ]);
                            });

                        $value->data2 = $data2[0]['data'] ?? [];
                        $value->data2_created_at = $data2[0]['created_at'] ?? '';
                        $value->data2_created_at_timestamp = $data2[0]['created_at_timestamp'] ?? '';
                        $value->data2_id = $data2[0]['_id'] ?? '';
                    }
                }

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function($row){
                        return $row->created_at;
                    }) 
                    ->addColumn('inv_phase1_current', function($row){
                        return $this->roundMe($row['data2']['inv_phase1_current']['value'] ?? '');
                    })
                    ->addColumn('inv_phase2_current', function($row){
                        return $this->roundMe($row['data2']['inv_phase2_current']['value'] ?? '');
                    })
                    ->addColumn('inv_phase3_current', function($row){
                        return $this->roundMe($row['data2']['inv_phase3_current']['value'] ?? '');
                    }) 
                    ->addColumn('inv_phase1_voltage', function($row){
                        return $this->roundMe($row['data2']['inv_phase1_voltage']['value'] ?? '');
                    })
                    ->addColumn('inv_phase2_voltage', function($row){
                        return $this->roundMe($row['data2']['inv_phase2_voltage']['value'] ?? '');
                    })
                    ->addColumn('inv_phase3_voltage', function($row){
                        return $this->roundMe($row['data2']['inv_phase3_voltage']['value'] ?? '');
                    })
                    ->addColumn('inv_phase1_power', function($row){
                        return $this->roundMe($row['data2']['inv_phase1_power']['value'] ?? '');
                    })
                    ->addColumn('inv_phase2_power', function($row){
                        return $this->roundMe($row['data2']['inv_phase2_power']['value'] ?? '');
                    })
                    ->addColumn('inv_phase3_power', function($row){
                        return $this->roundMe($row['data2']['inv_phase3_power']['value'] ?? '');
                    })
                    ->addColumn('inv_energy_today', function($row){
                        return $this->roundMe($row['data2']['inv_energy_today']['value'] ?? '');
                    })
                    ->addColumn('inv_energy_total', function($row){
                        return $this->roundMe($row['data2']['inv_energy_total']['value'] ?? '');
                    })
                    ->addColumn('pv_energy_total', function($row){
                        return $this->roundMe($row['data']['pv_energy_total']['value'] ?? '');
                    })
                    ->make(true);
            } else if($streportFilter  == 'monthly'){

                $data = Data::raw(function ($collection) use($control_card_no,$enMonthly,$timezone) {
                   
                        $dt = $enMonthly . '-' . '01' . ' ' . '00:00:00';
                        $dt1 = date('Y-m-t', strtotime($dt));
                        $dt1 = $dt1 . ' ' . '23:59:59';
                        $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                        $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
        
                        return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.Control_card_sn' => $control_card_no,
                                        'data.content' => 'energy_details'
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                        'avg_total_load_consume' => ['$avg' => '$data.total_load_consume.value'],
                                        'avg_total_feedin_power' => ['$avg' => '$data.total_feedin_power.value'],
                                        'avg_battery_energy' => ['$avg' => '$data.battery_energy.value']
                                    ]
                                ],
                                [
                                    '$sort' =>["created_at_timestamp" => 1 ]
                                ]
                        ]);
                    
                });

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function($row){
                        return $row->_id;
                    }) 
                    ->addColumn('avg_total_load_consume', function($row){
                        return $this->roundMe($row['avg_total_load_consume'] ?? '');
                    })
                    ->addColumn('avg_total_feedin_power', function($row){
                        return $this->roundMe($row['avg_total_feedin_power'] ?? '');
                    })
                    ->addColumn('avg_battery_energy', function($row){
                        return $this->roundMe($row['avg_battery_energy'] ?? '');
                    })
                    ->make(true);

            } else if($streportFilter  == 'yearly'){
                $data = Data::raw(function ($collection) use($control_card_no,$enYearly,$timezone) {
                   
                    $dt = $enYearly . '-' . '01-01' . ' ' . '00:00:00';
                    $dt1 = $enYearly . '-12-31';
                    $dt1 = $dt1 . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
    
                    return $collection->aggregate([
                            [
                                '$match' => [
                                    'created_at' => ['$gte' => $start, '$lte' => $end],
                                    'data.Control_card_sn' => $control_card_no,
                                    'data.content' => 'energy_details'
                                ],
                            ],
                            [
                                '$group' => [
                                    "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                    'avg_total_load_consume' => ['$avg' => '$data.total_load_consume.value'],
                                    'avg_total_feedin_power' => ['$avg' => '$data.total_feedin_power.value'],
                                    'avg_battery_energy' => ['$avg' => '$data.battery_energy.value']
                                ]
                            ],
                            [
                                '$sort' =>["created_at_timestamp" => 1 ]
                            ]
                    ]);
                
            });

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    return $row->_id;
                }) 
                ->addColumn('avg_total_load_consume', function($row){
                    return $this->roundMe($row['avg_total_load_consume'] ?? '');
                })
                ->addColumn('avg_total_feedin_power', function($row){
                    return $this->roundMe($row['avg_total_feedin_power'] ?? '');
                })
                ->addColumn('avg_battery_energy', function($row){
                    return $this->roundMe($row['avg_battery_energy'] ?? '');
                })
                ->make(true);
            }

            
        }
        $data           = [];
        $data['title']  = 'Inverter';
        return view('inverters.detail_alarm_history',$data);
    }
    public function roundMe($num)
    {
        if(!$num){
            return $num;
        }
        return number_format((float)$num, 2, '.', '');
    }
    public function testQry(Request $request)
    {
        $reqData = [
            'startDate' => $request->startDate ?? date('Y-m-d'),
            'endDate' => $request->endDate ?? date('Y-m-d'),
            'control_card_no' => $request->control_card_no
        ];

        $startDate = $rdata['startDate'] ?? date('Y-m-d') ;
        $endDate = $rdata['endDate'] ??  date('Y-m-d')  ;
        $endDate = $endDate . ' 23:59:59';

        // $stDate = new Carbon($startDate)->timezone('Asia/Manila');
        // $edDate = new Carbon($endDate)->timezone('Asia/Manila');

        // $stDate = new UTCDateTime($stDate);
        // $edDate = new UTCDateTime($edDate);
       // dd($stDate . ' ' . $edDate);
        $bars = Data::raw(function ($collection) use($stDate,$edDate) {
            
            return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $stDate, '$lte' => $edDate]
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                            'avg' => ['$avg' => '$data.total_pv_power.value']
                      ]
                    ]
            ]);
        });
        dd($bars);
    }
}
