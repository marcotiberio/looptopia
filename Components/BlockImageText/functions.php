<?php

namespace Flynt\Components\BlockImageText;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockImageText',
        'label' => __('Image + Text', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Image', 'flynt'),
                'name' => 'imageTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image Position', 'flynt'),
                'name' => 'imagePosition',
                'type' => 'button_group',
                'choices' => [
                    'lg:flex-row' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Image on the left', 'flynt')),
                    'lg:flex-row-reverse' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Image on the right', 'flynt'))
                ],
                'default' => 'flex-row-reverse',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            // [
            //     'label' => __('Image Alignment', 'flynt'),
            //     'name' => 'imageAlignment',
            //     'type' => 'button_group',
            //     'choices' => [
            //         'top' => sprintf('<i class=\'dashicons dashicons-arrow-up-alt\' title=\'%1$s\'></i>', __('Image top', 'flynt')),
            //         'center' => sprintf('<i class=\'dashicons dashicons-align-center\' title=\'%1$s\'></i>', __('Image center', 'flynt')),
            //         'bottom' => sprintf('<i class=\'dashicons dashicons-arrow-down-alt\' title=\'%1$s\'></i>', __('Image bottom', 'flynt'))
            //     ],
            //     'default_value' => 'center',
            //     'wrapper' => [
            //         'width' => 50
            //     ],
            // ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'wrapper' =>  [
                    'width' => 100,
                ],
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' =>  [
                    'width' => 100,
                ],
            ],
            [
                'label' => __('Regular Buttons', 'flynt'),
                'name' => 'regularButtonsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Button 1', 'flynt'),
                'name' => 'buttonLink1',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Button 2', 'flynt'),
                'name' => 'buttonLink2',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Anchor Scroll Buttons', 'flynt'),
                'name' => 'anchorScrollButtonsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Anchor Scroll Buttons', 'flynt'),
                'name' => 'repeaterButtons',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => __('Add Button', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Button', 'flynt'),
                        'name' => 'buttonLink',
                        'type' => 'link',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                ],
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
                    FieldVariables\getColorText(),
                ]
            ]
        ]
    ];
}
