<?php

namespace Flynt\Components\FeatureFlexibleContentExtension;

// Component screenshots are handled by FeatureAdminComponentScreenshots.
add_action('admin_enqueue_scripts', function (): void {
    $data = [
        'labels' => [
            'placeholder' => __('Search...', 'flynt'),
            'noResults' => __('No components found', 'flynt'),
        ],
    ];
    wp_localize_script('Flynt/assets/admin', 'FeatureFlexibleContentExtension', $data);
});
