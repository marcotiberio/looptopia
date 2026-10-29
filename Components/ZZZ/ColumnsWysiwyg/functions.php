<?php

namespace Flynt\Components\ColumnsWysiwyg;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ColumnsWysiwyg',
        'label' => 'Columns: Image/Text',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            // [
            //     'label' => __('Title', 'flynt'),
            //     'name' => 'preContent',
            //     'type' => 'text'
            // ],
            [
                'label' => __('Text Columns', 'flynt'),
                'name' => 'columnTexts',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Text Column', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'wrapper' =>  [
                            'width' => '50',
                        ]
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'columnText',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                    ],
                ],
            ],
        ],
    ];
}
