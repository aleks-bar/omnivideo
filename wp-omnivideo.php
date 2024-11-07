<?php
/**
 * Plugin Name:       OmniVideo
 * Plugin URI:        http://wpshop.ru/plugins/omnivideo
 * Description:       A versatile plugin to embed multiple video sources in WordPress posts.
 * Version:           1.0.1
 * Author:            WPShop.ru
 * Author URI:        https://wpshop.ru/
 * License:           WPShop License
 * License URI:       https://wpshop.ru/license
 * Requires at least: 5.6
 * Tested up to:      6.6
 * Requires PHP:      7.2
 * Text Domain:       wp-omnivideo
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Wpshop\OmniVideo\Admin\Settings;
use Wpshop\OmniVideo\AssetsProvider;
use Wpshop\OmniVideo\Blocks;
use Wpshop\OmniVideo\Shortcode;
use function Wpshop\OmniVideo\container;

require_once __DIR__ . '/vendor/autoload.php';

const WP_OMNIVIDEO_FILE = __FILE__;
const WP_OMNIVIDEO_SLUG = 'omnivideo';
define( 'WP_OMNIVIDEO_BASENAME', plugin_basename( WP_OMNIVIDEO_FILE ) );

add_action( 'plugins_loaded', 'Wpshop\OmniVideo\init_i18n' );
add_action( 'activated_plugin', 'Wpshop\OmniVideo\redirect_on_activated' );
add_filter( 'plugin_action_links_' . WP_OMNIVIDEO_BASENAME, 'Wpshop\OmniVideo\add_settings_plugin_action' );
add_action( 'plugins_loaded', function () {
    container()->get( AssetsProvider::class )->init();
    container()->get( Blocks::class )->init();
    container()->get( Settings::class )->init();
    container()->get( Shortcode::class )->init();
} );

register_activation_hook( WP_OMNIVIDEO_FILE, 'Wpshop\OmniVideo\activate' );
register_deactivation_hook( WP_OMNIVIDEO_FILE, 'Wpshop\OmniVideo\deactivate' );
