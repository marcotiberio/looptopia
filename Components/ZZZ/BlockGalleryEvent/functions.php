<?php

namespace Flynt\Components\blockGalleryEvent;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockGalleryEvent',
        'label' => 'Block: Event Views/Artworks',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContent',
                'type' => 'text'
            ],
            [
                'label' => __('Boxes', 'flynt'),
                'name' => 'boxes',
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'button_label' => __('Add Box', 'flynt'),
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
                            'width' => '30',
                        ]
                    ],
                    [
                        'label' => __('Artist', 'flynt'),
                        'name' => 'artist',
                        'type' => 'text',
                        'wrapper' =>  [
                            'width' => '33',
                        ]
                    ],
                    [
                        'label' => __('Title and Year', 'flynt'),
                        'name' => 'titleYear',
                        'type' => 'text',
                        'wrapper' =>  [
                            'width' => '33',
                        ]
                    ],
                ],
            ],
        ]
    ];
}
