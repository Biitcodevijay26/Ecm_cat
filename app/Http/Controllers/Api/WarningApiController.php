<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Device;
use App\Models\Warning;
use Gate;

class WarningApiController extends Controller
{
    // 

     //save the warning controller data from /notification-message

     public function saveWarning(Request $request)
     {
         // Fetch user data from the JWT token using JwtHelper
         $userData = JwtHelper::getUserData();
     
         if (!$userData) {
             return response()->json([
                 'success' => false,
                 'message' => 'Invalid token or user not authenticated'
             ], 401);
         }
     
         // Extract user role and company ID from JWT token payload
         $role_id = $userData['role_id'];
         $company_id = $userData['company_id'];
         $user_id = $userData['user_id'];
     
         $input = $request->all();
     
         // Check if required fields are present
         if (isset($input['error_code']) && !empty($input['error_code']) && 
             isset($input['title']) && !empty($input['title']) && 
             isset($input['message']) && !empty($input['message'])) {
             
             try {
                 if (isset($input['id']) && !empty($input['id'])) {
                     // Update Record
                     $exists = Warning::where('error_code', $input['error_code'])
                                      ->where('_id', '!=', $input['id'])
                                      ->first();
                     if ($exists) {
                         return response()->json([ 'message'=> 'record with same error code exists' ,'status' => 2,]); // Record with the same error code exists
                     }
     
                     $Record = Warning::find($input['id']);
                     if (!$Record) {
                         return response()->json(['status' => 0, 'message' => 'Record not found'], 404);
                     }
     
                     $Record->device_id  = $input['device_id'] ?? '';
                     $Record->error_code = $input['error_code'];
                     $Record->title      = $input['title'];
                     $Record->message    = $input['message'];
                     $Record->status     = $input['status'] ?? 1; // Default to 1
                     $Record->save();
     
                     return response()->json(['status' => 1, 'message'=>'recodrd updates'], 200); // Record updated successfully
                 } else {
                     // Create New Record
                     $exists = Warning::where('error_code', $input['error_code'])->first();
                     if ($exists) {
                         return response()->json(['status' => 2], 200); // Record with the same error code exists
                     }
     
                     $Record = new Warning();
                     $Record->device_id  = $input['device_id'] ?? '';
                     $Record->type       = $role_id == \Config::get('constants.roles.Master_Admin') ? 'admin' : 'user';
                     $Record->company_id = $company_id;
                     $Record->error_code = $input['error_code'];
                     $Record->title      = $input['title'];
                     $Record->message    = $input['message'];
                     $Record->status     = $input['status'] ?? 1; // Default to 1
                     $Record->created_by = $user_id;
                     $Record->save();
     
                     return response()->json(['status' => 1,'message'=>'the new record inserted '], 201); // Record created successfully
                 }
             } catch (\Exception $e) {
                 \Log::error('Error saving warning', ['input' => $input, 'error' => $e]);
                 return response()->json(['status' => 0, 'message' => 'An error occurred while saving the warning'], 500);
             }
         } else {
             return response()->json(['status' => 0, 'message' => 'Missing required fields'], 400);
         }
     }
     
 //get the warning records 

 
// get new list 
public function listWarningApi(Request $request)
{
    // Merge 'start' and 'length' into the request as integers
    $request->merge(array(
        'start' => (int)$request->input('start', 0),
        'length' => (int)$request->input('length', 10)
    ));

    // Initialize query
    $Record = Warning::with(['device'])->select('*');
    $seacrh_name = $request->get('seacrh_name');

    // Search functionality
    if ($seacrh_name) {
        $Record->where('title', 'like', "%{$seacrh_name}%")
            ->orWhereHas('device', function ($query) use ($seacrh_name) {
                $query->where('name', 'like', "%$seacrh_name%");
            })
            ->orWhere('error_code', 'LIKE', '%' . $seacrh_name . '%')
            ->orWhere('message', 'LIKE', '%' . $seacrh_name . '%');
    }

    // Pagination
    $totalRecords = $Record->count();
    $warnings = $Record->offset($request->start)->limit($request->length)->get();

    // Prepare data to return
    $data = [];
    foreach ($warnings as $row) {
        $data[] = [
            'id' => $row->id,
            'title' => $row->title,
            'error_code' => $row->error_code,
            'message' => mb_strimwidth($row->message, 0, 150, "..."),
            'status' => $row->status == 1 ? 'Active' : 'Inactive',
            'device_name' => isset($row->device->name) ? $row->device->name : '',
            'created_at' => date_format(date_create($row->created_at), "Y-m-d h:m A"),
            'action' => $this->getActionButtons($row)
        ];
    }

    // Return the JSON response
    return response()->json([
        'draw' => $request->input('draw'),
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

// delete function 
public function deleteWarning(Request $request){
    $userData=JwtHelper::getUserData();
    if(!$userData){
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $request->validate([
        'id' => 'required|exists:warning,_id' // Ensures the id exists in the warning collection
    ]);

   $deleted = Warning::where('_id', $request->id)->delete();

    if ($deleted) {
        // Return success response if deleted
        return response()->json([
            'status' => 'success',
            'message' => 'Warning deleted successfully',
            'id' => $request->id
        ], 200);
    } else {
        // Return failure response if not deleted
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete warning'
        ], 500);
    }
}



} 
