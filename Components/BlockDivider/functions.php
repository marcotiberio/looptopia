<?php

namespace Flynt\Components\BlockDivider;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockDivider',
        'label' => __('Divider', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Divider', 'flynt'),
                'name' => '',
                'type' => 'message',
                'message' => 'Add this component to display an horizontal black line as a divider between sections.',
                'new_lines' => 'wpautop',
                'esc_html' => 1,
                'wrapper' =>  [
                    'width' => '100',
                ],
            ],
        ]
    ];
}
