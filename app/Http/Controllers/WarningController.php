<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Device;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use \DataTables;
use Illuminate\Support\Facades\Log;


class WarningController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function list()
    {
        try{
            $company_login_id = session()->get('company_login_id');
            $company_id = auth()->guard('admin')->user()->company_id;
            if($company_login_id)
            {
                $company_id = $company_login_id;
            }
            $data = [
                'title'   => 'Home',
                'module'  => "Warning",
                'heading' => "Warning",
                'devices' => Device::where(['company_id' => $company_id])->get()
            ];
              Log::info('warning list page retrived successfullly by user', [  'ip_address' => $request->ip(),
              'user_id' => auth()->user()->email]);
    
            return view('warning.list',$data);
        }
        catch(Exception  $e){
            Log::error('error occurs warning list page',  $e->getMessage());
        }

   

    }

    // public function listWarning(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $request->merge(array(
    //             'start' => (int)$request->input('start'),
    //             'length' => (int)$request->input('length')
    //         ));
    //         $Record = Warning::with(['device'])->select('*');
    //         $seacrh_name = $request->get('seacrh_name');

    //         if($seacrh_name) {
    //             $Record->where('title', 'like', "%{$seacrh_name}%")->orWhereHas('device', function ($query) use ($seacrh_name) {
    //                 $query->where('name', 'like', "%$seacrh_name%");
    //             })->orWhere('error_code', 'LIKE', '%' . $seacrh_name . '%')->orWhere('message', 'LIKE', '%' . $seacrh_name . '%');
    //         }
    //         $data = $Record;
    //         return Datatables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('action', function($row){
    //             $clientCount =  0;
    //             $actionBtn = '';
    //             if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
    //             {
    //                     $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon  btn-primary me-2 editWarning" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
    //                     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger removeWarning" data-id="'.$row->id.'" title="Click to Remove"><i class="fe fe-trash"></i></a>';
    //             } else {
    //                 if (Gate::allows('NotificationMessageEdit')) {
    //                     if($row->created_by == auth()->guard('admin')->user()->id)
    //                     {
    //                         $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon  btn-primary me-2 editWarning" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
    //                         $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger removeWarning" data-id="'.$row->id.'" title="Click to Remove"><i class="fe fe-trash"></i></a>';
    //                     }
    //                 }
    //             }
    //             // if($row->status == 1){
    //             //     // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveCountry btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
    //             // } else {
    //             //     $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveWarning" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
    //             // }
    //             return $actionBtn;
    //         })
    //         ->addColumn('status', function($row){
    //             if($row->status == 1){
    //                 return  '<span class="badge bg-success">Active</span>';
    //             } else {
    //                 return  '<span class="badge bg-danger">Inactive</span>';
    //             }
    //         })
    //         ->addColumn('created_at', function($row){
    //             $startDate = date_create($row->created_at);
    //             return date_format($startDate,"Y-m-d h:m A");
    //         })
    //         ->addColumn('message_text', function($row){
    //             return mb_strimwidth($row->message, 0, 150, "...");
    //         })
    //         ->addColumn('device_name', function($row){
    //             if(isset($row->device) && $row->device->name){
    //                 return $row->device->name;
    //             } else {
    //                 return '';
    //             }
    //         })
    //         ->addColumn('created_at', function($row){
    //             $startDate = date_create($row->created_at);
    //             return date_format($startDate,"Y-m-d h:m A");
    //         })
    //         ->orderColumn('title', function ($query, $order) {
    //             $query->orderBy('title', $order);
    //         })
    //         ->orderColumn('error_code', function ($query, $order) {
    //             $query->orderBy('error_code', $order);
    //         })
    //         ->orderColumn('created_at', function ($query, $order) {
    //             $query->orderBy('created_at', $order);
    //         })
    //         ->rawColumns(['action','status'])
    //         ->make(true);
    //     }
    // }
    public function listWarning(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            
            $userId = auth()->guard('admin')->user()->_id; // Get the authenticated user's ID
            $Record = Warning::with(['device'])->select('*');
            $search_name = $request->get('search_name');
    
            // Apply search filter if a search name is provided
            if ($search_name) {
                $Record->where('title', 'like', "%{$search_name}%")
                    ->orWhereHas('device', function ($query) use ($search_name) {
                        $query->where('name', 'like', "%$search_name%");
                    })
                    ->orWhere('error_code', 'LIKE', '%' . $search_name . '%')
                    ->orWhere('message', 'LIKE', '%' . $search_name . '%');
            }
    
            // Fetch only warnings associated with the user's devices
            $Record->whereHas('device', function ($query) use ($userId) {
                $query->where('user_id_str', $userId); // Filter devices by user_id
            });
    
            $data = $Record;
    
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '';
                    if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin')) {
                        $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon btn-primary me-2 editWarning" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';
                    } else {
                        if (Gate::allows('NotificationMessageEdit') && $row->created_by == auth()->guard('admin')->user()->id) {
                            $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon btn-primary me-2 editWarning" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';
                            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger removeWarning" data-id="'.$row->id.'" title="Click to Remove"><i class="fe fe-trash"></i></a>';
                        }
                    }
                    return $actionBtn;
                })
                ->addColumn('status', function($row){
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('created_at', function($row){
                    return date_format(date_create($row->created_at), "Y-m-d h:i A"); // Corrected h:m to h:i
                })
                ->addColumn('message_text', function($row){
                    return mb_strimwidth($row->message, 0, 150, "...");
                })
                ->addColumn('device_name', function($row){
                    return isset($row->device) && $row->device->name ? $row->device->name : '';
                })
                ->orderColumn('title', function ($query, $order) {
                    $query->orderBy('title', $order);
                })
                ->orderColumn('error_code', function ($query, $order) {
                    $query->orderBy('error_code', $order);
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }
    
    public function saveWarning(Request $request)
    {
        try{
            $input = $request->all();
            if(isset($input['error_code']) && $input['error_code'] && isset($input['title']) && $input['title'] && isset($input['message']) && $input['message']){
                if(isset($input['id']) && $input['id'])
                {
                    // Update Record
                    $exits = Warning::where('error_code',$input['error_code'])->where('_id','!=',$input['id'])->first();
                    if($exits)
                    {
                        $response['status'] = 2; //exits code
                    } else {
                        $Record               = Warning::find($input['id']);
                        $Record->device_id    = (isset($input['device_id']) && $input['device_id']) ? $input['device_id'] : '';
                        $Record->error_code   = (isset($input['error_code']) && $input['error_code']) ? $input['error_code'] : '';
                        $Record->title        = (isset($input['title']) && $input['title']) ? $input['title'] : '';
                        $Record->message      = (isset($input['message']) && $input['message']) ? $input['message'] : '';
                        $Record->status       = (isset($input['status']) && $input['status'] == 1) ? 1 : 1;
                        $Record->save();
                        $response['status'] = 1;
                        Log::info('update warning record by user', [       'ip_address' => $request->ip(),
                        'user_id' => auth()->user()->email]);
                    }
                } else {
    
                    // New Record
                    $exits = Warning::where('error_code',$input['error_code'])->first();
                    if($exits)
                    {
                        $response['status'] = 2; //exits code
                    } else {
                        $company_login_id = session()->get('company_login_id');
    
                        $Record             = new Warning();
                        $Record->device_id  = (isset($input['device_id']) && $input['device_id']) ? $input['device_id'] : '';
                        if(auth()->guard('admin')->user()->role_id == \Config::get('constants.roles.Master_Admin'))
                        {
                            if($company_login_id)
                            {
                                $Record->type       = 'admin';
                                $Record->company_id = $company_login_id;
                            } else {
                                $Record->type         = 'user';
                                $Record->company_id = '';
                            }
                        } else {
                            $Record->type         = 'user';
                            $Record->company_id   = auth()->guard('admin')->user()->company_id;
                        }
                        $Record->error_code = (isset($input['error_code']) && $input['error_code']) ? $input['error_code'] : '';
                        $Record->title      = (isset($input['title']) && $input['title']) ? $input['title'] : '';
                        $Record->message    = (isset($input['message']) && $input['message']) ? $input['message'] : '';
                        $Record->status     = (isset($input['status']) && $input['status'] == 1) ? 1 : 1;
                        $Record->created_by = auth()->guard('admin')->user()->id;
    
                        $Record->save();
                         Log::info('add new warning record by user',[       'ip_address' => $request->ip(),
                         'user_id' => auth()->user()->email]);
                        $response['status'] = 1;
                    }
                }
                return response()->json($response);
            } else {
                $response['status'] = 0;
                Log::info('no record is found');
                return response()->json($response);
            }
        }
     catch(Expection  $e){
        Log::error('Error Occurs wjile saveing the warning by user '[$e->getMessage()]);
     }

    }

    public function deleteWarning(Request $request){
        try{
            $deletd = Warning::where('_id',$request->id)->delete();

            if($deletd){
                Log::info('warning delete by user' ,[       'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                return '1';
            } else {
                return '0';
            }
        }
        catch(Expection   $e){
          Log::error('Error Occurs while deleteing the warning by user '[$e->getMessage]);
        
    }
}
}
