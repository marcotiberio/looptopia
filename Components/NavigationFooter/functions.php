<?php

namespace Flynt\Components\NavigationFooter;

use Flynt\Utils\Options;
use Flynt\Utils\Asset;
use Flynt\Shortcodes;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_footer' => __('Navigation Footer', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function ($data) {
    $data['maxLevel'] = 0;
    $data['menu'] = Timber::get_menu('navigation_footer') ?? Timber::get_pages_menu();
    $data['wordmarkUrl'] = Asset::requireUrl('Components/NavigationFooter/Assets/wordmark.svg');

    return $data;
});

Options::addTranslatable('NavigationFooter', [
    // [
    //     'label' => __('Logo', 'flynt'),
    //     'name' => 'logoTab',
    //     'type' => 'tab',
    //     'placement' => 'top',
    //     'endpoint' => 0
    // ],
    // [
    //     'label' => __('Logo', 'flynt'),
    //     'name' => 'logoFooter',
    //     'type' => 'image',
    //     'preview_size' => 'medium',
    //     'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
    //     'required' => 0,
    //     'mime_types' => 'jpg,jpeg,png,svg',
    //     'wrapper' =>  [
    //         'width' => 100,
    //     ]
    // ],
    [
        'label' => __('Funders', 'flynt'),
        'name' => 'fundersTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
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
                'button_label' => __('Add Funder Logo', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Logo', 'flynt'),
                        'name' => 'logo',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 1,
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => [
                            'width' => 50,
                        ],
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'link',
                        'type' => 'link',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 50,
                        ],
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
                'instructions' => __('e.g. "Partner".', 'flynt'),
                'required' => 0,
            ],
            [
                'label' => __('Logos', 'flynt'),
                'name' => 'logos',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Funder Logo', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Logo', 'flynt'),
                        'name' => 'logo',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 1,
                        'mime_types' => 'jpg,jpeg,png,svg',
                        'wrapper' => [
                            'width' => 50,
                        ],
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'link',
                        'type' => 'link',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 50,
                        ],
                    ],
                ],
            ],
        ],
    ],
    [
        'label' => __('Content', 'flynt'),
        'name' => 'contentTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Columns', 'flynt'),
        'name' => 'columns',
        'type' => 'repeater',
        'layout' => 'block',
        'min' => 1,
        'max' => 4,
        'button_label' => __('Add Column', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
            // [
            //     'label' => __('Enable Newsletter Form', 'flynt'),
            //     'name' => 'enableNewsletter',
            //     'type' => 'true_false',
            //     'default_value' => 0,
            //     'ui' => 1,
            //     'wrapper' => [
            //         'width' => 50
            //     ],
            // ],
        ]
    ],
    [
        'label' => __('Newsletter', 'flynt'),
        'name' => 'newsletterTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Newsletter Link', 'flynt'),
        'name' => 'newsletterLink',
        'type' => 'link',
        'required' => 0,
        'wrapper' => [
            'width' => 100
        ],
    ],
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Light Theme', 'flynt'),
                'name' => 'themeLight',
                'type' => 'text',
                'default_value' => __('Dunkel', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Dark Theme', 'flynt'),
                'name' => 'themeDark',
                'type' => 'text',
                'default_value' => __('Hell', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Aria Label', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'default_value' => __('Footer', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '100',
                ],
            ],
        ],
    ],
]);
