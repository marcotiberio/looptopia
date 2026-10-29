<?php

namespace Flynt\Components\BlockWysiwygAccordion;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockWysiwygAccordion',
        'label' => __('Block: Text Editor Accordion', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Columns', 'flynt'),
                'name' => 'columns',
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'max' => 4,
                'button_label' => __('Add Column', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Text starts in column:', 'flynt'),
                        'name' => 'colStart',
                        'type' => 'button_group',
                        'choices' => [
                            'col-start-1 lg:col-start-1' => sprintf('<p>1</p>', __('1', 'flynt')),
                            'col-start-1 lg:col-start-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                            'col-start-1 lg:col-start-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                            'col-start-1 lg:col-start-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                            'col-start-1 lg:col-start-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                            'col-start-1 lg:col-start-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                            'col-start-1 lg:col-start-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                            'col-start-1 lg:col-start-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                            'col-start-1 lg:col-start-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                            'col-start-1 lg:col-start-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                            'col-start-1 lg:col-start-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                            'col-start-1 lg:col-start-12' => sprintf('<p>12</p>', __('12', 'flynt'))
                        ],
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    [
                        'label' => __('Text ends in column:', 'flynt'),
                        'name' => 'colEnd',
                        'type' => 'button_group',
                        'choices' => [
                            'col-end-4 lg:col-end-2' => sprintf('<p>2</p>', __('2', 'flynt')),
                            'col-end-4 lg:col-end-3' => sprintf('<p>3</p>', __('3', 'flynt')),
                            'col-end-4 lg:col-end-4' => sprintf('<p>4</p>', __('4', 'flynt')),
                            'col-end-4 lg:col-end-5' => sprintf('<p>5</p>', __('5', 'flynt')),
                            'col-end-4 lg:col-end-6' => sprintf('<p>6</p>', __('6', 'flynt')),
                            'col-end-4 lg:col-end-7' => sprintf('<p>7</p>', __('7', 'flynt')),
                            'col-end-4 lg:col-end-8' => sprintf('<p>8</p>', __('8', 'flynt')),
                            'col-end-4 lg:col-end-9' => sprintf('<p>9</p>', __('9', 'flynt')),
                            'col-end-4 lg:col-end-10' => sprintf('<p>10</p>', __('10', 'flynt')),
                            'col-end-4 lg:col-end-11' => sprintf('<p>11</p>', __('11', 'flynt')),
                            'col-end-4 lg:col-end-12' => sprintf('<p>12</p>', __('12', 'flynt')),
                            'col-end-4 lg:col-end-13' => sprintf('<p>13</p>', __('13', 'flynt')),
                        ],
                        'wrapper' => [
                            'width' => 50
                        ],
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'instructions' => __('If this is the first column, this will be the title of the whole row. If not the first column, this will be the toggable title of each accordion item.', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'tabs' => 'visual, text',
                        'delay' => 1,
                        'media_upload' => 0,
                        'required' => 0,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'required' => 0,
                        'mime_types' => 'jpg,jpeg,png',
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                    // [
                    //     'label' => __('Button', 'flynt'),
                    //     'name' => 'buttonLink',
                    //     'type' => 'link',
                    //     'required' => 0,
                    //     'wrapper' => [
                    //         'width' => 100
                    //     ],
                    // ],
                    [
                        'label' => __('Enable Newsletter Form', 'flynt'),
                        'name' => 'enableNewsletter',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
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
                    FieldVariables\getFirstComponent(),
                    [
                        'label' => __('Sticky title?', 'flynt'),
                        'name' => 'stickyTitle',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'wrapper' => [
                            'width' => 100
                        ],
                    ],
                ]
            ]
        ]
    ];
} 
