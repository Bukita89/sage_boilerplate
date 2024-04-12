<?php

namespace Bukita\SageBoilerplate\Console;

use Roots\Acorn\Console\Commands\Command;
use Illuminate\Filesystem\Filesystem;

class SetupTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:template {template_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My custom SSM command.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $template_name = $this->argument('template_name');

        $this->info($template_name);

        $files = new Filesystem();

        $acf_source = dirname(__DIR__) . '/templates/' . $template_name . '/acf';
        $view_source = dirname(__DIR__) . '/templates/' . $template_name . '/view';

        $acf_desctination = app_path('Fields/Templates');
        $view_destination = resource_path('/views/templates');

        $files->copyDirectory(
            $acf_source, 
            $acf_desctination
        );

        $files->copyDirectory(
            $view_source, 
            $view_destination
        );

       $this->info($acf_source);

       return $this->info( $template_name . ' setup - success');

    }
}
