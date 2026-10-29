<?php

namespace Flynt\Components\BlockEmbed;

use Flynt\FieldVariables;

function getACFLayout()
{
    return [
        'name' => 'blockEmbed',
        'label' => __('Embed', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text & embed position', 'flynt'),
                'name' => 'textPosition',
                'type' => 'button_group',
                'choices' => [
                    'left' => sprintf('<i class=\'dashicons dashicons-align-left\' title=\'%1$s\'></i>', __('Embed on the left (half-width)', 'flynt')),
                    'center_narrow' => sprintf('<i class=\'dashicons dashicons-align-center\' title=\'%1$s\'></i>', __('Embed centered (narrow)', 'flynt')),
                    'center_full' => sprintf('<i class=\'dashicons dashicons-menu-alt3\' title=\'%1$s\'></i>', __('Embed centered (full-width)', 'flynt')),
                    'right' => sprintf('<i class=\'dashicons dashicons-align-right\' title=\'%1$s\'></i>', __('Embed on the right (half-width)', 'flynt'))
                ],
                'default_value' => 'center_full',
            ],
            [
                'label' => __('Text', 'flynt'),
                'instructions' => __('Optional text shown above the embed.', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual,text',
                'delay' => 1,
                'media_upload' => 1,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'required' => 0,
                'wrapper' => [
                    'width' => 100
                ],
            ],
            [
                'label' => __('Embed', 'flynt'),
                'name' => 'embedTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Embed Code', 'flynt'),
                'instructions' => __('Paste the raw embed code here, e.g. an &lt;iframe&gt; or a provider script. Output as-is, so only paste code from sources you trust.', 'flynt'),
                'name' => 'embedHtml',
                'type' => 'textarea',
                'rows' => 6,
                'new_lines' => '',
                'required' => 1,
            ],
            [
                'label' => __('Full-screen embed?', 'flynt'),
                'instructions' => __('Stretch the embed to the full width of its container instead of keeping its own width.', 'flynt'),
                'name' => 'fluidEmbed',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
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
