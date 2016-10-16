<?php

namespace Bpwd\Content;

use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
        $this->loadViewsFrom(__DIR__.'/Views', 'content');

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
        $this->app->make('Bpwd\Content\Controllers\ContentController');
        $this->app->make('Bpwd\Content\Controllers\CategoryController');
        $this->app->make('Bpwd\Content\Controllers\TagController');
    }
}
