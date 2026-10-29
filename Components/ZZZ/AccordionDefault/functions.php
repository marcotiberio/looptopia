<?php

namespace Flynt\Components\AccordionDefault;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'AccordionDefault',
        'label' => 'Block: Curriculum',
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
                'name' => 'preContent',
                'type' => 'text'
            ],
            [
                'label' => __('Accordion Panels', 'flynt'),
                'name' => 'accordionPanels',
                'type' => 'repeater',
                'layout' => 'row',
                'min' => 1,
                'button_label' => __('Add Accordion Panel', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Panel Title', 'flynt'),
                        'name' => 'panelTitle',
                        'type' => 'text'
                    ],
                    [
                        'label' => __('Panel Content', 'flynt'),
                        'name' => 'panelContent',
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
