<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Console\Commands\InstallPackageCommand;
use Exist404\DatatableCruds\Console\Commands\NewDatatableCrudsCommand;
use Exist404\DatatableCruds\Middleware\DatatableInjection;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Blade;
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
        $this->app->bind('datatablecruds', fn() => new DatatableCruds());
        $this->mergeConfigFrom(__DIR__ . '/../config/datatablecruds.php', 'datatablecruds');
        $this->loadViewsFrom(__DIR__ . '/views', 'datatable');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->publishes([__DIR__ . '/../config/datatablecruds.php' => config_path('datatablecruds.php')], 'config');
        $this->commands([InstallPackageCommand::class, NewDatatableCrudsCommand::class]);
        $this->registerMiddleware(DatatableInjection::class);
        Blade::directive('datatable', function ($data) {
            return "<data-list data='{{ json_encode($data) }}'></data-list>";
        });
    }

    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }
}
