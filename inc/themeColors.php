<?php

/**
 * Theme color definitions
 * This file reads colors from theme.json and makes them available to PHP
 * 
 * To keep colors in sync, update theme.json and this file will automatically use those values
 */

namespace Flynt\ThemeColors;

/**
 * Get theme.json file path
 * 
 * @return string Path to theme.json file
 */
function getThemeJsonPath()
{
    return get_template_directory() . '/theme.json';
}

/**
 * Read and parse theme.json
 * 
 * @return array|null Parsed theme.json data or null if file doesn't exist
 */
function getThemeJson()
{
    static $themeJson = null;
    
    if ($themeJson === null) {
        $filePath = getThemeJsonPath();
        
        if (!file_exists($filePath)) {
            $themeJson = false;
            return null;
        }
        
        $content = file_get_contents($filePath);
        $themeJson = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $themeJson = false;
            return null;
        }
    }
    
    return $themeJson === false ? null : $themeJson;
}

/**
 * Get color palette from theme.json
 * Uses WordPress native WP_Theme_JSON_Resolver if available, otherwise reads file directly
 * 
 * @return array Array of color objects with 'slug', 'color', and 'name'
 */
function getThemeJsonPalette()
{
    // Try WordPress native resolver first (WordPress 5.8+)
    if (class_exists('WP_Theme_JSON_Resolver')) {
        try {
            $theme_data = \WP_Theme_JSON_Resolver::get_theme_data();
            $settings = $theme_data->get_settings();
            
            if (isset($settings['color']['palette']['theme']) && !empty($settings['color']['palette']['theme'])) {
                return $settings['color']['palette']['theme'];
            }
        } catch (\Exception $e) {
            // Fall through to file reading
        }
    }
    
    // Fallback: Read theme.json file directly
    $themeJson = getThemeJson();
    
    if (!$themeJson || !isset($themeJson['settings']['color']['palette'])) {
        // Fallback to default colors if theme.json doesn't exist or is invalid
        return [
            ['slug' => 'white', 'color' => '#ffffff', 'name' => 'White'],
            ['slug' => 'black', 'color' => '#000000', 'name' => 'Black'],
            ['slug' => 'grey', 'color' => '#f1f1f1', 'name' => 'Grey'],
            ['slug' => 'green', 'color' => '#00ff48', 'name' => 'Green'],
            ['slug' => 'yellow', 'color' => '#fff000', 'name' => 'Yellow'],
            ['slug' => 'blue', 'color' => '#3d6bff', 'name' => 'Blue'],
            ['slug' => 'beige', 'color' => '#fef7ca', 'name' => 'Beige'],
        ];
    }
    
    return $themeJson['settings']['color']['palette'];
}

/**
 * Get theme colors array
 * 
 * @return array Associative array of color slug => hex value
 */
function getThemeColors()
{
    $palette = getThemeJsonPalette();
    $colors = [];
    
    foreach ($palette as $color) {
        $colors[$color['slug']] = $color['color'];
    }
    
    return $colors;
}

/**
 * Get ACF color picker palette
 * 
 * @return array Array of [color, label] pairs for ACF color_picker field
 */
function getAcfColorPalette()
{
    $palette = getThemeJsonPalette();
    $acfPalette = [];
    
    foreach ($palette as $color) {
        $acfPalette[] = [$color['color'], $color['name']];
    }
    
    return $acfPalette;
}

/**
 * Get TinyMCE textcolor_map
 * 
 * @return array Flat array of [color, label, color, label, ...] for TinyMCE
 */
function getTinyMceColorMap()
{
    $palette = getThemeJsonPalette();
    $colorMap = [];
    
    foreach ($palette as $color) {
        // Remove # from hex color for TinyMCE
        $hex = str_replace('#', '', $color['color']);
        $colorMap[] = $hex;
        $colorMap[] = $color['name'];
    }
    
    return $colorMap;
}

/**
 * Integrate with ACF Color Restrict plugin
 * 
 * This provides theme colors from theme.json to the ACF Color Restrict plugin.
 * The plugin will use these colors to restrict the color picker palette.
 * 
 * Common filter names used by ACF Color Restrict plugins:
 * - 'acf_color_restrict_palette' (most common)
 * - 'acf_color_restrict_colors'
 * - 'acf/color_picker_args'
 * - 'acf/load_field/type=color_picker'
 * 
 * We try multiple approaches to ensure compatibility with different plugin versions.
 */

// Get theme colors as simple array of hex values
function getThemeColorsArray()
{
    $palette = getAcfColorPalette();
    return array_column($palette, 0);
}

// Primary filter for ACF Color Restrict (most common)
add_filter('acf_color_restrict_palette', function ($palette) {
    return getThemeColorsArray();
}, 20);

// Alternative filter name
add_filter('acf_color_restrict_colors', function ($colors) {
    return getThemeColorsArray();
}, 20);

// ACF color picker args filter (used by some plugins)
add_filter('acf/color_picker_args', function ($args, $field) {
    $colors = getThemeColorsArray();
    
    // Add palettes to color picker args
    $args['palettes'] = $colors;
    
    return $args;
}, 20, 2);

// Direct ACF field modification (alternative approach)
add_filter('acf/load_field/type=color_picker', function ($field) {
    $colors = getThemeColorsArray();
    
    // Store palette in field settings for plugin to pick up
    $field['palette'] = $colors;
    
    return $field;
}, 20);

/**
 * Register WordPress editor color palette from theme.json
 * This makes colors available to the block editor and can help with ACF integration
 */
add_action('after_setup_theme', function () {
    $palette = getThemeJsonPalette();
    
    if (!empty($palette)) {
        $editorPalette = [];
        
        foreach ($palette as $color) {
            $editorPalette[] = [
                'name' => $color['name'],
                'slug' => $color['slug'],
                'color' => $color['color'],
            ];
        }
        
        add_theme_support('editor-color-palette', $editorPalette);
    }
}, 20);

/**
 * Localize theme colors for JavaScript
 * This makes colors available in admin.js for any custom functionality
 */
add_action('admin_enqueue_scripts', function () {
    $palette = getAcfColorPalette();
    $colors = [];
    
    foreach ($palette as $item) {
        $colors[] = [
            'color' => $item[0],
            'label' => $item[1],
        ];
    }
    
    wp_localize_script('Flynt/assets/admin', 'FlyntThemeColors', [
        'palette' => $colors,
        'colors' => getThemeColors(),
    ]);
}, 20);
