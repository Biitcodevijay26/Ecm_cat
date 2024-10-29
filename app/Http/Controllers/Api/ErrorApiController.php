<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Device;
use App\Models\Error;
use App\Models\Warning;
use App\Models\Inverter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JwtHelper;
use Illuminate\Support\Facades\Gate;

class ErrorApiController extends Controller
{
    //
    // public function saveError(Request $request)
    // {
    //     // Fetch user data from the JWT token using JwtHelper
    //     $userData = JwtHelper::getUserData();
    
    //     if (!$userData) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid token or user not authenticated'
    //         ], 401);
    //     }
    
    //     // Extract role, company_id, and user_id from the JWT token payload
    //     $role_id = $userData['role_id'];
    //     $company_id = $userData['company_id'];
    //     $user_id = $userData['user_id'];
    
    //     $input = $request->all();
    
    //     // Check if the required 
    //     if (isset($input['error_code']) && $input['error_code'] && isset($input['title']) && $input['title'] && isset($input['message']) && $input['message']) {
            
    //         // 
    //         if (isset($input['error_id']) && $input['error_id']) {
    //             // Update Record
    //             $exists = Error::where('error_code', $input['error_code'])
    //                            ->where('_id', '!=', $input['error_id'])
    //                            ->first();
    //             if ($exists) {
    //                 $response['status'] = 2; // Error code already exists
    //             } else {
    //                 $Record = Error::find($input['error_id']);
    //                 $Record->device_id  = $input['error_device_id'] ?? null;
    //                 $Record->error_code = $input['error_code'] ?? '';
    //                 $Record->title      = $input['title'] ?? '';
    //                 $Record->message    = $input['message'] ?? '';
    //                 $Record->status     = $input['status'] == 1 ? 1 : 0;
    //                 $Record->save();
    //                 $response['status'] = 1; // Record updated successfully
    //             }
    //         } else {
    //             // New Record inserted here 
    //             $exists = Error::where('error_code', $input['error_code'])->first();
    //             if ($exists) {
    //                 $response['status'] = 2; // Error code already exists in databased
    //                 return response()->json(['data'=>$response,'message'=>'Error code already exists']);
    //             } else {
    //                 $Record = new Error();
    //                 $Record->device_id  = $input['error_device_id'] ?? null;
    
    //                 // If user is a Master_Admin, set company_id from the JWT token
    //                 if ($role_id == \Config::get('constants.roles.Master_Admin')) {
    //                     $Record->type = 'admin';
    //                     $Record->company_id = $company_id;
    //                 } else {
    //                     $Record->type = 'user';
    //                     $Record->company_id = $company_id;
    //                 }
    
    //                 $Record->error_code = $input['error_code'] ?? '';
    //                 $Record->title      = $input['title'] ?? '';
    //                 $Record->message    = $input['message'] ?? '';
    //                 $Record->status     = $input['status'] == 1 ? 1 : 0;
    //                 $Record->created_by = $user_id; // Set the created_by field from JWT user_id
    //                 $Record->save();
    
    //                 $response['status'] = 1; // Record created successfully
    //             }
    //         }
    //         return response()->json([$response, 'message'=>'record inserted successfully']);
    //     } else {
    //         // If required fields are missing
    //         $response['status'] = 0;
    //         return response()->json([$response,'data'=>'required fields are missing']);
    //     }
    // }
    
    public function saveErrorApi(Request $request)
{
    // Validate the request inputs
    $validatedData = $request->validate([
        'error_code' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'error_device_id' => 'nullable|string',
        'status' => 'nullable|integer'
    ]);

    $input = $request->all();

    if (isset($input['error_id']) && $input['error_id']) {
        // Update Record
        $exists = Error::where('error_code', $input['error_code'])
            ->where('_id', '!=', $input['error_id'])
            ->first();

        if ($exists) {
            return response()->json(['status' => 2, 'message' => 'Error code already exists'], 409); // Conflict
        } else {
            $record = Error::find($input['error_id']);
            $record->device_id = $input['error_device_id'] ?? null;
            $record->error_code = $input['error_code'];
            $record->title = $input['title'];
            $record->message = $input['message'];
            $record->status = $input['status'] == 1 ? 1 : 0;
            $record->save();

            return response()->json(['status' => 1, 'message' => 'Error updated successfully'], 200); // OK
        }
    } else {
        // New Record
        $exists = Error::where('error_code', $input['error_code'])->first();

        if ($exists) {
            return response()->json(['status' => 2, 'message' => 'Error code already exists'], 409); // Conflict
        } else {
            $user = auth()->guard('api')->user(); // Changed to API guard

            $record = new Error();
            $record->device_id = $input['error_device_id'] ?? null;

            // Handling user roles and company_id
            if ($user->role_id == \Config::get('constants.roles.Master_Admin')) {
                $record->type = 'admin';
                $record->company_id = $user->company_id ?? null;
            } else {
                $record->type = 'user';
                $record->company_id = $user->company_id;
            }

            $record->error_code = $input['error_code'];
            $record->title = $input['title'];
            $record->message = $input['message'];
            $record->status = $input['status'] == 1 ? 1 : 0;
            $record->created_by = $user->id;

            $record->save();

            return response()->json(['status' => 1, 'message' => 'Error created successfully'], 201); // Created
        }
    }
}


    // api for getting the error values 

    public function listError(Request $request)
    {
        // Fetch user data from the JWT token using JwtHelper
        $userData = JwtHelper::getUserData();
    
        if (!$userData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or user not authenticated'
            ], 401);
        }
    
        // Extract role_id and user_id from the JWT token payload
        $role_id = $userData['role_id'];
        $user_id = $userData['user_id'];
    
        // Sanitize and prepare pagination values
        $start = (int)$request->input('start', 0);
        $length = (int)$request->input('length', 10);
    
        // Prepare the query
        $Record = Error::with(['device']);
        $search_name = $request->get('search_name');
    
        // Apply search filter
        if ($search_name) {
            $Record->where('title', 'like', "%{$search_name}%")
                ->orWhereHas('device', function ($query) use ($search_name) {
                    $query->where('name', 'like', "%{$search_name}%");
                })
                ->orWhere('error_code', 'LIKE', "%{$search_name}%")
                ->orWhere('message', 'LIKE', "%{$search_name}%");
        }
    
        // Apply pagination
        $totalRecords = $Record->count();
        $errors = $Record->skip($start)->take($length)->get();
    
        // Process the records for the JSON response
        $data = [];
        foreach ($errors as $row) {
            $actionBtn = '';
    
            // Role-based action buttons
    
    
            // Process the row data
            $data[] = [
                'id' => $row->id,
                'title' => $row->title,
                'error_code' => $row->error_code,
                'message' => mb_strimwidth($row->message, 0, 150, "..."),
                'device_name' => $row->device->name ?? '',
                'status' => $row->status == 1 ? 'Active' : 'Inactive',
                'created_at' => date_format(date_create($row->created_at), "Y-m-d h:i A"),
                'action' =>  $this->getActionButtons($row)
            ];
        }
    
        // Return JSON response
        return response()->json([
            'success' => true,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }


    // Helper function to generate action buttons
    private function getActionButtons($row)
    {
        $actionBtn = '';
        $user = auth()->guard('api')->user();  // Changed to API guard
    
        if ($user->role_id == \Config::get('constants.roles.Master_Admin')) {
            $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon btn-primary me-2 editWarning" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';
            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger removeWarning" data-id="'.$row->id.'" title="Click to Remove"><i class="fe fe-trash"></i></a>';
        } else if (Gate::allows('NotificationMessageEdit') && $row->created_by == $user->id) {
            $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-title="'.$row->title.'" data-error-code="'.$row->error_code.'" data-message="'.$row->message.'" data-status="'.$row->status.'" data-device_id="'.$row->device_id.'" class="btn btn-icon btn-primary me-2 editWarning" title="Click to edit"><i class="fe fe-edit" aria-hidden="true"></i></a>';
            $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon btn-danger removeWarning" data-id="'.$row->id.'" title="Click to Remove"><i class="fe fe-trash"></i></a>';
        }
    
        return $actionBtn;
    }


    // list notifications 
    public function listMessage(Request $request, $companyid=''){
        $userData= JwtHelper::getUserData();
        if(!$userData){
            return response()->json(['message'=>'unauthorized data',400]);
        }
      //  $company_id=  $companyid;
      $company_id=auth('api')->user()->company_id;

    // $company_login_id = session()->get('company_login_id');
     //   $company_id       = auth('api')->user()->company_id;
        // if($company_login_id)
        // {
        //     $company_id = $company_login_id;
        // }
        $devices= Device::where('company_id', $company_id)->get();

        $data = [
            'title'   => 'Home',
            'module'  => "Notification Message",
            'heading' => "Notification Message",
            'devices' => $devices,
            'company_id'=>$userData['company_id']
        ];
        return response()->json(['data'=>$data]);
       // return response()->json(['companyId' => $companyid]);

    }

    // delete the error 
    public function deleteErrorApi(Request $request)
{
    // Validate the 'id' field
    $request->validate([
        'id' => 'required|exists:error,_id' // Ensures the id exists in the errors collection
    ]);

   
    $deleted = Error::where('_id', $request->id)->delete();

    if ($deleted) {
        // Return success response if the record is deleted
        return response()->json([
            'status' => 'success',
            'message' => 'Error deleted successfully',
            'id' => $request->id
        ], 200); // HTTP 200 OK
    } else {
        // Return failure response if deletion fails
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete error'
        ], 500); 
    }
}
    
}
