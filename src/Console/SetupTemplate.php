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
    protected $signature = 'setup:template {template_name} {has_composer=false}';

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
        $has_composer = $this->argument('has_composer');

        $files = new Filesystem();

        $acf_source = dirname(__DIR__) . '/templates/' . $template_name . '/acf';
        $view_source = dirname(__DIR__) . '/templates/' . $template_name . '/view';

        $acf_destination = app_path('Fields/Templates');
        $view_destination = resource_path('/views/templates');

        $files->copyDirectory(
            $acf_source, 
            $acf_destination
        );

        $files->copyDirectory(
            $view_source, 
            $view_destination
        );

        if( $has_composer ){
            $composer_source = dirname(__DIR__) . '/templates/' . $template_name . '/composer';
            $composer_destination = app_path('View/Composers/Templates');
            $composer_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $template_name)));

            $files->copyDirectory(
                $composer_source, 
                $composer_destination
            );
    
            //insert template line to composer switch
            $switch_file_path = app_path('View/Composers/Switches/Templates.php');
            $switch_file_contents = file_get_contents( $switch_file_path );
    
            if( !$switch_file_contents ){
                $this->info('The composer switch templates file was not found.');
                return;
            }
            
            $use_pos = strpos( $switch_file_contents, 'use App\View\Composers\SSM;' );
            if ( $use_pos === false ) {
                $this->info('The SSM class definition was not found in the switch file.');
                return;
            }

            $switch_file_contents = substr_replace( $switch_file_contents, PHP_EOL . 'use App\View\Composers\Templates\\' . $composer_name . ';', $use_pos + strlen( 'use App\View\Composers\SSM;' ), 0 );
    
            $switch_pos = strpos( $switch_file_contents, 'switch ($template[\'acf_fc_layout\']) {' );
            if ( $switch_pos === false ) {
                $this->info('The switch acf_fc_layout was not found in the switch file.');
                return;
            }
            $switch_file_contents = substr_replace( 
                $switch_file_contents, 
                PHP_EOL . ' case (\'' . $template_name . '\'):
                    $templateData = '. $composer_name . '::getTemplateData($template);
                    break;', 
                $switch_pos + strlen( 'switch ($template[\'acf_fc_layout\']) {' ), 0 );
    
            // Write back to the file.
            file_put_contents( $switch_file_path, $switch_file_contents );
        }

       return $this->info( $template_name . ' setup - success');

    }
}
