<?php

namespace Flynt\Components\BlockAbout;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockAbout',
        'label' => __('Block: About', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 1,
                'required' => 0,
                'wrapper' =>  [
                    'width' => 80,
                ],
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
                    'width' => 20,
                ],
            ],
            [
                'label' => __('Contact Form', 'flynt'),
                'name' => 'contactForm',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' =>  [
                    'width' => 100,
                ],
            ],
        ]
    ];
}
