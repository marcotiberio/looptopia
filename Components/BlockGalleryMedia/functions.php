<?php

namespace Flynt\Components\BlockGalleryMedia;

use Flynt\FieldVariables;
use Flynt\Utils\Oembed;

add_filter('Flynt/addComponentData?name=BlockGalleryMedia', function ($data) {
    if (isset($data['mediaItems']) && is_array($data['mediaItems'])) {
        foreach ($data['mediaItems'] as &$item) {
            if (isset($item['acf_fc_layout']) && $item['acf_fc_layout'] === 'oembed' && isset($item['oembed'])) {
                $item['oembed'] = Oembed::setSrcAsDataAttribute(
                    $item['oembed'],
                    [
                        'autoplay' => 'true',
                        'loop' => 'true',
                        'muted' => 'true',
                        'controls' => 'false'
                    ]
                );
            }
        }
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockGalleryMedia',
        'label' => 'Block: Gallery Media',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Media Items', 'flynt'),
                'name' => 'mediaItems',
                'type' => 'flexible_content',
                'button_label' => __('Add Gallery Item', 'flynt'),
                'layouts' => [
                    [
                        'name' => 'image',
                        'label' => __('Image', 'flynt'),
                        'display' => 'block',
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
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Text ends in column:', 'flynt'),
                                'name' => 'colEnd',
                                'type' => 'button_group',
                                'choices' => [
                                    'col-end-4 lg:col-end-1' => sprintf('<p>1</p>', __('1', 'flynt')),
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
                                    'col-end-4 lg:col-end-13' => sprintf('<p>12</p>', __('12', 'flynt')),
                                ],
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Align Vertically', 'flynt'),
                                'name' => 'alignY',
                                'type' => 'button_group',
                                'choices' => [
                                    'lg:items-start' => sprintf('<p>Top</p>', __('Top', 'flynt')),
                                    'lg:items-center' => sprintf('<p>Center</p>', __('Center', 'flynt')),
                                    'lg:items-end' => sprintf('<p>Bottom</p>', __('Bottom', 'flynt')),
                                ],
                                'default_value' => 'lg:items-start',
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Image', 'flynt'),
                                'name' => 'image',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'instructions' => __('Image-Format: JPG, PNG.', 'flynt'),
                                'required' => 1,
                                'mime_types' => 'jpg,jpeg,png',
                                'wrapper' => [
                                    'width' => 80
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => 'video_upload',
                        'label' => __('Video Upload', 'flynt'),
                        'display' => 'block',
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
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Text ends in column:', 'flynt'),
                                'name' => 'colEnd',
                                'type' => 'button_group',
                                'choices' => [
                                    'col-end-4 lg:col-end-1' => sprintf('<p>1</p>', __('1', 'flynt')),
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
                                    'col-end-4 lg:col-end-13' => sprintf('<p>12</p>', __('12', 'flynt')),
                                ],
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Align Vertically', 'flynt'),
                                'name' => 'alignY',
                                'type' => 'button_group',
                                'choices' => [
                                    'lg:items-start' => sprintf('<p>Top</p>', __('Top', 'flynt')),
                                    'lg:items-center' => sprintf('<p>Center</p>', __('Center', 'flynt')),
                                    'lg:items-end' => sprintf('<p>Bottom</p>', __('Bottom', 'flynt')),
                                ],
                                'default_value' => 'lg:items-start',
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Video File', 'flynt'),
                                'name' => 'video',
                                'type' => 'file',
                                'required' => 1,
                                'instructions' => __('Video-Format: MP4, MOV. Recommended: MP4 for best compatibility.', 'flynt'),
                                'mime_types' => 'mp4, mov',
                                'return_format' => 'array',
                                'wrapper' => [
                                    'width' => 100
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => 'oembed',
                        'label' => __('Video Embed', 'flynt'),
                        'display' => 'block',
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
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Text ends in column:', 'flynt'),
                                'name' => 'colEnd',
                                'type' => 'button_group',
                                'choices' => [
                                    'col-end-4 lg:col-end-1' => sprintf('<p>1</p>', __('1', 'flynt')),
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
                                    'col-end-4 lg:col-end-13' => sprintf('<p>12</p>', __('12', 'flynt')),
                                ],
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Align Vertically', 'flynt'),
                                'name' => 'alignY',
                                'type' => 'button_group',
                                'choices' => [
                                    'lg:items-start' => sprintf('<p>Top</p>', __('Top', 'flynt')),
                                    'lg:items-center' => sprintf('<p>Center</p>', __('Center', 'flynt')),
                                    'lg:items-end' => sprintf('<p>Bottom</p>', __('Bottom', 'flynt')),
                                ],
                                'default_value' => 'lg:items-start',
                                'wrapper' => [
                                    'width' => 33
                                ],
                            ],
                            [
                                'label' => __('Video Embed ID - Landscape', 'flynt'),
                                'name' => 'videoIDLandscape',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => 50
                                ],
                            ],
                            [
                                'label' => __('Video Embed ID - Portrait', 'flynt'),
                                'name' => 'videoIDPortrait',
                                'type' => 'text',
                                'wrapper' => [
                                    'width' => 50
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
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