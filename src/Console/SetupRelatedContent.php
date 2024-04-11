<?php

namespace Bukita\SageBoilerplate\Console;

use Roots\Acorn\Console\Commands\Command;
use Illuminate\Filesystem\Filesystem;

class SetupRelatedContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:related-content';

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

        $acf_source = dirname(__DIR__) . '/templates/related_content/acf';
        $view_source = dirname(__DIR__) . '/templates/related_content/view';
        $composer_source = dirname(__DIR__) . '/templates/related_content/composer';

        $acf_desctination = app_path('Fields/Templates');
        $view_destination = resource_path('/views/templates');
        $composer_source = app_path('View/Composers/Templates');

        $files->copyDirectory(
            $acf_source, 
            $acf_desctination
        );

        $files->copyDirectory(
            $view_source, 
            $view_destination
        );

        $files->copyDirectory(
            $composer_source, 
            $composer_source
        );

        //insert template line to composer switch
        $file_contents = file_get_contents( $composer_source . '/RelatedContent.php' );
        
        $pos = strpos( $file_contents, 'use App\View\Composers\SSM;' );

        if ( $pos === false ) {
            $this->info('The specified text was not found in the file.');
            return;
        }

        $file_contents = substr_replace( $file_contents, 'use App\View\Composers\Templates\RelatedContent;' . PHP_EOL, $pos + strlen( 'use App\View\Composers\SSM;' ), 0 );

        // Write back to the file.
        file_put_contents( $file, app_path('View/Composers/Switches/Templates.php') );

       return $this->info('Related Content setup - success');

    }
}
