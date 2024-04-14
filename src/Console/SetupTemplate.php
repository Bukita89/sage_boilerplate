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
    protected $signature = 'setup:template {template_slug} {has_composer=false}';

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
        $template_slug = $this->argument('template_slug');
        $has_composer = $this->argument('has_composer');

        $files = new Filesystem();

        $acf_source = dirname(__DIR__) . '/templates/' . $template_slug . '/acf';
        $view_source = dirname(__DIR__) . '/templates/' . $template_slug . '/view';
        $template_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $template_slug)));

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

        //insert template to layout builder
        $lb_file_path = app_path('Fields/Objects/LayoutBuilder.php');
        $lb_file_contents = file_get_contents( $lb_file_path );
 
        if( !$lb_file_contents ){
            $this->info('The layout builder file was not found.');
            return;
        }
         
        $lb_use_pos = strpos( $lb_file_contents, 'use StoutLogic\AcfBuilder\FieldsBuilder;' );
        if ( $lb_use_pos === false ) {
            $this->info('The FieldsBuilder class definition was not found in the layout builder file.');
            return;
        }

        $lb_file_contents = substr_replace( $lb_file_contents, PHP_EOL . 'use App\Fields\Templates\\' . $template_name . ';', $lb_use_pos + strlen( 'use StoutLogic\AcfBuilder\FieldsBuilder;' ), 0 );
 
        $lb_pos = strpos( $lb_file_contents, '->addLayout(Columns::getFields())' );
        if ( $lb_pos === false ) {
            $this->info('->addLayout(Columns::getFields()) was not found in the switch file.');
            return;
        }
        $lb_file_contents = substr_replace( 
            $lb_file_contents, 
            PHP_EOL . '\t->addLayout(' . $template_name . '::getFields())', 
            $lb_pos + strlen( '->addLayout(Columns::getFields()){' ), 0 );
 
        // Write back to the file.
        file_put_contents( $lb_file_path, $lb_file_contents );

        //add composer file
        if( $has_composer ){
            $composer_source = dirname(__DIR__) . '/templates/' . $template_slug . '/composer';
            $composer_destination = app_path('View/Composers/Templates');

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

            $switch_file_contents = substr_replace( $switch_file_contents, PHP_EOL . 'use App\View\Composers\Templates\\' . $template_name . ';', $use_pos + strlen( 'use App\View\Composers\SSM;' ), 0 );
    
            $switch_pos = strpos( $switch_file_contents, 'switch ($template[\'acf_fc_layout\']) {' );
            if ( $switch_pos === false ) {
                $this->info('The switch acf_fc_layout was not found in the switch file.');
                return;
            }
            $switch_file_contents = substr_replace( 
                $switch_file_contents, 
                PHP_EOL . '\tcase (\'' . $template_slug . '\'):
                    $templateData = '. $template_name . '::getTemplateData($template);
                    break;', 
                $switch_pos + strlen( 'switch ($template[\'acf_fc_layout\']) {' ), 0 );
    
            // Write back to the file.
            file_put_contents( $switch_file_path, $switch_file_contents );
        }

       return $this->info( $template_slug . ' setup - success');

    }
}
