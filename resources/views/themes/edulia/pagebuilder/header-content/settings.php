<?php

return [
    'id' => 'header-content',
    'name' => __('edulia.header_content'),
    'icon' => '<i class="icon-clipboard"></i>',
    'tab' => "Header",
    'fields' => [
        [
            'id' => 'header_top_menu',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.topbar_display'),
            'label_desc' => '',
            'options' => [
                1 => 'Show',
                0 => 'Hide',
            ],
            'default' => 1,
            'value' => 1,
        ],
        [
            'id'                => 'header-left-menus',
            'type'              => 'repeater',
            'label_title'       => __('edulia.topbar_left_content'),
            'repeater_title'    => __('edulia.content'),
            'multi'             => true,
            'fields'            =>
                [
                    [
                        'id'            => 'header-left-menu-icon-class',
                        'type'          => 'text',
                        'value'         => 'fa fa-phone-alt',
                        'class'         => 'icon_picker',
                        'label_title'   => __('edulia.topbar_icon_class'),
                        'placeholder'   => __('edulia.enter_icon_class'),
                    ],
                    [
                        'id'            => 'header-left-menu-label',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => ' +1 (123) 456-7890',
                        'label_title'   => __('edulia.topbar_label'),
                        'placeholder'   => __('edulia.enter_label'),
                    ],
                    [
                        'id'            => 'header-left-menu-icon-url',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => '',
                        'label_title'   => __('edulia.topbar_url'),
                        'placeholder'   => __('edulia.enter_url'),
                    ],
                ],
        ],
        [
            'id'                => 'header-right-menus',
            'type'              => 'repeater',
            'label_title'       => __('edulia.right_topbar_content'),
            'repeater_title'    => __('edulia.content'),
            'multi'             => true,
            'fields'            =>
                [
                    [
                        'id'            => 'header-right-icon-class',
                        'type'          => 'text',
                        'value'         => 'fab fa-twitter',
                        'class'         => 'icon_picker',
                        'label_title'   => __('edulia.enter_icon_class'),
                        'placeholder'   => __('edulia.enter_icon_class'),
                    ],
                    [
                        'id'            => 'header-right-menu-label',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => '',
                        'label_title'   => __('edulia.topbar_label'),
                        'placeholder'   => __('edulia.enter_label'),
                    ],
                    [
                        'id'            => 'header-right-icon-url',
                        'type'          => 'text',
                        'class'         => '',
                        'value'         => 'https://twitter.com/',
                        'label_title'   => __('edulia.topbar_url'),
                        'placeholder'   => __('edulia.enter_url'),
                    ],
                ],
        ],
        [
            'id'            => 'header_menu_image',
            'type'          => 'file',
            'field_desc' => __('only .jpg,.png allowed and max size is 3MB'),
            'max_size' => 3, // size in MB
            'ext' => [ 'jpg', 'png', ],
            'label_title'   => __('edulia.upload_logo')
        ],
        [
            'id' => 'header_menu_search',
            'type' => 'select',
            'class' => '',
            'label_title' => __('edulia.menu_search_display'),
            'label_desc' => '',
            'options' => [
                1 => 'Show',
                0 => 'Hide',
            ],
            'default' => 1,
            'value' => 1,
        ],
        [
            'id'            => 'header_menu_search_placeholder',
            'type'          => 'text',
            'value'         => 'Search...',
            'class'         => '',
            'label_title'   => __('edulia.menu_search_placeholder'),
            'placeholder'   => __('edulia.enter_placeholder'),
        ],
    ]
];
