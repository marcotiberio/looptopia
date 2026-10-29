<?php

namespace Flynt\Components\BlockPageHeader;

add_filter('Flynt/addComponentData?name=BlockPageHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
