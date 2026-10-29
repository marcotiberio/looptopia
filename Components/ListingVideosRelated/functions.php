<?php

namespace Flynt\Components\ListingVideosRelated;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

// add_filter('Flynt/addComponentData?name=ListingProjectsRelated', function (
//     $data
// ) {
//     $data['projects'] = array_map(function ($item) {
//         if (!empty($item['project'])) {
//             $item['project'] = new \Timber\Post($item['project']->ID ?? $item['project']);
//         }
//         return $item;
//     }, get_field('projects') ?: []);
//     return $data;
// });

add_filter('Flynt/addComponentData?name=ListingVideosRelated', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingVideosRelated',
        'label' => 'Related Videos',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Videos', 'flynt'),
                'name' => 'videos',
                'type' => 'repeater',
                'min' => 1,
                'max' => 4,
                'layout' => 'table',
                'button_label' => __('Add Video', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Video', 'flynt'),
                        'name' => 'video',
                        'type' => 'post_object',
                        'post_type' => [
                            'post',
                        ],
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'object',
                        'ui' => 1,
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100,
                        ]
                    ],
                    [
                        'label' => __('Video starts in column:', 'flynt'),
                        'name' => 'colStart',
                        'type' => 'button_group',
                        'choices' => [
                            'col-start-1 lg:col-start-1' => sprintf('<p>1</p>', __('1', 'flynt')),
                            'col-start-1 lg:col-start-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                            'col-start-1 lg:col-start-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                            'col-start-1 lg:col-start-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                            'col-start-1 lg:col-start-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                            'col-start-1 lg:col-start-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                            'col-start-1 lg:col-start-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                            'col-start-1 lg:col-start-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                            'col-start-1 lg:col-start-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                            'col-start-1 lg:col-start-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                            'col-start-1 lg:col-start-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                            'col-start-1 lg:col-start-12' => sprintf('<p>12</p>', __('12', 'flynt'))
                        ],
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    [
                        'label' => __('Video ends in column:', 'flynt'),
                        'name' => 'colEnd',
                        'type' => 'button_group',
                        'choices' => [
                            'col-end-13 lg:col-end-1' => sprintf('<p>1</p>', __('1', 'flynt')),
                            'col-end-13 lg:col-end-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                            'col-end-13 lg:col-end-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                            'col-end-13 lg:col-end-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                            'col-end-13 lg:col-end-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                            'col-end-13 lg:col-end-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                            'col-end-13 lg:col-end-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                            'col-end-13 lg:col-end-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                            'col-end-13 lg:col-end-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                            'col-end-13 lg:col-end-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                            'col-end-13 lg:col-end-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                            'col-end-13 lg:col-end-13' => sprintf('<p>12</p>', __('12', 'flynt')),
                        ],
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
                        'required' => 0,
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

Options::addTranslatable('ListingVideosRelated', [
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
                'label' => __('Title', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'text'
            ],
        ],
    ]
]);
