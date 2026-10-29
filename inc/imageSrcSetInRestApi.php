<?php

add_action('rest_api_init', function () {

    register_rest_field('attachment', 'srcset', array(
        'get_callback' => function ($input) {
            $srcset = wp_get_attachment_image_srcset($input['id']);
            return json_encode($srcset);
        },
        'schema' => array(
            'description' => __('Image sourceset (readonly)'),
            'type' => 'string'
        ),
    ));
});
