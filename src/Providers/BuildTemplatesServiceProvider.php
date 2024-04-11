<?php

namespace Bukita\SageBoilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use Bukita\SageBoilerplate\Console\SetupTestimonials;
use Bukita\SageBoilerplate\Console\SetupRelatedContent;
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

        $this->mergeConfigFrom(
            __DIR__.'/../../config/example.php',
            'example'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/example.php' => $this->app->configPath('example.php'),
        ], 'config');

        $this->commands([
            SetupTestimonials::class,
            SetupRelatedContent::class,
        ]);

        $this->app->make('BuildTemplates');
    }
}
