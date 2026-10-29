<?php

namespace Flynt\Components\ListingProjects;

use Flynt\FieldVariables;
use Timber\Timber;
use Flynt\Utils\Oembed;


add_filter('Flynt/addComponentData?name=ListingProjects', function ($data) {
    if (isset($data['mediaItems']) && is_array($data['mediaItems'])) {
        foreach ($data['projects'] as &$project) {
            if (isset($project['project']) && isset($project['project']->featVideoEmbed) && !empty($project['project']->featVideoEmbed)) {
                $project['project']->featVideoEmbed = Oembed::setSrcAsDataAttribute(
                    $item['oembed'],
                    [
                        'autoplay' => 'true',
                        'loop' => 'true',
                        'muted' => 'true',
                        'controls' => 'false'
                    ]
                );
            }
        }
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingProjects',
        'label' => 'Listing Projects',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Projects', 'flynt'),
                'name' => 'projects',
                'type' => 'repeater',
                'min' => 1,
                'layout' => 'table',
                'button_label' => __('Add Project', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Project', 'flynt'),
                        'name' => 'project',
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
                        'label' => __('Project Width', 'flynt'),
                        'name' => 'width',
                        'type' => 'button_group',
                        'choices' => [
                            'w-full' => sprintf('<p>Full</p>', __('Full', 'flynt')),
                            'w-full lg:w-[calc(50%_-_10px)]' => sprintf('<p>Half</p>', __('Half', 'flynt')),
                            'w-full lg:w-[calc(33.33%_-_10px)]' => sprintf('<p>Third</p>', __('Third', 'flynt')),
                            'w-full lg:w-[calc(25%_-_10px)]' => sprintf('<p>Quarter</p>', __('Quarter', 'flynt')),
                        ],
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    // [
                    //     'label' => __('Project starts in column:', 'flynt'),
                    //     'name' => 'colStart',
                    //     'type' => 'button_group',
                    //     'choices' => [
                    //         'col-start-1 lg:col-start-1' => sprintf('<p>1</p>', __('1', 'flynt')),
                    //         'col-start-1 lg:col-start-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                    //         'col-start-1 lg:col-start-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                    //         'col-start-1 lg:col-start-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                    //         'col-start-1 lg:col-start-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                    //         'col-start-1 lg:col-start-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                    //         'col-start-1 lg:col-start-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                    //         'col-start-1 lg:col-start-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                    //         'col-start-1 lg:col-start-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                    //         'col-start-1 lg:col-start-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                    //         'col-start-1 lg:col-start-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                    //         'col-start-1 lg:col-start-12' => sprintf('<p>12</p>', __('12', 'flynt'))
                    //     ],
                    //     'wrapper' => [
                    //         'width' => 50
                    //     ],
                    // ],
                    // [
                    //     'label' => __('Project ends in column:', 'flynt'),
                    //     'name' => 'colEnd',
                    //     'type' => 'button_group',
                    //     'choices' => [
                    //         'col-end-13 lg:col-end-1' => sprintf('<p>1</p>', __('1', 'flynt')),
                    //         'col-end-13 lg:col-end-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                    //         'col-end-13 lg:col-end-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                    //         'col-end-13 lg:col-end-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                    //         'col-end-13 lg:col-end-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                    //         'col-end-13 lg:col-end-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                    //         'col-end-13 lg:col-end-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                    //         'col-end-13 lg:col-end-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                    //         'col-end-13 lg:col-end-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                    //         'col-end-13 lg:col-end-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                    //         'col-end-13 lg:col-end-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                    //         'col-end-13 lg:col-end-13' => sprintf('<p>12</p>', __('12', 'flynt')),
                    //     ],
                    //     'wrapper' => [
                    //         'width' => 50
                    //     ],
                    // ],
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
                ]
            ]
        ],
    ];
}
