<?php

namespace App\Fields\Templates;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Components\TemplateHeader;
use App\Fields\Options\Background;
use App\Fields\Options\HtmlAttributes;
use App\Fields\Options\Admin;
use App\Fields\Options\TemplateSpacing;
use App\Fields\Components\Button;

class Testimonials {

	public static function getFields() {

        /**
         * [Template] - Testimonials
         */
        $testimonialsTemplate = new FieldsBuilder('testimonials', [
            'label'	=> 'Testimonials'
        ]);

        $testimonialsTemplate

            ->addTab('Content')

                ->addFields(TemplateHeader::getFields())

                ->addRelationship('testimonials', [
                    'label'          => 'Testimonials to show',
                    'post_type'      => ['nto_testimonial'],
                    'filters'        => ['search'],
                    'acfe_add_post'  => 1,
                    'acfe_edit_post' => 1,
                    'max'            => 4,
                    'return_format'  => 'id'
                ])

                ->addTrueFalse('include_button', [
                    'label'         => false,
                    'message'       => 'Include Button',
                    'default_value' => 0,
                ])

                ->addGroup('button', [
                    'label'         => false,
                ])
                    ->conditional('include_button', '==', 1)
                
                    ->addFields(Button::getFields())
                    
                ->endGroup()

            ->addTab('Options')

                ->addFields(Background::getFields())

                ->addFields(TemplateSpacing::getFields())

                ->addFields(HtmlAttributes::getFields())

            ->addTab('Admin')

                ->addFields(Admin::getFields());

        return $testimonialsTemplate;

	}

}