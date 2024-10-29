<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; 
use App\Models\Cluster;
use App\Models\Company;
use App\Models\Data;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Models\DeviceWarning;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use \DataTables;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        try{
            isCompanyLogin();
            $data = [
                'title'   => 'Home',
                'module'  => "Company",
                'heading' => "Company",
            ];
              Log::info('company list page  accessed by user' ,['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);

            return view('master.company.list', $data);
        }
        catch(Exception  $e){
            Log::error('company list page have some error'.$e->getMessage());
        }

        
    }

    public function listCompany(Request $request)
    {
        if ($request->ajax()) {
            $request->merge(array(
                'start' => (int)$request->input('start'),
                'length' => (int)$request->input('length')
            ));
            $company = Company::with(['companyOwner'=> function($query) {
                $company_admin = \Config::get('constants.roles.Company_Admin');
                // $query->select(['_id','first_name']);
                $query->where(['role_id' => $company_admin,'status' => 1,'is_active' => 1]);
            }])->select('*');
            $seacrh_name = $request->get('seacrh_name');
            if($seacrh_name) {
                $company->where('company_name', 'like', "%{$seacrh_name}%");
            }
            $data = $company;
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $clientCount =  0;
                $actionBtn = '';
                $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" data-company-name="'.$row->company_name.'" data-status="'.$row->status.'" class="btn btn-icon  btn-primary me-2 editCompany" title="Click to edit"><i><i class="fe fe-edit" aria-hidden="true"></i></a>';

                if($row->status == 1){
                    // $actionBtn .= '<a href="javascript:void(0)" title="Click to Inactive" class="activeInactiveCountry btn btn-success btn-sm mr7" data-id="'.$row->id.'"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                    $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-info activeInactiveCompany" data-id="'.$row->id.'" title="Click to Inactive"><i class="fa fa-toggle-on"></i></a>';
                } else {
                    $actionBtn .= '<a href="javascript:void(0)" class="btn btn-icon  btn-danger activeInactiveCompany" data-id="'.$row->id.'" title="Click to Active"><i class="fa fa-toggle-off"></i></a>';
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
            // ->addColumn('system_overview', function($row){
            //     // $counts = (isset($row->cluster) && $row->cluster ? count($row->cluster) : 0);
            //     return '<a href="/system-overview/'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to View System Overview"> <i class="fe fe-eye" aria-hidden="true"></i></a>';
            // })
            ->addColumn('company_login', function($row){
                // $counts = (isset($row->cluster) && $row->cluster ? count($row->cluster) : 0);
                return '<a href="/company/'.$row->id.'/dashboard" class="btn btn-icon  btn-primary me-2" title="Click to View Company"> <i class="fe fe-eye" aria-hidden="true"></i></a>';
            })
            // ->addColumn('charts', function($row){
            //     // $counts = (isset($row->cluster) && $row->cluster ? count($row->cluster) : 0);
            //     return '<a href="/charts-list/'.$row->id.'" class="btn btn-icon  btn-primary me-2" title="Click to View Charts"> <i class="fe fe-eye" aria-hidden="true"></i></a>';
            // })
            ->addColumn('created_at', function($row){
                $startDate = date_create($row->created_at);
                return date_format($startDate,"Y-m-d h:m A");
            })
            ->addColumn('company_owner', function ($row) {
                $owner_name = [];
                $return = '';
                if(count($row->companyOwner) > 0)
                {
                    foreach ($row->companyOwner as $key => $value) {
                        if($key == 0)
                        {
                            $owner_name[] = $value['first_name'].' '.$value['last_name'];
                        }
                    }
                }
                if($owner_name)
                {
                    $return = implode(', ',$owner_name);
                }
                return $return;
            })
            ->orderColumn('company_name', function ($query, $order) {
                $query->orderBy('company_name', $order);
            })
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->rawColumns(['action','status','system_overview','charts','company_login'])
            ->make(true);
        }
    }

    public function save(Request $request)
    {
        try{
            $input = $request->all();
            if(isset($input['company_name']) && $input['company_name']){
                if(isset($input['company_id']) && $input['company_id'])
                {
                    // Update Record
                    $company               = Company::find($input['company_id']);
                    $company->company_name = $input['company_name'] ?? '';
                    $company->status       = (isset($input['status']) && $input['status'] == 1) ? 1 : 0;
                    $company->save();
                    $response['status'] = 1;
                    Log::info('update the company by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                } else {
                    // New Record
                    $company               = new Company();
                    $company->company_name = $input['company_name'] ?? '';
                    $company->status       = (isset($input['status']) && $input['status'] == 1) ? 1 : 0;
                    $company->save();
    
                    $response['status'] = 1;
                    Log::info('save the company by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
                }
                return response()->json($response);
            } else {
                $response['status'] = 0;
                return response()->json($response);
            }
        }
        catch(Exception  $e){
            Log::error('the error occured while saving the record ',  ['error' => $e->getMessage()]);

        }

    }

    public function activeInactiveCompany(Request $request){
        try{
            $company = Company::where('_id',$request->id)->first();

            if($company){
    
                $status = 1;
                if($company->status == 1){
                    $status = 0;
                } else {
                    $status = 1;
                }
    
                $company->status = $status;
                $company->save();
              Log::info('activeinactiveCompanies  by user',['status'=>$status,'ip_address' => $request->ip(),
              'user_id' => auth()->user()->email]);

                return $status;
            } else {
                return '0';
            }
        }
        catch(Exception $e){
            Log::error('the error occured while activeInactive Company ',  ['error' => $e->getMessage()]);

        }
      
	}

// =============== Start Company Login =========================//

    public function companyLogin(Request $request ,$companyId,$id='')
    {
        try{
            $company = Company::find($companyId);
            if(!$company)
            {
                abort(404);
            }
            companyLoginByAdmin($companyId);
            $filterMacIds = [];
            if($id){
                $filterMacIds = getFilterMacIds($id);
            }
            $connected_minits   =  \Config::get('constants.CONNECTED_TIME_IN_MINITS');
            $comAdminRoleId     = \Config::get('constants.roles.Company_Admin');
            $macids             = Device::where(['company_id' => $company->id])->pluck('macid')->toArray();
            if($filterMacIds){
                $device_notification = DeviceNotification::whereIn('macid', $filterMacIds)->with('notification')->latest()->take(20)->get();
            } else {
                $device_notification = DeviceNotification::whereIn('macid', $macids)->with('notification')->latest()->take(20)->get();
            }

           // $data['user_count']= User::where(['company_id'=>$company->id]);
            $data['admin_role'] =  \Config::get('constants.roles.Master_Admin');
            $data['daily_activities']  = getDailyActivity($company->id,$id);
            $data['device_notification']    = $device_notification;
            $data['group_dropdown']         = Cluster::where(['company_id' => $company->id, 'status' => 1])->get();
            $data['powerbank_dropdown']     = Device::where(['company_id' => $company->id])->get();
            $data['filter_id']              = $id;
            $data['is_company_login']    = 'true';
            Log::info('login to company dashboard by user',['ip_address' => $request->ip(),
                'user_id' => auth()->user()->email]);
            return view('dashboard.user_dashboard',$data);
        }
        catch(Exception $e){
            Log::error('error while company dashboard page',['message'=>$e->getMessage()]);
        }
        
    }

    public function getDashboardPieChart($company_id = '')
    {
        $allMacIds = getDeviceMacids($company_id);
        $total_Gen_Fuel_Utilized = 0;
        $total_saving_doller = 0;
        if($allMacIds){
            foreach ($allMacIds as $key => $macid) {
                // Total Fuel Savings
                $total_saving = Data::whereNotNull('data.data.Gen_Fuel_Utilized(L)')->where('macid',$macid)->orderBy('created_at','desc')->pluck('data.data.Gen_Fuel_Utilized(L)')->first();
                $total_saving_doller = $total_saving_doller + $total_saving;

                // Total Fuel Consumption
                $Gen_Fuel_Utilized = Data::whereNotNull('data.data.Total_saving($)')->where('macid',$macid)->orderBy('created_at','desc')->pluck('data.data.Total_saving($)')->first();
                $total_Gen_Fuel_Utilized = $total_Gen_Fuel_Utilized + $Gen_Fuel_Utilized;

            }
        }
        $total_co2 = (int)$total_Gen_Fuel_Utilized * 2.653;
        $total_saving_doller = (int)$total_saving_doller * config('constants.CONVERT_TO_GALLONS');
        $data = [$total_saving_doller,$total_Gen_Fuel_Utilized,$total_co2];
        return $data;
    }
}
