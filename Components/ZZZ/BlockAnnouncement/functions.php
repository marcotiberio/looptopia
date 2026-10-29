<?php

namespace Flynt\Components\BlockAnnouncement;

use Flynt\Utils\Options;
use Flynt\FieldVariables;
use Flynt\ComponentManager;

function getACFLayout()
{
    return [
        'name' => 'BlockAnnouncement',
        'label' => 'Block: Announcement',
        'sub_fields' => [
            [
                'label' => 'CTA Link',
                'name' => 'ctaLink',
                'type' => 'link',
                'return_format' => 'array',
                'wrapper' => [
                    'width' => 50
                ]
            ],
        ],
    ];
}

// Options::addTranslatable('BlockAnnouncement', [
//     // [
//     //     'label' => __('Component Status', 'flynt'),
//     //     'name' => 'blockAnnouncementIsEnabled',
//     //     'type' => 'true_false',
//     //     'default_value' => 1,
//     //     'ui' => 1,
//     //     'ui_on_text' => __('Activated', 'flynt'),
//     //     'ui_off_text' => __('Deactivated', 'flynt'),
//     //     'wrapper' => [
//     //         'width' => 50
//     //     ]
//     // ],
//     // [
//     //     'label' => 'CTA Link',
//     //     'name' => 'ctaLink',
//     //     'type' => 'link',
//     //     'return_format' => 'array',
//     //     'wrapper' => [
//     //         'width' => 50
//     //     ]
//     // ],
//     // [
//     //     'label' => __('Read More', 'flynt'),
//     //     'name' => 'readMoreLabel',
//     //     'type' => 'text',
//     //     'default_value' => 'Read More',
//     //     'required' => 1,
//     //     'wrapper' => [
//     //         'width' => 33
//     //     ],
//     // ]
// ]);
