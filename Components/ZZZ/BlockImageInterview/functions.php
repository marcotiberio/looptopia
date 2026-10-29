<?php

namespace Flynt\Components\BlockImageInterview;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockImageInterview',
        'label' => __('Block: Image', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image height', 'flynt'),
                'name' => 'imageHeight',
                'type' => 'select',
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'choices' => [
                    'h-auto lg:h-[calc(100vh_-_120px)]' => __('100%', 'flynt'),
                    'h-auto lg:h-[calc(70vh_-_120px)]' => __('60%', 'flynt'),
                    'h-auto lg:h-[calc(40vh_-_120px)]' => __('30%', 'flynt'),
                ],
                'default_value' => 'h-auto lg:h-[calc(100vh_-_120px)]',
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 1,
                'mime_types' => 'jpg,jpeg,png,svg,webp'
            ]
        ]
    ];
}
