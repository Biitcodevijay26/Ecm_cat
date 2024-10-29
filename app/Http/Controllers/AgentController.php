<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Models\Company;
use App\Models\CompanyAgent;
use App\Models\CompanyAgentDetail;
use App\Models\Device;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log;
class AgentController extends Controller
{
    use UserTrait;

    // Agent List
    public function list(Request $request){
        try{
            Log::info('Agent page  method called.', [
                'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email
            ]);

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
    
            Log::info('Agent page retrieved successfully.', [
                'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email 
            ]);
            return view('agent/list',$data);
        }
        catch  (\Exception $e) {
            Log::error('Error in agent list by user : ' . $e->getMessage());
        }

    	
    }

    public function getAgentsList(Request $request){
        if ($request->ajax()) {

            $record         = CompanyAgent::with('company')->select('*');
            $seacrh_name    = $request->get('seacrh_name');

            $record->when($seacrh_name, function ($query, $seacrh_name) {
                $query->where('company_id', $seacrh_name);
            });

            $data = $record->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $clientCount =  0;
                    $actionBtn = '';
                    // $actionBtn .= '<a href="/agent-edit/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2 editCompany" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                    $actionBtn .= '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to view"><i><i class="fe fe-eye" aria-hidden="true"></i></a>';
                    if($row->status == 1){
                        // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                    } else {
                        // $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                    }
                    return $actionBtn;
                })
                ->addColumn('company_name', function($row){
                    if(isset($row->company) &&  $row->company->company_name){
                        return  $row->company->company_name;
                    } else {
                        return  '';
                    }
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
                    return date_format($startDate,"Y-m-d H:m A");
                })
                ->rawColumns(['action','status'])
                ->make(true);

        }
    }

    public function agentAdd(Request $request) {
        try{

        
        $company_list = Company::where('status',1)->get();
        $agentInfo = $this->getDwsServicesInfo();
        $dwsData         = [];
        $available_agent = '';
        if($agentInfo){
            $agentInfo = json_decode($agentInfo,true);
            if($agentInfo['status'] == '200'){
                $dwsData = json_decode($agentInfo['data'],true);
                $available_agent = $dwsData['agentsAllowed'] - $dwsData['agentsInstalled'];
                $available_agent = (int)$available_agent;
            } else {
                $dwsError = $agentInfo['message'];
            }
        }
        if($available_agent && $available_agent > 0){
            $data = [
                'heading'     => 'Add Agents',
                'module'      => 'Agents',
                'getCompany'  => $company_list,
                'available_agent' => $available_agent
            ];
         
            Log::info('Agent Add page retrieved successfully.', [
                'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email 
            ]);
            return view('agent/add_edit_agent',$data);
        } else {
            return redirect('agent');
        }
    }
    catch(\Exception $e){
        Log::error('Error in agentAdd method.', $e->getMessage());

    }
}

    public function agentEdit($id = '') {
        try{

            $dataOne = CompanyAgent::where('_id',$id)->first();
            if(!$dataOne){
                abort(404);
            }
            $company_list = Company::where('status',1)->get();
            $agentInfo = $this->getDwsServicesInfo();
            $dwsData         = [];
            $available_agent = '';
            if($agentInfo){
                $agentInfo = json_decode($agentInfo,true);
                if($agentInfo['status'] == '200'){
                    $dwsData = json_decode($agentInfo['data'],true);
                    $available_agent = $dwsData['agentsAllowed'] - $dwsData['agentsInstalled'];
                    $available_agent = (int)$available_agent;
                } else {
                    $dwsError = $agentInfo['message'];
                }
            }
            $data = [
                'heading'     => 'Edit Agents',
                'module'      => 'Agents',
                'getCompany'  => $company_list,
                'data'        => $dataOne,
                'available_agent' => $available_agent
            ];
            Log::info('AgentEdit page called Successfully', [
                'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email
            ]);
        }
        catch(\Execption $e){
            Log::error('Error in agentEdit method.', $e->getMessage());
        }
     
    }

    public function agentView($id=''){
        try{
        if(!$id){
            abort(404);
        }

        $data = [
            'heading'     => 'View Agents Details',
            'module'      => 'Agents',
            'id'          => $id
        ];
        Log::info('agentview page successfuly open by user ', [  'ip_address' => $request->ip(),
        'user_id' => auth()->user()->email]);

        return view('agent/view_agent',$data);
    }
    catch(\Exception $e){
        Log::error('error  in agentView method', $e->getMessage());

    }
}

    // public function getAgentDetails(Request $request){
    //     if ($request->ajax()) {

    //         $record           = CompanyAgentDetail::with('company')->select('*');
    //        // $seacrh_name      = $request->get('seacrh_name');
    //         $company_agent_id = $request->get('company_agent_id');

    //         $record->where('company_agent_id',$company_agent_id);

    //         $record->when($seacrh_name, function ($query, $seacrh_name) {
    //             $query->whereHas('company', function($q) use ($seacrh_name) {
    //                 $q->where('company_name', 'like', "%{$seacrh_name}%");
    //             });
    //         });

    //         $data = $record->get();
          //  return Datatables::of($data)
                // ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $clientCount =  0;
                //     $actionBtn = '';
                //     // $actionBtn .= '<a href="/agent-edit/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2 editCompany" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                //     // $actionBtn .= '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to view"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                //     // if($row->status == 1){
                //     //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                //     // } else {
                //     //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                //     // }
                //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger deleteAgent" data-id="'.$row->id.'" title="Click to Remove"><i class="fa fa-trash"></i></a>';
                //     return $actionBtn;
                // })
                // ->addColumn('company_name', function($row){
                //     if(isset($row->company) &&  $row->company->company_name){
                //         return  $row->company->company_name;
                //     } else {
                //         return  '';
                //     }
                // })
                // ->addColumn('used', function($row){
                //     if($row->used == 1){
                //         return  '<span class="badge bg-success">Yes</span>';
                //     } else {
                //         return  '<span class="badge bg-danger">No</span>';
                //     }
                // })
                // ->rawColumns(['action','used'])
                // ->make(true);

    //     }
    // }
    // the running code with search name and company _id 
    // public function getAgentDetails(Request $request) {
    //     if ($request->ajax()) {
    //         $record = CompanyAgentDetail::with('company')->select('*');
            
    //         // Capture company_agent_id from request
    //         $company_agent_id = $request->get('company_agent_id');
            
    //         // Apply condition on company_agent_id
    //         $record->where('company_agent_id', $company_agent_id);
            
    //         // Capture search name from request
    //        $search_name = $request->get('search_name'); // Make sure frontend sends this value
    
    //         // If search name is present, filter the query
    //         $record->when($search_name, function ($query, $search_name) {
    //             $query->whereHas('company', function($q) use ($search_name) {
    //                 $q->where('company_name', 'like', "%{$search_name}%");
    //             });
    //         });
    
    //         // Execute the query and get results
    //         $data = $record->get();
    
    //         // Return data as JSON with Datatables formatting
    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function($row){
    //                 $actionBtn = '<a href="javascript:void(0)" class="btn btn-icon btn-danger deleteAgent" data-id="'.$row->id.'" title="Click to Remove"><i class="fa fa-trash"></i></a>';
    //                 return $actionBtn;
    //             })
    //             ->addColumn('company_name', function($row){
    //                 return isset($row->company->company_name) ? $row->company->company_name : '';
    //             })
    //             ->addColumn('used', function($row){
    //                 return $row->used == 1 
    //                     ? '<span class="badge bg-success">Yes</span>' 
    //                     : '<span class="badge bg-danger">No</span>';
    //             })
    //             ->rawColumns(['action', 'used'])
    //             ->make(true);
    //     } else {
    //         return response()->json(['error' => 'This route only supports Ajax requests'], 400);
    //     }
    // }

    // th ecode with id only 
    public function getAgentDetails(Request $request) {
        if ($request->ajax()) {
          
            $record = CompanyAgentDetail::with('company')->select('*');
    
            // Capture company_agent_id from request
            $company_agent_id = $request->get('company_agent_id');
            if (!empty($company_agent_id)) {
                // Apply condition on company_agent_id
                $record->where('company_agent_id', $company_agent_id);
            } else {
                Log::error('Invalid or missing company_agent_id',['message'=>400]);
                return response()->json(['error' => 'Invalid or missing company_agent_id'], 400);
            }
    
            // Fetch the records
            $data = $record->get();
    
            // Return data as JSON with Datatables formatting
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $clientCount =  0;
                $actionBtn = '';
                // $actionBtn .= '<a href="/agent-edit/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2 editCompany" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                // $actionBtn .= '<a href="/agent-detail-view/'.$row->id.'" data-id="'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to view"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
                // if($row->status == 1){
                //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                // } else {
                //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompanyAgent" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
                // }
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

        } else {
            return response()->json(['error' => 'This route only supports Ajax requests'], 400);
        }
    }
    
    
    
    public function save(Request $request){
        try{
            if($request->has('company_name') && $request->company_name && $request->has('agent') && $request->agent){
                if($request->has('id') && $request->id){
                    // Edit Section
    
                    $record = CompanyAgent::where('_id',$request->id)->first();
                    $record->company_id   = $request->company_name ?? '';
                    $record->agent        = $request->agent ?? '';
                    if($record->save()){
                        Log::info('agent save by user ', [
                            'ip_address' => $request->ip(),
                            'user_id' => auth()->user()->email
                        ]);
    
                        return response()->json(['status' => 'true']);
                    } else {
                        return response()->json(['status' => 'false']);
                    }
                } else {
                    // CALL API Add Single Curl (Code Comment : 07-02-2024)
                    // $data_single_curl = [
                    //     'agents'        => (int)$request->agent,
                    //     'channelsBasic' => 0
                    // ];
    
                    // $resp = $this->addDwsServices($data_single_curl);
                    // $resp = json_decode($resp,true);
                    // if($resp['status'] == '200'){
    
                        // Add Section
                        $mytime   = Carbon::now();
                        $now_time = $mytime->toDateTimeString();
                        $record =  new CompanyAgent();
                        $record->company_id   = $request->company_name ?? '';
                        $record->agent        = $request->agent ?? '';
                        $record->status       = 1;
                        $record->created_at   = $now_time;
                        if($record->save()){
                            $agent_id = $record->_id;
                            if($agent_id){
    
                                $counts     = CompanyAgentDetail::where('company_id',$request->company_name)->count();
                                for ($i = 0; $i < $request->agent; $i++) {
                                    $randomString = Str::random(12);
                                    $counts     = $counts + 1;
                                    $agent_name = getCompanyName($request->company_name).' agent no '.$counts.$randomString;
    
                                    //  CALL API ADD Multiple
                                    $data_multi_curl = [
                                        'name'        => $agent_name,
                                        'description' => $agent_name
                                    ];
                                    $res_api_data = $this->createDwsAgents($data_multi_curl);
                                    $res_api_data = json_decode($res_api_data,true);
    
                                    if($res_api_data['status'] == '200'){
                                        $res_data = json_decode($res_api_data['data'],true);
                                        $record_detail =  new CompanyAgentDetail();
                                        $record_detail->company_id       = $request->company_name ?? '';
                                        $record_detail->company_agent_id = $agent_id ?? '';
                                        $record_detail->agent_id         = $res_data['id'] ?? '';
                                        $record_detail->name             = $agent_name;
                                        $record_detail->description      = $agent_name;
                                        $record_detail->install_code     = $res_data['installCode'] ?? '';
                                        $record_detail->used             = 0;
                                        $record_detail->save();
                                    }
    
                                }
                            }
                            Log::info('Agent are save by user', [
                                'ip_address' => $request->ip(),
                                'user_id' => auth()->user()->email
                            ]);
                            return response()->json(['status' => 'true']);
                        } else {
                            // Log::info('the record is not save',['company agent details' =>$record_detail]);
                            return response()->json(['status' => 'false', 'response_msg' => 'Cannot saved.']);
                        }
                    // } else {
                    //     return response()->json(['status' => 'false','response_msg' => $resp['message']]);
                    // }
                }
            }
        }
        catch(\Exception  $e){

         Log::error('Error occurs  while saving agent details',['error' => $e->getMessage()]);

    }
}

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

			return '1';
		} else {
			return '0';
		}
    }

    // Save Allow Agents From Modal box
    public function saveAllowAgents(Request $request){
        if($request->has('allow_agent') && $request->allow_agent){

            // CALL API Add Single Curl
             $data_single_curl = [
                'agents'        => (int)$request->allow_agent,
                'channelsBasic' => 0
            ];

            $resp = $this->addDwsServices($data_single_curl);
            $resp = json_decode($resp,true);
            if($resp['status'] == '200'){
                return response()->json(['status' => 'true']);
            } else {
                return response()->json(['status' => 'false','response_msg' => $resp['message']]);
            }
        } else {
            return response()->json(['status' => 'false','response_msg' => 'Cannot save.']);
        }
    }

    // Remove Agents
    public function deleteAgents(Request $request){
       try{
        
        if ($request->has('agent_detail_id') && $request->agent_detail_id && $request->has('company_agent_id') && $request->company_agent_id) {

            $com_agent_details = CompanyAgentDetail::where('_id',$request->agent_detail_id)->first();
            $device = Device::where('agent_id',$com_agent_details->agent_id)->first();

            if($com_agent_details && $com_agent_details->agent_id){
                $data_curl = [
                    'agent_id' => $com_agent_details->agent_id,
                ];
                $res_api_data = $this->removeDwsAgent($data_curl);
                $res_api_data = json_decode($res_api_data,true);

                if($res_api_data['status'] == '200'){
                    // Remove AgentId and InstallCode on device.
                    $device = Device::where('agent_id',$com_agent_details->agent_id)->first();
                    if($device){
                        $device->agent_id = null;
                        $device->install_code = null;
                        $device->save();
                    }

                    // Remove Agent details record
                    CompanyAgentDetail::where('_id',$request->agent_detail_id)->delete();

                    // Check No. of agents
                    $com_agent_details_cnt = CompanyAgentDetail::where('company_agent_id',$request->company_agent_id)->count();
                    if($com_agent_details_cnt > 0){
                        // No Activity
                        Log::info('Agent deleted  Successfully by user', [
                            'ip_address' => $request->ip(),
                            'user_id' => auth()->user()->email
                        ]);
                        return response()->json(['status' => 'true','response_msg' => 'The agent has been deleted successfully.','redirect_main_screen' => 'false']);
                    } else {
                        // If no more agent found then remove main records
                        CompanyAgent::where('_id',$request->company_agent_id)->delete();
                        Log::info('Agent deleted  Successfully by user', [
                            'ip_address' => $request->ip(),
                            'user_id' => auth()->user()->email
                        ]);
                        return response()->json(['status' => 'true','response_msg' => 'The agent has been deleted successfully.','redirect_main_screen' => 'true']);
                    }

                } else {
                    return response()->json(['status' => 'false','response_msg' => $res_api_data['message']]);
                }
            } else {
                return response()->json(['status' => 'false','response_msg' => "Agent not found."]);
            }
        }
       }
       catch(\Exception  $e){
        Log::error('while deleting the agent get error',$e->getMessage());
    }
}
}
