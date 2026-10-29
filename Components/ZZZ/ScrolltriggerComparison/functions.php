<?php

namespace Flynt\Components\ScrolltriggerComparison;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ScrolltriggerComparison',
        'label' => __('Photo Comparison', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text before photo', 'flynt'),
                'name' => 'textBeforeComparison',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Text after photo', 'flynt'),
                'name' => 'textAfterComparison',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50,
                ]
            ],
            [
                'label' => __('Image: Open Pool', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                'name' => 'imageOpenPool',
                'type' => 'image',
                'preview_size' => 'large',
                'mime_types' => 'jpg,jpeg,png',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Image: Covered Pool', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                'name' => 'imageCoveredPool',
                'type' => 'image',
                'preview_size' => 'large',
                'mime_types' => 'jpg,jpeg,png',
                'wrapper' => [
                    'width' => 50
                ],
            ],
        ]
    ];
}
