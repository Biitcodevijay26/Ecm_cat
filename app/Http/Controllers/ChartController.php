<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Models\Cluster;
use App\Models\Data;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inverter;
use App\Models\UserChart;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use LDAP\Result;
use \DataTables;
use MongoDB\BSON\UTCDateTime;
use Carbon\CarbonPeriod;
use DateTime;
use DateTimeZone;
use DateInterval;
use Illuminate\Support\Facades\Log;

class ChartController extends Controller
{
    use UserTrait;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    // Tmp Charts
    public function getCharts(Request $request,$id='',$deviceId='')
    {
        try{
            if($deviceId)
            {
                $id = $deviceId;
            }
            $dataOne = Device::where('_id',$id)->first();
            if(!$dataOne)
            {
                abort(404);
            }
    
            $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
            $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
            $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
            $data = machineDatas();
            $frequentlyArray = [];
            $frequentlyArray = array_filter($data, function($subarray) {
                return array_key_exists('is_frequently', $subarray);
            });
            $data = [
                'title'       => 'Charts',
                'macid'       => '',
                'grid_data'   => $grid,
                'battery'     => $battery, // Battery means Unit
                'PV'          => $PV, // For AC and DC Solar
                'data'        => $data,
                'device_data' => $dataOne,
                'years'       => getChartYearRange(),
                'months'      => getChartMonthRange(),
                'frequentlyArray' => $frequentlyArray
            ];
            Log::info('charts page is retrived by user successfully',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('chart.tmp_charts', $data);
        }
        catch(Exception $e){
            Log::error('error  in charts page is retrived by user',['ip_address' => $request->ip(),
             'MESSAGE'=>$e->getessage()]);

        }
       
    }

    public function getGridGensetGraphData(Request $request)
    {
        $current_date = '';
        if($request->has('current_date') && $request->current_date)
        {

            $current_date = date('Y-m-d', strtotime($request->current_date));
        }
        $enchartFilter = $request->filter_type ?? 'today';
        $current_date  = $current_date;
        $chart_type    = $request->chart_type ?? '';      // Chart Type means (Grid/Genset)
        $selected      = $request->selected ?? '';        // Selected Checkbox name
        $selected_type = $request->selected_type ?? '';  // Means (W) OR (V)
        $selected_key  = $request->selected_key ?? '';  // Means Array Key (Power) OR (Frequency)
        $timezone      = config('app.timezone');

        $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key) {
            if($enchartFilter == 'day'){
                $dt  = $current_date . ' ' . '00:00:00';
                $dt1 = $current_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
        });
        if($data){
            if($enchartFilter == 'today'){
                $fulldate = $current_date;
                $x = 0;
                while($x <= 23) {
                    $labels[] = $x;
                    $seachStr = $fulldate . ' ' . ($x < 10 ? '0'.$x : $x);
                    $filtered = $data->where('_id', $seachStr)->first();

                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }
                    $x++;
                }
            }

            if($selected_type == "W")
            {
                $dataset[0]['label']           = "(W)";
                $dataset[0]['content']         = $chart_type;
                $dataset[0]['backgroundColor'] = 'transparent';
                $dataset[0]['borderColor']     = '#6259ca';
                $dataset[0]['borderWidth']     = 2;
                $dataset[0]['pointBackgroundColor']     = "#ffffff";
                $dataset[0]['fill']            = false;
                $dataset[0]['lineTension']     = 0.3;
                $dataset[0]['line_id']         = $selected;
                $dataset[0]['yAxisID']         = "y";
            } else {
                $dataset[0]['label']           = "(V)";
                $dataset[0]['content']         = $chart_type;
                $dataset[0]['backgroundColor'] = 'transparent';
                $dataset[0]['borderColor']     = '#eb6f33';
                $dataset[0]['borderWidth']     = 2;
                $dataset[0]['pointBackgroundColor']     = "#ffffff";
                $dataset[0]['fill']            = false;
                $dataset[0]['lineTension']     = 0.3;
                $dataset[0]['line_id']         = $selected;
                $dataset[0]['yAxisID']         = "y1";
            }


        }

        $mainData['labels'] = $labels;
        $mainData['datasets'] = $dataset;
        $mainData['y_axis_label'] = '( '.$selected_type.' )';

        return $mainData;
    }

    public function getChartsData(Request $request)
    {
        $input = $request->all();
        if(isset($input['chart']) && $input['chart'])
        {
            $dataset = [];
            $dataset['labels'] = [];
            foreach ($input['chart'] as $key => $value) {
                $datas = $this->getDatasetsDynamic($value);
                $dataset['labels']          = $datas['labels'] ?? [];
                $dataset['x_axis_label']    = $datas['x_axis_label'] ?? [];
                $dataset['dataset'][$key]   = $datas['datasets'];
                $dataset['days_diff']       = $datas['days_diff'] ?? 0;
            }
            return $dataset;
        }
    }

    public function getDatasetsDynamic($value)
    {
        $current_date = date('Y-m-d');
        if(isset($value['current_date']) && $value['current_date'])
        {
            $current_date = date('Y-m-d', strtotime($value['current_date']));
        }
        $enchartFilter = $value['filter_type'] ?? 'today';
        $current_date  = $current_date;
        $chart_type    = $value['chart_type'] ?? '';      // Chart Type means (Grid/Genset)
        $selected      = $value['selected'] ?? '';        // Selected Checkbox name
        $selected_type = $value['selected_type'] ?? '';  // Means (W) OR (V)
        $selected_key  = $value['selected_key'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $filter_value  = $value['filter_value'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $macid         = $value['macid'] ?? '';  // MacId Optional
        $year          = $value['filter_value_year'] ?? '';  // Year
        $month         = $value['filter_value_month'] ?? '';  // Month
        $display_option = $value['display_option'] ?? '';  // MacId Optional
        $timezone      = config('app.timezone');
        $startDateOriginal = $value['filter_start_date'] ?? '';
        $endDateOriginal   = $value['filter_end_date'] ?? '';
        $dates_filter  = 'false';
        if($startDateOriginal ){
            $dates_filter  = 'true';
            $startDate     = Carbon::parse($startDateOriginal);
            if($startDateOriginal && $endDateOriginal)
            {
                $endDate       = Carbon::parse($endDateOriginal);
                $diffInDays    = $startDate->diffInDays($endDate);
                if($diffInDays >= 0 && $diffInDays <= 9){
                    $enchartFilter = 'interval';
                }
                else if($diffInDays < '1'){
                    $enchartFilter = 'interval';
                } else if($diffInDays <= 30){
                    $enchartFilter = 'dates';
                } else if($diffInDays <= 365){
                    $enchartFilter = 'month';
                } else {
                    $enchartFilter = 'global';
                }
            } else {
                $enchartFilter = 'interval';
            }


        }
        if($macid)
        {
            $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key,$filter_value,$macid, $year,$month,$dates_filter,$startDateOriginal,$endDateOriginal) {
                if($selected_key == "System_calculated"){
                    $whr  = '$data.data.'.$selected;
                    $wind = '$data.data';
                } else {
                    $whr  = '$data.data.'.$selected_key.'.'.$selected;
                    $wind = '$data.data.Power';
                }

                if($enchartFilter == 'today'){
                    if($dates_filter == 'true'){
                        $current_date = $startDateOriginal;
                    }
                    $dt  = $current_date . ' ' . '00:00:00';
                    $dt1 = $current_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                            [
                                '$match' => [
                                    'created_at' => ['$gte' => $start, '$lte' => $end],
                                    'data.MacId' => $macid,
                                    'data.data.Contain' => $chart_type
                                ],
                            ],
                            [
                                '$unwind' => $wind
                            ],
                            [
                                '$group' => [
                                    "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                    // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                    // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                    'sum' => ['$avg' => ['$toDouble' => $whr]],
                                ]
                            ],
                            [
                                '$sort' =>["created_at_timestamp" => 1 ]
                            ]
                    ]);
                }
                else if($enchartFilter == 'days')
                {
                    $start_date  = Carbon::now()->format('Y-m-01');
                    $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                    $dt  = $start_date . ' ' . '00:00:00';
                    $dt1 = $end_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year_month')
                {
                    $start_date   = Carbon::now()->format($year.'-'.$month.'-01');
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
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year')
                {
                    $dt  = $year . '-01-01' . ' ' . '00:00:00';
                    $dt1 = $year . '-12-31' . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if ($enchartFilter == 'global') {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if ($enchartFilter == 'dates') {
                    $start_date  = $startDateOriginal;
                    $end_date    = $endDateOriginal;
                    $dt  = $start_date . ' ' . '00:00:00';
                    $dt1 = $end_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'month')
                {
                    $start_date   = $startDateOriginal;
                    $end_date     = $endDateOriginal;
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'interval'){
                    if ($dates_filter == 'false') {
                        $start_date  = $current_date;
                        $end_date    = $current_date;
                    } else {
                        $start_date  = $startDateOriginal;
                        $end_date    = $endDateOriginal;
                    }

                    $startOfDay = new DateTime($start_date . ' 00:00:00');
                    $endOfDay   = new DateTime($end_date . ' 23:59:59');
                    $startOfDay->setTimezone(new DateTimeZone('UTC'));
                    $endOfDay->setTimezone(new DateTimeZone('UTC'));
                    $start = new UTCDateTime($startOfDay->getTimestamp() * 1000); // Multiply by 1000 for milliseconds
                    $end   = new UTCDateTime($endOfDay->getTimestamp() * 1000);

                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$addFields' => [
                                'timestamp_ms' => ['$toLong' => '$created_at'], // Convert 'created_at' to milliseconds
                                'interval_ms' => 1000 * 60 * 10,
                                'rounded_timestamp_ms' => [
                                    '$subtract' => [
                                        ['$toLong' => '$created_at'],
                                        ['$mod' => [['$toLong' => '$created_at'], 1000 * 60 * 10]]
                                    ]
                                ]
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => [
                                    '$dateToString' => [
                                        'format' => '%Y-%m-%d %H:%M',
                                        'date' => ['$toDate' => '$rounded_timestamp_ms'],
                                        'timezone' => $timezone
                                    ]
                                ],
                                'sum' => ['$avg' => ['$toDouble' => $whr]]
                            ]
                        ],
                        [
                            '$sort' => ['_id' => 1]
                        ]
                    ]);


                }
            });
        }
        else
        {
            $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key,$filter_value,$year,$month,$dates_filter,$startDateOriginal,$endDateOriginal) {
                if($selected_key == "System_calculated"){
                    $whr  = '$data.data.'.$selected;
                    $wind = '$data.data';
                } else {
                    $whr  = '$data.data.'.$selected_key.'.'.$selected;
                    $wind = '$data.data.Power';
                }

                if($enchartFilter == 'today'){
                    if($dates_filter == 'true'){
                        $current_date = $startDateOriginal;
                    }

                    $dt  = $current_date . ' ' . '00:00:00';
                    $dt1 = $current_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                            [
                                '$match' => [
                                    'created_at' => ['$gte' => $start, '$lte' => $end],
                                    // 'data.Control_card_sn' => $control_card_no,
                                    'data.data.Contain' => $chart_type
                                ],
                            ],
                            [
                                '$unwind' => $wind
                            ],
                            [
                                '$group' => [
                                    "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                    // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                    // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                    'sum' => ['$avg' => ['$toDouble' => $whr]],
                                ]
                            ],
                            [
                                '$sort' =>["created_at_timestamp" => 1 ]
                            ]
                    ]);
                }
                else if($enchartFilter == 'days')
                {
                    $start_date  = Carbon::now()->format('Y-m-01');
                    $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                    $dt  = $start_date . ' ' . '00:00:00';
                    $dt1 = $end_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year_month')
                {
                    $start_date   = Carbon::now()->format($year.'-'.$month.'-01');
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
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'year')
                {
                    $dt  = $year . '-01-01' . ' ' . '00:00:00';
                    $dt1 = $year . '-12-31' . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if ($enchartFilter == 'global') {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }

                else if ($enchartFilter == 'dates') {
                    $start_date  = $startDateOriginal;
                    $end_date    = $endDateOriginal;
                    $dt  = $start_date . ' ' . '00:00:00';
                    $dt1 = $end_date . ' ' . '23:59:59';
                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
                else if($enchartFilter == 'month')
                {
                    $start_date   = $startDateOriginal;
                    $end_date     = $endDateOriginal;
                    $dt           = $start_date . ' ' . '00:00:00';
                    $dt1          = $end_date . ' ' . '23:59:59';

                    $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                    $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => ['$toDouble' => $whr]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                    ]);
                }
            });
        }

        if($data)
        {
            $x_label = 'Hours';
            $color = random_color();
            if($enchartFilter == 'today'){
                if($dates_filter == 'true'){
                    $current_date = $startDateOriginal;
                }
                $todayDate = Carbon::now()->format('Y-m-d');
                $fulldate = $current_date;
                $x = 0;
                $currentHour = Carbon::now()->hour; // Get the current hour

                if ($fulldate != $todayDate) {
                    $currentHour = 24;
                }
                while($x <= $currentHour) {
                    $x_label  = 'Hours';
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
            else if($enchartFilter == "days"){
                $x_label  = 'Hours';
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
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
            else if($enchartFilter == "year_month"){
                $x_label  = 'Dates';
                $start_date   = Carbon::now()->format($year.'-'.$month.'-01');
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
                $x_label  = 'Month';
                $dt  = $year . '-01-01';
                $dt1 = $year . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay   = Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("m");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "global") {
                $x_label  = 'Years';
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset['data'][] = $value['sum'];
                }
            }
            else if($enchartFilter == "dates"){
                $x_label  = 'Days';
                $start_date   = $startDateOriginal;
                $end_date     = $endDateOriginal;
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
            else if($enchartFilter == "month"){
                $x_label  = 'Month';
                $dt  = $startDateOriginal;
                $dt1 = $endDateOriginal;
                $startDay = Carbon::parse($dt);
                $endDay   = Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("F");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if($enchartFilter == "interval"){
                $today = 'false';
                if($dates_filter == 'false'){
                    $start_date  = $current_date;
                    $end_date    = Carbon::now()->format('Y-m-d H:i:s');
                    $today = 'true';
                } else {
                    $start_date  = $startDateOriginal;
                    if($startDateOriginal && $endDateOriginal){
                        $end_date    = $endDateOriginal;
                    } else {
                        $end_date    = $startDateOriginal;
                    }
                }
                $periodDates = $this->generatePeriodDatesWithIntervals($start_date, $end_date, 10,$today);
                foreach ($periodDates as $key => $date) {
                    $labels[$key] = $date;
                    $filtered = $data->first(function ($item) use ($date) {
                        return $item['_id'] === $date;
                    });

                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }


            }


            if($selected_type == "W"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
                $dataset['units']           = "W";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Hz"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y1";
                $dataset['units']           = "Hz";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "AH"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y2";
                $dataset['units']           = "AH";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "KWH"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y3";
                $dataset['units']           = "KWH";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "A"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y4";
                $dataset['units']           = "A";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "V"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y5";
                $dataset['units']           = "V";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Battery"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y6";
                $dataset['units']           = "Battery";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "State"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y7";
                $dataset['units']           = "State";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Status_Alarms"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y8";
                $dataset['units']           = "Status_Alarms";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Temperature"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y9";
                $dataset['units']           = "Temperature";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Status_Battry"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y10";
                $dataset['units']           = "Status_Battry";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "S"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y11";
                $dataset['units']           = "S";
                $dataset['hidden']          = false;

            }
            else if($selected_type == "L"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y12";
                $dataset['units']           = "L";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "Wh"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y13";
                $dataset['units']           = "Wh";
                $dataset['hidden']          = false;
            }
            else if($selected_type == "%"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y14";
                $dataset['units']           = "%";
                $dataset['hidden']         = false;
            }
            else if($selected_type == "°C"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y15";
                $dataset['units']           = "°C";
                $dataset['hidden']         = false;
            }
            else if($selected_type == "Value 1"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y16";
                $dataset['units']           = "Value 1";
                $dataset['hidden']         = false;
            }
            else if($selected_type == "Value 2"){
                $dataset['label']           = $display_option ?? $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     =  getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                // $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y17";
                $dataset['units']           = "Value 2";
                $dataset['hidden']         = false;
            }
        }
        if($enchartFilter == "interval"){
            $dataset['pointStyle'] = 'none';
            $dataset['pointRadius'] = 0;
            $mainData['days_diff'] = 0;
            if($startDateOriginal && $endDateOriginal)
            {
                $endDate       = Carbon::parse($endDateOriginal);
                $diffInDays    = $startDate->diffInDays($endDate);
                if($diffInDays > 0 && $diffInDays <= 9){
                    $mainData['days_diff'] = $diffInDays;
                }
            }
        }
        $mainData['labels'] = $labels ?? [];
        $mainData['datasets'] = $dataset;
        $mainData['x_axis_label'] = '('.$x_label.')';
        $mainData['y_axis_label'] = '( '.$selected_type.' )';
        return $mainData;
    }

    // Create Charts
    // public function addChart($id = '')
    // {
    //     $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
    //     $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
    //     $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
    //     $data = machineDatas();
    //     $data = [
    //         'title'       => 'Add Chart',
    //         'module'      => 'Charts List',
    //         'macid'       => '',
    //         'grid_data'   => $grid,
    //         'battery'     => $battery, // Battery means Unit
    //         'PV'          => $PV, // For AC and DC Solar
    //         'data'        => [],
    //         'chk_data'    => $data,
    //         'company_id'  => $id,

    //     ];
    //     return view('chart.tmp_charts_new', $data);
    // }


    public function addChart(Request $request,$id = '')
{
    try{
        $grid = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
        if (!$grid) {
            // Handle case where no grid data is found
            $grid = []; // or set to a default value
        }
    
        $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
        if (!$battery) {
            // Handle case where no battery data is found
            $battery = []; // or set to a default value
        }
    
        $PV = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
        if (!$PV) {
            // Handle case where no PV data is found
            $PV = []; // or set to a default value
        }
    
        $data = machineDatas(); // Ensure this function handles potential errors
    
        $data = [
            'title'       => 'Add Chart',
            'module'      => 'Charts List',
            'macid'       => '',
            'grid_data'   => $grid,
            'battery'     => $battery, // Battery means Unit
            'PV'          => $PV, // For AC and DC Solar
            'data'        => [],
            'chk_data'    => $data,
            'company_id'  => $id,
        ];
          Log::info('add chart data');
        return view('chart.tmp_charts_new', $data);
    }
    catch(Exception $e){
        Log::error('eror in addchart page',$e->getMessage());
    }
    
}


    // saved Charts
    public function saveCharts(Request $request)
    {
       try{
        $input = $request->all();

        if(isset($input['title']) && $input['title']  && isset($input['chart-type']) && $input['chart-type'])
        {
            $checkboxData = json_decode($input['checkboxData'],true);
            if($checkboxData)
            {
                if(isset($input['chart_id']) && $input['chart_id'])
                {
                    $u_chart               = UserChart::find($request->chart_id);
                    $u_chart->title        = $input['title'];
                    $u_chart->chart_type   = $input['chart-type'];
                    $u_chart->option_data  = $checkboxData;
                    // $u_chart->status       = 1;
                    $u_chart->save();
                    Log::info('update charts bu user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                } else {
                    $adminRoleId = \Config::get('constants.roles.Master_Admin');
                    if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId){
                        $company_id = $input['company_id'] ?? '';
                    } else {
                        $company_id = auth()->guard('admin')->user()->company_id;
                    }

                    $u_chart               = new UserChart();
                    $u_chart->user_id      = auth()->guard('admin')->user()->id;
                    $u_chart->company_id   =  $company_id;
                    $u_chart->title        = $input['title'];
                    $u_chart->chart_type   = $input['chart-type'];
                    $u_chart->option_data  = $checkboxData;
                    $u_chart->status       = 1;
                    $u_chart->save();
                    Log::info('save charts by user', ['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                }

                return response()->json(['status' => 'true']);
            }
            else {
                Log::info('no record found');
                return response()->json(['status' => 'false']);
            }

        }
        else{
            Log::info('no record found');
            return response()->json(['status' => 'false']);
        }
       } 
       catch(Exception $e){
        Log::error('error occured while saving chart recorde',$e->getMessage());
       }
      
    }






    // Charts Listing
    public function chartsList(Request $request,$id='')
    { try{
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id != $adminRoleId){
            $id = '';
        }
        $data = [
            'heading'     => 'Charts List',
            'title'       => 'Charts List',
            // 'data'     => $charts,
            'company_id'  => $id,
        ];
        Log::info('Chart list page retrived by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return view('chart.chart_list', $data);
    }
    catch(Exception $e){
        Log::error('error occured while chart list',$e->getMessage());
    }
        
    }


    public function getChartsList(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));

            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $usersChart = UserChart::select('*');
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId){
                $usersChart->where('company_id','=',$request->company_id);
            } else {
                $usersChart->where('user_id','=',auth()->guard('admin')->user()->_id);
            }
            $data = $usersChart;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '';

                    $company_login_id = session()->get('company_login_id');
                    if($company_login_id)
                    {
                        if (Gate::allows('ChartEdit')) {
                            $actionBtn .= '<a href="/company/'.$company_login_id.'/edit-chart/'.$row->id.'" class="btn btn-icon btn-primary me-2" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                        }
                        if (Gate::allows('ChartView')) {
                            $actionBtn .= '<a href="/company/'.$company_login_id.'/view-chart/'.$row->id.'" class="btn btn-icon btn-primary me-2 viewChart" title="Click to view"><i><i class="fe fe-eye" aria-hidden="true"></i></a>';
                        }
                    } else {
                        if (Gate::allows('ChartEdit')) {
                            $actionBtn .= '<a href="/edit-chart/'.$row->id.'" class="btn btn-icon btn-primary me-2" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                        }
                        if (Gate::allows('ChartView')) {
                            $actionBtn .= '<a href="/view-chart/'.$row->id.'" class="btn btn-icon btn-primary me-2 viewChart" title="Click to view"><i><i class="fe fe-eye" aria-hidden="true"></i></a>';
                        }
                    }

                    // if($row->status == 1){
                    //     // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveByAdmin btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                    //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                    // } else {
                    //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveByAdmin" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                    // }

                return $actionBtn;
            })

            ->addColumn('status', function($row){
                if($row->status == 1){
                    return  '<span class="badge bg-success">Active</span>';
                } else {
                    return  '<span class="badge bg-danger">Inactive</span>';
                }
            })

            ->addColumn('created_at', function($row){
                $startDate = date_create($row->created_at);
                return date_format($startDate,"Y-m-d h:m A");
            })
            ->orderColumn('title', function ($query, $order) {
                $query->orderBy('title', $order);
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
    }

    public function editChart(Request $request,$id='',$deviceId='')
    {
      try{
        if($deviceId)
        {
            $id = $deviceId;
        }
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        $dataOne = UserChart::where('_id',$id)->first();
        if(!$dataOne)
        {
            abort(404);
        }
        $company_id = $dataOne->company_id;

        $chartData = UserChart::where('_id',$id);
        if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId){
            $chartData->where('company_id','=',$company_id);
        } else {
            $chartData->where('company_id','=',auth()->guard('admin')->user()->company_id);
        }
        $chartData = $chartData->first();
        $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
        $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
        $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
        $data = machineDatas();
        $data = [
            'title'       => 'Edit Chart',
            'module'      => 'Charts List',
            'macid'       => '',
            'grid_data'   => $grid,
            'battery'     => $battery, // Battery means Unit
            'PV'          => $PV, // For AC and DC Solar
            'data'        => $chartData,
            'chk_data'    => $data,
            'company_id'  => '',
        ];
        Log::info('edit chart list retrived by user ',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
        return view('chart.tmp_charts_new', $data);
      }
      catch(Exception  $e){
        Log::error('Error in edit chart list',['error'=>$e->getMessage()]);
      }

      
    }

    public function viewChart(Request $request, $id='',$userId='')
    {
        try{
            if($userId)
            {
                $id = $userId;
            }
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            $dataOne = UserChart::where('_id',$id)->first();
            if(!$dataOne)
            {
                abort(404);
            }
            $company_id = $dataOne->company_id;
    
            $chartData = UserChart::where('_id',$id);
            if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role_id == $adminRoleId){
                $chartData->where('company_id','=',$company_id);
            } else {
                $chartData->where('company_id','=',auth()->guard('admin')->user()->company_id);
            }
            $chartData    = $chartData->first();
            $device_list  = Device::where('company_id',$company_id)->orderBy('_id','desc')->get()->toArray();
            $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
            $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
            $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
    
            $data = [
                'title'       => 'View Chart',
                'module'      => 'Charts List',
                'macid'       => '',
                'grid_data'   => $grid,
                'battery'     => $battery, // Battery means Unit
                'PV'          => $PV, // For AC and DC Solar
                'data'        => $chartData,
                'device_list' => $device_list,
                'years'       => getChartYearRange(),
                'months'      => getChartMonthRange()
    
            ];
            Log::info(' chartview page retrived by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('chart.view_chart', $data);
        }
        catch (\Exception $e) {
            Log::error('Error in view chart list',['error'=>$e->getMessage()]);
        }

       
    }


      // Tmp Charts
    public function getChartsTemporary(Request $request)
    {
        $data = machineDatas();

        $data = [
            'title'  => 'Charts',
            'macid'  => '',
            'data'   => $data,
        ];
        return view('chart.chart_temporary', $data);
    }


    //========================================== Testing charts ===============================//

    // Tmp Charts
    public function getTestCharts($id='',$deviceId='')
    {
      if($deviceId)
      {
          $id = $deviceId;
      }
      $dataOne = Device::where('_id',$id)->first();
      if(!$dataOne)
      {
          abort(404);
      }

      $grid    = Data::where('data.data.Contain','Grid/Genset')->orderBy('created_at_timestamp','desc')->first();
      $battery = Data::where('data.data.Contain','Battery')->orderBy('created_at_timestamp','desc')->first();
      $PV      = Data::where('data.data.Contain','PV')->orderBy('created_at_timestamp','desc')->first();
      $data    = machineDatas();
      $data = [
          'title'       => 'Charts',
          'macid'       => '',
          'grid_data'   => $grid,
          'battery'     => $battery, // Battery means Unit
          'PV'          => $PV, // For AC and DC Solar
          'data'        => $data,
          'device_data' => $dataOne
      ];
      return view('chart.test_charts', $data);
    }

    public function getTestChartsData(Request $request)
    {
        $input = $request->all();
        if(isset($input['chart']) && $input['chart'])
        {
            $dataset = [];
            $dataset['labels'] = [];
            foreach ($input['chart'] as $key => $value) {
                $datas = $this->getTestDatasetsDynamic($value);
                $dataset['labels']          = $datas['labels'] ?? [];
                $dataset['dataset'][$key]   = $datas['datasets'];
            }
            return $dataset;
        }
    }

    public function getTestDatasetsDynamic($value)
    {
        $current_date = '';
        if(isset($value['current_date']) && $value['current_date'])
        {
            $current_date = date('Y-m-d', strtotime($value['current_date']));
        }
        $enchartFilter = $value['filter_type'] ?? 'today';
        $current_date  = $current_date;
        $chart_type    = $value['chart_type'] ?? '';      // Chart Type means (Grid/Genset)
        $selected      = $value['selected'] ?? '';        // Selected Checkbox name
        $selected_type = $value['selected_type'] ?? '';  // Means (W) OR (V)
        $selected_key  = $value['selected_key'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $filter_value  = $value['filter_value'] ?? '';  // Means Array Key (Power) OR (Frequency)
        $macid         = $value['macid'] ?? '';  // MacId Optional
        $timezone      = config('app.timezone');
        if($macid)
        {
        $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key,$filter_value,$macid) {
            if($enchartFilter == 'today'){
                $dt  = $current_date . ' ' . '00:00:00';
                $dt1 = $current_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.MacId' => $macid,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                                'count' => ['$sum' => 1],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
            else if($enchartFilter == 'days')
            {
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                $dt  = $start_date . ' ' . '00:00:00';
                $dt1 = $end_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            'data.MacId' => $macid,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            // 'sum' => ['$count' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                            'count' => ['$sum' => 1],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'month')
            {
                $start_date   = Carbon::now()->format('Y-'.$filter_value.'-01');
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
                            'data.MacId' => $macid,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'year')
            {
                $dt  = $filter_value . '-01-01' . ' ' . '00:00:00';
                $dt1 = $filter_value . '-12-31' . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            'data.MacId' => $macid,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if ($enchartFilter == 'global') {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'data.MacId' => $macid,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
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
        $data = Data::raw(function ($collection) use($enchartFilter,$chart_type,$current_date,$selected,$timezone,$selected_key,$filter_value) {
            if($enchartFilter == 'today'){
                $dt  = $current_date . ' ' . '00:00:00';
                $dt1 = $current_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => $chart_type
                            ],
                        ],
                        [
                            '$unwind' => '$data.data.Power'
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
            else if($enchartFilter == 'days')
            {
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                $dt  = $start_date . ' ' . '00:00:00';
                $dt1 = $end_date . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'month')
            {
                $start_date   = Carbon::now()->format('Y-'.$filter_value.'-01');
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
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if($enchartFilter == 'year')
            {
                $dt  = $filter_value . '-01-01' . ' ' . '00:00:00';
                $dt1 = $filter_value . '-12-31' . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at' => ['$gte' => $start, '$lte' => $end],
                            // 'data.Control_card_sn' => $control_card_no,
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
                        ]
                    ],
                    [
                        '$sort' =>["created_at_timestamp" => 1 ]
                    ]
                ]);
            }
            else if ($enchartFilter == 'global') {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'data.data.Contain' => $chart_type
                        ],
                    ],
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.'.$selected_key.'.'.$selected]],
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
                $fulldate = $current_date;
                $x = 0;
                while($x <= 23) {
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
            else if($enchartFilter == "days"){
                $start_date  = Carbon::now()->format('Y-m-01');
                $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
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
            else if($enchartFilter == "month"){
                $start_date   = Carbon::now()->format('Y-'.$filter_value.'-01');
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
                $dt  = $filter_value . '-01-01';
                $dt1 = $filter_value . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay   = Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("m");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset['data'][] = $filtered['sum'];
                    } else {
                        $dataset['data'][] = 0;
                    }
                }
            }
            else if ($enchartFilter == "global") {
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset['data'][] = $value['sum'];
                }
            }
            if($selected_type == "W")
            {
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y";
            }
            else if($selected_type == "Hz"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y1";
            }
            else if($selected_type == "AH"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y2";
            }
            else if($selected_type == "KWH"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y3";
            }
            else if($selected_type == "A"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y4";
            }
            else if($selected_type == "V"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y5";
            }
            else if($selected_type == "Battery"){
                $dataset['label']           = $selected;
                $dataset['content']         = $chart_type;
                // $dataset['backgroundColor'] = 'transparent';
                $dataset['borderColor']     = getColorFromChartKey($selected);
                $dataset['borderWidth']     = 2;
                $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
                $dataset['fill']            = false;
                $dataset['lineTension']     = 0.5;
                $dataset['line_id']         = $selected;
                $dataset['yAxisID']         = "y6";
            }
            else if($selected_type == "State"){
            $dataset['label']           = $selected;
            $dataset['content']         = $chart_type;
            // $dataset['backgroundColor'] = 'transparent';
            $dataset['borderColor']     = getColorFromChartKey($selected);
            $dataset['borderWidth']     = 2;
            $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
            $dataset['fill']            = false;
            $dataset['lineTension']     = 0.5;
            $dataset['line_id']         = $selected;
            $dataset['yAxisID']         = "y7";
        }
        else if($selected_type == "Status_Alarms"){
            $dataset['label']           = $selected;
            $dataset['content']         = $chart_type;
            // $dataset['backgroundColor'] = 'transparent';
            $dataset['borderColor']     = getColorFromChartKey($selected);
            $dataset['borderWidth']     = 2;
            $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
            $dataset['fill']            = false;
            $dataset['lineTension']     = 0.5;
            $dataset['line_id']         = $selected;
            $dataset['yAxisID']         = "y8";
        }
        else if($selected_type == "Temperature"){
            $dataset['label']           = $selected;
            $dataset['content']         = $chart_type;
            // $dataset['backgroundColor'] = 'transparent';
            $dataset['borderColor']     = getColorFromChartKey($selected);
            $dataset['borderWidth']     = 2;
            $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
            $dataset['fill']            = false;
            $dataset['lineTension']     = 0.5;
            $dataset['line_id']         = $selected;
            $dataset['yAxisID']         = "y9";
        }
        else if($selected_type == "Status_Battry"){
            $dataset['label']           = $selected;
            $dataset['content']         = $chart_type;
            // $dataset['backgroundColor'] = 'transparent';
            $dataset['borderColor']     = getColorFromChartKey($selected);
            $dataset['borderWidth']     = 2;
            $dataset['pointBackgroundColor']     = getColorFromChartKey($selected);
            $dataset['fill']            = false;
            $dataset['lineTension']     = 0.5;
            $dataset['line_id']         = $selected;
            $dataset['yAxisID']         = "y10";
        }


        }

        $mainData['labels'] = $labels ?? [];
        $mainData['datasets'] = $dataset;
        $mainData['y_axis_label'] = '( '.$selected_type.' )';

        return $mainData;
    }

    public function getWeeks($startDate,$endDate){
        $startDate = Carbon::parse($startDate);
        $endDate   = Carbon::parse($endDate);
        $days = [];

        while ($startDate->lte($endDate)) {
            // $dayName = $startDate->englishDayOfWeek; // Or ->format('l') for the full day name
            $dayName = $startDate->format('D'); // Or ->format('l') for the full day name
            $dates   = $startDate->format('Y-m-d');
            $days[] = $dayName . "\n" . "(" . $dates . ")";
            $startDate->addDay();
        }
        return $days;
    }

    public function getMonth($startDate,$endDate){
        $startDate = Carbon::parse($startDate);
        $endDate   = Carbon::parse($endDate);
        $months = [];

        while ($startDate->lte($endDate)) {
            $monthName = $startDate->englishMonth;
            $months[] = $monthName;

            $startDate->addMonthNoOverflow()->startOfMonth();
        }
        return $months;
    }

    public function exportChart(Request $request){
        if($request->has('chart_data') && $request->chart_data){
            $chartData = json_decode($request->chart_data,'true');
            if($chartData){
                $checkedName = [];
                $csvData     = [];
                $headerRow = ['Date Time'];
                $labels = [];
                foreach ($chartData as $key => $value) {
                    $startDateOriginal = $value['filter_start_date'] ?? '';
                    $endDateOriginal   = $value['filter_end_date'] ?? '';
                    if($startDateOriginal ){
                        $startDate     = Carbon::parse($startDateOriginal);
                        if($startDateOriginal && $endDateOriginal)
                        {
                            $endDate       = Carbon::parse($endDateOriginal);
                            $diffInDays    = $startDate->diffInDays($endDate);
                            if($diffInDays >= 0 && $diffInDays <= 9){
                                $value['filter_type'] = 'interval';
                            }
                            else if($diffInDays <= 30){
                                $value['filter_type'] = 'dates';
                            } else if($diffInDays <= 365){
                                $value['filter_type'] = 'month';
                            } else {
                                $value['filter_type'] = 'global';
                            }
                        } else {
                            $value['filter_type'] = 'interval';
                        }
                    }

                    $headerRow[]  = $value['selected'];
                    $checkedName[] = $value['selected'];
                    if($value['macid'] && $value['filter_type'] == 'today'){
                        $current_date = date('Y-m-d');
                        if($startDateOriginal){
                            $current_date = $startDateOriginal;
                        }

                        $exportData = Data::raw(function ($collection) use($value,$current_date) {
                            $dt  = $current_date . ' ' . '00:00:00';
                            $dt1 = $current_date . ' ' . '23:59:59';
                            $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }

                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    // '$sort' =>["created_at_timestamp" => 1 ]
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });

                        $todayDate = Carbon::now()->format('Y-m-d');
                        $fulldate = $current_date;
                        $x = 0;
                        $currentHour = Carbon::now()->hour; // Get the current hour

                        if ($fulldate != $todayDate) {
                            $currentHour = 24;
                        }
                        while($x <= $currentHour) {
                            $labels[$x] = $x;
                            $seachStr = $fulldate . ' ' . ($x < 10 ? '0'.$x : $x);
                            $filtered = $exportData->where('_id', $seachStr)->first();

                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                            $x++;
                        }

                    }
                    else if($value['macid'] && $value['filter_type'] == 'days'){

                        $exportData = Data::raw(function ($collection) use($value) {
                            $filter_value = $value['filter_value'];
                            $start_date  = Carbon::now()->format('Y-m-01');
                            $end_date    = Carbon::now()->format('Y-m-'.$filter_value);
                            $dt  = $start_date . ' ' . '00:00:00';
                            $dt1 = $end_date . ' ' . '23:59:59';
                            $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }

                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    // '$sort' =>["created_at_timestamp" => 1 ]
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });

                        $start_date  = Carbon::now()->format('Y-m-01');
                        $end_date    = Carbon::now()->format('Y-m-'.$value['filter_value']);
                        $periodDates = periodDates($start_date,$end_date);
                        $labels      = $periodDates;
                        foreach ($periodDates as $key => $dates) {
                            $filtered = $exportData->where('_id', $dates)->first();

                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }
                    }
                    else if($value['macid'] && $value['filter_type'] == 'dates'){
                        $exportData = Data::raw(function ($collection) use($value) {
                            $start_date  = $value['filter_start_date'];
                            $end_date    = $value['filter_end_date'];
                            $dt  = $start_date . ' ' . '00:00:00';
                            $dt1 = $end_date . ' ' . '23:59:59';
                            $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    // '$sort' =>["created_at_timestamp" => 1 ]
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });

                        $start_date   = $startDateOriginal;
                        $end_date     = $endDateOriginal;
                        $periodDates = periodDates($start_date,$end_date);
                        $labels      = $periodDates;
                        foreach ($periodDates as $key => $dates) {
                            $filtered = $exportData->where('_id', $dates)->first();

                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }
                    }
                    else if ($value['macid'] && $value['filter_type'] == 'year_month') {
                        $exportData = Data::raw(function ($collection) use($value) {
                            $year         = $value['filter_value_year'];
                            $month        = $value['filter_value_month'];
                            $start_date   = Carbon::now()->format($year.'-'.$month.'-01');
                            $date         = new Carbon($start_date);
                            $end_date     = $date->endOfMonth()->format('Y-m-d');
                            $dt           = $start_date . ' ' . '00:00:00';
                            $dt1          = $end_date . ' ' . '23:59:59';
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    // '$sort' =>["created_at_timestamp" => 1 ]
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });
                        $start_date   = Carbon::now()->format($value['filter_value_year'].'-'.$value['filter_value_month'].'-01');
                        $date         = new Carbon($start_date);
                        $end_date     = $date->endOfMonth()->format('Y-m-d');
                        $periodDates = periodDates($start_date,$end_date);
                        $labels      = $periodDates;
                        foreach ($periodDates as $key => $dates) {
                            $filtered = $exportData->where('_id', $dates)->first();

                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }
                    }
                    else if ($value['macid'] && $value['filter_type'] == 'year') {
                        $dt  = $value['filter_value_year'] . '-01-01';
                        $dt1 = $value['filter_value_year'] . '-12-31';
                        $exportData = Data::raw(function ($collection) use($value,$dt,$dt1) {

                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end   = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    // '$sort' =>["created_at_timestamp" => 1 ]
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });

                        $startDay = Carbon::parse($dt);
                        $endDay   = Carbon::parse($dt1);
                        $period = $startDay->range($endDay, 1, 'month');
                        foreach ($period as $key => $dt) {
                            $labels[$key] = $dt->format("F Y");
                            $filtered = $exportData->where('_id', $dt->format("Y-m"))->first();
                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }
                    }
                    else if($value['macid'] && $value['filter_type'] == 'month'){
                        $exportData = Data::raw(function ($collection) use($value,$startDateOriginal,$endDateOriginal) {
                            $start_date   = $startDateOriginal;
                            $end_date     = $endDateOriginal;
                            $dt           = $start_date . ' ' . '00:00:00';
                            $dt1          = $end_date . ' ' . '23:59:59';
                            $start        = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                            $end          = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at', "timezone" => $timezone ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });
                        $dt  = $startDateOriginal;
                        $dt1 = $endDateOriginal;
                        $startDay = Carbon::parse($dt);
                        $endDay   = Carbon::parse($dt1);
                        $period = $startDay->range($endDay, 1, 'month');

                        foreach ($period as $dt) {
                            $labels[] = $dt->format("F");
                            $filtered = $exportData->where('_id', $dt->format("Y-m"))->first();
                            if($filtered){
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }
                    }
                    else if($value['macid'] && $value['filter_type'] == 'global'){
                        $exportData = Data::raw(function ($collection) use($value) {
                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$group' => [
                                        "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]],
                                    ]
                                ],
                                [
                                    '$sort' => ["_id" => 1]
                                ]
                            ]);
                        });
                        $sorted = $exportData->sortBy('_id');
                        foreach ($sorted as $key => $st) {
                            $labels[] = $st['_id'];
                            $csvData[$value['selected']][] = $st['sum'];
                        }
                    }
                    else if($value['macid'] && $value['filter_type'] == 'interval'){

                        $start_date  = $value['filter_start_date'];
                        $end_date    = $value['filter_end_date'];

                        if($start_date && $end_date){
                            $end_date    = $end_date;
                        } else {
                            $end_date    = $start_date;
                        }
                        $periodDates = $this->generatePeriodDatesWithIntervals($start_date, $end_date, 10);
                        $exportData = Data::raw(function ($collection) use($value) {
                            $start_date  = $value['filter_start_date'];
                            $end_date    = $value['filter_end_date'];

                            $startOfDay = new DateTime($start_date . ' 00:00:00');
                            $endOfDay = new DateTime($end_date . ' 23:59:59');
                            $startOfDay->setTimezone(new DateTimeZone('UTC'));
                            $endOfDay->setTimezone(new DateTimeZone('UTC'));
                            $start = new UTCDateTime($startOfDay->getTimestamp() * 1000); // Multiply by 1000 for milliseconds
                            $end = new UTCDateTime($endOfDay->getTimestamp() * 1000);

                            $macid        = $value['macid'];
                            $chart_type   = $value['chart_type'];
                            $timezone     = config('app.timezone');
                            if($value['selected_key'] == "System_calculated"){
                                $whr  = '$data.data.'.$value['selected'];
                                $wind = '$data.data';
                            } else {
                                $whr  = '$data.data.'.$value['selected_key'].'.'.$value['selected'];
                                $wind = '$data.data.Power';
                            }
                            return $collection->aggregate([
                                [
                                    '$match' => [
                                        'created_at' => ['$gte' => $start, '$lte' => $end],
                                        'data.MacId' => $macid,
                                        'data.data.Contain' => $chart_type
                                    ],
                                ],
                                [
                                    '$addFields' => [
                                        'timestamp_ms' => ['$toLong' => '$created_at'], // Convert 'created_at' to milliseconds
                                        'interval_ms' => 1000 * 60 * 10,
                                        'rounded_timestamp_ms' => [
                                            '$subtract' => [
                                                ['$toLong' => '$created_at'],
                                                ['$mod' => [['$toLong' => '$created_at'], 1000 * 60 * 10]]
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    '$group' => [
                                        '_id' => [
                                            '$dateToString' => [
                                                'format' => '%Y-%m-%d %H:%M',
                                                'date' => ['$toDate' => '$rounded_timestamp_ms'],
                                                'timezone' => $timezone
                                            ]
                                        ],
                                        'sum' => ['$avg' => ['$toDouble' => $whr]]
                                    ]
                                ],
                                [
                                    '$sort' => ['_id' => 1]
                                ]
                            ]);


                        });
                        foreach ($periodDates as $key => $date) {
                            $labels[$key] = $date;
                            $filtered = $exportData->first(function ($item) use ($date) {
                                return $item['_id'] === $date;
                            });

                            if ($filtered) {
                                $csvData[$value['selected']][] = $filtered['sum'];
                            } else {
                                $csvData[$value['selected']][] = 0;
                            }
                        }

                    }
                }

                $filename = "chart-data-" . date("Ymdhis").'.csv';
                $headers = array(
                    'Content-Type' => 'text/csv',
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );
                $callback = function() use($csvData, $headerRow, $labels, $checkedName) {
                    $handle = fopen('php://output', 'w');
                    fputcsv($handle, $headerRow);

                    foreach ($labels as $Lkey => $row) {
                        $rowData = [$row];
                        foreach ($checkedName as $fkey => $fieldName) {
                            $rowData[] = isset($csvData[$fieldName][$Lkey]) ? $csvData[$fieldName][$Lkey] : '';
                        }
                        fputcsv($handle, $rowData);
                    }

                    fclose($handle);
                };
                return \Response::stream($callback, 200, $headers);

            }
        }
    }

    private function generatePeriodDatesWithIntervals($start_date, $end_date, $interval_minutes, $today = 'false')
    {
        $periodDates = [];
        $currentDate = new DateTime($start_date . ' 00:00:00', new DateTimeZone('UTC'));
        if($today == 'true'){
            while ($currentDate->format('Y-m-d H:i:s') <= $end_date) {
                $periodDates[] = $currentDate->format('Y-m-d H:i');
                $currentDate->add(new DateInterval('PT' . $interval_minutes . 'M'));
            }
        } else {
            while ($currentDate->format('Y-m-d H:i:s') <= $end_date . ' 23:59:59') {
                $periodDates[] = $currentDate->format('Y-m-d H:i');
                $currentDate->add(new DateInterval('PT' . $interval_minutes . 'M'));
            }
        }
        return $periodDates;
    }
}
