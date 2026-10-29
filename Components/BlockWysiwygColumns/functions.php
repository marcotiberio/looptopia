<?php

namespace Flynt\Components\BlockWysiwygColumns;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockWysiwygColumns',
        'label' => __('Text Editor (Columns)', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Columns', 'flynt'),
                'name' => 'columnsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Columns', 'flynt'),
                'name' => 'columns',
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'max' => 4,
                'button_label' => __('Add Column', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual,text',
                        'delay' => 1,
                        'media_upload' => 0,
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, GIF.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png,gif',
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Button', 'flynt'),
                        'name' => 'buttonLink',
                        'type' => 'link',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    FieldVariables\getColorBackground(),
                ]
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getColorBackground(),
                ]
            ]
        ]
    ];
} 