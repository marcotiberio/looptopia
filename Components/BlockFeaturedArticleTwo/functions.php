<?php

namespace Flynt\Components\BlockFeaturedArticleTwo;

use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockFeaturedArticleTwo', function ($data) {
    $posts = [];

    if (!empty($data['selectedPosts'])) {
        foreach ($data['selectedPosts'] as $postId) {
            $post = Timber::get_post($postId);
            if ($post) {
                $posts[] = $post;
            }
        }
    }

    $data['posts'] = $posts;

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockFeaturedArticleTwo',
        'label' => __('Block: Featured Article (x2)', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Select Posts', 'flynt'),
                'instructions' => __('Choose exactly 2 posts to feature.', 'flynt'),
                'name' => 'selectedPosts',
                'type' => 'post_object',
                'post_type' => ['post'],
                'return_format' => 'id',
                'multiple' => 1,
                'min' => 2,
                'max' => 2,
                'required' => 1,
            ],
        ],
    ];
}
