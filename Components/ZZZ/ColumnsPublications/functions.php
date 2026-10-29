<?php

namespace Flynt\Components\ColumnsPublications;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'ColumnsPublications',
        'label' => 'Columns: Publications',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Publications', 'flynt'),
                'name' => 'columnTexts',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add List', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('List Publications', 'flynt'),
                        'name' => 'columnText',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                    ],
                ],
            ],
        ],
    ];
}
