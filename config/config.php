<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

return [
    'plugin_config' => [
        'verify_url' => 'https://wpshop.ru/api.php',
        'update'     => [
            'url'          => 'https://api.wpgenerator.ru/wp-update-server/?action=get_metadata&slug=wp-omnivideo',
            'slug'         => 'wp-omnivideo',
            'check_period' => 12,
            'opt_name'     => 'wp-omnivideo-check-update',
        ],
    ],
    'partner_id'    => '24931',
];
