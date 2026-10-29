<?php

namespace Flynt\Components\BlockVideoText;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Flynt\Utils\Oembed;

add_filter('Flynt/addComponentData?name=BlockVideoText', function ($data) {
    $data['oembed'] = Oembed::setSrcAsDataAttribute(
        $data['oembed'],
        ['autoplay' => 'false']
    );

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockVideoText',
        'label' => __('Block: Video Text', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Video', 'flynt'),
                'name' => 'videoTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Video Position', 'flynt'),
                'name' => 'videoPosition',
                'type' => 'button_group',
                'choices' => [
                    'left' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Video on the left', 'flynt')),
                    'right' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Video on the right', 'flynt'))
                ],
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Video Alignment', 'flynt'),
                'name' => 'videoAlignment',
                'type' => 'button_group',
                'choices' => [
                    'top' => sprintf('<i class=\'dashicons dashicons-arrow-up-alt\' title=\'%1$s\'></i>', __('Video top', 'flynt')),
                    'center' => sprintf('<i class=\'dashicons dashicons-align-center\' title=\'%1$s\'></i>', __('Video center', 'flynt')),
                    'bottom' => sprintf('<i class=\'dashicons dashicons-arrow-down-alt\' title=\'%1$s\'></i>', __('Video bottom', 'flynt'))
                ],
                'default_value' => 'top',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Video', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 1,
                'videoParams' => [
                    'autoplay' => 1,
                ]
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contntTab',
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
            // [
            //     'label' => __('Button', 'flynt'),
            //     'name' => 'buttonLink',
            //     'type' => 'link',
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => 100
            //     ],
            // ],
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
