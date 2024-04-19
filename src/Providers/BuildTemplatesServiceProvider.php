<?php

namespace Bukita\SageBoilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use Bukita\SageBoilerplate\Console\SetupTemplate;
use Bukita\SageBoilerplate\BuildTemplates;

class BuildTemplatesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('BuildTemplates', function () {
            return new BuildTemplates($this->app);
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->commands([
            SetupTemplate::class
        ]);

        $this->app->make('BuildTemplates');
    }
}
