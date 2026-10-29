<?php

namespace Flynt\Components\BlockProgramm;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

function getACFLayout()
{
    return [
        'name' => 'BlockProgramm',
        'label' => 'Programm',
        'sub_fields' => [
            [
                'label' => '',
                'name' => 'message',
                'type' => 'message',
                'message' => __('This is a default component to show all the posts in your intel archive', 'flynt'),
            ],
        ]
    ];
}

// Options::addGlobal('BlockProgramm', [
//     [
//         'label' => __('Load More Button?', 'flynt'),
//         'name' => 'loadMore',
//         'type' => 'true_false',
//         'default_value' => 1,
//         'ui' => 1
//     ],
// ]);

// Options::addTranslatable('BlockProgramm', [
//     [
//         'label' => __('Labels', 'flynt'),
//         'name' => 'labelsTab',
//         'type' => 'tab',
//         'placement' => 'top',
//         'endpoint' => 0
//     ],
//     [
//         'label' => '',
//         'name' => 'labels',
//         'type' => 'group',
//         'sub_fields' => [
//             [
//                 'label' => __('Reading Time', 'flynt'),
//                 'name' => 'loadMore',
//                 'type' => 'text',
//                 'default_value' => 'Load More'
//             ]
//         ],
//     ]
// ]);
