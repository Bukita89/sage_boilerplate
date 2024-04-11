<?php

namespace App\Fields\Templates;

use StoutLogic\AcfBuilder\FieldsBuilder;
use App\Fields\Components\TemplateHeader;
use App\Fields\Options\Background;
use App\Fields\Options\HtmlAttributes;
use App\Fields\Options\Admin;
use App\Fields\Options\TemplateSpacing;

class RelatedContent {

	public static function getFields() {

        /**
         * [Template] - Related Content
         */
        $relatedContentTemplate = new FieldsBuilder('related-content', [
            'title'	=> 'Related Content'
        ]);

        $relatedContentTemplate

            ->addTab('Content')

                ->addFields(TemplateHeader::getFields())

                ->addRadio('query', [
                    'label'         => 'Query',
                    'layout'	    => 'horizontal',
                    'choices'       => [
                        'latest'    => 'Latest',
                        'type'      => 'Latest by Type',
                        'topic'     => 'Latest by Topic',
                        'curated'   => 'Curated'
                    ]
                ])

                ->addNumber('posts_number', [
                    'label'         => 'Number of Posts to show',
                    'min'           => 0,
                    'max'           => 100,
                    'step'          => 1,
                    'default_value' => 6
                ])
                    ->conditional('query', '!=', 'curated')

                ->addField('resource_feed_message', 'message', [
                    'label'     => false,
                    'message'   => '<b>No other input is required. The list of most recent published resources will be displayed here.</b>',
                ])
                    ->conditional('query', '==', 'latest')

                ->addTaxonomy('types', [
                    'label'         => 'Types to show',
                    'taxonomy'      => 'ssm_resource_type',
                    'field_type'    => 'multi_select',
                    'allow_null'    => 0,
                    'add_term'      => 0,
                    'save_terms'    => 0,
                    'load_terms'    => 0,
                    'return_format' => 'object',
                    'multiple'      => 1
                ])
                    ->conditional('query', '==', 'type')

                ->addTaxonomy('topics', [
                    'label'         => 'Topics to show',
                    'taxonomy'      => 'ssm_resource_topic',
                    'field_type'    => 'multi_select',
                    'allow_null'    => 0,
                    'add_term'      => 0,
                    'save_terms'    => 0,
                    'load_terms'    => 0,
                    'return_format' => 'object',
                    'multiple'      => 1
                ])
                    ->conditional('query', '==', 'topic')

                ->addRelationship('posts_to_show', [
                    'label'          => 'Posts to show',
                    'post_type'      => ['post'],
                    'filters'        => ['search'],
                    'return_format'  => 'id',
                    'acfe_add_post'  => 1,
                    'acfe_edit_post' => 1,
                ])
                    ->conditional('query', '==', 'curated')

            ->addTab('Options')

                ->addFields(Background::getFields())

                ->addFields(TemplateSpacing::getFields())

                ->addFields(HtmlAttributes::getFields())

            ->addTab('Admin')

                ->addFields(Admin::getFields());

        return $relatedContentTemplate;

	}

}