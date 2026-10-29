<?php

/**
 * Extra columns for the Event list table in wp-admin.
 *
 * Shows the submitting organisation next to the title so the client can
 * scan pending submissions without opening each entry.
 */

namespace Flynt\Event;

// The hook names below are built at load time from POST_TYPE, and FileLoader
// requires inc/*.php in filesystem order — so pull the definitions in first.
require_once __DIR__ . '/eventFields.php';

// Insert the column directly after the title.
add_filter('manage_' . POST_TYPE . '_posts_columns', function ($columns) {
    $output = [];

    foreach ($columns as $key => $label) {
        $output[$key] = $label;

        if ($key === 'title') {
            $output['orgName'] = __('Name der Organisation', 'flynt');
        }
    }

    return $output;
});

add_action('manage_' . POST_TYPE . '_posts_custom_column', function ($column, $postId) {
    if ($column !== 'orgName') {
        return;
    }

    $orgName = get_field('orgName', $postId);

    echo $orgName ? esc_html($orgName) : '—';
}, 10, 2);

add_filter('manage_edit-' . POST_TYPE . '_sortable_columns', function ($columns) {
    $columns['orgName'] = 'orgName';

    return $columns;
});

add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('orderby') !== 'orgName') {
        return;
    }

    $query->set('meta_key', 'orgName');
    $query->set('orderby', 'meta_value');
});
