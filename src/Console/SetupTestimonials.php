<?php

namespace Bukita\SageBoilerplate\Console;

use Roots\Acorn\Console\Commands\Command;
use Illuminate\Filesystem\Filesystem;

class SetupTestimonials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:testimonials';

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
        $files = new Filesystem();

        $acf_source = __DIR__ . '../templates/testimonials/acf';
        $view_source = __DIR__ . '../templates/testimonials/view';

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

        $this->info('Testimonials setup - success');

        return $this->info($acf_source);

    }
}
