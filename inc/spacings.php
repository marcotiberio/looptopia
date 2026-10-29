<?php

namespace Flynt\Spacings;

use Flynt\Utils\Options;

add_action('acf/init', function () {
    // Add Spacings fields to Global Options
    Options::addGlobal('Spacings', [
        [
            'label' => __('Spacing - Min', 'flynt'),
            'instructions' => __('Minimum spacing value in pixels (e.g., 5)', 'flynt'),
            'name' => 'spacingMin',
            'type' => 'number',
            'default_value' => 5,
            'min' => 0,
            'max' => 50,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - XS', 'flynt'),
            'instructions' => __('Extra small spacing in pixels (e.g., 12)', 'flynt'),
            'name' => 'spacingXs',
            'type' => 'number',
            'default_value' => 12,
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - SM', 'flynt'),
            'instructions' => __('Small spacing in pixels (e.g., 24)', 'flynt'),
            'name' => 'spacingSm',
            'type' => 'number',
            'default_value' => 24,
            'min' => 0,
            'max' => 200,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - MD', 'flynt'),
            'instructions' => __('Medium spacing in pixels (e.g., 46)', 'flynt'),
            'name' => 'spacingMd',
            'type' => 'number',
            'default_value' => 46,
            'min' => 0,
            'max' => 300,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - LG', 'flynt'),
            'instructions' => __('Large spacing in pixels (e.g., 60)', 'flynt'),
            'name' => 'spacingLg',
            'type' => 'number',
            'default_value' => 60,
            'min' => 0,
            'max' => 400,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - XL', 'flynt'),
            'instructions' => __('Extra large spacing in pixels (e.g., 80)', 'flynt'),
            'name' => 'spacingXl',
            'type' => 'number',
            'default_value' => 80,
            'min' => 0,
            'max' => 500,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - XXL', 'flynt'),
            'instructions' => __('2X large spacing in pixels (e.g., 100)', 'flynt'),
            'name' => 'spacingXxl',
            'type' => 'number',
            'default_value' => 100,
            'min' => 0,
            'max' => 600,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
        [
            'label' => __('Spacing - Max', 'flynt'),
            'instructions' => __('Maximum spacing in pixels (e.g., 120)', 'flynt'),
            'name' => 'spacingMax',
            'type' => 'number',
            'default_value' => 120,
            'min' => 0,
            'max' => 800,
            'step' => 1,
            'wrapper' => [
                'width' => 25,
            ],
        ],
    ], 'Spacings');
});

// Output dynamic CSS variables based on ACF options
add_action('wp_head', function () {
    $spacingOptions = Options::getGlobal('Spacings');
    
    // Get values with fallbacks
    $spacingMin = !empty($spacingOptions['spacingMin']) 
        ? intval($spacingOptions['spacingMin']) 
        : 5;
    $spacingXs = !empty($spacingOptions['spacingXs']) 
        ? intval($spacingOptions['spacingXs']) 
        : 12;
    $spacingSm = !empty($spacingOptions['spacingSm']) 
        ? intval($spacingOptions['spacingSm']) 
        : 24;
    $spacingMd = !empty($spacingOptions['spacingMd']) 
        ? intval($spacingOptions['spacingMd']) 
        : 46;
    $spacingLg = !empty($spacingOptions['spacingLg']) 
        ? intval($spacingOptions['spacingLg']) 
        : 60;
    $spacingXl = !empty($spacingOptions['spacingXl']) 
        ? intval($spacingOptions['spacingXl']) 
        : 80;
    $spacingXxl = !empty($spacingOptions['spacingXxl']) 
        ? intval($spacingOptions['spacingXxl']) 
        : 100;
    $spacingMax = !empty($spacingOptions['spacingMax']) 
        ? intval($spacingOptions['spacingMax']) 
        : 120;
    
    // Build CSS variables
    $css = ":root {\n";
    $css .= "  --spacing-min: {$spacingMin}px;\n";
    $css .= "  --spacing-xs: {$spacingXs}px;\n";
    $css .= "  --spacing-sm: {$spacingSm}px;\n";
    $css .= "  --spacing-md: {$spacingMd}px;\n";
    $css .= "  --spacing-lg: {$spacingLg}px;\n";
    $css .= "  --spacing-xl: {$spacingXl}px;\n";
    $css .= "  --spacing-xxl: {$spacingXxl}px;\n";
    $css .= "  --spacing-max: {$spacingMax}px;\n";
    $css .= "}\n";
    
    echo "<style id='flynt-spacings-css'>\n{$css}\n</style>\n";
}, 100);
