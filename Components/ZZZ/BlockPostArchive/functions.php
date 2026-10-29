<?php

namespace Flynt\Components\BlockPostArchive;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

function getACFLayout()
{
    return [
        'name' => 'BlockPostArchive',
        'label' => 'Block: Post Archive',
        'sub_fields' => [
            [
                'label' => __('Post', 'flynt'),
                'name' => 'post',
                'type' => 'post_object',
                'post_type' => [
                    'artist',
                    'interview',
                    'release'
                ],
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
                'required' => 0,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            [
                'label' => __('Image Position', 'flynt'),
                'name' => 'imagePosition',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'choices' => [
                    'justify-center' => __('Center', 'flynt'),
                    'justify-start' => __('Left', 'flynt'),
                    'justify-end' => __('Right', 'flynt'),
                ],
                'default_value' => 'justify-center',
            ],
            [
                'label' => __('Add animation', 'flynt'),
                'name' => 'addAnimation',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => __('Yes', 'flynt'),
                'ui_off_text' => __('No', 'flynt'),
                'wrapper' => [
                    'width' => 100
                ]
            ],
            // [
            //     'label' => __('Select animation', 'flynt'),
            //     'name' => 'selectAnimation',
            //     'type' => 'select',
            //     'allow_null' => 1,
            //     'multiple' => 0,
            //     'ui' => 0,
            //     'ajax' => 0,
            //     'choices' => [
            //         '' => __('(none)', 'flynt'),
            //         'fade-in' => __('Fade-in', 'flynt'),
            //     ],
            //     'default_value' => '',
            // ],
            // [
            //     'label' => __('Add parallax effect', 'flynt'),
            //     'name' => 'addParallax',
            //     'type' => 'true_false',
            //     'default_value' => 0,
            //     'ui' => 1,
            //     'ui_on_text' => __('Yes', 'flynt'),
            //     'ui_off_text' => __('No', 'flynt'),
            //     'wrapper' => [
            //         'width' => 100
            //     ]
            // ],
        ]
    ];
}
