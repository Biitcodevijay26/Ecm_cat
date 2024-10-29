<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Helpers\JwtHelper;

use \DataTables;
class CountryApiController extends Controller
{
    //
    public function list(){
        $data = [
            'title'   => 'Home',
            'module'  => "Countries",
            'heading' => "Countries",
        ];
        return response()->json($data);
    }

    // Country list 

    public function listCountries(Request $request){
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $countries = Country::select('*');
        $seacrh_name = $request->get('seacrh_name');
        if($seacrh_name) {
            $countries->where('name', 'like', "%{$seacrh_name}%");
        }
        $data = $countries;
       $country_list=   Datatables::of($data)
       ->addIndexColumn()
       ->addColumn('action', function($row){
           $clientCount =  0;
           $actionBtn = '';
           $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-countries-name="'.$row->name.'" data-countries-code="'.$row->code.'" data-countries-dialcode="'.$row->dial_code.'" data-status="'.$row->status.'" class="btn btn-icon  btn-primary me-2 editCountries" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';
           if($row->status == 1){
               // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveCountry btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
               $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCountries" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
           } else {
               $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCountries" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
           }
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
       ->orderColumn('name', function ($query, $order) {
           $query->orderBy('name', $order);
       })
       ->orderColumn('code', function ($query, $order) {
        $query->orderBy('code', $order);
    })
    ->rawColumns(['action','status'])
    ->make(true);
     
    return response()->json(['country list'=> $country_list]);
    }
//  save the data 
public function saveCountry(Request $request){
    $userdata=JwtHelper::getUserData();
     
    $userId = $userdata['user_id'] ?? null;
    $userRole = $userdata['role'] ?? null;
    if (!$userId) {
       return response()->json([
           'status' => 'error',
           'message' => 'Unauthorized access. Invalid token.'
       ], 401);
   }
      // Validate the request data
    //   $validatedData = $request->validate([
    //     'name' => 'required|string|max:255',
    //     'code' => 'required|string|max:10',
    //     'dial_code' => 'sometimes|string|max:10',
    //     'status' => 'sometimes|boolean',
    //     'country_id' => 'sometimes|integer' // For updating the country record
    // ]);
    $input = $request->all();
    if(isset($input['name']) && $input['name'] && isset($input['code']) && $input['code']){
        if(isset($input['country_id']) && $input['country_id'])
        {

        
        // Update existing country record
        $country = Country::find($input['country_id']);
        if ($country) {
            $country->name = $input['name'] ?? '';
            $country->code = $input['code'] ?? '';
            $country->dial_code = $input['dial_code'] ?? '';
            $country->status = isset($input['status']) && $input['status'] == 1 ? 1 : 0;
            $country->save();

            $response = [
                'status' => 1,
                'message' => 'Country updated successfully'
            ];
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Country not found.'
            ], 404);
        }
    } else {
        // Create a new country record
        $country = new Country();
        $country->name = $input['name'] ?? '';
        $country->code = $input['code'] ?? '';
        $country->dial_code = $input['dial_code'] ?? '';
        $country->status = isset($input['status']) && $input['status'] == 1 ? 1 : 0;
        $country->save();

        $response = [
            'status' => 1,
            'message' => 'Country created successfully'
        ];
    }

    
    return response()->json($response, 200);

} else {
    // If 'name' or 'code' is not provided
    return response()->json([
        'status' => 0,
        'message' => 'Country name and code are required.'
    ], 400);
}

}

//active and inactive 
public function countryStatus(Request $request)
{
    $userData = JwtHelper::getUserData();
  
        $countries = Country::where('_id',$request->id)->first();
        if($countries){

			$status = 1;
			if($countries->status == 1){
				$status = 0;
			} else {
				$status = 1;
			}

			$countries->status = $status;
			$countries->save();
         return  response()->json([  'status' => 1, 'message' => 'Country status updated successfully'],200);           
}
else{
    return response()->json([  'status' => 0, 'message' => 'Country  not found'],400);
}

}

}
