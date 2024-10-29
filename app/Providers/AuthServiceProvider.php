<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $permissionList = Permission::all()->toArray();
        if($permissionList)
        {

            // echo "<pre>"; print_r(Gate::abilities()); exit("CALL");
            foreach ($permissionList as $key => $permission) {
                Gate::define($permission['permission_code'], // Create new gate with the permission
                function($user) use ($permission){
                    if ($user->role_id != config('constants.roles.Master_Admin')) {
                        $userPer = $user->userPermission->pluck('permission_code')->toArray();
                        if($userPer && in_array($permission['permission_code'],$userPer) ){
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                });
                // exit("CALL");
            }
        }
    }
}
