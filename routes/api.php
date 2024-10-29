<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;

use App\Http\Controllers\Api\ChartApiController;
use App\Http\Controllers\Api\ErrorApiController;
use App\Http\Controllers\Api\SystemOverviewApiController;
use App\Http\Controllers\Api\ScreenApiController;
use App\Http\Controllers\Api\WarningApiController;
use App\Http\Controllers\Api\UserApiController;
//use App\Http\Controllers\SyetemOverviewController; 
use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\AgentApiController;
use App\Http\Controllers\Api\ChannelApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group(['middleware'=>'api','prefix'=>'auth'],function($router){ 
//     Route::post('/register',[AuthController::class,'register']);
//     Route::post('/login',[AuthController::class,'login']);
//     Route::post('/logout', [AuthController::class,'logoutsession']);
//     Route::post('/refresh', [AuthController::class,'refresh']);
// });

Route::group(['middleware'=>'multi-guard'],function(){
    Route::post('/test',[AuthApiController::class,'test']);

//AuthApi 
Route::post('/login',[AuthApiController::class,'login']);
Route::post('register',[AuthApiController::class,'register']);
Route::post('/admin/login',[AuthApiController::class,'superAdminLogin']);
Route::get('/logout',[AuthApiController::class, 'logout']);
Route::get('/auth0', [AuthApiController::class, 'auth_login']);
Route::get('/auth0/callback',[AuthApiController::class, 'handleAuth0Callback']);

    //Dashboard api 
    //uderstanding to get info 
  //  Route::get('/dashboard',[DashboardApiController::class,'index1']);
   //   Route::get('/getdata',[DashboardApiController::class,'anotherFunction']);
    //index mentod 
    Route::get('/dashboard/index',[DashboardApiController::class,'apiindex']);
    Route::get('/getalert',[DashboardApiController::class,'getAlerts']);
   Route::get('/getsaving',[DashboardApiController::class,'getMySavings']);
   Route::get('/getgrouplist',[DashboardApiController::class,'getGroupList']);
   //Route::get('/index',[DashboardApiController::class,'index1']);
   Route::post('/getdailyactivity',[DashboardApiController::class,'getDailyActivity']);
   Route::post('/getpowerbanknotification',[DashboardApiController::class,'getPowerBankNotification']);
   Route::get('/get-device-list',[DashboardApiController::class,'getDeviceListApi']);
   Route::post('/get-account-summery',[DashboardApiController::class,'getAccountSummery']);
   Route::post('/get-powerbank-usage-new', [DashboardApiController::class, 'getPowerBankUsageNew']);
    //   Warning Controller

Route::post('/savewarning',[WarningApiController::class,'saveWarning']);
//Route::get('/getwarning',[WarningApiController::class,'listWarning']);
Route::post('/save-errors',[ErrorApiController::class,'saveErrorApi']);
Route::get('/geterrorslist',[ErrorApiController::class,'listError']);
Route::post('/delete-error', [ErrorApiController::class, 'deleteErrorApi']);
// notification tab
// http://127.0.0.1:8000/company/65e0b1e404c5193b7209af33/notification-message
Route::get('/notification-message', [ErrorApiController::class, 'listMessage'])->middleware('can:NotificationMessageList');
Route::get('/company/{companyId}/notification-message', [ErrorApiController::class, 'listMessage'])->middleware('can:isAdmin');
Route::get('/get-list-warning',[WarningApiController::class,'listWarningApi']);
Route::post('/delete-warning', [WarningApiController::class, 'deleteWarning']);
// User Controller api 
 Route::get('/users-list', [UserApiController::class, 'listUsers'])->middleware('can:UserManagementList');
 Route::get('/get-userlists',[UserApiController::class,'getUserlists']);
 Route::post('/active-inactive-users', [UserApiController::class, 'activeInactiveUsers']);
//  new user 
Route::get('/company/{companyId}/new-users-list', [UserApiController::class, 'listNewUsers'])->middleware('can:isAdmin');
Route::get('/new-users-list', [UserApiController::class, 'listNewUsers'])->middleware('can:UserManagementList');
Route::get('/get-new-users-list', [UserApiController::class, 'getListNewUsers']);
Route::post('/active-inactive-new-users', [UserApiController::class, 'activeInactiveUsers']);

Route::get('/company/{companyId}/new-user-edit/{id}', [UserApiController::class, 'getEditNewUser'])->middleware('can:isAdmin');
Route::get('/company/{companyId}/new-user-add', [UserApiController::class, 'addNewUser'])->middleware('can:isAdmin');
Route::post('/new-user-save', [UserApiController::class, 'saveNewUser']);

// Route::post('/user-save', [UsersController::class, 'saveUser']);

 Route::get('/company/{companyId}/user-add', [UserApiController::class, 'addUser'])->middleware('can:isAdmin');
 Route::get('/user-add', [UserApiController::class, 'addUser'])->middleware('can:UsereManagementAdd');
Route::post('/save-user-data',[UserApiController::class,'saveUser']);
 Route::get('/user-edit/{id}', [UserApiController::class, 'editUser'])->middleware('can:UserManagementEdit');
 Route::get('/company/{companyId}/user-edit/{id}', [UserApiController::class, 'editUser'])->middleware('can:isAdmin');
//Route::get('/get-userlists',[UserApiController::class,'getUserlists']);


// SystemOverview Controller 
Route::get('/add-cluster', [SyetemOverviewApiController::class, 'addCluster']);
Route::get('/add-device', [SystemOverviewApiController::class, 'addDevice']);
Route::post('/save-data-device',[SystemOverviewApiController::class,'saveDevice']);
Route::post('/save-cluster', [SystemOverviewApiController::class, 'saveCluster']);
Route::post('/get-tmp-device-list-cluster-wise',[SystemOverviewApiController::class,'getTmpDeviceListClusterWise']);
Route::get('/get-device-details/{id}',[SystemOverviewApiController::class,'deviceDetailPageApi']);
Route::get('/get-battery-details/{id}',[SystemOverviewApiController::class,'batteryDetailPage']);
Route::get('/edit-device/{id}',[SystemOverviewApiController::class,'editDeviepage']);
     Route::get('/charts/{id}', [ChartApiController::class, 'getchartDetails']);
    //  Route::get('/charts/{id}', [ChartApiController::class, 'getchartDetails']);

// chartController
Route::get('/charts-list/{id?}', [ChartApiController::class, 'chartsList'])->middleware('can:ChartList');
Route::get('/get-chart-list',[ChartApiController::class,'getChartsListApi']);
Route::get('/get-chart-list1',[ChartApiController::class,'getChartsList1']);

//Route::post('/save-chart-data',[ChartApiController::class,'savecharts']);
Route::post('/save-chart', [ChartApiController::class, 'saveCharts']);
// edit chart data
Route::get('/edit-charts/{id}', [ChartApiController::class, 'editChartApi'])->middleware('can:ChartEdit');
//Route::post('/update-edit-char/{id}',[ChartApiController::class,'updateeditChart']);
Route::get('/view-chart/{id}',[ChartApiController::class,'viewChartApi'])->middleware('can:ChartView');
// remote access view
Route::get('/remote-access-view/{id}', [ScreenApiController::class, 'index']);


// CompanyApi 
Route::get('get-company-list',[CompanyApiController::class, 'listCompany']);
Route::post('save-company',[CompanyApiController::class, 'saveCompany']);
Route::post('get-active-inactive',[CompanyApiController::class,'getActiveInactive']);

// access the company dashboard 
Route::get('/company/{companyId}/dashboard/{id?}', [CompanyApiController::class, 'companyLogin'])->middleware('can:isAdmin');
Route::post('/company/getdailyactivity',[CompanyApiController::class,'getDailyActivity']);
Route::get('/company/{companyId}/users-list', [UsersController::class, 'listUsers'])->middleware('can:isAdmin');


Route::get('/company/{companyId}/system-overview', [SystemOverviewApiController::class, 'getSystemOverview']);
Route::get('/company/{companyId}/device_details/{id}', [SystemOverviewApiController::class, 'deviceDetailPageApi']);
Route::get('/company/{companyId}/edit-device/{id}', [SystemOverviewApiController::class, 'editDeviepage']);
Route::get('/company/{companyId}/charts/{id}', [ChartApiController::class, 'getchartDetails']);
Route::get('/company/{companyId}/add-cluster', [SystemOverviewApiController::class, 'addCluster']);
Route::get('/company/{companyId}/add-device', [SystemOverviewApiController::class, 'addDevice']);
Route::get('/company/{companyId}/device-alarms-list/{id}', [SystemOverviewApiController::class, 'deviceAlarmsList']);
Route::get('/company/{companyId}/battery_details/{id}', [SystemOverviewApiController::class, 'batteryDetailPage']);
Route::get('/company/{companyId}/charts-list', [ChartApiController::class, 'chartsList']);
Route::get('/company/{companyId}/view-chart/{id}',[ChartApiController::class,'viewChartApi']);
Route::get('/company/{companyId}/edit-chart/{id}', [ChartApiController::class, 'editChartApi']);
Route::get('/company/{companyId}/add-chart', [ChartApiController::class, 'addChart']);
Route::get('/add-chart', [ChartApiController::class, 'addChart']);
//Route::get('/company/{companyId}/view-chart/{id}', [ChartApiController::class, 'viewChart']);
Route::get('/company/{companyId}/remote-access-view/{id}', [ScreenApiController::class, 'index']);
// country api 
Route::get('/countries-list',[CountryApiController::class,'listCountries']);
Route::post('/save-country',[CountryApiController::class,'saveCountry']);
Route::post('/active-inactive-countries', [CountryApiController::class, 'countryStatus']);

// agent api

        Route::get('/agent', [AgentApiController::class, 'index'])->middleware(['can:isAdmin']);
        Route::get('/get-agents', [AgentApiController::class, 'getAgentsList']);
         Route::get('/agent-add', [AgentApiController::class, 'add_agent'])->middleware(['can:isAdmin']);
         Route::post('/save-agents', [AgentApiController::class, 'save']);
        // Route::get('/agent-edit/{id}', [AgentController::class, 'agentEdit'])->middleware(['can:isAdmin']);
         Route::get('/agent-detail-view/{id}', [AgentApiController::class, 'agentDetails_index'])->middleware(['can:isAdmin']);

         Route::get('/get-agent-details', [AgentApiController::class, 'getAgentDetails'])->middleware(['can:isAdmin']);
       
         Route::post('/active-company-agent', [AgentApiController::class, 'activeInactiveAgent']);
         Route::post('/save-allow-agents', [AgentApiController::class, 'saveAllowAgents']);
         Route::post('/delete-agents', [AgentApiController::class, 'deleteAgent']);

    //   Channel api  
   // Route::get('/channel', [ChannelApiController::class, 'list'])->middleware(['can:isAdmin']);
     Route::get('/get-channel', [ChannelApiController::class, 'getlistChannels']);
    Route::get('/channel-add', [ChannelApiController::class, 'channelAdd'])->middleware(['can:isAdmin']);
     Route::get('/channel-edit/{id}', [ChannelApiController::class, 'channelEdit'])->middleware(['can:isAdmin']);
     Route::get('/channel-assign/{id}', [ChannelApiController::class, 'channelAssign']);
     Route::get('/get-channel-assign-list', [ChannelApiController::class, 'getAssignDeviceList'])->middleware(['can:isAdmin']);
     Route::post('/save-channel', [ChannelApiController::class, 'save']);
    // Route::post('/active-company-channel', [ChannelController::class, 'activeInactiveChannel']);
    Route::post('/update-device-channel', [ChannelApiController::class, 'updateChannelIntoDevice']);
     Route::post('/remove-channel', [ChannelApiController::class, 'removeServicesChannel']);

});

Route::post('/verified-machine', [SyetemOverviewController::class, 'verifiedDevice']);


