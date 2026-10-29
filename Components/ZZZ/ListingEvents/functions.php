<?php

namespace Flynt\Components\ListingEvents;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'event';

add_filter('Flynt/addComponentData?name=ListingEvents', function ($data) {
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
        'posts_per_page' => -1,
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
        'name' => 'ListingEvents',
        'label' => 'Events',
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
                'instructions' => __('Want to add a headline? And a paragraph? Go ahead! Or just leave it empty and nothing will be shown.', 'flynt'),
                'name' => 'headlineTitle',
                'type' => 'text',
            ],
            [
                'label' => __('Modus', 'flynt'),
                'instructions' => __('Select 1 or more modus terms or leave empty to show from all posts.', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'taxonomy' => 'modus',
                'field_type' => 'multi_select',
                'allow_null' => 1,
                'multiple' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object'
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
