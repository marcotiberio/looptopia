<?php

namespace Flynt\Components\BlockNewsItem;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockNewsItem',
        'label' => __('Block: News Item', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
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
                    'width' => 30,
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
                    'width' => 70,
                ],
            ],
            [
                'label' => 'Link',
                'name' => 'buttonLink',
                'type' => 'link',
                'return_format' => 'array',
                'wrapper' => [
                    'width' => 100
                ]
            ],
        ]
    ];
}
