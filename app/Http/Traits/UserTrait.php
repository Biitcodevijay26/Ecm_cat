<?php

namespace App\Http\Traits;
use App\Models\Data;
use App\Models\InverterSetting;
use App\Models\InverterWarningCode;
use Cache;
use Storage;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait UserTrait
{
    public function getInverterSettingDetails($control_card_no,$inverter_id)
    {
        $dataIMD = Data::where('data.Control_card_sn',$control_card_no)->where('data.content','inverter_menu_details')->orderBy('created_at_timestamp','desc')->first();
        if($dataIMD){
            InverterSetting::where('control_card_no',$control_card_no)->delete();
            $setting = new InverterSetting;
            $setting->control_card_no = $control_card_no;
            $setting->inverter_id = $inverter_id;
            $setting->inverter_start_stop = $dataIMD['data']['inverter_start_stop'];

            $meter = [
                'meter_en_di'=>$dataIMD['data']['meter_en_di'],
                'meter_1id'=>$dataIMD['data']['meter_1id'],
                'meter_2id'=>$dataIMD['data']['meter_2id']
            ];
            $setting->meter = $meter;

            $grid_vac = [
                'vac_min' => [
                    'value' => $dataIMD['data']['vac_min']['value'],
                    'unit' => $dataIMD['data']['vac_min']['unit']
                ],
                'vac_max' => [
                    'value' => $dataIMD['data']['vac_max']['value'],
                    'unit' => $dataIMD['data']['vac_max']['unit']
                ],
                'vac_min_slow' => [
                    'value' => $dataIMD['data']['vac_min_slow']['value'],
                    'unit' => $dataIMD['data']['vac_min_slow']['unit']
                ],
                'vac_max_slow' => [
                    'value' => $dataIMD['data']['vac_max_slow']['value'],
                    'unit' => $dataIMD['data']['vac_max_slow']['unit']
                ]
            ];
            $setting->grid_vac = $grid_vac;

            $grid_fac = [
                'fac_min' => [
                    'value' => $dataIMD['data']['fac_min']['value'],
                    'unit' => $dataIMD['data']['fac_min']['unit']
                ],
                'fac_max' => [
                    'value' => $dataIMD['data']['fac_max']['value'],
                    'unit' => $dataIMD['data']['fac_max']['unit']
                ],
                'fac_min_slow' => [
                    'value' => $dataIMD['data']['fac_min_slow']['value'],
                    'unit' => $dataIMD['data']['fac_min_slow']['unit']
                ],
                'fac_max_slow' => [
                    'value' => $dataIMD['data']['fac_max_slow']['value'],
                    'unit' => $dataIMD['data']['fac_max_slow']['unit']
                ]
            ];
            $setting->grid_fac = $grid_fac;

            $setting->grid_10min_high = [
                'value' => $dataIMD['data']['grid_10min_high']['value'],
                'unit' => $dataIMD['data']['grid_10min_high']['unit']
            ];

            $setting->save();
        }

        $dataBMD = Data::where('data.Control_card_sn',$control_card_no)->where('data.content','battery_menu_details')->orderBy('created_at_timestamp','desc')->first();
        if($dataBMD && $setting){
            $setting->battery_min_capcity = [
                'value' => $dataBMD['data']['battery_min_capcity']['value'],
                'unit' => $dataBMD['data']['battery_min_capcity']['unit']
            ];
            $setting->battery_charge_max_current = [
                'value' => $dataBMD['data']['battery_charge_max_current']['value'],
                'unit' => $dataBMD['data']['battery_charge_max_current']['unit']
            ];
            $setting->battery_discharge_max_current = [
                'value' => $dataBMD['data']['battery_discharge_max_current']['value'],
                'unit' => $dataBMD['data']['battery_discharge_max_current']['unit']
            ];

            $setting->grid_tie_limit_en_di = $dataBMD['data']['grid_tie_limit_en_di'];
            $setting->dischCutOffCapacity_GridMode = [
                'value' => $dataBMD['data']['dischCutOffCapacity_GridMode']['value'],
                'unit' => $dataBMD['data']['dischCutOffCapacity_GridMode']['unit']
            ];

            $setting->save();
        }

        $dataPCMD = Data::where('data.Control_card_sn',$control_card_no)->where('data.content','power_control_menu_details')->orderBy('created_at_timestamp','desc')->first();
        if($dataPCMD && $setting){
            $setting->operating_mode = $dataPCMD['data']['operating_mode'];
            $setting->charge_period_1 = $dataPCMD['data']['charge_period_1'];
            $setting->CP1_max_cap = $dataPCMD['data']['CP1_max_cap'];
            $setting->charge_period_2 = $dataPCMD['data']['charge_period_2'];
            $setting->CP2_max_cap = $dataPCMD['data']['CP2_max_cap'];

            $setting->BackUp_GridChargeEN = $dataPCMD['data']['BackUp_GridChargeEN'];
            $setting->backup_mode = $dataPCMD['data']['backup_mode'];

            $setting->EPS_Mute = $dataPCMD['data']['EPS_Mute'];
            $setting->EPS_AutoRestart = $dataPCMD['data']['EPS_AutoRestart'];
            $setting->EPS_Frequency = $dataPCMD['data']['EPS_Frequency'];
            $setting->EPS_MinEscSoc = $dataPCMD['data']['EPS_MinEscSoc'];

            $setting->Export_control_User_Limit = $dataPCMD['data']['Export_control_User_Limit'];
            $setting->save();
        }

        $dataInfMD = Data::where('data.Control_card_sn',$control_card_no)->where('data.content','date&time')->orderBy('created_at_timestamp','desc')->first();
        if($dataInfMD && $setting){
            $setting->info_menu_date = $dataInfMD['data']['date'];
            $setting->info_menu_time = $dataInfMD['data']['time'];
            $setting->save();
        }
        return $setting ?? [];
    }
    public function getInverterWaningCodeByCodeOrccn($control_card_no='',$codes=[])
    {
        $wCodes = [];
        if($codes){
            $wCodes = InverterWarningCode::select('code','msg')->whereIn('code',$codes)->get();
        } elseif($control_card_no){
            $lastAlWarn = Data::where('data.Control_card_sn',$control_card_no)
                            ->where('data.content','alarm_warning_details')
                            //->where('data.alarm_warning','>',0)
                            ->orderBy('created_at_timestamp','desc')
                            ->first();
            if($lastAlWarn && $lastAlWarn['data']['alarm_warning'] > 0){
                $alarm_warning_code = $lastAlWarn['data']['alarm_warning_code'];
                $wCodes = InverterWarningCode::select('code','msg')->whereIn('code',$alarm_warning_code)->get();
            }
        }
        return $wCodes;
    }
    public function invokeNodeApi($route, $post)
    {
        try {
                $post['apiKey'] = config('constants.API_KEY_NODE');
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => config('constants.BASE_URL_NODE'). $route,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($post),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                curl_close($curl);

                if (isset($error_msg)) {
                    return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
                } else {
                    return $response;
                }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }
    public function getPowerGraphData($rdata=[])
    {
        $mainData = [];
        $dataset = [];
        $startDate = $rdata['startDate'] ?? date('Y-m-d') ;
        $endDate = $rdata['endDate'] ??  date('Y-m-d')  ;

        $to = Carbon::createFromFormat('Y-m-d', $startDate);
        $from = Carbon::createFromFormat('Y-m-d', $endDate);

        $diff_in_days = $to->diffInDays($from);

        $endDate = $endDate . ' 23:59:59';

        $stDate = new Carbon($startDate);
        $edDate = new Carbon($endDate);

        $froFormat = $this->getGroupDateFOrmate($diff_in_days);

        // pv_details
        $data = Data::where('data.Control_card_sn',$rdata['control_card_no'])->where('data.content','pv_details');
        $data->whereBetween('created_at',[$stDate, $edDate]);
        if($froFormat){
           /* $data->raw(function ($collection) use($froFormat) {
                return $collection->aggregate([
                    [
                       '$group' => [
                            "_id" => ['$month'=>'$created_at'],
                          // "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                          // 'sum' => ['$sum' => '$data.total_pv_power.value']

                        //    "_id" => [
                        //         "year" => [ '$year' => '$created_at'],
                        //         "month" => [ '$month' => '$created_at']
                        //     ]
                          ]
                    ],

                ]);
            });*/
            //$data->groupBy('created_at');
        }

       /* $bars = Data::raw(function ($collection) use($stDate,$edDate) {

            return $collection->aggregate([
                    [
                        '$match' => [
                            'created_at_timestamp' => ['$gte' => $stDate, '$lte' => $edDate]
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
        dd($bars);*/

        $data = $data->get();
       // dd($data->toArray());
        if($data){
            foreach ($data as $key => $value) {
                $dataset[0]['data'][] = [ 'x' => $value['created_at_timestamp'],  'y' => $value['data']['total_pv_power']['value'] ];
            }
            $dataset[0]['label'] = "PV Power";
            $dataset[0]['content'] = "pv_details";
            $dataset[0]['fill'] = true;
            $dataset[0]['tension'] = 0.5;
            $dataset[0]['yAxisID'] = 'y';
        }

        // inverter_details
        $data2 = Data::where('data.Control_card_sn',$rdata['control_card_no'])->where('data.content','inverter_details');
        if($diff_in_days > 40){
            /*$data2->raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ]
                          ]
                    ],

                ]);
            });*/
        }
        $data2->whereBetween('created_at',[$stDate, $edDate]);
        $data2 = $data2->cursor();


        if($data2){
            foreach ($data2 as $key => $value) {
                $dataset[1]['data'][] = [ 'x' => $value['created_at_timestamp'], 'y' => $value['data']['inv_total_power']['value'] ];
            }
            $dataset[1]['label'] = "AC Power";
            $dataset[1]['content'] = "inverter_details";
            $dataset[1]['fill'] = true;
            $dataset[1]['tension'] = 0.5;
            $dataset[1]['yAxisID'] = 'y';
        }

        // eps_details
        $data3 = Data::where('data.Control_card_sn',$rdata['control_card_no'])->where('data.content','eps_details');
        if($diff_in_days > 31){
            /*$data3->raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ]
                          ]
                    ],

                ]);
            });*/
        }
        $data3->whereBetween('created_at',[$stDate, $edDate]);
        $data3 = $data3->cursor();

        if($data3){
            foreach ($data3 as $key => $value) {
                $dataset[2]['data'][] = [ 'x' => $value['created_at_timestamp'], 'y' => $value['data']['eps_total_power']['value'] ];
            }
            $dataset[2]['label'] = "EPS Power";
            $dataset[2]['content'] = "eps_details";
            $dataset[2]['fill'] = true;
            $dataset[2]['tension'] = 0.5;
            $dataset[2]['yAxisID'] = 'y';
        }

        // battery_details
        $data4 = Data::where('data.Control_card_sn',$rdata['control_card_no'])->where('data.content','battery_details');
        if($diff_in_days > 40){
           /* $data4->raw(function ($collection) {
                return $collection->aggregate([
                    [
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ]
                          ]
                    ],

                ]);
            });*/
        }
        $data4->whereBetween('created_at',[$stDate, $edDate]);
        $data4 = $data4->cursor();

        if($data4){
            foreach ($data4 as $key => $value) {
                $dataset[3]['data'][] = [ 'x' => $value['created_at_timestamp'], 'y' => $value['data']['bat_power']['value'] ];
                $dataset[4]['data'][] = [ 'x' => $value['created_at_timestamp'], 'y' => $value['data']['bat_soc']['value'] ];
            }
            $dataset[3]['label'] = "Battery Power";
            $dataset[3]['content'] = "battery_details";
            $dataset[3]['fill'] = true;
            $dataset[3]['tension'] = 0.5;
            $dataset[3]['yAxisID'] = 'y';

            $dataset[4]['label'] = "Battery SoC";
            $dataset[4]['content'] = "battery_details_soc";
            $dataset[4]['fill'] = true;
            $dataset[4]['tension'] = 0.5;
            $dataset[4]['yAxisID'] = 'y1';
            $dataset[4]['hidden'] = true;
        }


        $mainData['labels'] = '';
        $mainData['datasets'] = $dataset;
        $mainData['diff_in_days'] = $diff_in_days;
        $mainData['time_type'] = $this->getTimeTypeByDayDiff($diff_in_days);
        return $mainData;
    }
    public function getEnergyGraphData($rdata=[])
    {
        $mainData = [];
        $dataset = [];
        $labels = [];
        $timezone = config('app.timezone');

        $control_card_no = $rdata['control_card_no'];
        $enchartFilter   = $rdata['enchartFilter'];
        $enDay           = $rdata['enDay'];
        $enMonth         = $rdata['enMonth'];
        $enYear          = $rdata['enYear'];


        $data = Data::raw(function ($collection) use($control_card_no,$enchartFilter,$enDay,$enMonth,$enYear,$timezone) {
            if($enchartFilter == 'day'){
                $dt = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '00:00:00';
                $dt1 = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);

                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.data.Contain' => 'Grid/Genset'
                            ],
                        ],
                        '$group' => [
                            "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                            // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                            'sum' => ['$avg' => ['$toDouble' => '$data.data.Power.Gen_P_L1(W)']],
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'month'){
                $dt = $enYear . '-' . $enMonth . '-' . '01' . ' ' . '00:00:00';
                $dt1 = date('Y-m-t', strtotime($dt));
                $dt1 = $dt1 . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);

                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'Grid/Genset'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.eps_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'year'){
                $dt = $enYear . '-01-01' . ' ' . '00:00:00';
                $dt1 = $enYear . '-12-31' . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'Grid/Genset'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.eps_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'all'){
                return $collection->aggregate([
                        [
                            '$match' => [
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'Grid/Genset'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.eps_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
        });

        $data1 = Data::raw(function ($collection) use($control_card_no,$enchartFilter,$enDay,$enMonth,$enYear,$timezone) {
            if($enchartFilter == 'day'){
                $dt = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '00:00:00';
                $dt1 = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);

                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                // 'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'inverter_details'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                'sum' => ['$avg' => '$data.inv_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'month'){
                $dt = $enYear . '-' . $enMonth . '-' . '01' . ' ' . '00:00:00';
                $dt1 = date('Y-m-t', strtotime($dt));
                $dt1 = $dt1 . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);

                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'inverter_details'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.inv_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'year'){
                $dt = $enYear . '-01-01' . ' ' . '00:00:00';
                $dt1 = $enYear . '-12-31' . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'inverter_details'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.inv_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            } else if($enchartFilter == 'all'){
                return $collection->aggregate([
                        [
                            '$match' => [
                                'data.Control_card_sn' => $control_card_no,
                                'data.content' => 'inverter_details'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
                                'sum' => ['$avg' => '$data.inv_energy_today.value']
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
        });


        if($data || $data1){
            if($enchartFilter == 'day'){
                $fulldate = $enYear . '-' . $enMonth . '-' . $enDay;
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

                    $filtered1 = $data1->where('_id', $seachStr)->first();
                    if($filtered1){
                        $dataset[1]['data'][] = $filtered1['sum'];
                    } else {
                        $dataset[1]['data'][] = 0;
                    }

                    $x++;
                }
            } else if($enchartFilter == 'month'){

                $dt = $enYear . '-' . $enMonth . '-' . '01';
                $dt1 = date('Y-m-t', strtotime($dt));
                $startDay = Carbon::parse($dt);
                $endDay= Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'day');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("Y-m-d");
                    $filtered = $data->where('_id', $dt->format("Y-m-d"))->first();
                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }

                    $filtered1 = $data1->where('_id', $dt->format("Y-m-d"))->first();
                    if($filtered1){
                        $dataset[1]['data'][] = $filtered1['sum'];
                    } else {
                        $dataset[1]['data'][] = 0;
                    }

                }
            } else if($enchartFilter == 'year'){
                $dt = $enYear . '-01-01';
                $dt1 = $enYear . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay= Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("m");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }

                    $filtered1 = $data1->where('_id', $dt->format("Y-m"))->first();
                    if($filtered1){
                        $dataset[1]['data'][] = $filtered1['sum'];
                    } else {
                        $dataset[1]['data'][] = 0;
                    }
                }
            } else if($enchartFilter == 'all'){
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset[0]['data'][] = $value['sum'];
                }

                $sorted1 = $data1->sortBy('_id');
                foreach ($sorted1 as $key => $value) {
                    $dataset[1]['data'][] = $value['sum'];
                }
            }


            $dataset[0]['label'] = "EPS - On-grid yield";
            $dataset[0]['content'] = "Grid/Genset";
            $dataset[0]['fill'] = true;
            $dataset[0]['tension'] = 0.5;

            $dataset[1]['label'] = "Off-grid yield";
            $dataset[1]['content'] = "inverter_details";
            $dataset[1]['fill'] = true;
            $dataset[1]['tension'] = 0.5;


        }

        $mainData['labels'] = $labels;
        $mainData['datasets'] = $dataset;
        return $mainData;

    }
    public function getBatteryStatusGraphData($rdata=[])
    {
        $mainData = [];
        $dataset = [];
        $startDate = $rdata['startDate'] ?? date('Y-m-d') ;
        $endDate = $rdata['endDate'] ??  date('Y-m-d')  ;

        $to = Carbon::createFromFormat('Y-m-d', $startDate);
        $from = Carbon::createFromFormat('Y-m-d', $endDate);

        $diff_in_days = $to->diffInDays($from);

        $endDate = $endDate . ' 23:59:59';

        $stDate = new Carbon($startDate);
        $edDate = new Carbon($endDate);


        // battery_details
        $data = Data::where('data.Control_card_sn',$rdata['control_card_no'])->where('data.content','battery_details');
        $data->whereBetween('created_at',[$stDate, $edDate]);


        $data = $data->get();
       // dd($data->toArray());
        if($data){
            foreach ($data as $key => $value) {
                $dataset[0]['data'][] = [ 'x' => $value['created_at_timestamp'],  'y' => $value['data']['bat_Voltage']['value'] ];
                $dataset[1]['data'][] = [ 'x' => $value['created_at_timestamp'],  'y' => $value['data']['bat_current']['value'] ];
                $dataset[2]['data'][] = [ 'x' => $value['created_at_timestamp'],  'y' => $value['data']['bat_power']['value'] ];
                $dataset[3]['data'][] = [ 'x' => $value['created_at_timestamp'],  'y' => $value['data']['bat_soc']['value'] ];
            }
            $dataset[0]['label'] = "Voltage(V)";
            $dataset[0]['content'] = "battery_details_bat_Voltage";
            $dataset[0]['yAxisID'] = 'y';

            $dataset[1]['label'] = "Current(A)";
            $dataset[1]['content'] = "battery_details_bat_current";
            $dataset[1]['yAxisID'] = 'y1';

            $dataset[2]['label'] = "Power(W)";
            $dataset[2]['content'] = "battery_details_bat_power";
            $dataset[2]['yAxisID'] = 'y2';

            $dataset[3]['label'] = "Battery SoC(%)";
            $dataset[3]['content'] = "battery_details_bat_soc";
            $dataset[3]['yAxisID'] = 'y3';
        }


        $mainData['labels'] = '';
        $mainData['datasets'] = $dataset;
        $mainData['diff_in_days'] = $diff_in_days;
        $mainData['time_type'] = $this->getTimeTypeByDayDiff($diff_in_days);
        return $mainData;
    }
    public function getTimeTypeByDayDiff($diff_in_days=0)
    {
        if($diff_in_days == 0){
            return 'minute';
        } else if($diff_in_days > 0 && $diff_in_days <= 1){
            return 'hour';
        } else if($diff_in_days > 0 && $diff_in_days < 7){
            return 'day';
        } else if($diff_in_days > 0 && $diff_in_days < 31){
            return 'day';
        } else if($diff_in_days > 0 && $diff_in_days < 365){
            return 'month';
        } else {
            return 'year';
        }
    }
    public function getGroupDateFOrmate($diff_in_days=0)
    {
        if($diff_in_days > 0 && $diff_in_days < 31){
            return "%Y-%m-%d";
        } else if($diff_in_days > 0 && $diff_in_days < 365){
            return "%Y-%m";
        } else {
            return false;
        }
    }

    public function addDiviceApi($route, $data)
    {

        try {
                $data['apiKey'] = config('constants.API_KEY_NODE_CURL');

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => config('constants.BASE_URL_NODE_CURL'). $route,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => http_build_query($data),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                curl_close($curl);

                if (isset($error_msg)) {
                    return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
                } else {
                    return $response;
                }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }


    public function getGridGensetGraphData($rdata=[])
    {
        $mainData = [];
        $dataset  = [];
        $labels   = [];
        $timezone = config('app.timezone');

        $selected_option = $rdata['selected_option'];
        $enchartFilter   = $rdata['enchartFilter'];
        $enDay           = $rdata['enDay'];
        $enMonth         = $rdata['enMonth'];
        $enYear          = $rdata['enYear'];


        $data = Data::raw(function ($collection) use($selected_option,$enchartFilter,$enDay,$enMonth,$enYear,$timezone) {
            if($enchartFilter == 'day'){
                $dt = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '00:00:00';
                $dt1 = $enYear . '-' . $enMonth . '-' . $enDay . ' ' . '23:59:59';
                $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
                $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
                return $collection->aggregate([
                        [
                            '$match' => [
                                'created_at' => ['$gte' => $start, '$lte' => $end],
                                'data.data.Contain' => 'Grid/Genset'
                            ],
                        ],
                        [
                            '$group' => [
                                "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d %H", "date" => '$created_at', "timezone" => $timezone ] ],
                                // 'sum' => ['$avg' => '$data.data.Power.Gen_P_L1(W)'],
                                'sum' => ['$avg' => ['$toDouble' => '$data.data.Power.Gen_P_L1(W)']],
                            ]
                        ],
                        [
                            '$sort' =>["created_at_timestamp" => 1 ]
                        ]
                ]);
            }
            // else if($enchartFilter == 'month'){
            //     $dt = $enYear . '-' . $enMonth . '-' . '01' . ' ' . '00:00:00';
            //     $dt1 = date('Y-m-t', strtotime($dt));
            //     $dt1 = $dt1 . ' ' . '23:59:59';
            //     $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
            //     $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);

            //     return $collection->aggregate([
            //             [
            //                 '$match' => [
            //                     'created_at' => ['$gte' => $start, '$lte' => $end],
            //                     // 'data.Control_card_sn' => $control_card_no,
            //                     'data.content' => 'Grid/Genset'
            //                 ],
            //             ],
            //             [
            //                 '$group' => [
            //                     "_id" => [ '$dateToString' => [ "format" => "%Y-%m-%d", "date" => '$created_at' ] ],
            //                     'sum' => ['$avg' => '$data.eps_energy_today.value']
            //                 ]
            //             ],
            //             [
            //                 '$sort' =>["created_at_timestamp" => 1 ]
            //             ]
            //     ]);
            // } else if($enchartFilter == 'year'){
            //     $dt = $enYear . '-01-01' . ' ' . '00:00:00';
            //     $dt1 = $enYear . '-12-31' . ' ' . '23:59:59';
            //     $start = new \MongoDB\BSON\UTCDateTime(strtotime($dt) * 1000);
            //     $end = new \MongoDB\BSON\UTCDateTime(strtotime($dt1) * 1000);
            //     return $collection->aggregate([
            //             [
            //                 '$match' => [
            //                     'created_at' => ['$gte' => $start, '$lte' => $end],
            //                     // 'data.Control_card_sn' => $control_card_no,
            //                     'data.content' => 'Grid/Genset'
            //                 ],
            //             ],
            //             [
            //                 '$group' => [
            //                     "_id" => [ '$dateToString' => [ "format" => "%Y-%m", "date" => '$created_at' ] ],
            //                     'sum' => ['$avg' => '$data.eps_energy_today.value']
            //                 ]
            //             ],
            //             [
            //                 '$sort' =>["created_at_timestamp" => 1 ]
            //             ]
            //     ]);
            // } else if($enchartFilter == 'all'){
            //     return $collection->aggregate([
            //             [
            //                 '$match' => [
            //                     // 'data.Control_card_sn' => $control_card_no,
            //                     'data.content' => 'Grid/Genset'
            //                 ],
            //             ],
            //             [
            //                 '$group' => [
            //                     "_id" => [ '$dateToString' => [ "format" => "%Y", "date" => '$created_at' ] ],
            //                     'sum' => ['$avg' => '$data.eps_energy_today.value']
            //                 ]
            //             ],
            //             [
            //                 '$sort' =>["created_at_timestamp" => 1 ]
            //             ]
            //     ]);
            // }
        });
        echo "<pre>"; print_r($data); exit("CALL");
        if($data){
            if($enchartFilter == 'day'){
                $fulldate = $enYear . '-' . $enMonth . '-' . $enDay;
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
                }
            } else if($enchartFilter == 'month'){

                $dt = $enYear . '-' . $enMonth . '-' . '01';
                $dt1 = date('Y-m-t', strtotime($dt));
                $startDay = Carbon::parse($dt);
                $endDay= Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'day');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("Y-m-d");
                    $filtered = $data->where('_id', $dt->format("Y-m-d"))->first();
                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }
                }
            } else if($enchartFilter == 'year'){
                $dt = $enYear . '-01-01';
                $dt1 = $enYear . '-12-31';
                $startDay = Carbon::parse($dt);
                $endDay= Carbon::parse($dt1);
                $period = $startDay->range($endDay, 1, 'month');

                foreach ($period as $dt) {
                    $labels[] = $dt->format("m");
                    $filtered = $data->where('_id', $dt->format("Y-m"))->first();
                    if($filtered){
                        $dataset[0]['data'][] = $filtered['sum'];
                    } else {
                        $dataset[0]['data'][] = 0;
                    }
                }
            } else if($enchartFilter == 'all'){
                $sorted = $data->sortBy('_id');
                foreach ($sorted as $key => $value) {
                    $labels[] = $value['_id'];
                    $dataset[0]['data'][] = $value['sum'];
                }
            }


            $dataset[0]['label'] = "Grid-Genset ( Power )";
            $dataset[0]['content'] = "Grid/Genset";
            $dataset[0]['fill'] = true;
            $dataset[0]['tension'] = 0.5;
        }

        $mainData['labels'] = $labels;
        $mainData['datasets'] = $dataset;
        return $mainData;

    }

    public function createDwsAgent($data)
    {

        try {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => config('constants.DWS_URL') . '/json/agents',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('name' => 'Agent 1', 'description'=> 'Agent 1' ),
                    CURLOPT_HTTPHEADER => array(
                      'Authorization: Basic '.base64_encode(config('constants.DWS_KEY').":".config('constants.DWS_SECRET'))
                    ),
                ));
                $response = curl_exec($curl);
                dd($response);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                curl_close($curl);

                if (isset($error_msg)) {
                    return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
                } else {
                    return $response;
                }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }
    public function createDwsSession($data)
    {
        try {
                $postField  = [
                    "idChannel" => $data['idChannel'],
                    "idAgent"   => $data['idAgent'], //"yHKgpjXOpsYIGFiCDdIr",
                    "locale" => "en",
                    "fullAccess" => false,
                    "hideAppsBar" => true,
                    "postMessageOrigin" => config('constants.DWS_ALLOWD_ORIGIN'),
                    "applications" => [
                        "desktop" => [
                            "fullAccess" => true,
                            "hideToolBar" => true,
                            "backgroundColor" => "#ffffff"
                        ]
                    ]
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL =>  config('constants.DWS_URL') . '/json/sessions',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>json_encode($postField),
                    CURLOPT_HTTPHEADER => array(
                      'Content-Type: application/json',
                      'Authorization: Basic '.base64_encode(config('constants.DWS_KEY').":".config('constants.DWS_SECRET'))
                    ),
                  ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                curl_close($curl);

                if (isset($error_msg)) {
                    return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
                } else {
                    return json_encode(["status"=> true, 'curl_error' => false, 'data' => json_decode($response) ]);
                }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }

    public function addDwsServices($data)
    {
        try {
            $URL          = config('constants.BASE_URL_AGENT_CURL');
            $BARRIER_CODE = config('constants.AGENT_BARRIER_CODE');

            $data = json_encode($data);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $URL.'/json/account?request=addServices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: '.$BARRIER_CODE
                ),
            ));

            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            \Log::info("Agent Single API CALL" . $response );

            $dataString = json_decode($response,true);
            if (isset($error_msg)) {
                return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
            } else {
                // return $response;
                return json_encode(["status"=> $dataString['status'] ?? '200', 'data' =>  $response, 'message' => $dataString['message'] ?? '']);
            }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }

    public function createDwsAgents($data)
    {
        try {
            $URL          = config('constants.BASE_URL_AGENT_CURL');
            $BARRIER_CODE = config('constants.AGENT_BARRIER_CODE');

            $data = json_encode($data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $URL.'/json/agents',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: '.$BARRIER_CODE
                ),
            ));
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            \Log::info("Agent API CALL" . $response);
            $dataString = json_decode($response,true);
            if (isset($error_msg)) {
                return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
            } else {
                return json_encode(["status"=> $dataString['status'] ?? '200', 'data' =>  $response, 'message' => $dataString['message'] ?? '']);
            }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }

    public function removeServicesChannelAPI($data){
        try {
            $URL          = config('constants.BASE_URL_AGENT_CURL');
            $BARRIER_CODE = config('constants.AGENT_BARRIER_CODE');

            $data = json_encode($data);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $URL.'/json/account?request=removeServices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: '.$BARRIER_CODE
                ),
            ));

            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }

            \Log::info("Remove Service API CALL : " . $response);
            $dataString = json_decode($response,true);
            if (isset($error_msg)) {
                return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
            } else {
                return json_encode(["status"=> $dataString['status'] ?? '200', 'data' =>  $response, 'message' => $dataString['message'] ?? '']);
            }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }

    public function getDwsServicesInfo($data=[])
    {
        try {
            $URL          = config('constants.BASE_URL_AGENT_CURL');
            $BARRIER_CODE = config('constants.AGENT_BARRIER_CODE');

            $data = json_encode($data);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $URL.'/json/account?request=info',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: '.$BARRIER_CODE
                ),
            ));

            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            \Log::info("getDwsServicesInfo" . $response );

            $dataString = json_decode($response,true);
            if (isset($error_msg)) {
                return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
            } else {
                // return $response;
                return json_encode(["status"=> $dataString['status'] ?? '200', 'data' =>  $response, 'message' => $dataString['message'] ?? '']);
            }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }

     // Remove Agent Crul
     public function removeDwsAgent($data){
        try {
            $URL          = config('constants.BASE_URL_AGENT_CURL');
            $BARRIER_CODE = config('constants.AGENT_BARRIER_CODE');
            $agent_id     = $data['agent_id'] ?? '';
            if($agent_id){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $URL.'/json/agents?id='.$agent_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'DELETE',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: '.$BARRIER_CODE
                    ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                \Log::info("Remove Agent API CALL : " . $response);
                $dataString = json_decode($response,true);
                if (isset($error_msg)) {
                    return json_encode(["status"=> false, 'curl_error' => true, 'message' => $error_msg ]);
                } else {
                    return json_encode(["status"=> $dataString['status'] ?? '200', 'data' =>  $response, 'message' => $dataString['message'] ?? '']);
                }
            } else {
                return json_encode(["status"=> false, 'message' =>  'Agent not found.']);
            }
        } catch (\Exception $e) {
            return json_encode(["status"=> false, 'curl_error' => false, 'message' => $e->getMessage() ]);
        }
    }
}
