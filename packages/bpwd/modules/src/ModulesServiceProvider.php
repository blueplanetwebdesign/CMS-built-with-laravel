<?php

namespace Bpwd\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
        $this->loadViewsFrom(__DIR__.'/Views', 'module');
        
        //$this->publishes([
        //    __DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'
        //], 'migrations');

        //require __DIR__ . '/Helpers/Layouts.php';

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Bpwd\Modules\Controllers\ModuleController');
        //$this->app->make('Bpwd\Pages\Controllers\CategoryController');
    }
}
