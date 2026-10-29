<?php

namespace Flynt\Components\BlockWysiwygTwoCol;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockWysiwygTwoCol',
        'label' => 'Block: Text Editor (2 columns)',
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
                'instructions' => __('Want to add a headline? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'label' => __('Left Column', 'flynt'),
                'name' => 'contentLeftTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'contentHtmlLeft',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLinkLeft',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Color Background', 'flynt'),
                'name' => 'colorBackgroundLeft',
                'type' => 'color_picker',
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            // [
            //     'label' => __('Html Embed', 'flynt'),
            //     'name' => 'embedHtmlLeft',
            //     'type' => 'wysiwyg',
            //     'tabs' => 'visual',
            //     'delay' => 1,
            //     'media_upload' => 0,
            //     'required' => 0,
            // ],
            [
                'label' => __('Right Column', 'flynt'),
                'name' => 'contentRightTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'contentHtmlRight',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLinkRight',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Color Background', 'flynt'),
                'name' => 'colorBackgroundRight',
                'type' => 'color_picker',
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            // [
            //     'label' => __('Html Embed', 'flynt'),
            //     'name' => 'embedHtmlRight',
            //     'type' => 'wysiwyg',
            //     'tabs' => 'visual',
            //     'delay' => 1,
            //     'media_upload' => 0,
            //     'required' => 0,
            // ],
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
        ]
    ];
}
