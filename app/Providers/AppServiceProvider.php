<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->register();

        if(!App::runningInConsole()){ // se não estiver rodando pelo terminal console
            foreach($this->listPermissions() as $key => $value):
                Gate::define($value->slug, function (User $user) use($value) {
                    return $user->hasPermissions($value->permissions); // chama funcão do modelo User.php
                });
            endforeach;
        }*/
    }

    /*public function listPermissions()
    {
        // chama a função users() do modelo Permission.php
        return Permission::with('users')->get();
    }*/

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Paginator::useBootstrapFive();
    }
}
