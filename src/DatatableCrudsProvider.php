<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Console\Commands\PrepareCommand;
use Exist404\DatatableCruds\Console\Commands\InstallPackageCommand;
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
        $this->app->bind('datatablecruds',function() {return new DatatableCruds;});
        $this->mergeConfigFrom(__DIR__ . '/../config/datatablecruds.php', 'datatablecruds');
        $this->loadViewsFrom(__DIR__.'/views', 'datatable');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->publishes([__DIR__ . '/../config/datatablecruds.php' => config_path('datatablecruds.php')], 'config');
        $this->commands([
            InstallPackageCommand::class,
            PrepareCommand::class,
        ]);
    }
}
