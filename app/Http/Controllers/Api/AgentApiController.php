<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserTrait;
use App\Models\Company;
use App\Models\CompanyAgent;
use App\Models\CompanyAgentDetail;
use App\Models\Device;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\JwtHelper;
use Validator;

class AgentApiController extends Controller

{
    use UserTrait;

    // agent index page 
    public function index(Request $request)
    {
        try{
            $company_list = Company::where('status',1)->get();
            $agentInfo = $this->getDwsServicesInfo();
            $dwsError = '';
            $dwsData = [];
            if($agentInfo){
                $agentInfo = json_decode($agentInfo,true);
                if($agentInfo['status'] == '200'){
                    $dwsData = json_decode($agentInfo['data'],true);
                } else {
                    $dwsError = $agentInfo['message'];
                }
            }
            $data = [
                'heading'     => 'Agents',
                'title'       => 'Home',
                'getCompany'  => $company_list,
                'dwsError' => $dwsError,
                'dwsData' => $dwsData,
            ];
            Log::info('user retrived the agen page successfully', ['ip_address' => $request->ip(),
                'user_id' => auth('api')->user()->email]);
            return response()->json($data);
        }
       catch(Exception $e){
           Log::error('Error in agent index page', ['error'=>$e->getMessage(), 'ip_address' => $request->ip()]);
       }
    }



    //get list
    public function getAgentsList(Request $request)
    {
        $userdata=JwtHelper::getUserData();
        $userId = $userdata['user_id'] ?? null;

        // Check for valid token and user
        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Invalid token.'
            ], 401);
        }

        // Proceed with handling the request if it is an AJAX request
      //  if ($request->ajax()) {

            // Fetch the agents list with optional filtering by company ID
            $record = CompanyAgent::with('company')->select('*');
            $search_name = $request->get('seacrh_name');

            // Filter based on the provided search name (company_id)
            $record->when($search_name, function ($query, $search_name) {
                $query->where('company_id', $search_name);
            });

            // Fetch the data
            $data = $record->get();

            // Return formatted data for Datatables
            $agent_list= Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $actionBtn = '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon btn-primary me-2" title="Click to view"><i class="fe fe-eye" aria-hidden="true"></i></a>';
                    return $actionBtn;
                })
                ->addColumn('company_name', function($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('status', function($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('created_at', function($row) {
                    return date('Y-m-d H:i A', strtotime($row->created_at));
                })
                ->rawColumns(['action', 'status'])
                ->make(true);

                return response()->json(['data'=>$agent_list]);
       /// }

        // If the request is not send by ajax
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid request type. This endpoint accepts only AJAX requests.'
        ], 400);
    }


    // add  agent 
    public function add_agent(Request $request)
    {
        try{
            $company_list = Company::where('status', 1)->get();
            // this info from dws services 
            $agentInfo = $this->getDwsServicesInfo();
            $dwsData = [];
            $available_agent = 0;
        
                   if ($agentInfo) {
            $agentInfo = json_decode($agentInfo, true);
            if ($agentInfo['status'] == '200') {
                $dwsData = json_decode($agentInfo['data'], true);
                $available_agent = $dwsData['agentsAllowed'] - $dwsData['agentsInstalled'];
                $available_agent = (int)$available_agent;
            } else {
                $dwsError = $agentInfo['message'];
                return response()->json([
                    'status' => 'error',
                    'message' => $dwsError
                ], 400);
            }
                        }
    
                    if ($available_agent && $available_agent > 0) {
                          return response()->json([
                           'status' => 'success',
                           'heading' => 'Add Agents',
                            'module' => 'Agents',
                            'company_list' => $company_list,
                           'available_agent' => $available_agent
                                ], 200);
                            } else {
                              return response()->json([
                                'status' => 'error',
                                'message' => 'No available agents'
                                ], 400);
                     }
        }
        catch(Exception $e)
        {
            return Log::error('error while adding the agent data');
        }
       
}

// save the data 
public function save(Request $request) {
    // Validate required fields
    $validator = Validator::make($request->all(), [
        'company_name' => 'required|string',
        'agent'        => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'false',
            'response_msg' => $validator->errors()->first()
        ], 400);
    }

    try {
        if ($request->has('id') && $request->id) {
            // Edit Section: Update an existing company agent record
            $record = CompanyAgent::where('_id', $request->id)->first();
            if ($record) {
                $record->company_id = $request->company_name;
                $record->agent      = $request->agent;

                if ($record->save()) {
                    return response()->json(['status' => 'true'], 200);
                } else {
                    return response()->json(['status' => 'false', 'response_msg' => 'Unable to update record.'], 500);
                }
            } else {
                return response()->json(['status' => 'false', 'response_msg' => 'Record not found.'], 404);
            }

        } else {
            // Add Section: Create new agents
            $mytime   = Carbon::now();
            $now_time = $mytime->toDateTimeString();

            $record = new CompanyAgent();
            $record->company_id   = $request->company_name;
            $record->agent        = $request->agent;
            $record->status       = 1;
            $record->created_at   = $now_time;

            if ($record->save()) {
                $agent_id = $record->_id;
                $counts   = CompanyAgentDetail::where('company_id', $request->company_name)->count();

                for ($i = 0; $i < $request->agent; $i++) {
                    $randomString = Str::random(12);
                    $counts += 1;
                    $agent_name = getCompanyName($request->company_name) . ' agent no ' . $counts . $randomString;

                    //  CALL API ADD Multiple
                    $data_multi_curl = [
                        'name'        => $agent_name,
                        'description' => $agent_name
                    ];

                    $res_api_data = $this->createDwsAgents($data_multi_curl);
                    $res_api_data = json_decode($res_api_data, true);

                    if ($res_api_data['status'] == '200') {
                        $res_data = json_decode($res_api_data['data'], true);
                        $record_detail = new CompanyAgentDetail();
                        $record_detail->company_id       = $request->company_name;
                        $record_detail->company_agent_id = $agent_id;
                        $record_detail->agent_id         = $res_data['id'];
                        $record_detail->name             = $agent_name;
                        $record_detail->description      = $agent_name;
                        $record_detail->install_code     = $res_data['installCode'];
                        $record_detail->used             = 0;
                        $record_detail->save();
                    }
                }
                return response()->json(['status' => 'true'], 200);
            } else {
                return response()->json(['status' => 'false', 'response_msg' => 'Cannot save.'], 500);
            }
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'response_msg' => $e->getMessage()], 500);
    }
}

// agent details page index deatils  
public function agentDetails_index($id)
{
    if(!$id){
        return response()->json(['data'=>'the id is not found',404]);
    }
    $data = [
        'heading'     => 'View Agents Details',
        'module'      => 'Agents',
        'id'          => $id
    ];
   return response()->json(['data' =>$data,]);
 
}

public function getAgentDetails(Request  $request)
{
    $record           = CompanyAgentDetail::with('company')->select('*');
    $seacrh_name      = $request->get('seacrh_name');
    $company_agent_id = $request->get('company_agent_id');

    $record->where('company_agent_id',$company_agent_id);

    $record->when($seacrh_name, function ($query, $seacrh_name) {
        $query->whereHas('company', function($q) use ($seacrh_name) {
            $q->where('company_name', 'like', "%{$seacrh_name}%");
        });
    });
    $data = $record->get();
    $data_table =Datatables::of($data)
    
        ->addIndexColumn()
                ->addColumn('action', function($row){
                    $clientCount =  0;
                    $actionBtn = '';
                   
                    $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger deleteAgent" data-id="'.$row->id.'" title="Click to Remove"><i class="fa fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->addColumn('company_name', function($row){
                    if(isset($row->company) &&  $row->company->company_name){
                        return  $row->company->company_name;
                    } else {
                        return  '';
                    }
                })
                ->addColumn('used', function($row){
                    if($row->used == 1){
                        return  '<span class="badge bg-success">Yes</span>';
                    } else {
                        return  '<span class="badge bg-danger">No</span>';
                    }
                })
                ->rawColumns(['action','used'])
                ->make(true);

            return response()->json(['data_table'=>$data_table]);   
        
}
    

// delete agent api 
public function deleteAgent(Request $request){
    $request->validate([
        'agent_detail_id'   => 'required|string',
        'company_agent_id'  => 'required|string',
    ]);
    if ($request->has('agent_detail_id') && $request->has('company_agent_id')) {

        // Fetch the company agent details by ID
        $com_agent_details = CompanyAgentDetail::where('_id', $request->agent_detail_id)->first();

        if ($com_agent_details && $com_agent_details->agent_id) {
            
            
            $device = Device::where('agent_id', $com_agent_details->agent_id)->first();
            
            
            $data_curl = [
                'agent_id' => $com_agent_details->agent_id,
            ];

            
            $res_api_data = $this->removeDwsAgent($data_curl);
            $res_api_data = json_decode($res_api_data, true);

            if ($res_api_data['status'] == '200') {
                // If successful, remove agent details from the device
                if ($device) {
                    $device->agent_id = null;
                    $device->install_code = null;
                    $device->save();
                }

                // Delete the agent details record
                CompanyAgentDetail::where('_id', $request->agent_detail_id)->delete();

                // Check the number of agents remaining for the company
                $com_agent_details_cnt = CompanyAgentDetail::where('company_agent_id', $request->company_agent_id)->count();

                if ($com_agent_details_cnt > 0) {
                    // If there are remaining agents, no need to redirect
                    return response()->json([
                        'status'              => 'true',
                        'response_msg'        => 'The agent has been deleted successfully.',
                        'redirect_main_screen' => 'false'
                    ]);
                } else {
                    // If no more agents, delete the main company agent record
                    CompanyAgent::where('_id', $request->company_agent_id)->delete();
                    return response()->json([
                        'status'              => 'true',
                        'response_msg'        => 'The agent has been deleted successfully.',
                        'redirect_main_screen' => 'true'
                    ]);
                }
            } else {
                // Handle the case where the external API returns an error
                return response()->json([
                    'status'        => 'false',
                    'response_msg'  => $res_api_data['message']
                ]);
            }
        } else {
            // If agent is not found
            return response()->json([
                'status'        => 'false',
                'response_msg'  => "Agent not found."
            ]);
        }

}

}

// active inactive agent check 
public function activeInactiveAgent(Request $request){
    $record = CompanyAgent::where('_id',$request->id)->first();
    if($record){

        $status = 1;
        if($record->status == 1){
            $status = 0;
        } else {
            $status = 1;
        }

        $record->status = $status;
        $record->save();
        return response()->json([ 
            'status' => 1,
            'response_msg' => 'Agent status updated successfully.',
        ]);
    }
    else {
        return response()->json(['status'=> 0]);
    }

}
// allow agent  modal 
public function saveAllowAgents(Request $request){
    if($request->has('allow_agent') && $request->allow_agent){

        // CALL API Add Single Curl
         $data_single_curl = [
            'agents'        => (int)$request->allow_agent,
            'channelsBasic' => 0
        ];

        $resp = $this->addDwsServices($data_single_curl);
        $resp = json_decode($resp,true);
         // Handle the response from the external API
        if($resp['status'] == '200'){
            return response()->json(['status' => 'true']);
        } else {
            return response()->json(['status' => 'false','response_msg' => $resp['message']]);
        }
    } else {
        return response()->json(['status' => 'false','response_msg' => 'Cannot save.']);
    }
}

}