<?php

namespace Flynt\Components\BlockImageTextInterview;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockImageTextInterview',
        'label' => __('Block: Image Text', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image Position', 'flynt'),
                'name' => 'imagePosition',
                'type' => 'button_group',
                'choices' => [
                    'top' => sprintf('<i class=\'dashicons dashicons-table-row-before\' title=\'%1$s\'></i>', __('Image top', 'flynt')),
                    'bottom' => sprintf('<i class=\'dashicons dashicons-table-row-after\' title=\'%1$s\'></i>', __('Image bottom', 'flynt'))
                ]
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 1,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'wrapper' =>  [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 1,
                'wrapper' =>  [
                    'width' => 50,
                ],
            ]
        ]
    ];
}
