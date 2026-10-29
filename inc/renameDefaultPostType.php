<?php

/*** Remove Default Post Type ***/

add_filter('post_type_labels_post', 'post_rename_labels');

/**
* Rename default post type to news
*
* @param object $labels
* @hooked post_type_labels_post
* @return object $labels
*/
function post_rename_labels($labels)
{
    # Labels
    $labels->name = 'Articles';
    $labels->singular_name = 'Article';

    # Menu
    $labels->menu_name = 'Articles';
    $labels->name_admin_bar = 'Articles';

    return $labels;
}
