<?php

if ( ! defined( 'WPINC' ) ) {
    die;
}

use WPShop\Container\ServiceRegistry;
use Wpshop\OmniVideo\Admin\Settings;
use Wpshop\OmniVideo\AssetsProvider;
use Wpshop\OmniVideo\Blocks;
use Wpshop\OmniVideo\PosterManager;
use Wpshop\OmniVideo\Shortcode;
use Wpshop\OmniVideo\VideoBlock;
use Wpshop\Settings\Maintenance;
use Wpshop\Settings\MaintenanceInterface;

return function ( $config ) {
    $container = new ServiceRegistry( [
        'config'                    => $config,
        AssetsProvider::class       => function ( $c ) {
            return new AssetsProvider( $c[ Settings::class ] );
        },
        Blocks::class               => function ( $c ) {
            return new Blocks(
                $c[ Settings::class ],
                $c[ VideoBlock::class ]
            );
        },
        MaintenanceInterface::class => function ( $c ) {
            return new Maintenance(
                $c['config']['plugin_config'],
                'plugin',
                WP_OMNIVIDEO_SLUG,
                WP_OMNIVIDEO_FILE,
                'wp-omnivideo'
            );
        },
        Settings::class             => function ( $c ) {
            return new Settings(
                $c[ MaintenanceInterface::class ],
                'wp-omnivideo-r',
                'wp-omnivideo-settings'
            );
        },
        PosterManager::class        => function () {
            return new PosterManager();
        },
        Shortcode::class            => function ( $c ) {
            return new Shortcode(
                $c[ Settings::class ],
                $c[ VideoBlock::class ] );
        },
        VideoBlock::class           => function ( $c ) {
            return new VideoBlock(
                $c[ PosterManager::class ],
                $c[ Settings::class ]
            );
        },
    ] );

    return $container;
};
