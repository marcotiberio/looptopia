<?php

namespace Flynt\Components\BlockPilotHeader;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockPilotHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});

Options::addTranslatable('BlockPilotHeader', [
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
                'label' => __('Posted by', 'flynt'),
                'name' => 'postedBy',
                'type' => 'text',
                'default_value' => __('Posted by', 'flynt'),
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('(Posted) in', 'flynt'),
                'name' => 'postedIn',
                'type' => 'text',
                'default_value' => __('in', 'flynt'),
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Reading Time - (20) min read', 'flynt'),
                /* translators: %d: Placeholder for a number */
                'instructions' => __('%d is placeholder for number of minutes', 'flynt'),
                'name' => 'readingTime',
                'type' => 'text',
                /* translators: %d: Placeholder for a number */
                'default_value' => __('%d min read', 'flynt'),
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],
]);
