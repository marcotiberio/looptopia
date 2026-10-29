<?php

namespace Flynt\Components\NavigationBurger;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_burger' => __('Navigation Burger', 'flynt'),
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationBurger', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_burger') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => get_theme_mod('custom_header_logo') ? get_theme_mod('custom_header_logo') : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

Options::addTranslatable('NavigationBurger', [
    [
        'label' => __('CTA', 'flynt'),
        'name' => 'ctaTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('CTA Link', 'flynt'),
        'name' => 'ctaMenuItem',
        'type' => 'link',
        'return_format' => 'array',
        'wrapper' =>  [
            'width' => '100',
        ]
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
                'label' => __('Aria Label', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'default_value' => __('Main', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Toggle Menu', 'flynt'),
                'name' => 'toggleMenu',
                'type' => 'text',
                'default_value' => __('Toggle Menu', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],
    [
        'label' => __('Footer Links', 'flynt'),
        'name' => 'footerLinksTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Footer Links', 'flynt'),
        'name' => 'footerLinks',
        'type' => 'repeater',
        'layout' => 'table',
        'button_label' => __('Add Footer Link', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Menu Link', 'flynt'),
                'name' => 'menuLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
        ],
    ],
    [
        'label' => __('Language Menu', 'flynt'),
        'name' => 'languageMenuTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Show Language Menu', 'flynt'),
        'name' => 'showLanguageMenu',
        'type' => 'true_false',
        'default_value' => 1,
        'ui' => 1,
        'ui_on_text' => __('Show', 'flynt'),
        'ui_off_text' => __('Hide', 'flynt'),
        'wrapper' => [
            'width' => 50
        ],
    ],
    [
        'label' => __('Language Menu Position', 'flynt'),
        'name' => 'languageMenuPosition',
        'type' => 'select',
        'choices' => [
            'footer' => __('Footer Area', 'flynt'),
            'main' => __('Main Menu Area', 'flynt'),
        ],
        'default_value' => 'footer',
        'wrapper' => [
            'width' => 50
        ],
    ],
]);
