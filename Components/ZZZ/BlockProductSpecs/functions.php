<?php

namespace Flynt\Components\BlockProductSpecs;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockProductSpecs', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockProductSpecs',
        'label' => __('Cover Specifications', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Description', 'flynt'),
                'name' => 'descriptionTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'media_upload' => 0,
                'delay' => 1,
                'wrapper' => [
                    'width' => 100,
                ]
            ],
            [
                'label' => __('Table', 'flynt'),
                'name' => 'tableTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Material', 'flynt'),
                'name' => 'tableMaterial',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Softening Temperature', 'flynt'),
                'name' => 'tableSofteningTemperature',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Impact Strength', 'flynt'),
                'name' => 'tableImpactStrength',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Tensile Strength', 'flynt'),
                'name' => 'tableTensileStrength',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Colors', 'flynt'),
                'name' => 'tableColors',
                'type' => 'text',
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Product Images', 'flynt'),
                'name' => 'carouselTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Images', 'flynt'),
                'name' => 'carousel',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Image', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                        'name' => 'productImage',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'required' => 1,
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'wrapper' =>  [
                            'width' => 50,
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Technical Drawing', 'flynt'),
                'name' => 'techDrawingTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Technical Drawing', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, SVG.', 'flynt'),
                'name' => 'techDrawing',
                'type' => 'image',
                'preview_size' => 'medium',
                'required' => 1,
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'wrapper' =>  [
                    'width' => 100,
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
                    ],
                ]
            ]
        ]
    ];
}

Options::addTranslatable('BlockProductSpecs', [
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
                'label' => __('Material', 'flynt'),
                'name' => 'tableMaterial',
                'type' => 'text',
                'default_value' => __('Material', 'flynt'),
                'wrapper' => [
                    'width' => 25,
                ],
            ],
            [
                'label' => __('Softening Temperature', 'flynt'),
                'name' => 'tableSofteningTemperature',
                'type' => 'text',
                'default_value' => __('Softening Temperature', 'flynt'),
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Impact Strength', 'flynt'),
                'name' => 'tableImpactStrength',
                'type' => 'text',
                'default_value' => __('Impact Strength', 'flynt'),
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Tensile Strength', 'flynt'),
                'name' => 'tableTensileStrength',
                'type' => 'text',
                'default_value' => __('Tensile Strength', 'flynt'),
                'wrapper' => [
                    'width' => 25
                ]
            ],
            [
                'label' => __('Colors', 'flynt'),
                'name' => 'tableColors',
                'type' => 'text',
                'default_value' => __('Colors', 'flynt'),
                'wrapper' => [
                    'width' => 25
                ]
            ],
        ],
    ],
]);
