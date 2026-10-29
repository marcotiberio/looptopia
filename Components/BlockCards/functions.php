<?php

namespace Flynt\Components\BlockCards;

use Flynt\FieldVariables;

// Adapted from the SliderBox component (marcotiberio/greenteams): same box
// repeater (image/title/text), rendered as a static numbered grid instead of a
// Swiper carousel — for Looptopia's three "Themenfelder" cards.

function getACFLayout()
{
    return [
        'name' => 'BlockCards',
        'label' => __('Cards', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'title',
                'type' => 'text',
                'instructions' => __('Text shown above the cards. Leave empty to hide.', 'flynt'),
                'required' => 0,
            ],
            [
                'label' => __('Show Numbers', 'flynt'),
                'name' => 'showNumbers',
                'type' => 'true_false',
                'instructions' => __('Prefix each card with an auto-incrementing number (1. 2. 3.).', 'flynt'),
                'default_value' => 1,
                'ui' => 1,
            ],
            [
                'label' => __('Cards', 'flynt'),
                'name' => 'cards',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Icon / Illustration', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'small',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'text',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'delay' => 1,
                        'media_upload' => 0,
                        'required' => 0,
                    ],
                ],
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
