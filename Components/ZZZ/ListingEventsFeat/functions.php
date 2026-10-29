<?php

namespace Flynt\Components\ListingEventsFeat;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'event';

add_filter('Flynt/addComponentData?name=ListingEventsFeat', function ($data) {
    $postType = POST_TYPE;
    $data['taxonomies'] = $data['taxonomies'] ?? [];

    // Fetch all terms from the modus taxonomy
    $data['categories'] = Timber::get_terms([
        'taxonomy'   => 'modus',
        'hide_empty' => false,
    ]);

    // Fetch posts based on selected categories (if any)
    $categoryIds = !empty($data['taxonomies'])
        ? join(',', array_map(fn($taxonomy) => $taxonomy->term_id, $data['taxonomies']))
        : '';

    $queryArgs = [
        'post_status' => 'publish',
        'post_type' => $postType,
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 3,
        'meta_key' => 'end_date',
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ];

    if (!empty($categoryIds)) {
        $queryArgs['tax_query'] = [
            [
                'taxonomy' => 'modus',
                'field'    => 'term_id',
                'terms'    => explode(',', $categoryIds),
            ],
        ];
    }

    $data['posts'] = Timber::get_posts($queryArgs);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingEventsFeat',
        'label' => 'Events (Latest 3)',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'text'
            ],
            [
                'label' => __('Content', 'flynt'),
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
                'wrapper' => [
                    'width' => 100
                ],
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
                    FieldVariables\getColorText(),
                ]
            ]
        ],
    ];
}
