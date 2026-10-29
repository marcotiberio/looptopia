<?php

namespace Flynt\Components\NavigationMainLogo;

use Flynt\Utils\Asset;

add_filter('Flynt/addComponentData?name=NavigationMainLogo', function ($data) {
    $data['logo'] = [
        'src' => get_theme_mod('custom_header_logo') ? get_theme_mod('custom_header_logo') : Asset::requireUrl('Components/NavigationMainLogo/Assets/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});
