<?php

/**
 * Defines field variables to be used across multiple components.
 */

namespace Flynt\FieldVariables;

function getTheme($default = '')
{
    return [
        'label' => __('Theme', 'flynt'),
        'name' => 'theme',
        'type' => 'select',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'choices' => [
            '' => __('(none)', 'flynt'),
            'light' => __('Light', 'flynt'),
            'dark' => __('Dark', 'flynt'),
        ],
        'default_value' => $default,
    ];
}

function getRawSvg()
{
    return [
        'label' => __('Raw SVG', 'flynt'),
        'instructions' => sprintf(
            'Insert raw svg e. g. from <a ref="%1$s" target="_blank">%1$s</a>',
            'https://heroicons.com/'
        ),
        'name' => 'rawSvg',
        'type' => 'textarea',
        'required' => 1,
        'rows' => 1,
        'new_lines' => '',
    ];
}

function getColorBackground()
{
    return [
        'label' => __('Color Background', 'flynt'),
        'name' => 'colorBackground',
        'type' => 'color_picker',
        'wrapper' => [
            'width' => 100,
        ],
        // [
        //     'label' => __('Color Background', 'flynt'),
        //     'name' => 'colorBackground',
        //     'type' => 'select',
        //     'choices' => [
        //         'bg-transparent' => 'Transparent',
        //         'bg-orange' => 'Orange',
        //     ],
        //     'default_value' => 'transparent',
        //     'required' => 0
        // ],
    ];
}

function getColorText()
{
    return [
        'label' => __('Color Text', 'flynt'),
        'instructions' => sprintf(
            'Overrides text editor color'
        ),
        'name' => 'colorText',
        'type' => 'color_picker',
        'default_value' => '#000000',
        'wrapper' => [
            'width' => 100,
        ],
    ];
}

// function getBoxedTopPadding()
// {
//     return [
//         'label' => __('Custom Top Padding', 'flynt'),
//         'instructions' => sprintf(
//             'Set custom top padding for component.'
//         ),
//         'name' => 'topPadding',
//         'type' => 'select',
//         'choices' => [
//             '0' => 'None',
//             '15px' => 'Default',
//             '5vw' => 'Small', // 50px
//             '8vw' => 'Medium', // 120px
//             '160px' => 'Large',
//         ],
//         'return_format' => 'value',
//         'default_value' => '15px',
//         'wrapper' => [
//             'width' => 50,
//         ],
//     ];
// }

// function getBoxedBottomPadding()
// {
//     return [
//         'label' => __('Custom Bottom Padding', 'flynt'),
//         'instructions' => sprintf(  
//             'Set custom bottom padding for component.'
//         ),
//         'name' => 'bottomPadding',
//         'type' => 'select',
//         'choices' => [
//             '0' => 'None',
//             '15px' => 'Default',
//             '5vw' => 'Small',
//             '8vw' => 'Medium',
//             '160px' => 'Large',
//         ],
//         'return_format' => 'value',
//         'default_value' => '15px',
//         'wrapper' => [
//             'width' => 50,
//         ],
//     ];
// }

function getFirstComponent()
{
    return [
        'label' => __('First Component', 'flynt'),
        'instructions' => sprintf(  
            'Set to "Yes" if this is the first component on the page.'
        ),
        'name' => 'firstComponent',
        'type' => 'true_false',
        'ui' => 1,
        'choices' => [
        'default_value' => '',
            '1' => 'True',
            '0' => 'No',
        ],
        'return_format' => 'value',
        'default_value' => '0',
        'wrapper' => [
            'width' => 100,
        ],
    ];
}
