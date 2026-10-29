<?php

namespace Flynt\Components\BlockFeaturedArticle;

use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockFeaturedArticle', function ($data) {
    if (!empty($data['selectedPost'])) {
        $post = Timber::get_post($data['selectedPost']);
        if ($post) {
            $data['post'] = $post;
            $data['postImage'] = $post->thumbnail();
            $data['halftoneSvg'] = get_post_meta($data['selectedPost'], 'halftone_svg', true);
        }
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockFeaturedArticle',
        'label' => __('Block: Featured Article', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'generalTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Select Post', 'flynt'),
                'instructions' => __('Choose a post to feature.', 'flynt'),
                'name' => 'selectedPost',
                'type' => 'post_object',
                'post_type' => ['post'],
                'return_format' => 'id',
                'multiple' => 0,
                'required' => 1,
                'wrapper' => [
                    'width' => 50,
                ],
            ],
            [
                'label' => __('Height', 'flynt'),
                'name' => 'height',
                'type' => 'select',
                'default_value' => 'h-screen',
                'choices' => [
                    'h-screen' => __('Full Screen', 'flynt'),
                    'h-[75vh]' => __('2/3 Screen', 'flynt'),
                ],
                'wrapper' => [
                    'width' => 50,
                ],
            ],
        ],
    ];
}
