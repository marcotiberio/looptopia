<?php

/**
 * Event CPT (internal key kept as `event` to preserve existing data).
 *
 * Public visitors submit events through the FormEvent component.
 * Submissions are created as `pending` and only become part of the program
 * (and appear on the BlockEventMap) once the client reviews and publishes.
 */

namespace Flynt\CustomPostTypes;

function registerEventPostType()
{
    $labels = [
        'name'                  => _x('Events', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Event', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Events', 'flynt'),
        'name_admin_bar'        => __('Event', 'flynt'),
        'archives'              => __('Event Archives', 'flynt'),
        'all_items'             => __('All Events', 'flynt'),
        'add_new_item'          => __('Add New Event', 'flynt'),
        'add_new'               => __('Add New', 'flynt'),
        'new_item'              => __('New Event', 'flynt'),
        'edit_item'             => __('Edit Event', 'flynt'),
        'update_item'           => __('Update Event', 'flynt'),
        'view_item'             => __('View Event', 'flynt'),
        'view_items'            => __('View Events', 'flynt'),
        'search_items'          => __('Search Events', 'flynt'),
        'not_found'             => __('Not found', 'flynt'),
        'not_found_in_trash'    => __('Not found in Trash', 'flynt'),
        'featured_image'        => __('Featured Image', 'flynt'),
        'set_featured_image'    => __('Set featured image', 'flynt'),
        'remove_featured_image' => __('Remove featured image', 'flynt'),
        'use_featured_image'    => __('Use as featured image', 'flynt'),
        'items_list'            => __('Events list', 'flynt'),
        'items_list_navigation' => __('Events list navigation', 'flynt'),
        'filter_items_list'     => __('Filter events list', 'flynt'),
    ];
    $args = [
        'label'                 => __('Event', 'flynt'),
        'description'           => __('Public event submissions for the program map', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'thumbnail', 'revisions'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 9,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'show_in_rest'          => true,
        'can_export'            => true,
        'has_archive'           => true,
        'rewrite'               => ['slug' => 'event'],
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    ];
    register_post_type('event', $args);
}

add_action('init', '\\Flynt\\CustomPostTypes\\registerEventPostType');

// Use the classic (TinyMCE) editor for events instead of Gutenberg.
add_filter('use_block_editor_for_post_type', function ($useBlockEditor, $postType) {
    if ($postType === 'event') {
        return false;
    }
    return $useBlockEditor;
}, 10, 2);
