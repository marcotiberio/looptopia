<?php

namespace Flynt\Components\BlockPostNav;

use Flynt\Utils\Options;

Options::addTranslatable('BlockPostNav', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('All Articles - Blog', 'flynt'),
                'name' => 'allArticlesBlogLink',
                'type' => 'link',
                'wrapper' => [
                    'width' => 33,
                ],
            ],
            [
                'label' => __('All Articles - Veranstaltungen', 'flynt'),
                'name' => 'allArticlesEventsLink',
                'type' => 'link',
                'wrapper' => [
                    'width' => 33,
                ],
            ],
            [
                'label' => __('All Articles - Lernen', 'flynt'),
                'name' => 'allArticlesLernenLink',
                'type' => 'link',
                'wrapper' => [
                    'width' => 33,
                ],
            ],
            [
                'label' => __('Prev Button', 'flynt'),
                'name' => 'prevButton',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Next Button', 'flynt'),
                'name' => 'nextButton',
                'type' => 'text',
                'wrapper' => [
                    'width' => 50,
                ],
            ]
        ],
    ],
]);
