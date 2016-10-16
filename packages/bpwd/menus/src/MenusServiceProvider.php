<?php

namespace Bpwd\Menus;

use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
        $this->loadViewsFrom(__DIR__.'/Views', 'menus');
        
        $this->publishes([
            __DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        //require __DIR__ . '/Helpers/Layouts.php';

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Bpwd\Menus\Controllers\MenuController');
        $this->app->make('Bpwd\Menus\Controllers\TypeController');
    }
}
