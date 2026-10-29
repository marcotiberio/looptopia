<?php

namespace Flynt\Components\BlockJobHeader;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockJobHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});

Options::addTranslatable('BlockJobHeader', [
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Print Button', 'flynt'),
        'name' => 'printButton',
        'type' => 'wysiwyg',
        'tabs' => 'visual',
        'media_upload' => 0,
        'delay' => 0,
    ]
]);
