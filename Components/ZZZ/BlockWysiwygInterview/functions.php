<?php

namespace Flynt\Components\BlockWysiwygInterview;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'BlockWysiwygInterview',
        'label' => __('Block: Text Editor', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Top', 'flynt'),
                'name' => 'topTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Top Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 1,
            ],
            [
                'label' => __('Bottom', 'flynt'),
                'name' => 'bottomTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Bottom Content', 'flynt'),
                'name' => 'contentHtmlBottom',
                'type' => 'wysiwyg',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 1,
            ],
        ]
    ];
}
