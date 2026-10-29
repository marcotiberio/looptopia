<?php

namespace Flynt\Components\HeroContactForm;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'HeroContactForm',
        'label' => __('Contact Form', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General/ContactForm', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Contact Form', 'flynt'),
                'name' => 'contactForm',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Contact Info', 'flynt'),
                'name' => 'contactInfoTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Contact Info', 'flynt'),
                'name' => 'contactInfo',
                'type' => 'repeater',
                'collapsed' => '',
                'layout' => 'block',
                'button_label' => __('Add Column', 'flynt'),
                'max' => 3,
                'sub_fields' => [
                    [
                        'label' => __('Text Editor', 'flynt'),
                        'name' => 'textEditorHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual',
                        'media_upload' => 0,
                        'delay' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ]
                ]
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
                ]
            ]
        ]
    ];
}
