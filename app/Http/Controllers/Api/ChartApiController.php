<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\UserTrait;
use App\Models\Cluster;
use App\Models\Data;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Inverter;
use App\Models\UserChart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use LDAP\Result;
use \DataTables;
use MongoDB\BSON\UTCDateTime;
use Carbon\CarbonPeriod;
use DateTime;
use DateTimeZone;
use DateInterval;
use App\Helpers\JwtHelper;
use Auth;
use  Illuminate\Support\Facades\Validator;


class ChartApiController extends Controller
{
    //
    use UserTrait;

    public function chartsList(Request $request, $id = '')
    {
        // Fetch user data from the JWT token or session
        $userData = JwtHelper::getUserData();
        
        if (!$userData) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
    
        
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        if ($userData['role_id'] != $adminRoleId) {
            $id = '';
        }
    
        // Prepare the response data
        $data = [
            'heading'     => 'Charts List',
            'title'       => 'Charts List',
         
            'company_id'  => $id,
        ];
    
        // Return the data as JSON
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    



    // get the chart list api
    public function getChartsList2(Request $request)
    {
        
        $userData = JwtHelper::getUserData();
    
        if (!$userData) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
    
    //    if($company_id){
    //     $company_id = $company_id;
    //    }

        // $start = (int) $request->input('start', 0);
        // $length = (int) $request->input('length', 10);
    
        // Get constants
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        $usersChartQuery = UserChart::select('*'); // 
    
        
        if ($userData['role_id'] == $adminRoleId) {
           
            $usersChartQuery->where('company_id', '=', $request->company_id);
        } else {
            // Otherwise, show charts for the user's company
            $usersChartQuery->where('company_id', '=', $userData['company_id']);
        }
    
        
        $totalRecords = $usersChartQuery->count();
    
      $usersChart = $usersChartQuery;
        // Prepare data for the response
        $data = [];
        foreach ($usersChart as $row) {
            $data = [
                'id' => $row->id,
                'title' => $row->title,
                'chart_type' => $row->chart_type,
                'status' => [
                    'label' => $row->status == 1 ? 'Active' : 'Inactive',
                    'type'  => $row->status == 1 ? 'success' : 'danger',
                ],
                'created_at' => date_format(date_create($row->created_at), "Y-m-d h:i A"),
                'action' => [
                    'edit' => Gate::allows('ChartEdit') ? "/edit-chart/{$row->id}" : null,
                    'view' => Gate::allows('ChartView') ? "/view-chart/{$row->id}" : null,
                ],
            ];
        }
    
        // Build response
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // Assuming no filtering applied for now
            'data' => $data
        ]);
    }
    
    
// get list 2 
public function getChartsList1(Request $request)
{
   
    $userData = JwtHelper::getUserData();  // This method should return user data from the token
    
   
    // $request->merge(array(
    //     'start' => (int)$request->input('start'),
    //     'length' => (int)$request->input('length')
    // ));

    
    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $usersChart = UserChart::select('*'); // Selecting all charts

   
    if ($userData['role_id'] == $adminRoleId) {
     
        $usersChart->where('company_id', '=', $request->company_id);
    } else {
       
        $usersChart->where('company_id', '=', $userData['company_id']);
    }

    
     Datatables::of($usersChart)
        ->addIndexColumn()
        ->addColumn('action', function($row) use ($userData) {
          
            $actionBtn = [];

            if (Gate::allows('ChartEdit')) {
                $actionBtn[] = [
                    'type' => 'edit',
                    'url'  => "/edit-chart/{$row->id}",
                    'tooltip' => 'Click to edit'
                ];
            }
            if (Gate::allows('ChartView')) {
                $actionBtn[] = [
                    'type' => 'view',
                    'url'  => "/view-chart/{$row->id}",
                    'tooltip' => 'Click to view'
                ];
            }

            return $actionBtn;
        })
        ->addColumn('status', function($row) {
          
            return [
                'label' => $row->status == 1 ? 'Active' : 'Inactive',
                'type'  => $row->status == 1 ? 'success' : 'danger'
            ];
        })
        ->addColumn('created_at', function($row) {
           
            $startDate = date_create($row->created_at);
            return date_format($startDate, "Y-m-d h:i A");
        })
        ->orderColumn('title', function ($query, $order) {
         
            $query->orderBy('title', $order);
        })
        ->orderColumn('created_at', function ($query, $order) {
            // Sort by created_at
            $query->orderBy('created_at', $order);
        })
        ->rawColumns(['action', 'status']) // Treat these columns as raw HTML
        ->toJson(); // Convert the entire response to JSON
    
        return response()->json([
           'data' => $usersChart,
        ]);

} 


// list for charts  
public function getChartsListApi(Request $request)
{
    // Pagination  (start, length)
    $start = (int)$request->input('start', 0);
    $length = (int)$request->input('length', 10);

    $adminRoleId = \Config::get('constants.roles.Master_Admin');
    $usersChart = UserChart::select('*');


    // Role-based access control
    if(auth('api')->user()->role_id == $adminRoleId){
        $usersChart->where('company_id','=',$request->company_id);
    } else {
        $usersChart->where('company_id','=',auth('api')->user()->company_id);
    }
    $company_login_id=auth('api')->user()->company_id;

    // Fetch and paginate data
    $data = $usersChart->skip($start)->take($length)->get();

    // Formatting data for API response
    $formattedData = $data->map(function($row) use ($company_login_id) {
        // Initialize the action buttons
        $actionBtn = [
            'edit_url' => null,
            'view_url' => null
        ];

        // Build the action URLs based on company_login_id
        if ($company_login_id) {
            if (Gate::allows('ChartEdit')) {
                $actionBtn['edit_url'] = url("/company/{$company_login_id}/edit-chart/{$row->id}");
            }
            if (Gate::allows('ChartView')) {
                $actionBtn['view_url'] = url("/company/{$company_login_id}/view-chart/{$row->id}");
            }
        } else {
            if (Gate::allows('ChartEdit')) {
                $actionBtn['edit_url'] = url("/edit-charts/{$row->id}");
            }
            if (Gate::allows('ChartView')) {
                $actionBtn['view_url'] = url("/view-chart/{$row->id}");
            }
        }

        return [
            'id' => $row->id,
            'title' => $row->title,
            'status' => $row->status == 1 ? 'Active' : 'Inactive',
            'created_at' => $row->created_at->format('Y-m-d h:i A'),
            'action' => $actionBtn
        ];
    });

    // Return the formatted data as JSON
    return response()->json([
        'data' => $formattedData,
        'recordsTotal' => $usersChart->count(),
        'recordsFiltered' => $usersChart->count()
    ]);
}



// add the chart 
  // Create Charts


  public function addChart(Request $request, $id = '')
{
    // Fetch the most recent data
    $grid = Data::where('data.data.Contain', 'Grid/Genset')->orderBy('created_at_timestamp', 'desc')->first();
    if (!$grid) {
        return response()->json(['error' => 'No grid data found'], 404);
    }

    $battery = Data::where('data.data.Contain', 'Battery')->orderBy('created_at_timestamp', 'desc')->first();
    if (!$battery) {
        return response()->json(['error' => 'No battery data found'], 404);
    }

    $PV = Data::where('data.data.Contain', 'PV')->orderBy('created_at_timestamp', 'desc')->first();
    if (!$PV) {
        return response()->json(['error' => 'No PV data found'], 404);
    }

    $chk_data = machineDatas(); // Ensure this function handles potential errors

    // Structure the data to return
    $data = [
        'title' => 'Add Chart',
        'module' => 'Charts List',
        'macid' => '',
        'grid_data' => $grid,
        'battery' => $battery, // Battery means Unit
        'PV' => $PV, // For AC and DC Solar
        'data' => [],
        'chk_data' => $chk_data,
        'company_id' => $id,
    ];

    return response()->json($data, 200);
}


  //save the chart from 
  
  
// public function saveChart(Request $request)
//     {
//         // Validate the request data
//         $request->validate([
//             'title' => 'required|string',
//             'chart-type' => 'required|string',
//             'checkboxData' => 'required|array',
//         ]);

//         // Get user data from the JWT token
//         $userdata = JwtHelper::getUserData();
//         if (!$userdata) {
//             return response()->json(['status' => 'false', 'message' => 'Invalid JWT token'], 401);
//         }

//         // Prepare data for saving
//         $data = $request->all();
//         $checkboxData = $request->input('checkboxData');

//         // Create a new chart record in the database
//         $userChart = new UserChart();
//         $userChart->user_id = $userdata['user_id'];
//         $userChart->company_id = $userdata['company_id'];
//         $userChart->title = $data['title'];
//         $userChart->chart_type = $data['chart-type'];
//         $userChart->option_data = json_encode($checkboxData); // Save checkbox data as JSON
//         $userChart->status = 1;

//         // Save the chart and check for success
//         if ($userChart->save()) {
//             return response()->json(['status' => 'true', 'message' => 'Chart saved successfully', 'data' => $userChart]);
//         } else {
//             return response()->json(['status' => 'false', 'message' => 'Failed to save chart'], 500);
//         }
//     }



public function saveCharts(Request $request)
{
    $input = $request->all();

    // Validate the request input
    $validator = Validator::make($input, [
        'title' => 'required|string',
        'chart-type' => 'required|string',
       // 'checkboxData' => 'required', // Assuming checkboxData is sent as a JSON string
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 'false', 'errors' => $validator->errors()], 400);
    }

    $checkboxData = json_decode($input['checkboxData'], true);

    if ($checkboxData) {
        if (isset($input['chart_id']) && $input['chart_id']) {
            // Update existing chart
            $u_chart = UserChart::find($input['chart_id']);
            if (!$u_chart) {
                return response()->json(['status' => 'false', 'message' => 'Chart not found'], 404);
            }

            $u_chart->title = $input['title'];
            $u_chart->chart_type = $input['chart-type'];
            $u_chart->option_data = $checkboxData;
            $u_chart->save();
            return response()->json(['status' => 'true', 'message' => 'Chart update successfully']);

        } else {
            // Create a new chart
            $adminRoleId = \Config::get('constants.roles.Master_Admin');
            if (auth('api')->user() && auth('api')->user()->role_id == $adminRoleId) {
                $company_id = $input['company_id'];
            } else {
                $company_id = auth('api')->user()->company_id;
            }

            $u_chart = new UserChart();
            $u_chart->user_id = auth('api')->user()->id;
            $u_chart->company_id = $company_id;
            $u_chart->title = $input['title'];
            $u_chart->chart_type = $input['chart-type'];
            $u_chart->option_data = $checkboxData;
            $u_chart->status = 1;
            $u_chart->save();
            
        return response()->json(['status' => 'true', 'message' => 'Chart saved successfully']);
        }

    } else {
        return response()->json(['status' => 'false', 'message' => 'Invalid checkbox data'], 400);
    }
}




    // view chart based on id pass url - http://3.7.110.174/charts/65e5f198f6f78502b9021270
public function getchartDetails(Request $resquest,$id='',$deviceId=''){
    $userdata = JwtHelper::getUserData();
    if (!$userdata) {
        return response()->json(['status' => 'false', 'message' => 'Invalid JWT token'], 401);
    }
    if ($deviceId) {
        $id = $deviceId;
    }
    
    $dataOne = Device::where('_id', $id)->first();
    if (!$dataOne) {
        return response()->json(['error' => 'Device not found'], 404); // Return 404 if device not found
    }

    
    $grid = Data::where('data.data.Contain', 'Grid/Genset')->orderBy('created_at_timestamp', 'desc')->first();
    $battery = Data::where('data.data.Contain', 'Battery')->orderBy('created_at_timestamp', 'desc')->first();
    $PV = Data::where('data.data.Contain', 'PV')->orderBy('created_at_timestamp', 'desc')->first();

    //  machineDatas() is a helper function 
    $data = machineDatas();

    // Filter for frequently used data
    $frequentlyArray = array_filter($data, function ($subarray) {
        return array_key_exists('is_frequently', $subarray);
    });

 
    $responseData = [
        'title'       => 'Charts',
        'macid'       => '',  
        'grid_data'   => $grid,
        'battery'     => $battery, // Battery means Unit
        'PV'          => $PV, // For AC and DC Solar
        'data'        => $data,
        'device_data' => $dataOne,
        'years'       => "", //  this is from  helper function
        'months'      => getChartMonthRange(), // A this is from  helper function
        'frequentlyArray' => $frequentlyArray
    ];

    
    return response()->json($responseData, 200);

}



// edit chart form api for url - http://3.7.110.174/edit-chart/6695d41096177dbe2c030202
    public function editChartApi($id = '', $deviceId = '')
    {
        if ($deviceId) {
            $id = $deviceId;
        }
    
        $adminRoleId = \Config::get('constants.roles.Master_Admin');
        $dataOne = UserChart::where('_id', $id)->first();
    
        if (!$dataOne) {
            return response()->json(['error' => 'Chart not found'], 404);
        }
    
        $company_id = $dataOne->company_id;
        $chartData = UserChart::where('_id', $id);
    
        if (auth('api')->user() && auth('api')->user()->role_id == $adminRoleId) {
            $chartData->where('company_id', '=', $company_id);
        } else {
            $chartData->where('company_id', '=', auth()->guard('admin')->user()->company_id);
        }
    
        $chartData = $chartData->first();
        
        // Fetch grid, battery, and PV data
        $grid = Data::where('data.data.Contain', 'Grid/Genset')->orderBy('created_at_timestamp', 'desc')->first();
        $battery = Data::where('data.data.Contain', 'Battery')->orderBy('created_at_timestamp', 'desc')->first();
        $PV = Data::where('data.data.Contain', 'PV')->orderBy('created_at_timestamp', 'desc')->first();
        
        // Fetch machine data
        $chk_data = machineDatas();
    
        // Prepare the response data
        $responseData = [
            'title'      => 'Edit Chart',
            'module'     => 'Charts List',
            'macid'      => '',  // Assuming you need to populate this somewhere
            'grid_data'  => $grid,
            'battery'    => $battery,  // Battery information
            'PV'         => $PV,  // PV (AC and DC Solar) information
            'data'       => $chartData,
            'chk_data'   => $chk_data,
            'company_id' => $company_id,  // Populate this as necessary
        ];
    
        // Return the response as JSON
        return response()->json($responseData);
    }
    

    // save the update records form charts 

    public function updateeditChart(Request $request, $id)
        {
             
             // Validate the request data
        // $request->validate([
        //     'title' => 'required|string',
        //     'chart-type' => 'required|string',
        //     'checkboxData' => 'required|array',
        // ]);

        $input = $request->all();

        $userdata = JwtHelper::getUserData();
        if (!$userdata) {
            return response()->json(['status' => 'false', 'message' => 'Invalid JWT token'], 401);
        }
        $checkboxData = json_decode($input['checkboxData'], true);
        if (!$checkboxData) {
            return response()->json(['status' => 'false', 'message' => 'Invalid checkbox data format'], 400);
        }
        $u_chart = UserChart::where('_id', $id)
                        ->where('user_id', $userdata['user_id']) // Ensure the user is the owner of the chart
                        ->first();

    if (!$u_chart) {
        return response()->json(['status' => 'false', 'message' => 'Chart not found or unauthorized'], 404);
    }
    $u_chart->title = $input['title'];
    $u_chart->chart_type = $input['chart-type'];
    $u_chart->option_data = $checkboxData; 

    if ($u_chart->save()) {
        return response()->json(['status' => 'true', 'message' => 'Chart updated successfully', 'data' => $u_chart], 200);
    } else {
        return response()->json(['status' => 'false', 'message' => 'Failed to update chart'], 500);
    }
    }

    // view charts 
    public function viewChartApi(Request $request,$id='',$userId=''){
        $userdata = JwtHelper::getUserData();
        if (!$userdata) {
        response()->json(['message'=>'unauthorized user',400]);

    }
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
    if($userdata['role_id']==$adminRoleId){
        $chartData->where('company_id','=',$company_id);
    }
    else {
        $chartData->where('company_id','=',$userdata['company_id']);
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
    return response()->json(['message'=>'view chart data', 'data'=>$data]);
}

}
