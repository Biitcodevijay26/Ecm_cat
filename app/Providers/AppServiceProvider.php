<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sanctum\PersonalAccessToken;
use App\Models\User;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         // Loader Alias
         $loader = AliasLoader::getInstance();

         // SANCTUM CUSTOM PERSONAL-ACCESS-TOKEN
         $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\Sanctum\PersonalAccessToken::class);

         Gate::define('isAdmin', function(User $user) {
            if ($user->role_id == "6440caeb7f86dd3c207e508f") {
                return true;
            } else {
                return false;
            }
        });

         Gate::define('isUser', function(User $user) {
            if ($user->role_id != "6440caeb7f86dd3c207e508f") {
                return true;
            } else {
                return false;
            }
        });
    }
}
