<?php

namespace Bpwd\Admin;

use Illuminate\Support\ServiceProvider;
use Form;
use DB;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'admin');
        include __DIR__.'/routes.php';
        
        
        $this->publishes([
            __DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        require __DIR__ . '/Libraries/AdminToolbar.php';
        require __DIR__ . '/Libraries/Pagination.php';
        require __DIR__ . '/Libraries/FormHelper.php';
        
        require __DIR__ . '/Macros/AdminToolbar.php';
        require __DIR__ . '/Macros/AdminFormBuilder.php';


        DB::enableQueryLog();
        DB::listen(function ($query) {
             $query->sql;
             //$query->bindings;
             //$query->time;

            //print_r($query->sql);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('FormHelper', 'Bpwd\Admin\Libraries\FormHelper');
        $this->app->bind('LayoutHelper', 'Bpwd\Admin\Libraries\LayoutHelper');

        //$this->app->make('Bpwd\Admin\Controllers\StandardAbstractController');
        $this->app->make('Bpwd\Admin\Controllers\Auth\AuthController');
        $this->app->make('Bpwd\Admin\Controllers\Auth\PasswordController');
        $this->app->make('Bpwd\Admin\Controllers\Users\UserController');
        $this->app->make('Bpwd\Admin\Controllers\Users\PermissionController');
        $this->app->make('Bpwd\Admin\Controllers\Users\RoleController');
    }
}
