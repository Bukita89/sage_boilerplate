<?php

namespace Bukita\SageBoilerplate\Providers;

use Illuminate\Support\ServiceProvider;
use Bukita\SageBoilerplate\Console\ExampleCommand;
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

        $this->publishes([
            __DIR__ . '/../../publishes/app/Fields/Templates' => $this->app->path('Fields/Templates'),
        ], 'template-acf-fields');

        $this->publishes([
            __DIR__ . '/../../publishes/resources/views/templates' => $this->app->resourcePath('views/templates'),
        ], 'template-views');

        $this->commands([
            ExampleCommand::class,
        ]);

        $this->app->make('BuildTemplates');
    }
}
