<?php

namespace Flynt\Components\GridPostSelector;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'post';

add_filter('Flynt/addComponentData?name=GridPostSelector', function ($data) {
    $postType = POST_TYPE;

    $data['items'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $postType,
    ]);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'GridPostSelector',
        'label' => 'Grid: Post Selection',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'headlineTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Content Selection', 'flynt'),
                'name' => 'contentSelectionTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Posts', 'flynt'),
                'name' => 'selectedPosts',
                'type' => 'relationship',
                'post_type' => [
                    'post',
                    'event',
                ],
                'allow_null' => 1,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
                'required' => 0,
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
                        'label' => __('Grid Columns', 'flynt'),
                        'name' => 'gridColumns',
                        'type' => 'button_group',
                        'choices' => [
                            '1' => sprintf('<p>1</p>', __('1', 'flynt')),
                            '2' => sprintf('<p>2</p>', __('2', 'flynt')),
                            '3' => sprintf('<p>3</p>', __('3', 'flynt')),
                            '4' => sprintf('<p>4</p>', __('4', 'flynt')),
                            '5' => sprintf('<p>5</p>', __('5', 'flynt')),
                            '6' => sprintf('<p>6</p>', __('6', 'flynt'))
                        ],
                        'default_value' => '3',
                    ],
                    FieldVariables\getColorBackground(),
                    FieldVariables\getColorText(),
                ]
            ]
        ]
    ];
}

Options::addTranslatable('GridPostSelector', [
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
                'label' => __('Read More', 'flynt'),
                'name' => 'readMore',
                'type' => 'text',
                'default_value' => __('Mehr Erfahren', 'flynt'),
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ]
        ],
    ]
]);
