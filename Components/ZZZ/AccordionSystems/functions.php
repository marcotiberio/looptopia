<?php

namespace Flynt\Components\AccordionSystems;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=AccordionSystems', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'AccordionSystems',
        'label' => 'Block: Systems',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Header', 'flynt'),
                'name' => 'header',
                'type' => 'text',
                'wrapper' => [
                    'width' => 100
                ],
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => 100
                ],
            ],
            [
                'label' => __('Inner Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Systems', 'flynt'),
                'name' => 'accordionPanels',
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'button_label' => __('Add System', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Text', 'flynt'),
                        'name' => 'textTab',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0
                    ],
                    [
                        'label' => __('Panel Title', 'flynt'),
                        'name' => 'panelTitle',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => 33
                        ],
                    ],
                    [
                        'label' => __('Panel Content', 'flynt'),
                        'name' => 'panelContent',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual,text',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'imageTab',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    [
                        'label' => __('Carousel', 'flynt'),
                        'name' => 'carouselTab',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0
                    ],
                    [
                        'label' => __('Carousel', 'flynt'),
                        'name' => 'carousel',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'min' => 1,
                        'button_label' => __('Add Image', 'flynt'),
                        'sub_fields' => [
                            [
                                'label' => __('Image', 'flynt'),
                                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                                'name' => 'systemImage',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'required' => 0,
                                'mime_types' => 'jpg,jpeg,png,svg,webp',
                                'wrapper' =>  [
                                    'width' => 50,
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => __('Video', 'flynt'),
                        'name' => 'videoTab',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0
                    ],
                    [
                        'label' => __('Video: Vimeo', 'flynt'),
                        'name' => 'vimeoCode',
                        'type' => 'text',
                        'required' => 0,
                        'instructions' => __('https://vimeo.com/XXXXXXXXX', 'flynt'),
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
                    [
                        'label' => __('Enable Autoplay', 'flynt'),
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1
                    ],
                    [
                        'label' => __('Autoplay Speed (in milliseconds)', 'flynt'),
                        'name' => 'autoplaySpeed',
                        'type' => 'number',
                        'min' => 2000,
                        'step' => 1,
                        'default_value' => 4000,
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'autoplay',
                                    'operator' => '==',
                                    'value' => 1
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ],
    ];
}
