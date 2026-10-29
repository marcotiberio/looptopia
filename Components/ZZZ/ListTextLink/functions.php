<?php

namespace Flynt\Components\ListTextLink;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ListTextLink',
        'label' => 'List: Text/Link',
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
                'name' => 'headlineTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Press List', 'flynt'),
                'name' => 'ListPress',
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'pressTitle',
                        'type' => 'text',
                        'wrapper' => [
                            'width' => 50
                        ]
                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'pressLink',
                        'type' => 'link',
                        'return_format' => 'array',
                        'wrapper' => [
                            'width' => 50
                        ]
                    ],
                ],
            ],
        ],
    ];
}
