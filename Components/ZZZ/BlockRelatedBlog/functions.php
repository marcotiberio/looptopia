<?php

namespace Flynt\Components\BlockRelatedBlog;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

const POST_TYPE = 'blog';

add_filter('Flynt/addComponentData?name=BlockRelatedBlog', function ($data) {
    $postType = POST_TYPE;

    $data['taxonomies'] = $data['taxonomies'] ?: [];

    $data['items'] = Timber::get_posts([
        'post_status' => 'publish',
        'post_type' => $postType,
        'category' => join(',', array_map(function ($taxonomy) {
            return $taxonomy->term_id;
        }, $data['taxonomies'])),
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $data['options']['maxPosts'],
        'post__not_in' => array(get_the_ID())
    ]);

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockRelatedBlog',
        'label' => 'Related: Blog',
        'sub_fields' => [
            [
                'label' => __('Title', 'flynt'),
                'name' => 'titleTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'headlineTitle',
                'type' => 'text'
            ],
            [
                'label' => __('Content Selection', 'flynt'),
                'name' => 'contentSelectionTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Category', 'flynt'),
                'instructions' => __('Select 1 or more categories or leave empty to show from all posts.', 'flynt'),
                'name' => 'taxonomies',
                'type' => 'taxonomy',
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
                    [
                        'label' => __('Max Number of Items', 'flynt'),
                        'name' => 'maxPosts',
                        'type' => 'number',
                        'default_value' => -1,
                        'min' => -1,
                        'step' => 1
                    ]
                ]
            ],
        ]
    ];
}

// Options::addTranslatable('RelatedPosts', [
//     [
//         'label' => __('Labels', 'flynt'),
//         'name' => 'labelsTab',
//         'type' => 'tab',
//         'placement' => 'top',
//         'endpoint' => 0
//     ],
//     [
//         'label' => '',
//         'name' => 'labels',
//         'type' => 'group',
//         'sub_fields' => [
//             [
//                 'label' => __('All Posts', 'flynt'),
//                 'name' => 'allPosts',
//                 'type' => 'text',
//                 'default_value' => 'See More Posts',
//                 'required' => 1,
//                 'wrapper' => [
//                     'width' => 50
//                 ],
//             ],
//         ],
//     ]
// ]);
