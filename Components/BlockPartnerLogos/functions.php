<?php

namespace Flynt\Components\BlockPartnerLogos;

use Flynt\FieldVariables;

// Adapted from GridLogosFlexible (marcotiberio/sustainableeconomysummit2025):
// tiered logo rows (title + logo grid). Simplified — dropped the per-row
// half/full width option and trimmed the column choices to 3–6. Adds a trailing
// CTA paragraph/button and a badge image for Looptopia's "Partner & Förderer".

function getACFLayout()
{
    return [
        'name' => 'BlockPartnerLogos',
        'label' => __('Partner Logos', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
                'instructions' => __('e.g. Förderer, Vorreiter, Gestalter, Unterstützer.', 'flynt'),
            ],
            [
                'label' => __('Logo Tiers', 'flynt'),
                'name' => 'logoRows',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => __('Add Tier', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Tier Title', 'flynt'),
                        'name' => 'blockTitle',
                        'type' => 'text',
                        'instructions' => __('e.g. Förderer, Vorreiter, Gestalter, Unterstützer.', 'flynt'),
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Columns', 'flynt'),
                        'name' => 'columns',
                        'type' => 'button_group',
                        'choices' => [
                            '3' => __('3', 'flynt'),
                            '4' => __('4', 'flynt'),
                            '5' => __('5', 'flynt'),
                            '6' => __('6', 'flynt'),
                        ],
                        'default_value' => '5',
                        'wrapper' => ['width' => 50],
                    ],
                    [
                        'label' => __('Logos', 'flynt'),
                        'name' => 'logos',
                        'type' => 'repeater',
                        'layout' => 'block',
                        'button_label' => __('Add Logo', 'flynt'),
                        'min' => 1,
                        'sub_fields' => [
                            [
                                'label' => __('Logo', 'flynt'),
                                'instructions' => __('Image-Format: SVG, PNG, JPG.', 'flynt'),
                                'name' => 'logo',
                                'type' => 'image',
                                'preview_size' => 'medium',
                                'mime_types' => 'svg,png,jpg,jpeg',
                                'wrapper' => ['width' => 50],
                            ],
                            [
                                'label' => __('Link', 'flynt'),
                                'name' => 'logoLink',
                                'type' => 'url',
                                'wrapper' => ['width' => 50],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Call to action', 'flynt'),
                'name' => 'ctaTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Text', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'tabs' => 'visual',
                'delay' => 1,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('Button', 'flynt'),
                'name' => 'buttonLink',
                'type' => 'link',
                'required' => 0,
            ],
            [
                'label' => __('Badge Image', 'flynt'),
                'name' => 'badge',
                'type' => 'image',
                'preview_size' => 'small',
                'instructions' => __('Optional badge, e.g. the Zero-Waste mark.', 'flynt'),
                'required' => 0,
                'mime_types' => 'svg,png,jpg,jpeg',
            ],
            [
                'label' => __('Badge Link', 'flynt'),
                'name' => 'badgeLink',
                'type' => 'url',
                'required' => 0,
            ],
            [
                'label' => __('Badge Caption', 'flynt'),
                'name' => 'badgeCaption',
                'type' => 'text',
                'required' => 0,
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
                ],
            ],
        ],
    ];
}
