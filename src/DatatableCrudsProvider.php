<?php

namespace Exist404\DatatableCruds;

use Illuminate\Support\ServiceProvider;

class DatatableCrudsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/datatablecruds.php', 'datatablecruds');
        $this->loadViewsFrom(__DIR__.'/views', 'datatable');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->publishes([__DIR__.'/routes' => base_path('routes')]);
        $this->publishes([__DIR__.'/views/app.blade.php' => base_path('resources/views/app.blade.php')]);
        $this->publishes([__DIR__.'/Controllers/DatatableExampleController.php' => base_path('app/Http/Controllers/DatatableExampleController.php')]);
        $this->publishes([__DIR__ . '/../config/datatablecruds.php' => config_path('datatablecruds.php')], 'config');
    }
}
