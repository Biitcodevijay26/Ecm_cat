<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \DataTables;

class CountryController extends Controller
{
    public function list(Request $request)
    {
 try{
    $data = [
        'title'   => 'Home',
        'module'  => "Countries",
        'heading' => "Countries",
    ];
    Log::info('the Country page retrived successfully by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
    return view('master.country.list', $data);
 }
 catch (Exception $e) {
    Log::error('Error in Country page retrived by user',['ip_address' => $request->ip(), 'message'=>$e->getMessage()]);
 }
        

    }

    public function listCountries(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $countries = Country::select('*');
            $seacrh_name = $request->get('seacrh_name');

            if($seacrh_name) {
                $countries->where('name', 'like', "%{$seacrh_name}%");
            }
            $data = $countries;
            return Datatables::of($data)
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
        }
    }

    public function save(Request $request)
    {
        try{
            $input = $request->all();
            if(isset($input['name']) && $input['name'] && isset($input['code']) && $input['code']){
                if(isset($input['country_id']) && $input['country_id'])
                {
                    // Update Record
                    $countries               = Country::find($input['country_id']);
                    $countries->name         = $input['name'] ?? '';
                    $countries->code         = $input['code'] ?? '';
                    $countries->dial_code    = $input['dial_code'] ?? '';
                    $countries->status       = (isset($input['status']) && $input['status'] == 1) ? 1 : 0;
                    $countries->save();
                    $response['status'] = 1;
                    Log::info('update the Country by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                } else {
                    // New Record
                    $countries            = new Country();
                    $countries->name      = $input['name'] ?? '';
                    $countries->code      = $input['code'] ?? '';
                    $countries->dial_code = $input['dial_code'] ?? '';
                    $countries->status    = (isset($input['status']) && $input['status'] == 1) ? 1 : 0;
                    $countries->save();
    
                    $response['status'] = 1;
                    Log::info('save new Country record by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                }
                return response()->json($response);
            } else {
                $response['status'] = 0;
                Log::info('not found any record');
                return response()->json($response);
            }
        }
        catch(Exception $e){
            Log::error('the error occured  while saving Country record',['error' => $e->getMessage()]);

        }
       
    }

    public function activeInactiveCountries(Request $request){
        try{
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
                Log::info('activeInactiveCountries record  by user',['status'=>$status, 'ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

                return $status;
            } else {

                return '0';
            }
        }
        catch(Exception $e){
            Log::error('the error occured  while activeInactiveCountries record',['error' => $e->getMessage()]);
        }
        
	}
    
}
