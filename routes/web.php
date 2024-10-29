<?php

use App\Http\Controllers\ReportingController;														 
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InverterController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SyetemOverviewController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TestChartController;
use App\Http\Controllers\CheckAuthController;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\HandleLogsController;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

    // the checkauth0 function 
//     Route::get('/auth0',[CheckAuthController::class,'auth0']);
//  Route::get('/auth0-logout',[CheckAuthController::class,'logout']);
//     Route::get('/callback',[CheckAuthController::class,'callback']);
//     Route::get('/auth0-login',[CheckAuthController::class,'LoginAuth0'])->name('login');
// //Route::post('/auth0-login',[CheckAuthController::class,'storeLoginAuth0']);
// Route::post('/auth-login',[CheckAuthController::class,'auth0Login']);
//     Route::get('/test-redirect', function () {
//         return redirect('https://dev-hldzq5hbrub0deck.us.auth0.com/authorize?client_id=vjmQwlLszuGiyxFkT8HcFs83iuAMfs2n&response_type=code&redirect_uri=http://localhost:8000/callback&scope=openid%20profile%20email');
//     });




Route::get('/', function () {
   // return view('welcome');
    if (Auth::check()) {
        return redirect('dashboard');
    }
    else
    {
       return redirect('login');


   }
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    }
    else
    {
        return redirect('login');
    }
});

Route::get('/admin', function () {
    if (Auth::guard('admin')->check()) {
        return redirect('/dashboard');
    }
    else
    {
        return redirect('admin/login');
    }
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    $base_url = url('/');
    return "<h1 style='text-align:center; margin-top:100px;'> Cache and Config Clear Successfully...<br><br>
        <a href='".$base_url."' style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 8px;'> Back To Home </a>
    </h1>";
});
Route::view('/testing','test');

//Route::group(['prefix'=>'admin'], function () {
Route::group([], function () {

    // auth-0 login 
// Route::get('/auth0-login',[CheckAuthController::class,'LoginAuth0']);
// //Route::post('/auth0-login',[CheckAuthController::class,'storeLoginAuth0']);
// Route::post('/auth-login',[CheckAuthController::class,'auth0Login']);
    # UserList
    Route::get('user-list', [AuthController::class, 'userList']);
 
     # Login and Logout
     Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
     Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
     Route::post('/login', [AuthController::class, 'attemptLogin']);
     Route::post('/admin-login', [AuthController::class, 'attemptAdminLogin']);
   Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

     Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
     Route::post('/register', [AuthController::class, 'saveRegister']);
     Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
     Route::post('/resend-otp', [AuthController::class, 'resendOTP']);
     Route::get('/forgot-password', [AuthController::class, 'showForgotPassword']);
     Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
     Route::post('/verify-forgot-otp', [AuthController::class, 'verifyForgotOTP']);
     Route::post('/reset-password', [AuthController::class, 'resetPassword']);


    # Admin Groups
   Route::group(['middleware' => ['auth:admin']], function () {

// test mail
Route::get('/send-test-email', function () {
    $email = 'vijayakaspate7@gmail.com'; // Replace with the your mail
    Mail::send('emails.testmail', [], function ($message) use ($email) {
        $message->to($email)
                ->subject('Test Email from Laravel');
    });

    return 'Email has been sent successfully!';
});

        Route::get('/logging-monitoring',[HandleLogsController::class,'index']);

        Route::get('/vnc', [ScreenController::class, 'index']);
        Route::get('/dwservice', [ScreenController::class, 'dwservice']);
        Route::get('/dwserviceCreateAgent', [ScreenController::class, 'dwserviceCreateAgent']);
        Route::get('/company/{companyId}/remote-access-view/{id}', [ScreenController::class, 'index']);
        Route::get('/remote-access-view/{id}', [ScreenController::class, 'index'])->middleware('can:RemoteAccessView');


        # Permission (When Added new permission then uncomment)
        Route::get('/add-edit-permission', [AuthController::class, 'savePermission']);

        # Password Changed
        Route::get('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/save-password', [AuthController::class, 'updatePassword']);

        # Dashboard
        Route::get('/dashboard/{id?}', [DashboardController::class, 'index']);
        Route::get('/get-dashboard-device-list', [DashboardController::class, 'getDeviceList']);
        Route::post('/get-dashboard-energy-chart', [DashboardController::class, 'getDashboardEnergyChart']);
        Route::get('/theme-setting', [DashboardController::class, 'themeSetting']);
        Route::get('/get-dashboard-group-list', [DashboardController::class, 'getGroupList']);

        # DashBoard Ajax
        Route::post('/get-account-summery', [DashboardController::class, 'getAccountSummery']);
        Route::post('/get-powerbank-usage', [DashboardController::class, 'getPowerBankUsage']);
        Route::post('/get-powerbank-usage-new', [DashboardController::class, 'getPowerBankUsageNew']);
        Route::post('/get-dashboard-alerts', [DashboardController::class, 'getAlerts']);
        Route::post('/get-my-saving', [DashboardController::class, 'getMySavings']);
        Route::post('/get-daily-activity', [DashboardController::class, 'getDailyActivity']);
        Route::post('/get-powerbank-notification', [DashboardController::class, 'getPowerBankNotification']);
        Route::post('/upload-mysaving-image', [DashboardController::class, 'uploadMySavingImage']);

        # Manage Logo
        Route::get('/manage-logo', [SettingController::class, 'showLogo'])->middleware(['can:isAdmin']);
        Route::post('/update-logo', [SettingController::class, 'saveLogo']);

        #users
        Route::get('/users', [UsersController::class, 'index']);
        Route::post('/save-user-pin', [UsersController::class, 'savePin']);
        Route::post('/verify-pin', [UsersController::class, 'verifyPin']);

         #inverters
        Route::get('/inverters', [InverterController::class, 'index']);
        Route::get('/inverter-detail/{id}', [InverterController::class, 'detail']);
        Route::post('/tmp-inverter-content-data', [InverterController::class, 'getTmpContentData']);
        Route::get('/inverter-settings/{id}', [InverterController::class, 'settings']);
        Route::post('/save-inverter-setting', [InverterController::class, 'saveInverterSetting']);
        Route::get('/inverters-warning-master-upsert', [InverterController::class, 'createWarningCodeMaster']);
        Route::post('/get-inverter-warning-message', [InverterController::class, 'getInverterWarningMsg']);

        Route::get('/graph-power/{id}', [InverterController::class, 'graphPower']);
        Route::post('/get-power-graph-data', [InverterController::class, 'getPowerChartData']);
        Route::post('/get-battery-status-graph-data', [InverterController::class, 'getBatteryStatusChartData']);
        Route::post('/get-energy-graph-data', [InverterController::class, 'getEnergyChartData']);
        Route::get('/static-report', [InverterController::class, 'getStaticReport']);
        Route::post('/testQry', [InverterController::class, 'testQry']);

         #alarm warning
         Route::get('/alarm', [AlarmController::class, 'index']);

        /*CMS*/
        Route::get('/cms/{page}', [CmsController::class, 'page']);
        Route::post('/save-cms', [CmsController::class, 'save']);
        /*CMS*/

        /* Company  */
        Route::get('/company', [CompanyController::class, 'list'])->middleware('can:isAdmin');

        Route::get('/get-company-list', [CompanyController::class, 'listCompany']);
        Route::post('/save-company', [CompanyController::class, 'save']);
        Route::post('/active-inactive-company', [CompanyController::class, 'activeInactiveCompany']);
        /* Company  */

        /* Country */
        Route::get('/countries', [CountryController::class, 'list'])->middleware(['can:isAdmin']);
        Route::get('/get-countries-list', [CountryController::class, 'listCountries']);
        Route::post('/save-countries', [CountryController::class, 'save']);
        Route::post('/active-inactive-countries', [CountryController::class, 'activeInactiveCountries']);
        /* Country */

        /* New Users */
        Route::get('/new-users-list', [UsersController::class, 'listNewUsers'])->middleware('can:UserManagementList');
        Route::get('/get-new-users-list', [UsersController::class, 'getListNewUsers']);
        Route::post('/active-inactive-new-users', [UsersController::class, 'activeInactiveUsers']);
        Route::get('/new-user-edit/{id}', [UsersController::class, 'editNewUser'])->middleware('can:UserManagementEdit');
        Route::post('/new-user-save', [UsersController::class, 'saveNewUser']);
        Route::get('/new-user-add', [UsersController::class, 'addUserNew'])->middleware('can:UsereManagementAdd');

        /* New Users */

        /* Users */
        Route::get('/users-list', [UsersController::class, 'listUsers'])->middleware('can:UserManagementList');
        Route::get('/get-users-list', [UsersController::class, 'getListUsers']);
        Route::post('/active-inactive-users', [UsersController::class, 'activeInactiveUsers']);
        Route::get('/user-edit/{id}', [UsersController::class, 'editUser'])->middleware('can:UserManagementEdit');
        Route::post('/user-save', [UsersController::class, 'saveUser']);
        Route::get('/user-add', [UsersController::class, 'addUser'])->middleware('can:UsereManagementAdd');
        /* Users */

        /* System Overview */

        Route::get('/system-overview/{id?}', [SyetemOverviewController::class, 'list'])->middleware('can:DeviceManagementList');
        Route::get('/add-cluster', [SyetemOverviewController::class, 'addCluster'])->middleware('can:isUser','can:ClusterManagementAdd');
        Route::get('/add-device', [SyetemOverviewController::class, 'addDevice'])->middleware('can:isUser','can:DeviceManagementAdd');
        Route::post('/save-cluster', [SyetemOverviewController::class, 'saveCluster']);
        Route::post('/save-device', [SyetemOverviewController::class, 'saveDevice']);
        Route::post('/get-tmp-device-list', [SyetemOverviewController::class, 'getTmpDeviceList']);
        Route::post('/get-tmp-device-list-cluster-wise', [SyetemOverviewController::class, 'getTmpDeviceListClusterWise']);
        Route::get('/device_details/{id}', [SyetemOverviewController::class, 'deviceDetailPage']);
        Route::get('/battery_details/{id}', [SyetemOverviewController::class, 'batteryDetailPage']);
        Route::post('/check-device-verified', [SyetemOverviewController::class, 'checkDeviceVerified']);
        Route::get('/edit-device/{id}', [SyetemOverviewController::class, 'editDevice'])->middleware('can:isUser','can:DeviceManagementEdit');
        Route::get('/device-alarms-list/{id}', [SyetemOverviewController::class, 'deviceAlarmsList']);
        Route::get('/get-alarms-list', [SyetemOverviewController::class, 'getAlarmsList']);
        Route::post('/verified-machine', [SyetemOverviewController::class, 'verifiedDevice']);
        Route::get('/company/{companyId}/remort-access/{id}', [SyetemOverviewController::class, 'remortAccess']);
        Route::post('/save-remort-access', [SyetemOverviewController::class, 'saveRemortAccess']);
        Route::get('/device_details_copy/{id}', [SyetemOverviewController::class, 'deviceDetailPageCopy']);
        Route::get('/battery_details_copy/{id}', [SyetemOverviewController::class, 'batteryDetailPageCopy']);
        Route::get('/get-warning-list-by-machine', [SyetemOverviewController::class, 'getWarningList']);
        Route::get('/get-current-war-list-by-machine', [SyetemOverviewController::class, 'getCurrentWarningList']);
        Route::get('/get-current-noti-list-by-machine', [SyetemOverviewController::class, 'getCurrentNotificationList']);
        Route::get('/get-noti-list-by-machine', [SyetemOverviewController::class, 'getNotificationList']);
        Route::post('/get-last-5-minit-data', [SyetemOverviewController::class, 'getLast5MinitData']);
        Route::post('/device-assign-to-group', [SyetemOverviewController::class, 'deviceAssignToGroup']);
        Route::post('/delete-device', [SyetemOverviewController::class, 'deleteDevice']);

        /* System Overview */

        /* Chart */
        Route::get('/charts/{id}', [ChartController::class, 'getCharts'])->middleware('can:ChartView');
        Route::post('/charts-grid-genset-data', [ChartController::class, 'getGridGensetGraphData']);
        Route::post('/get-charts-data', [ChartController::class, 'getChartsData']);

        Route::get('/add-chart/{id?}', [ChartController::class, 'addChart'])->middleware('can:ChartAdd');
        Route::post('/save-charts', [ChartController::class, 'saveCharts']);
        Route::get('/charts-list/{id?}', [ChartController::class, 'chartsList'])->middleware('can:ChartList');
        Route::get('/get-charts-list', [ChartController::class, 'getChartsList']);
        Route::get('/edit-chart/{id}', [ChartController::class, 'editChart'])->middleware('can:ChartEdit');
        Route::get('/view-chart/{id}', [ChartController::class, 'viewChart'])->middleware('can:ChartView');
        Route::get('/charts-temp', [ChartController::class, 'getChartsTemporary']);
        Route::post('/export-chart-csv', [ChartController::class, 'exportChart']);

        /* Chart */

        /* Testing Chart */
        Route::get('/testing-charts/{id}', [TestChartController::class, 'getTestingCharts'])->middleware('can:ChartView');
        Route::post('/get-testing-charts-data', [TestChartController::class, 'getChartsData']);
        Route::post('/export-testing-chart-csv', [TestChartController::class, 'exportChart']);
        /* Testing Chart */


        /** Admin Access To Company Details*/
        Route::get('/company/{companyId}/dashboard/{id?}', [CompanyController::class, 'companyLogin'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/new-users-list', [UsersController::class, 'listNewUsers'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/new-user-edit/{id}', [UsersController::class, 'editNewUser'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/new-user-add', [UsersController::class, 'addUserNew'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/users-list', [UsersController::class, 'listUsers'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/user-edit/{id}', [UsersController::class, 'editUser'])->middleware('can:isAdmin');
        Route::get('/company/{companyId}/user-add', [UsersController::class, 'addUser'])->middleware('can:isAdmin');

        Route::get('/company/{companyId}/system-overview', [SyetemOverviewController::class, 'list']);
        Route::get('/company/{companyId}/device_details/{id}', [SyetemOverviewController::class, 'deviceDetailPage']);
        Route::get('/company/{companyId}/edit-device/{id}', [SyetemOverviewController::class, 'editDevice']);
        Route::get('/company/{companyId}/charts/{id}', [ChartController::class, 'getCharts']);
        Route::get('/company/{companyId}/add-cluster', [SyetemOverviewController::class, 'addCluster']);
        Route::get('/company/{companyId}/add-device', [SyetemOverviewController::class, 'addDevice']);
        Route::get('/company/{companyId}/device-alarms-list/{id}', [SyetemOverviewController::class, 'deviceAlarmsList']);
        Route::get('/company/{companyId}/battery_details/{id}', [SyetemOverviewController::class, 'batteryDetailPage']);

        Route::get('/company/{companyId}/charts-list', [ChartController::class, 'chartsList']);
        Route::get('/company/{companyId}/edit-chart/{id}', [ChartController::class, 'editChart']);
        Route::get('/company/{companyId}/add-chart', [ChartController::class, 'addChart']);
        Route::get('/company/{companyId}/view-chart/{id}', [ChartController::class, 'viewChart']);

        # Warning
        Route::get('/warning', [WarningController::class, 'list']);
        Route::get('/get-warning-list', [WarningController::class, 'listWarning']);
        Route::post('/save-warning', [WarningController::class, 'saveWarning']);
        Route::post('/delete-warning', [WarningController::class, 'deleteWarning']);

        Route::get('/company/{companyId}/warning', [WarningController::class, 'list']);

        # Errors
        Route::get('/error', [ErrorController::class, 'list']);
        Route::get('/get-error-list', [ErrorController::class, 'listError']);
        Route::post('/save-error', [ErrorController::class, 'saveError']);
        Route::post('/delete-error', [ErrorController::class, 'deleteError']);

        Route::get('/company/{companyId}/error', [ErrorController::class, 'list']);

        Route::get('/company/{companyId}/notification-message', [ErrorController::class, 'listMessage'])->middleware('can:isAdmin');
        Route::get('/notification-message', [ErrorController::class, 'listMessage'])->middleware('can:NotificationMessageList');

        # Test Energy Counts
        Route::get('/test-energy-hour', [SyetemOverviewController::class, 'testEnergyHour']);

        Route::get('/test-charts/{id}', [ChartController::class, 'getTestCharts']);
        Route::post('/get-test-charts-data', [ChartController::class, 'getTestChartsData']);

        Route::get('/test-query', [AuthController::class, 'TestQuery']);

        Route::get('/test-dashboard', [DashboardController::class, 'testDashboard']);

        # Agents
        Route::get('/agent', [AgentController::class, 'list'])->middleware(['can:isAdmin']);
        Route::get('/get-agents', [AgentController::class, 'getAgentsList']);
        Route::get('/agent-add', [AgentController::class, 'agentAdd'])->middleware(['can:isAdmin']);
        Route::get('/agent-edit/{id}', [AgentController::class, 'agentEdit'])->middleware(['can:isAdmin']);
        Route::get('/agent-detail-view/{id}', [AgentController::class, 'agentView'])->middleware(['can:isAdmin']);
        Route::get('/get-agent-details', [AgentController::class, 'getAgentDetails'])->middleware(['can:isAdmin']);
        Route::post('/save-agents', [AgentController::class, 'save']);
        Route::post('/active-company-agent', [AgentController::class, 'activeInactiveAgent']);
        Route::post('/save-allow-agents', [AgentController::class, 'saveAllowAgents']);
        Route::post('/delete-agents', [AgentController::class, 'deleteAgents']);

        # channel
        Route::get('/channel', [ChannelController::class, 'list'])->middleware(['can:isAdmin']);
        Route::get('/get-channel', [ChannelController::class, 'getChannelList']);
        Route::get('/channel-add', [ChannelController::class, 'channelAdd'])->middleware(['can:isAdmin']);
        Route::get('/channel-edit/{id}', [ChannelController::class, 'channelEdit'])->middleware(['can:isAdmin']);
        Route::get('/channel-assign/{id}', [ChannelController::class, 'channelAssign']);
        Route::get('/get-channel-assign-list', [ChannelController::class, 'getAssignDeviceList'])->middleware(['can:isAdmin']);
        Route::post('/save-channel', [ChannelController::class, 'save']);
        Route::post('/active-company-channel', [ChannelController::class, 'activeInactiveChannel']);
        Route::post('/update-device-channel', [ChannelController::class, 'updateChannelIntoDevice']);
        Route::post('/remove-channel', [ChannelController::class, 'removeServicesChannel']);

		# reporting
        Route::get('/reporting', [ReportingController::class, 'showreport']);	 
		
        # Check Module Access Password
        Route::post('/verify-module-password', [AuthController::class, 'verifyModuleAccessPassword']);

        # Notification
        Route::get('/notification-list', [NotificationController::class, 'showNotification'])->middleware(['can:isAdmin']);
        Route::post('/get-notification-list', [NotificationController::class, 'getNotificationList'])->middleware(['can:isAdmin']);
        Route::post('/mark-notifications-as-read', [NotificationController::class, 'markAsRead'])->middleware(['can:isAdmin']);
        Route::post('/mark-notify-as-read-single', [NotificationController::class, 'markAsReadSingle'])->middleware(['can:isAdmin']);

        # Icon Settings
        Route::get('/icon-settings', [SettingController::class, 'showIconSettings'])->middleware(['can:isAdmin']);
        Route::post('/update-icon-setting', [SettingController::class, 'saveIconSetting'])->middleware(['can:isAdmin']);
        Route::post('/get-icons', [SettingController::class, 'getIcons']);

        // Route::get('/create-icon-settings', [AuthController::class, 'saveIconSettings']);
    });
});
