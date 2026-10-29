<?php

namespace Flynt\Components\HeroVideoBackground;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

function getACFLayout()
{
    return [
        'name' => 'HeroVideoBackground',
        'label' =>  __('Hero Video', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Subtitle', 'flynt'),
                'name' => 'blockSubtitle',
                'type' => 'text',
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'media_upload' => 0,
                'delay' => 1,
            ],
            [
                'label' => __('Video', 'flynt'),
                'name' => 'multimediaTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            // [
            //     'label' => __('Here you can choose to add either a background image or a background video (desktop and mobile).', 'flynt'),
            //     'name' => 'message',
            //     'type' => 'message',
            //     'message' => '',
            //     'new_lines' => 'wpautop',
            //     'esc_html' => 1
            // ],
            [
                'label' => __('Background Overlay', 'flynt'),
                'instructions' => __('Add overlay to the background', 'flynt'),
                'name' => 'bgOverlay',
                'type' => 'true_false',
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'default_value' => 1,
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            // [
            //     'label' => __('Image', 'flynt'),
            //     'name' => 'image',
            //     'type' => 'image',
            //     'preview_size' => 'medium',
            //     'mime_types' => 'jpg,jpeg,png',
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => 33,
            //     ]
            // ],
            [
                'label' => __('Video', 'flynt'),
                'instructions' => __('Video-Format: mp4.', 'flynt'),
                'name' => 'videoFile',
                'type' => 'file',
                'return_format' => 'url',
                'max_size' => 20,
                'mime_types' => 'mp4',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
            [
                'label' => __('Video Mobile', 'flynt'),
                'instructions' => __('Video-Format: mp4.', 'flynt'),
                'name' => 'videoFileMobile',
                'type' => 'file',
                'return_format' => 'url',
                'mime_types' => 'mp4',
                'wrapper' => [
                    'width' => 33,
                ]
            ],
        ]
    ];
}
