<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Access\Entities\Permission;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $permissions = Permission::all();
        foreach ($permissions as $permission){
            Gate::define($permission->name , function ($user) use ($permission){
                return $user->hasPermissionTo($permission);
            });
        }


    }
}
