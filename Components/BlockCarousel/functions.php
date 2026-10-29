<?php

namespace Flynt\Components\BlockCarousel;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

// Carousel sibling of BlockCards: the same icon/title/text boxes, but laid out
// in a Swiper row inside a coloured panel, with an optional pill button
// underneath — Looptopia's "Wie kann ich mitmachen" section.

add_filter('Flynt/addComponentData?name=BlockCarousel', function ($data) {
    // Screen-reader labels are shared with the other carousels via the global
    // slider options.
    $data['jsonData'] = [
        'options' => array_merge(Options::getTranslatable('SliderOptions'), $data['options'] ?? []),
    ];

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockCarousel',
        'label' => __('Carousel: Cards', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Text', 'flynt'),
                'name' => 'textTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text',
                'instructions' => __('Heading shown above the cards. Leave empty to hide.', 'flynt'),
                'required' => 0,
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'wysiwyg',
                'instructions' => __('Text between the heading and the cards.', 'flynt'),
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'required' => 0,
            ],
            [
                'label' => __('Cards', 'flynt'),
                'name' => 'cardsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
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
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'link',
                        'type' => 'link',
                        'instructions' => __('Optional — makes the whole card clickable.', 'flynt'),
                        'required' => 0,
                    ],
                ],
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'instructions' => __('Pill button centred below the panel.', 'flynt'),
                'required' => 0,
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
