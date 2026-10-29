<?php

namespace Flynt\Components\ListingJournalRelated;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'journal';

add_filter('Flynt/addComponentData?name=ListingJournalRelated', function ($data) {
    $postType = POST_TYPE;

    // Get the current post's journal categories
    $currentPostCategories = get_the_terms(get_the_ID(), 'journal-category');
    $categoryIds = [];
    
    if (!empty($currentPostCategories) && !is_wp_error($currentPostCategories)) {
        $categoryIds = array_map(function ($category) {
            return $category->term_id;
        }, $currentPostCategories);
    }

    // Build the query arguments
    $queryArgs = [
        'post_status' => 'publish',
        'post_type' => $postType,
        'posts_per_page' => 4,
        'ignore_sticky_posts' => 1,
        'post__not_in' => array(get_the_ID()),
        'meta_key'            => 'end_date',
        'orderby'             => 'meta_value',
        'order'               => 'DESC',
    ];

    // Only add category filter if we have categories
    if (!empty($categoryIds)) {
        $queryArgs['tax_query'] = [
            [
                'taxonomy' => 'journal-category',
                'field' => 'term_id',
                'terms' => $categoryIds,
                'operator' => 'IN'
            ]
        ];
    }

    $data['items'] = Timber::get_posts($queryArgs);

    $data['postTypeArchiveLink'] = get_post_type_archive_link($postType);

    return $data;
});

add_filter('Flynt/addComponentData?name=ListingJournalRelated', function ($data) {
    $translatableOptions = Options::getTranslatable('SliderOptions');
    $data['jsonData'] = [
        'options' => array_merge($translatableOptions, $data['options']),
    ];
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingJournalRelated',
        'label' => 'Listing: Journal Related',
        'sub_fields' => [
            [
                'label' => __('General', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Categories', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
                'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
                'taxonomy' => 'category',
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
                    FieldVariables\getTheme(),
                    [
                        'label' => __('Enable Autoplay', 'flynt'),
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1
                    ],
                    [
                        'label' => __('Autoplay Speed (in milliseconds)', 'flynt'),
                        'name' => 'autoplaySpeed',
                        'type' => 'number',
                        'min' => 2000,
                        'step' => 1,
                        'default_value' => 4000,
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'autoplay',
                                    'operator' => '==',
                                    'value' => 1
                                ]
                            ]
                        ],
                    ]
                ]
            ],
        ]
    ];
}

Options::addTranslatable('ListingJournalRelated', [
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
                'label' => __('Title', 'flynt'),
                'name' => 'preContentHtml',
                'type' => 'text'
            ],
        ],
    ]
]);
