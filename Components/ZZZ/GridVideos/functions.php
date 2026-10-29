<?php

namespace Flynt\Components\GridVideos;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

function getACFLayout()
{
    return [
        'name' => 'GridVideos',
        'label' => 'Grid: Vertical Videos',
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
                'name' => 'title',
                'type' => 'text'
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
            [
                'label' => __('Videos', 'flynt'),
                'name' => 'videosTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Video 1', 'flynt'),
                'instructions' => __('Video-Format: mp4.', 'flynt'),
                'name' => 'videoFile1',
                'type' => 'file',
                'return_format' => 'url',
                'max_size' => 20,
                'mime_types' => 'mp4',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => __('Video 2', 'flynt'),
                'instructions' => __('Video-Format: mp4.', 'flynt'),
                'name' => 'videoFile2',
                'type' => 'file',
                'return_format' => 'url',
                'max_size' => 20,
                'mime_types' => 'mp4',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => __('Video 3', 'flynt'),
                'instructions' => __('Video-Format: mp4.', 'flynt'),
                'name' => 'videoFile3',
                'type' => 'file',
                'return_format' => 'url',
                'max_size' => 20,
                'mime_types' => 'mp4',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => __('Logos', 'flynt'),
                'name' => 'logosTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Intro Logos', 'flynt'),
                'name' => 'introLogosHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Logos', 'flynt'),
                'name' => 'repeaterLogos',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Logo', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Logo', 'flynt'),
                        'instructions' => __('Accepted Image-Formats: SVG, PNG. Recommended Image-Format: SVG.', 'flynt'),
                        'name' => 'logo',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'svg,png',
                        'wrapper' => [
                            'width' => 50
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
