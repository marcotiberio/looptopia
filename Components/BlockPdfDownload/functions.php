<?php

namespace Flynt\Components\BlockPdfDownload;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'BlockPdfDownload',
        'label' => __('Block: PDF Download', 'flynt'),
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
                    'left' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Image on the left', 'flynt')),
                    'right' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Image on the right', 'flynt'))
                ],
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Image Alignment', 'flynt'),
                'name' => 'imageAlignment',
                'type' => 'button_group',
                'choices' => [
                    'top' => sprintf('<i class=\'dashicons dashicons-arrow-up-alt\' title=\'%1$s\'></i>', __('Image top', 'flynt')),
                    'center' => sprintf('<i class=\'dashicons dashicons-align-center\' title=\'%1$s\'></i>', __('Image center', 'flynt')),
                    'bottom' => sprintf('<i class=\'dashicons dashicons-arrow-down-alt\' title=\'%1$s\'></i>', __('Image bottom', 'flynt'))
                ],
                'default_value' => 'center',
                'wrapper' => [
                    'width' => 50
                ],
            ],
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
                'label' => __('File / PDF', 'flynt'),
                'name' => 'LinkFileButton',
                'type' => 'file',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
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
