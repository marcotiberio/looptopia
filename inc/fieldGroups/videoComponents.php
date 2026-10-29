<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'videoMeta',
        'title' => 'Main Content',
        'style' => '',
        'menu_order' => 1,
        'position' => 'acf_after_title',
        'fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            // [
            //     'label' => 'Date (Required)',
            //     'instructions' => '',
            //     'required' => 1,
            //     'name' => 'end_date',
            //     'type' => 'date_picker',
            //     'display_format' => 'd.m.y',
            //     'return_format' => 'd.m.y',
            //     'first_day' => 1,
            //     'wrapper' => [
            //         'width' => 100,
            //     ]
            // ],
            // [
            //     'label' => __('Author', 'flynt'),
            //     'name' => 'postAuthor',
            //     'type' => 'text',
            //     'wrapper' => [
            //         'width' => 100,
            //     ]
            // ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'postContent',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'video',
                ],
            ],
        ],
    ]);
    ACFComposer::registerFieldGroup([
        'name' => 'videoComponents',
        'title' => __('Video Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'videoComponents',
                'label' => __('Video Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockVideoOembed\getACFLayout(),
                    Components\BlockEmbed\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockVideoText\getACFLayout(),
                    Components\ListingVideosRelated\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'video',
                ],
            ],
        ],
    ]);
});
