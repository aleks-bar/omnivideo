<?php

namespace Wpshop\OmniVideo;

use Wpshop\OmniVideo\Admin\Settings;

class AssetsProvider {

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param Settings $settings
     */
    public function __construct( Settings $settings ) {
        $this->settings = $settings;
    }

    /**
     * @return void
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, '_admin_enqueue_scripts' ] );
    }

    /**
     * @return void
     */
    public function _enqueue_scripts() {
        if ( ! $this->settings->verify() ) {
            return;
        }

        wp_enqueue_style(
            'wp-omnivideo-style',
            plugin_dir_url( WP_OMNIVIDEO_FILE ) . 'assets/public/css/omnivideo.min.css',
            [],
            '1.0.1'
        );

        wp_enqueue_script(
            'wp-omnivideo-script',
            plugin_dir_url( WP_OMNIVIDEO_FILE ) . 'assets/public/js/omnivideo.min.js',
            [],
            '1.0.1',
            true
        );
    }

    /**
     * @return void
     */
    public function _admin_enqueue_scripts() {
        if ( ! get_current_screen() ) {
            return;
        }

        if ( get_current_screen()->id === 'settings_page_' . WP_OMNIVIDEO_SLUG ) {
            wp_enqueue_style(
                'wp-omnivideo-style',
                plugin_dir_url( WP_OMNIVIDEO_FILE ) . 'assets/admin/css/settings.min.css',
                [],
                '1.0.0'
            );

            wp_enqueue_script(
                'wp-omnivideo-settings',
                plugin_dir_url( WP_OMNIVIDEO_FILE ) . 'assets/admin/js/settings.min.js',
                [ 'jquery', 'wp-color-picker' ],
                '1.0.0', true
            );
            wp_localize_script( 'wp-omnivideo-settings', 'wp_omnivideo_settings_globals', [
                'storage_key' => 'wp-omnivideo-settings-tab',
                'actions'     => Settings::ajax_actions(),
            ] );
        }
    }
}
