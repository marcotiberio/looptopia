<?php

namespace Flynt\Components\BlockPress;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockPress',
        'label' => 'Block: Press',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Year', 'flynt'),
                'name' => 'pressYear',
                'type' => 'text',
            ],
            [
                'label' => __('Publications', 'flynt'),
                'name' => 'columnTexts',
                'type' => 'repeater',
                'layout' => 'table',
                'min' => 1,
                'button_label' => __('Add Article', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'articleLink',
                        'type' => 'url',
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'articleTitle',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'articleDescription',
                        'type' => 'text',
                    ]
                ],
            ],
        ],
    ];
}
