<?php

namespace Flynt\Components\BlockHero;

use Flynt\FieldVariables;
use Flynt\Utils\Asset;

add_filter('Flynt/addComponentData?name=BlockHero', function ($data) {
    $data['wordmarkUrl'] = Asset::requireUrl('Components/BlockHero/Assets/wordmark.svg');
    $data['chevronUrl'] = Asset::requireUrl('Components/BlockHero/Assets/chevron-down.svg');
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockHero',
        'label' => __('Hero', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Kicker', 'flynt'),
                'name' => 'kicker',
                'type' => 'text',
                'instructions' => __('Small label top-left, e.g. "BAU MIT UNS".', 'flynt'),
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Date', 'flynt'),
                'name' => 'dateText',
                'type' => 'text',
                'instructions' => __('Date top-right, e.g. "13.–15.11.26".', 'flynt'),
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Wordmark', 'flynt'),
                'name' => 'wordmark',
                'type' => 'image',
                'instructions' => __('Brand wordmark shown below the kicker/date. Leave empty to use the default LOOPTOPIA wordmark.', 'flynt'),
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'svg,png,webp',
            ],
            [
                'label' => __('Headline', 'flynt'),
                'name' => 'headline',
                'type' => 'text',
                'instructions' => __('Main hero headline, e.g. "48 Stunden Berlin neu entdecken".', 'flynt'),
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'return_format' => 'array',
                'instructions' => __('Optional green button below the headline.', 'flynt'),
                'required' => 0,
                'wrapper' => ['width' => 50], 
            ],
            [
                'label' => __('Button (Secondary)', 'flynt'),
                'name' => 'buttonSecondary',
                'type' => 'link',
                'return_format' => 'array',
                'instructions' => __('Optional green button below the headline.', 'flynt'),
                'required' => 0,
                'wrapper' => ['width' => 50],
            ],
            [
                'label' => __('Funders (left)', 'flynt'),
                'name' => 'funders',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'label' => __('Label', 'flynt'),
                        'name' => 'label',
                        'type' => 'text',
                        'instructions' => __('e.g. "Gefördert durch".', 'flynt'),
                        'required' => 0,
                    ],
                    [
                        'label' => __('Logos', 'flynt'),
                        'name' => 'logos',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => __('Add Logo', 'flynt'),
                        'sub_fields' => [
                            [
                                'label' => __('Logo', 'flynt'),
                                'name' => 'logo',
                                'type' => 'image',
                                'preview_size' => 'small',
                                'mime_types' => 'svg,png,jpg,jpeg',
                                'required' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Funders (right)', 'flynt'),
                'name' => 'funders2',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'label' => __('Label', 'flynt'),
                        'name' => 'label',
                        'type' => 'text',
                        'instructions' => __('e.g. "Organisiert von".', 'flynt'),
                        'required' => 0,
                    ],
                    [
                        'label' => __('Logos', 'flynt'),
                        'name' => 'logos',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => __('Add Logo', 'flynt'),
                        'sub_fields' => [
                            [
                                'label' => __('Logo', 'flynt'),
                                'name' => 'logo',
                                'type' => 'image',
                                'preview_size' => 'small',
                                'mime_types' => 'svg,png,jpg,jpeg',
                                'required' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Image', 'flynt'),
                'name' => 'imageTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Background Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WEBP.', 'flynt'),
                'name' => 'backgroundImage',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => '',
                'name' => 'options',
                'type' => 'group',
                'layout' => 'row',
                'sub_fields' => [
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ],
            ],
        ],
    ];
}
