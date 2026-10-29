<?php

namespace Flynt\Components\ListingArticles;

use Timber\Timber;

add_filter('Flynt/addComponentData?name=ListingArticles', function ($data) {
    $queryArgs = [
        'post_status'         => 'publish',
        'post_type'           => 'post',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => 6,
        'orderby'             => 'date',
        'order'               => 'DESC',
    ];

    $posts = Timber::get_posts($queryArgs);

    $data['articles'] = [];
    foreach ($posts as $post) {
        $data['articles'][] = [
            'post'        => $post,
            'postImage'   => $post->thumbnail(),
            'halftoneSvg' => get_post_meta($post->ID, 'halftone_svg', true),
        ];
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'ListingArticles',
        'label' => __('Listing: Articles', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Block Title', 'flynt'),
                'instructions' => __('Title displayed above the articles grid.', 'flynt'),
                'name' => 'blockTitle',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'label' => __('See All Link', 'flynt'),
                'instructions' => __('Link for the "See All" button.', 'flynt'),
                'name' => 'seeAllLink',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
            ],
        ],
    ];
}
