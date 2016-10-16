<?php

namespace Bpwd\Shopping;

use Illuminate\Support\ServiceProvider;

class ShoppingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/Admin/routes.php';
        $this->loadViewsFrom(__DIR__ . '/Admin/Views', 'shopping-admin');
        
        $this->publishes([
            __DIR__ . '/Migrations' => $this->app->databasePath() . '/migrations'
        ], 'migrations');

        include __DIR__ . '/Site/routes.php';
        $this->loadViewsFrom(__DIR__ . '/Site/Views', 'shopping-site');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Bpwd\Shopping\Admin\Controllers\ProductController');
        $this->app->make('Bpwd\Shopping\Admin\Controllers\CategoryController');

        $this->app->make('Bpwd\Shopping\Site\Controllers\CategoryController');
    }
}
