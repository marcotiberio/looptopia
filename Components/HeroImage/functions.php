<?php

namespace Flynt\Components\HeroImage;

use Flynt\FieldVariables;
use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function () {
    register_nav_menus([
        'navigation_main' => __('Navigation Main', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=HeroImage', function ($data) {
    $data['menu'] = Timber::get_menu('navigation_main') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => get_theme_mod('custom_header_logo') ? get_theme_mod('custom_header_logo') : Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'HeroImage',
        'label' => __('Hero (Image)', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Image', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image Desktop', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Image Mobile', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'imageMobile',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 0,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Height', 'flynt'),
                'name' => 'height',
                'type' => 'select',
                'default_value' => 'h-screen',
                'choices' => [
                    'h-screen' => __('Full Screen', 'flynt'),
                    'h-[75vh]' => __('2/3 Screen', 'flynt'),
                ],
                'wrapper' => [
                    'width' => 100
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
                'label' => __('Title', 'flynt'),
                'name' => 'titleHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'toolbar' => 'default',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => 100
                ],
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
        ]

    ];
}
    
