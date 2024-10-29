<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\CurrencyRate;
use App\Models\Data;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inverter;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(Request $request,$id='')
    {
       try{
        $data = [];
        $filterMacIds = [];
        if($id){
            $filterMacIds = getFilterMacIds($id);
        }
        $user = auth()->guard('admin')->user();
        $data['user_currency']      = $user->currency ?? 'USD';
        $data['user_liquid_unit']   = $user->liquid_unit ?? 'gallons';
        $data['user_weight_unit']   = $user->weight_unit ?? 'lbs';

        // $allowedIpAddress = '43.252.116.167';
        // if ($_SERVER['REMOTE_ADDR'] === $allowedIpAddress) {
        //     echo "<pre>"; print_r($data); exit("CALL");
        // }
        // if (!Gate::check('CompanyFleetDashboard') {
        //     abort(403,"not authorized to access");
        // }

    	$data['title']         = 'Dashboard';
        $data['totalUser']     = null;
        $data['totalInveter']  = null;

        $data['admin_role']    =  \Config::get('constants.roles.Master_Admin');
        $connected_minits      =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
           
            $company_login_id = session()->get('company_login_id');
            if($company_login_id)
            {
               $group_dropdown  = Cluster::where(['company_id' => $company_login_id, 'status' => 1])->get();
               $powerbank_dropdown = Device::where(['company_id' => $company_login_id])->get();
            } else {
                $powerbank_dropdown = Device::all();
                $group_dropdown     = Cluster::where(['status' => 1])->get();
            }

            $data['device_notification']  = DeviceNotification::with('notification')->when($filterMacIds, function($query) use ($filterMacIds) {
                $query->whereIn('macid', $filterMacIds);
               })->latest()->take(20)->get();
            $data['group_dropdown']       = $group_dropdown;
            $data['powerbank_dropdown']   = $powerbank_dropdown;
            $data['filter_id']            = $id;
               
            
            Log::info(" Dashboard page retrived by user ", ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

    	    return view('dashboard.index',$data);

        } else {
            // based on company login role 
            // $comAdminRoleId     = \Config::get('constants.roles.Company_Admin');
            // $macids             = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->pluck('macid')->toArray();
            // if($filterMacIds){
            //     $device_notification = DeviceNotification::whereIn('macid', $filterMacIds)->with('notification')->latest()->take(20)->get();
            // } else {
            //     $device_notification = DeviceNotification::whereIn('macid', $macids)->with('notification')->latest()->take(20)->get();
            // }
            // $data['device_notification']    = $device_notification;
            // $data['is_company_login']       = 'false';
            // $data['group_dropdown']         = Cluster::where(['company_id' => auth()->guard('admin')->user()->company_id, 'status' => 1])->get();
            // $data['powerbank_dropdown']     = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->get();
            // $data['filter_id']              = $id;
            // Log::info(" Dashboard page retrived by user ", ['ip_address' => $request->ip(),
            //     'user_id' => auth()->user()->email]);
    	    // return view('dashboard.user_dashboard',$data);

            // based on user 
            $user = auth()->guard('admin')->user();
             $user_id=$user->_id;
           //  dd($user_id);
            
            $companyId = $user->company_id;
        
          
        
            if ($companyId) {
              
                $comAdminRoleId = \Config::get('constants.roles.Company_Admin');
                
                $macids = Device::where('user_id_str', $user_id)->pluck('macid')->toArray();
                //$macids = Device::where('company_id', $companyId)->pluck('macid')->toArray();
        
                if ($filterMacIds) {
                    $device_notification = DeviceNotification::whereIn('macid', $filterMacIds)
                                        ->with('notification')
                                        ->latest()
                                        ->take(20)
                                        ->get();
                } else {
          
                    $device_notification = DeviceNotification::whereIn('macid', $macids)
                                        ->with('notification')
                                        ->latest()
                                        ->take(20)
                                        ->get();
                }
        
                
                $data['device_notification'] = $device_notification;
                $data['is_company_login'] = 'false';
                $data['group_dropdown'] = Cluster::where([
                    'company_id' => $companyId,
                    'status' => 1
                ])->get();
                $data['powerbank_dropdown'] = Device::where('user_id_str', $user_id)->get();
                $data['filter_id'] = $id;
        
              
                Log::info("Dashboard page retrieved by user", [
                    'ip_address' => $request->ip(),
                    'user_id' => auth()->user()->email
                ]);
                //dd($data);
                
                return view('dashboard.user_dashboard', $data);
        }

       }
    }
       catch(Exception  $e){
            Log::error('the error occured  in dashboard page', ['error' => $e->getMessage()]);

       }

        
    }

    public function testDashboard()
    {
        $data = [];
    	$data['title']         = 'Dashboard';
        $data['totalUser']     = null;
        $data['totalInveter']  = null;

        $data['admin_role']    =  \Config::get('constants.roles.Master_Admin');
        $connected_minits      =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $data['user_count_admin']              = User::where('role_id','!=',\Config::get('constants.roles.Master_Admin'))->count();
            $data['device_count_admin']            = Device::count();
            $data['cluster_count_admin']           = Cluster::count();
            $data['verified_device_count_admin']   = Device::where('is_verified',1)->count();
            $data['unverified_device_count_admin'] = Device::where('is_verified','!=',1)->count();
            $data['connected_device_count_admin']  = Data::where("created_at",">",Carbon::now()->subMinutes($connected_minits))->groupBy('macid')->get()->count();
            $data['daily_activities']     = getDailyActivity();
            // $data['device_usage']      = getDeviceUsage();
            $data['device_usage_new']     = getDeviceUsageTest();
            $data['device_history']       = DeviceWarning::with('warning')->latest()->take(20)->get();
            $data['device_notification']  = DeviceNotification::with('notification')->latest()->take(20)->get();
            $data['piechart_data']        = $this->getDashboardPieChart();

            return view('dashboard.index',$data);

        } else {

            $comAdminRoleId     = \Config::get('constants.roles.Company_Admin');
            $macids             = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->pluck('macid')->toArray();
            $data['user_count'] = User::where(['company_id' => auth()->guard('admin')->user()->company_id])->where('role_id','!=',\Config::get('constants.roles.Master_Admin'))->count();
            $data['device_count_admin']  = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->count();
            $data['cluster_count_admin'] = Cluster::where(['company_id' => auth()->guard('admin')->user()->company_id])->count();
            $data['verified_device_count_admin']   = Device::where(['company_id' => auth()->guard('admin')->user()->company_id,'is_verified' => 1])->count();
            $data['unverified_device_count_admin'] = Device::where('is_verified','!=',1)->where('company_id', auth()->guard('admin')->user()->company_id)->count();
            $data['connected_device_count_admin']  = Data::whereIn('macid', $macids)->where("created_at",">",Carbon::now()->subMinutes($connected_minits))->groupBy('macid')->get()->count();
            $data['daily_activities']       = getDailyActivity(auth()->guard('admin')->user()->company_id);
            // $data['device_usage']        = getDeviceUsage(auth()->guard('admin')->user()->company_id);
            $data['device_usage_new']       = getDeviceUsageTest(auth()->guard('admin')->user()->company_id);
            $data['device_history']         = DeviceWarning::whereIn('macid', $macids)->with('warning')->latest()->take(20)->get();
            $data['device_notification']    = DeviceNotification::whereIn('macid', $macids)->with('notification')->latest()->take(20)->get();
            $data['piechart_data']          = $this->getDashboardPieChart(auth()->guard('admin')->user()->company_id);
            $data['is_company_login']       = 'false';

            return view('dashboard.user_dashboard_test',$data);
        }

    }

    public function themeSetting()
    {
    	$data           = [];
    	$data['title']  = 'Theme';
        $data['totalUser']  = null;
        $data['totalInveter']  = null;
    	return view('theme_switcher',$data);
    }

    public function getDeviceList(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start'  => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
     
            $Records = Device::select('*')->with(['company','cluster']);
            $user_id = auth()->guard('admin')->user()->_id;
            if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
            {
                $company_login_id = session()->get('company_login_id');
                if($company_login_id)
                {
                    $Records->where(['company_id' => $company_login_id]);
                }
            } else {
                $Records->where(['user_id' => auth()->guard('admin')->user()->_id]);
              
           
            }
            $data = $Records;

            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('verifed', function($row){
                if(isset($row->is_verified) && $row->is_verified == 1)
                {
                    return '<span class="badge bg-status-verified text-white">Verified</span>';
                } else {
                    return '<span class="badge bg-status-no text-white">Not Verified</span>';
                }
            })
            ->addColumn('name', function($row){
                if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                {
                    // return (isset($row->name) && $row->name ? $row->name : '');
                    $id = $row['_id'];
                    $company_id = $row['company_id'];
                    $url = '/company/'.$company_id.'/device_details/'.$id;
                    return "<a href='$url'>".(isset($row->name) && $row->name ? $row->name : '')."</a>";
                } else {
                    $id = $row['_id'];
                    return "<a href='/device_details/$id'>".(isset($row->name) && $row->name ? $row->name : '')."</a>";
                }
            })
            ->addColumn('company_name', function($row){
                return (isset($row->company->company_name) && $row->company->company_name ? $row->company->company_name : '');
            })
            ->addColumn('group_name', function($row){
                return (isset($row->cluster->name) && $row->cluster->name ? $row->cluster->name : '');
            })
            ->addColumn('address', function($row){
                return (isset($row->location['address']) && $row->location['address'] ? $row->location['address'] : '');
            })
            ->addColumn('connected', function($row){
                return $this->deviceIsConnected($row->macid);
            })
            ->addColumn('SOC', function($row){
                $record = Data::where('macid',$row->macid)->where('data.data.Contain','Battery')->orderBy('created_at','desc')->options(['allowDiskUse' => true])->first();

                if (isset($record->data['data']) && $record->data['data']['Status']['SOC(%)']) {
                    return $record->data['data']['Status']['SOC(%)'] . '%' ?? 0;
                } else {
                    return 0;
                }
            })
            ->addColumn('current_status', function($row){
                if($row->macid)
                {
                    return deviceCurrentStatus($row->macid);
                }
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })

            ->rawColumns(['verifed','connected','name',"current_status"])
            ->make(true);
        }
    }

    // Cluster list
    public function getGroupList(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start'  => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));

            $Records = Cluster::select('*')
            ->with(['company', 'device:_id,cluster_id,user_id_str']);
            if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
            {
                $company_login_id = session()->get('company_login_id');
                if($company_login_id)
                {
                    $Records->where(['company_id' => $company_login_id]);
                }
            } else {
                $Records->where(['company_id' => auth()->guard('admin')->user()->company_id]);
            }
            $Records->whereHas('device', function ($query) {
                $query->where('user_id_str', auth()->guard('admin')->user()->_id);  
            });
            $Records->where('status',1);
            $data = $Records;
            return Datatables::of($data)
            ->addIndexColumn()

            ->addColumn('name', function($row){
                if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                {
                    // return (isset($row->name) && $row->name ? $row->name : '');
                    $id = $row['_id'];
                    $company_id = $row['company_id'];
                    $url = '/company/'.$company_id.'/system-overview';
                    return "<a href='$url'>".(isset($row->name) && $row->name ? $row->name : '')."</a>";
                } else {
                    // return (isset($row->name) && $row->name ? $row->name : '');
                    $id = $row['_id'];
                    return "<a href='/system-overview'>".(isset($row->name) && $row->name ? $row->name : '')."</a>";
                }
            })
            ->addColumn('company_name', function($row){
                return (isset($row->company->company_name) && $row->company->company_name ? $row->company->company_name : '');
            })
            ->addColumn('address', function($row){
                return (isset($row->location['address']) && $row->location['address'] ? $row->location['address'] : '');
            })
            ->addColumn('device_count', function($row){
                return (isset($row->device) && $row->device ? count($row->device) : 0);
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            // ->orderColumn('device_count', function ($query, $order) {
            //     $query->orderBy('device_count', $order);
            // })

            ->rawColumns(['name'])
            ->make(true);
        }
    }

    public function deviceIsConnected($macid='')
    {
        if($macid)
        {
            $connected_minits =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
            $records = Data::where('macid',$macid)->where("created_at",">",Carbon::now()->subMinutes($connected_minits))->groupBy('macid')->get()->count();
            if($records > 0)
            {
                return '<span class="badge bg-status-yes text-white">Yes</span>';
            } else {
                return '<span class="badge bg-status-off text-white">No</span>';
            }
        }
    }

    public function getDashboardPieChart($user_id = '',$filterMacIds=[])
    {
        
        //$allMacIds = getDeviceMacids($company_id);
        $allMacIds = getDeviceMacids($user_id);
        if($filterMacIds){
            $allMacIds = $filterMacIds;
        }
        $total_Gen_Fuel_Utilized = 0;
        $total_saving_doller = 0;
        if($allMacIds){
            // OLD Logic (06-03-2024)
            // foreach ($allMacIds as $key => $macid) {
            //     // Total Fuel Savings
            //     $total_saving = Data::whereNotNull('data.data.Gen_Fuel_Utilized(L)')->where('macid',$macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.Gen_Fuel_Utilized(L)')->first();
            //     $total_saving_doller = $total_saving_doller + $total_saving;

            //     // Total Fuel Consumption
            //     $Gen_Fuel_Utilized = Data::whereNotNull('data.data.Total_saving($)')->where('macid',$macid)->orderBy('created_at_timestamp','desc')->options(['allowDiskUse' => true])->pluck('data.data.Total_saving($)')->first();
            //     $total_Gen_Fuel_Utilized = $total_Gen_Fuel_Utilized + $Gen_Fuel_Utilized;
            // }

            // New Logic (06-03-2024)
            foreach ($allMacIds as $macid) {
                // Total Fuel Savings
                $total_saving = Data::where('macid', $macid)
                    ->whereNotNull('data.data.Gen_Fuel_Utilized(L)')
                    ->orderByDesc('created_at_timestamp')
                    ->take(1)
                    ->options(['allowDiskUse' => true])
                    ->pluck('data.data.Gen_Fuel_Utilized(L)')
                    ->first();
                $total_saving_doller += $total_saving;

                // Total Fuel Consumption
                $Gen_Fuel_Utilized = Data::where('macid', $macid)
                    ->whereNotNull('data.data.Total_saving($)')
                    ->orderByDesc('created_at_timestamp')
                    ->take(1) // Limit to 1 record
                    ->options(['allowDiskUse' => true])
                    ->pluck('data.data.Total_saving($)')
                    ->first();
                $total_Gen_Fuel_Utilized += $Gen_Fuel_Utilized;
            }
        }
        $total_co2           = (int)$total_Gen_Fuel_Utilized * 2.653;
        $total_saving_doller = (int)$total_saving_doller * config('constants.CONVERT_TO_GALLONS');

        $data = [$total_saving_doller,$total_Gen_Fuel_Utilized,$total_co2];
       Log::info('getDeviceMacids' , $data);
        return $data;
    }

    public function getDashboardEnergyChart(Request $request)
    {
        $datasets = [
            'AC_Solar_Tot_Energy(Wh)',
            'DC_Solar_Energy(Wh)',
            'Gen_Tot_Energy(Wh)',
            'Out_Tot_Energy(Wh)',
        ];
        $input = $request->all();
        $filter_id = $request->filter_id ?? '';
        $filterMacIds = [];
        if($filter_id){
            $filterMacIds = getFilterMacIds($filter_id);
        }
        $dataset           = [];
        $dataset['labels'] = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_id = session()->get('company_login_id');
            if($company_id){
                $macids          = getDeviceMacids($company_id);
                if($filter_id){
                    $macids = $filterMacIds;
                }
                $selected_option = $input['selected_option'];

                foreach ($datasets as $key => $val) {
                    $datas = $this->getDashboardChartDatasets($macids,$selected_option,$val);
                    $dataset['labels']          = $datas['labels'] ?? [];
                    $dataset['dataset'][$key]   = $datas['datasets'];
                }

            } else{
                $macids          = [];
                if($filter_id){
                    $macids = $filterMacIds;
                }
                $selected_option = $input['selected_option'];

                foreach ($datasets as $key => $val) {
                    $datas = $this->getDashboardChartDatasets($macids,$selected_option,$val);
                    $dataset['labels']          = $datas['labels'] ?? [];
                    $dataset['dataset'][$key]   = $datas['datasets'];
                }

            }
        } else {
            $company_id      = auth()->guard('admin')->user()->company_id;
            $macids          = getDeviceMacids($company_id);
            if($filter_id){
                $macids = $filterMacIds;
            }
            $selected_option = $input['selected_option'];
            $dataset           = [];
            $dataset['labels'] = [];
            foreach ($datasets as $key => $val) {
                $datas = $this->getDashboardChartDatasets($macids,$selected_option,$val);
                $dataset['labels']          = $datas['labels'] ?? [];
                $dataset['dataset'][$key]   = $datas['datasets'];
            }
        }
        if($dataset){

            $summedData = [
                'data' => array_map(function($a, $b) {
                    return $a + $b;
                }, $dataset['dataset'][0]['data'], $dataset['dataset'][1]['data']),
                'label' => 'Solar',
                'backgroundColor' => '#90ABB5',
                'borderColor' => '#90ABB5',
                'borderWidth' => 2,
                'fillColor' => '#90ABB5',
                'fill' => '',
                'lineTension' => 0.5,
                'line_id' => 'solar',
                'yAxisID' => 'y',
                'hidden' => false
            ];
            unset($dataset['dataset'][0]);
            unset($dataset['dataset'][1]);
            $dataset['dataset'] = array_values($dataset['dataset']);
            array_unshift($dataset['dataset'], $summedData);
        }
        return $dataset;
    }

    public function getDashboardChartDatasets($macids,$selected_option,$val)
    {

        $enchartFilter = $selected_option ?? 'year';
        $selected      = $val ?? '';
        $macid         = $macids ?? '';
        $timezone      = config('app.timezone');

        if($macid)
        {
            $data = Data::whereIn('macid',$macid)->where('data.data.'.$val,'>',0)->raw(function ($collection) use($enchartFilter,$selected,$timezone) {
                if($enchartFilter == 'today'){
                    $current_date = Carbon::now()->format('Y-m-d');
                    $dt  = $current_date . ' ' . '00:00:00';
                    $dt1 = $current_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                            [
                                '$match' => [
                                    'created_at' => ['$gte' => $start, '$lte' => $end],
                                ],
                            ],
                            [
                                '$unwind' => '$data.data.Power'
                            ],
                            [
                                '$group' => [
                                    "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                    'sum' => ['$sum' => '$data.data.'.$selected],
                                ]
                            ],
                            [
                                '$sort' =>["created_at_timestamp" => 1 ]
                            ]
                    ]);
                }
                else if($enchartFilter == 'month')
                {
                    $start_date   = Carbon::now()->format('Y-m-01');
                    $date         = new Carbon($start_date);
                    $end_date     = $date->endOfMonth()->format('Y-m-d');
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year')
                {
                    $current_year = Carbon::now()->format('Y');
                    $dt  = $current_year . '-01-01' . ' ' . '00:00:00';
                    $dt1 = $current_year . '-12-31' . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if ($enchartFilter == 'all') {
                    return $collection->aggregate([
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'last_30_days')
                {
                    $start_date   = Carbon::now()->subDays(30)->format('Y-m-d');
                    $date         = new Carbon($start_date);
                    $end_date     = Carbon::now()->format('Y-m-d');
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
            });
        }
        else
        {
            $data = Data::where('data.data.'.$val,'>',0)->raw(function ($collection) use($enchartFilter,$selected,$timezone) {
                if($enchartFilter == 'today'){
                    $current_date = Carbon::now()->format('Y-m-d');
                    $dt    = $current_date . ' ' . '00:00:00';
                    $dt1   = $current_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'month')
                {
                    $start_date   = Carbon::now()->format('Y-m-01');
                    $date         = new Carbon($start_date);
                    $end_date     = $date->endOfMonth()->format('Y-m-d');
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year')
                {
                    $current_year = Carbon::now()->format('Y');
                    $dt  = $current_year . '-01-01' . ' ' . '00:00:00';
                    $dt1 = $current_year . '-12-31' . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if ($enchartFilter == 'all') {
                    return $collection->aggregate([
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'last_30_days')
                {
                    $start_date   = Carbon::now()->subDays(30)->format('Y-m-d');
                    // $date         = new Carbon($start_date);
                    $end_date     = Carbon::now()->format('Y-m-d');
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$sum' => '$data.data.'.$selected],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
            });
        }


        if($data){
            $color = random_color();
            if($enchartFilter == 'today'){
                $fulldate = Carbon::now()->format('Y-m-d');
                $x = 0;
                $currentHour = Carbon::now()->hour; // Get the current hour
                while($x <= $currentHour) {
                    $labels[] = $x;
                    $seachStr = $fulldate . ' ' . ($x < 10 ? '0'.$x : $x);
                    $filtered = $data->where('_id', $seachStr)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                    $x++;
                }
            }
            else if($enchartFilter == "month"){
                $start_date   = Carbon::now()->format('Y-m-01');
                $date         = new Carbon($start_date);
                $end_date     = $date->endOfMonth()->format('Y-m-d');
                $dt           = $start_date . ' ' . '00:00:00';
                $dt1          = $end_date . ' ' . '23:59:59';
                $periodDates = periodDates($start_date,$end_date);
                $labels      = $periodDates;
                foreach ($periodDates as $key => $dates) {
                    $filtered = $data->where('_id', $dates)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "year") {
                $current_year = Carbon::now()->format('Y');
                $dt  = $current_year . '-01-01';
                $dt1 = $current_year . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay   = Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("M");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "all") {
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset['data'][] = $value['sum'];
                }
            }
            else if($enchartFilter == "last_30_days"){
                $start_date   = Carbon::now()->subDays(30)->format('Y-m-d');
                $end_date     = Carbon::now()->format('Y-m-d');
                $dt           = $start_date . ' ' . '00:00:00';
                $dt1          = $end_date . ' ' . '23:59:59';
                $periodDates = periodDates($start_date,$end_date);
                $labels      = $periodDates;
                foreach ($periodDates as $key => $dates) {
                    $filtered = $data->where('_id', $dates)->first();

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }

            if($selected == "AC_Solar_Tot_Energy(Wh)" || $selected == "DC_Solar_Energy(Wh)")
            {
                $dataset['label']           = "Solar";
                $dataset['backgroundColor'] = '#90ABB5';
                $dataset['borderColor']     = "#90ABB5";
                $dataset['borderWidth']     = 2;
                $dataset['fillColor']     = "#90ABB5";
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
                $dataset['hidden']         = false;
            }
            else if($selected == "Gen_Tot_Energy(Wh)"){
                $dataset['label']           = "Generator";
                $dataset['backgroundColor'] = "#1F3D4E";
                $dataset['borderColor']     = "#1F3D4E";
                $dataset['borderWidth']     = 2;
                $dataset['fillColor']     = "#1F3D4E";
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
                $dataset['hidden']         = false;
            }
            else if($selected == "Out_Tot_Energy(Wh)"){
                $dataset['label']           = "Powerbank";
                $dataset['backgroundColor'] = "#AAC760";
                $dataset['borderColor']     = "#AAC760";
                $dataset['borderWidth']     = 2;
                $dataset['fillColor']     = "#AAC760";
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
                $dataset['hidden']         = false;
            }
        }

        $mainData['labels'] = $labels ?? [];
        $mainData['datasets'] = $dataset;
        $mainData['y_axis_label'] = '( '.$selected.' )';

        return $mainData;
    }

    // Get Account Summery
    public function getAccountSummery(Request $request){
        $admin_role       = \Config::get('constants.roles.Master_Admin');
        $connected_minits =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            // Admin sections
            $company_login_id = session()->get('company_login_id');
            //dd($company_login_id);
            // Log::info($company_login_id);
            if($company_login_id)
            {
               $group_counts    = Cluster::where(['company_id' => $company_login_id, 'status' => 1])->get();
               $device_counts   = Device::where('company_id',$company_login_id)->count();
               $verified_device_count     =  Device::where(['company_id' => $company_login_id,'is_verified' => 1])->count();
               $unverified_device_count   =  Device::where('company_id',$company_login_id)->where('is_verified','!=',1)->count();
              
            } else {
                $group_counts              = Cluster::where(['status' => 1])->count();
                $device_counts             = Device::count();
                $verified_device_count     =  Device::where('is_verified',1)->count();
                $unverified_device_count   =  Device::where('is_verified','!=',1)->count();
               
                }
            $data['user_count_admin']    = User::where('role_id','!=',\Config::get('constants.roles.Master_Admin'))->count();
          
           $data['device_count_admin']  = $device_counts;
            $data['cluster_count_admin'] = $group_counts ?? 0;
            $data['verified_device_count_admin'] = $verified_device_count;
            $data['unverified_device_count_admin'] =  $unverified_device_count;
            $data['connected_device_count_admin']  = Data::where("created_at",">",Carbon::now()->subMinutes($connected_minits))->options(['allowDiskUse' => true])->groupBy('macid')->get()->count();
        } 
        else {
            // Users Sections
// when company admin login into dashboard 
            $macids           = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->pluck('macid')->toArray();
            $company_login_id = auth()->guard('admin')->user()->company_id;
            $user_id=auth()->guard('admin')->user()->_id;
            $device_counts = Device::where('company_id',  $company_login_id)
                       ->where('user_id_str', $user_id)
                        ->count();
            $verified_device_count = Device::where('company_id',  $company_login_id)
                                 ->where('user_id_str', $user_id)
                                  ->where('is_verified', 1)
                                 ->count();
            $unverified_device_count = Device::where('company_id',  $company_login_id)
                                 ->where('user_id_str', $user_id)
                                 ->where('is_verified', '!=', 1)
                                 ->count();

            // $device_counts             = Device::where('company_id',$company_login_id)->count();
            // $verified_device_count     =  Device::where(['company_id' => $company_login_id,'is_verified' => 1])->count();
            // $unverified_device_count   =  Device::where('company_id',$company_login_id)->where('is_verified','!=',1)->count();

            $data['user_count']           = User::where(['company_id' => auth()->guard('admin')->user()->company_id])->where('role_id','!=',\Config::get('constants.roles.Master_Admin'))->count();
            $data['device_count_admin']   = $device_counts ?? 0;
            $data['cluster_count_admin']  = Cluster::where(['company_id' => auth()->guard('admin')->user()->company_id,'status' => 1])->count();
            $data['verified_device_count_admin']   = $verified_device_count ?? 0;
            $data['unverified_device_count_admin'] = $unverified_device_count ?? 0;
            $data['connected_device_count_admin']  = Data::whereIn('macid', $macids)->where("created_at",">",Carbon::now()->subMinutes($connected_minits))->options(['allowDiskUse' => true])->groupBy('macid')->get()->count();
 

             }
             Log::info('str',  $data);

        return response()->json($data);
    }

    //  Get PowerBankUsage
    public function getPowerBankUsage(Request $request){

        $filter_id = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');
            if($company_login_id){
                $data =  getDeviceUsageTest($company_login_id,$filter_id);
            } else {
                $data =  getDeviceUsageTest('',$filter_id);
            }
        } else {
            $company_id = auth()->guard('admin')->user()->company_id;
            $data = getDeviceUsageTest($company_id,$filter_id);
        }

        return response()->json($data);
    }

    public function getPowerBankUsageNew(Request $request){

        $filter_id = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');
            if($company_login_id){
                $data =  $this->getDeviceUsageNew($company_login_id,$filter_id);
            } else {
                $data =  $this->getDeviceUsageNew('',$filter_id);
            }
        } else {
            $user_id = auth()->guard('admin')->user()->_id;
            $data = $this->getDeviceUsageNewbyUser($user_id,$filter_id);
        }

        return response()->json($data);
    }
//   based on user create the  deviceusages 
   public function getDeviceUsageNewbyUser($user_id='',$filter_id=''){
    $filterMacIds = [];
    if($filter_id){
        $filterMacIds = getFilterMacIds($filter_id);
    }

    $data = [];
    if($user_id)
    {
        if($filterMacIds){
            // $macIds = Device::select('_id','name','macid','created_at')->where('company_id',$company_id)->whereIn('macid',$filterMacIds)->get();
            $macIds = Device::where('user_id', $user_id)->whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
        } else {
            $macIds = Device::where('user_id', $user_id)->pluck('macid')->toArray();
        }
    } else {
        if($filterMacIds){
            $macIds = Device::whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
        } else {
            $macIds = Device::pluck('macid')->toArray();
        }
    }

    if($macIds){
        $powrbank_runtime = 0;
        $charged_with_solar = 0;
        $charged_with_genset = 0;
        $total_fuel_used = 0;
        foreach ($macIds as $LRkey => $macid) {

            $latestData = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Battery_Discharge_Time(s)')
                ->orWhereNotNull('data.data.AC_Solar_Tot_Energy(Wh)')
                ->orWhereNotNull('data.data.DC_Solar_Energy(Wh)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($latestData){
                $AC_Solar_Tot_Energy = (int)$latestData->data['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0;
                $DC_Solar_Energy     = (int)$latestData->data['data']['DC_Solar_Energy(Wh)'] ?? 0;

                $powrbank_runtime   +=  (int)$latestData->data['data']['Battery_Discharge_Time(s)'] ?? 0;
                $charged_with_solar +=  $AC_Solar_Tot_Energy + $DC_Solar_Energy;
            }

            $ChargedWithGenset = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Gen_Tot_Energy(Wh)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($ChargedWithGenset){
                $charged_with_genset   +=  (int)$ChargedWithGenset->data['data']['Gen_Tot_Energy(Wh)'] ?? 0;
            }


            $FuelUsed = Data::where('macid',$macid)
            ->where(function ($query) {
                $query->whereNotNull('data.data.Gen_Fuel_Utilized(L)');
            })
            ->orderByDesc('created_at_timestamp')
            ->take(1)
            ->options(['allowDiskUse' => true])
            ->first();

            if($FuelUsed){
                $total_fuel_used   +=  (int)$FuelUsed->data['data']['Gen_Fuel_Utilized(L)'] ?? 0;
            }


        }

        if($powrbank_runtime){
            $powrbank_runtime = $powrbank_runtime / 3600;
            $powrbank_runtime = number_format($powrbank_runtime, 1);
        }

        if($charged_with_solar){
            $charged_with_solar = $charged_with_solar / 1000;
            $charged_with_solar = number_format($charged_with_solar, 1);
        }

        if($charged_with_genset){
            $charged_with_genset = $charged_with_genset / 1000;
            $charged_with_genset = number_format($charged_with_genset, 1);
        }

        $total_fuel_used = (int)$total_fuel_used * config('constants.CONVERT_TO_GALLONS');
        $total_fuel_used = number_format($total_fuel_used, 1);
        $data = [
            'powrbank_runtime'    => $powrbank_runtime ?? 0,
            'charged_with_solar'  => $charged_with_solar ?? 0,
            'charged_with_genset' => $charged_with_genset ?? 0,
            'total_fuel_used'     => $total_fuel_used ?? 0,
        ];
        return $data;

    }
   }



    // getAlert
    public function getAlerts(Request $request){
        $filter_id     = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $filterMacIds = [];
        if($filter_id){
            $filterMacIds = getFilterMacIds($filter_id);
        }
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');
            if($company_login_id){
                $data =  DeviceWarning::with(['warning'])->where('company_id',$company_login_id)->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                   })->latest()->take(20)->get();
            } else {
                $data =  DeviceWarning::with(['warning'])->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                   })->latest()->take(20)->get();
            }
            if($data){
                foreach ($data as $key => $value) {
                    $value->device_name = getDeviceNameByMacId($value->macid);
                    $value->warning_title = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                    $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                    $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                    $value->code_date_format = $formattedDate;
                    // if($value->code_date){
                    //     $formattedDate = Carbon::createFromTimestamp($value->code_date->toDateTime()->getTimestamp())->format('d M \'y H:i:s');
                    //     $value->code_date_format = $formattedDate;
                    // }
                }
            }
        } else {
            // based on user id
            
            $user = auth()->guard('admin')->user();
            $user_id=$user->_id;
          //  dd($user_id);
           
           $companyId = $user->company_id;

           // $company_id = auth()->guard('admin')->user()->company_id;
            if($filterMacIds){
                $data =  DeviceWarning::with('warning')->where('company_id',$companyId)->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                   })->latest()->take(20)->get();
                if($data){
                    foreach ($data as $key => $value) {
                        $value->device_name = getDeviceNameByMacId($value->macid);
                        $value->warning_title = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                        $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                        $formattedDate = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                    }
                }
            } else {
                $macids = Device::where('user_id_str', $user_id)->pluck('macid')->toArray();
               // $macids = Device::where(['company_id' => auth()->guard('admin')->user()->company_id])->pluck('macid')->toArray();
                $data   = DeviceWarning::whereIn('macid', $macids)->with('warning')->latest()->take(20)->get();
                if($data){
                    foreach ($data as $key => $value) {
                        $value->device_name     = getDeviceNameByMacId($value->macid);
                        $value->warning_title   = (isset($value->warning) && $value->warning->title ? $value->warning->title : '');
                        $value->warning_message = (isset($value->warning) && $value->warning->message ? $value->warning->message : '');
                        $formattedDate          = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                    }
                }
            }
        }
        return response()->json($data);
    }

    // Get My Savings
    public function getMySavings(Request $request){
        $user = auth()->guard('admin')->user();
        $user_id=$user->_id;
        $companyId = $user->company_id; 
        $filter_id     = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $filterMacIds = [];
        if($filter_id){
        
            $filterMacIds = getFilterMacIds($filter_id);
            
        }
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');
            if($company_login_id){
               // $data = $this->getDashboardPieChart($company_login_id,$filterMacIds);
               $data = $this->getMySavings( $user_id,$filterMacIds);
            } else {
                $data = $this->getDashboardPieChart('',$filterMacIds);
            }
        } 
        else {
            //login as user based 

            $company_id = auth()->guard('admin')->user()->company_id;
            //$data = $this->getDashboardPieChart($company_id,$filterMacIds);
              $data = $this->getDashboardPieChart($user_id, $filterMacIds);
        }
        $user_currency_rate = 0;
        $user_currency_sign = '';
        if($user){
           $currency = $user->currency ?? '';
          
           if($currency != 'USD'){
               $rates = CurrencyRate::where('base_currency', 'USD')->first();
               $contriesRates = (isset($rates->rates) && $rates->rates ? json_decode($rates->rates,true) : []);
               if($contriesRates){
                foreach ($contriesRates as $key => $rate) {
                    if($currency == $key){
                        $user_currency_rate = $rate;
                        $user_currency_sign = $key;
                    }
                }
               }
           }
        }
        $data['user_currency_rate'] = $user_currency_rate;
        $data['user_currency_sign'] = $user_currency_sign;
      
        return response()->json($data);
    }

    // Get Daily Activity
    public function getDailyActivity(Request $request){
        $filter_id = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');

            if($company_login_id){
                $data = getDailyActivity($company_login_id,$filter_id);
            } else {
                $data = getDailyActivity('',$filter_id);
            }
        }
        // user section 


         else {
            $user_id =auth()->guard('admin')->user()->_id;
        
           // $data = getDailyActivity($company_id,$filter_id);
           $data = getDailyActivitybyUser($user_id, $filter_id);
          // Log::info('dailyact based on user',$user);
        }
       // Log::info('dailyact',$data);
        return response()->json($data);
    }

    // Get Power bank notification
    public function getPowerBankNotification(Request $request){
        $filter_id     = (isset($request->filter_id) && $request->filter_id ? $request->filter_id : '');
        $filterMacIds = [];
        if($filter_id){
            $filterMacIds = getFilterMacIds($filter_id);
        }
        $data = [];
        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
        {
            $company_login_id = session()->get('company_login_id');
            if($company_login_id){
                $data = DeviceNotification::with('notification')->where('company_id',$company_login_id)->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                })->latest()->take(20)->get();
            } else {
                $data = DeviceNotification::with('notification')->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                   })->latest()->take(20)->get();
            }
            if($data){
                foreach ($data as $key => $value) {
                    $value->device_name = getDeviceNameByMacId($value->macid);
                    $value->notification_title = (isset($value->notification) && $value->notification->title ? $value->notification->title : '');
                    $value->notification_message = (isset($value->notification) && $value->notification->message ? $value->notification->message : '');
                    $formattedDate          = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                    $value->code_date_format = $formattedDate;
                    // if($value->code_date){
                    //     $formattedDate = Carbon::createFromTimestamp($value->code_date->toDateTime()->getTimestamp())->format('d M \'y');
                    //     $value->code_date_format = $formattedDate;
                    // }
                }
            }
        } else {

            if($filterMacIds){
                $company_id = auth()->guard('admin')->user()->company_id;
                $user_id = auth()->guard('admin')->user()->_id;
                // $data =  DeviceNotification::with('notification')->where('company_id',$company_id)->when($filterMacIds, function($query) use ($filterMacIds) {
                //     $query->whereIn('macid', $filterMacIds);
                //    })->latest()->take(20)->get();
                $data = DeviceNotification::with(['notification', 'device'])
                ->where('company_id', $company_id)
                ->whereHas('device', function($query) use ($user_id) {
                    $query->where('user_id', $user_id); // Filter based on user_id
                })
                ->when($filterMacIds, function($query) use ($filterMacIds) {
                    $query->whereIn('macid', $filterMacIds);
                })
                ->latest()
                ->take(20)
                ->get();

                if($data){
                    foreach ($data as $key => $value) {
                        $value->device_name = getDeviceNameByMacId($value->macid);
                        $value->notification_title = (isset($value->notification) && $value->notification->title ? $value->notification->title : '');
                        $value->notification_message = (isset($value->notification) && $value->notification->message ? $value->notification->message : '');
                        $formattedDate          = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                        // if($value->code_date){
                        //     $formattedDate = Carbon::createFromTimestamp($value->code_date->toDateTime()->getTimestamp())->format('d M \'y');
                        //     $value->code_date_format = $formattedDate;
                        // }
                    }
                }
            } else {
                $macids = Device::where(['user_id' => auth()->guard('admin')->user()->_id])->pluck('macid')->toArray();
                $data   = DeviceNotification::whereIn('macid', $macids)->with('notification')->latest()->take(20)->get();
                if($data){
                    foreach ($data as $key => $value) {
                        $value->device_name = getDeviceNameByMacId($value->macid);
                        $value->notification_title = (isset($value->notification) && $value->notification->title ? $value->notification->title : '');
                        $value->notification_message = (isset($value->notification) && $value->notification->message ? $value->notification->message : '');
                        $formattedDate          = Carbon::parse($value->created_at)->format('d M \'y H:i:s');
                        $value->code_date_format = $formattedDate;
                        // if($value->code_date){
                        //     $formattedDate = Carbon::createFromTimestamp($value->code_date->toDateTime()->getTimestamp())->format('d M \'y');
                        //     $value->code_date_format = $formattedDate;
                        // }
                    }
                }
            }
        }
        return response()->json($data);
    }

    public function getDeviceUsageNew($company_id='',$filter_id='')
    {
        $filterMacIds = [];
        if($filter_id){
            $filterMacIds = getFilterMacIds($filter_id);
        }

        $data = [];
        if($company_id)
        {
            if($filterMacIds){
                // $macIds = Device::select('_id','name','macid','created_at')->where('company_id',$company_id)->whereIn('macid',$filterMacIds)->get();
                $macIds = Device::where('company_id', $company_id)->whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
            } else {
                $macIds = Device::where('company_id', $company_id)->pluck('macid')->toArray();
            }
        } else {
            if($filterMacIds){
                $macIds = Device::whereIn('macid',$filterMacIds)->pluck('macid')->toArray();
            } else {
                $macIds = Device::pluck('macid')->toArray();
            }
        }

        if($macIds){
            $powrbank_runtime = 0;
            $charged_with_solar = 0;
            $charged_with_genset = 0;
            $total_fuel_used = 0;
            foreach ($macIds as $LRkey => $macid) {

                $latestData = Data::where('macid',$macid)
                ->where(function ($query) {
                    $query->whereNotNull('data.data.Battery_Discharge_Time(s)')
                    ->orWhereNotNull('data.data.AC_Solar_Tot_Energy(Wh)')
                    ->orWhereNotNull('data.data.DC_Solar_Energy(Wh)');
                })
                ->orderByDesc('created_at_timestamp')
                ->take(1)
                ->options(['allowDiskUse' => true])
                ->first();

                if($latestData){
                    $AC_Solar_Tot_Energy = (int)$latestData->data['data']['AC_Solar_Tot_Energy(Wh)'] ?? 0;
                    $DC_Solar_Energy     = (int)$latestData->data['data']['DC_Solar_Energy(Wh)'] ?? 0;

                    $powrbank_runtime   +=  (int)$latestData->data['data']['Battery_Discharge_Time(s)'] ?? 0;
                    $charged_with_solar +=  $AC_Solar_Tot_Energy + $DC_Solar_Energy;
                }

                $ChargedWithGenset = Data::where('macid',$macid)
                ->where(function ($query) {
                    $query->whereNotNull('data.data.Gen_Tot_Energy(Wh)');
                })
                ->orderByDesc('created_at_timestamp')
                ->take(1)
                ->options(['allowDiskUse' => true])
                ->first();

                if($ChargedWithGenset){
                    $charged_with_genset   +=  (int)$ChargedWithGenset->data['data']['Gen_Tot_Energy(Wh)'] ?? 0;
                }


                $FuelUsed = Data::where('macid',$macid)
                ->where(function ($query) {
                    $query->whereNotNull('data.data.Gen_Fuel_Utilized(L)');
                })
                ->orderByDesc('created_at_timestamp')
                ->take(1)
                ->options(['allowDiskUse' => true])
                ->first();

                if($FuelUsed){
                    $total_fuel_used   +=  (int)$FuelUsed->data['data']['Gen_Fuel_Utilized(L)'] ?? 0;
                }


            }

            if($powrbank_runtime){
                $powrbank_runtime = $powrbank_runtime / 3600;
                $powrbank_runtime = number_format($powrbank_runtime, 1);
            }

            if($charged_with_solar){
                $charged_with_solar = $charged_with_solar / 1000;
                $charged_with_solar = number_format($charged_with_solar, 1);
            }

            if($charged_with_genset){
                $charged_with_genset = $charged_with_genset / 1000;
                $charged_with_genset = number_format($charged_with_genset, 1);
            }

            $total_fuel_used = (int)$total_fuel_used * config('constants.CONVERT_TO_GALLONS');
            $total_fuel_used = number_format($total_fuel_used, 1);
            $data = [
                'powrbank_runtime'    => $powrbank_runtime ?? 0,
                'charged_with_solar'  => $charged_with_solar ?? 0,
                'charged_with_genset' => $charged_with_genset ?? 0,
                'total_fuel_used'     => $total_fuel_used ?? 0,
            ];
            return $data;

        }
    }

    // Upload Mysaving image (html to png)
    public function uploadMySavingImage(Request $request){
        $filename = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = time().'-'.$file->getClientOriginalName();
            $file->move(public_path('uploads/'),$fileName);
            $filename = $fileName;
        }
        $data['file_name'] = $filename;
        $data['file_url']  = (isset($filename) && $filename ? getLocalFileUrl($filename) : '') ;
        return response()->json($data);
    }
}
