<?php

namespace Flynt\Components\BlockManifesto;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockManifesto',
        'label' => 'Block: Impact',
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'titleTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentTitle',
                'type' => 'text'
            ],
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Impact Panels', 'flynt'),
                'name' => 'accordionPanels',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Impact Panel', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'panelImage',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'required' => 1,
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'wrapper' =>  [
                            'width' => 100,
                        ],
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'panelTitle',
                        'type' => 'text'
                    ],
                    [
                        'label' => __('Intro', 'flynt'),
                        'name' => 'panelIntro',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'panelContent',
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
