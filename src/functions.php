<?php

namespace Wpshop\OmniVideo;

use Psr\Container\ContainerInterface;
use WPShop\Container\Container;
use Wpshop\OmniVideo\Admin\Settings;

/**
 * @return ContainerInterface
 */
function container() {
    static $container;
    if ( ! $container ) {
        $config    = require dirname( __DIR__ ) . '/config/config.php';
        $init      = require dirname( __DIR__ ) . '/config/container.php';
        $container = new Container( $init( $config ) );
    }

    return $container;
}

/**
 * @return void
 */
function init_i18n() {
    $text_domain = 'wp-omnivideo';
    $locale      = ( is_admin() && function_exists( 'get_user_locale' ) ) ? get_user_locale() : get_locale();
    $mo_file     = dirname( WP_OMNIVIDEO_FILE ) . "/languages/{$text_domain}-{$locale}.mo";

    if ( is_readable( $mo_file ) ) {
        $loaded = load_textdomain( $text_domain, $mo_file );
        if ( ! $loaded ) {
            trigger_error( "Unable to load translations for \"{$text_domain}\" text domain", E_USER_NOTICE );
        }
    }
}

function activate() {

}

function deactivate() {

}

function uninstall() {
    $settings = container()->get( Settings::class );
    if ( $settings->get_value( 'clear_database' ) ) {
        $settings->clear_database();
        delete_option( 'omnivideo_posters' );
    }
}

/**
 * @param string $plugin
 *
 * @return void
 */
function redirect_on_activated( $plugin ) {
    if ( $plugin === WP_OMNIVIDEO_BASENAME && ! container()->get( Settings::class )->verify() ) {
        $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
        $action        = $wp_list_table->current_action();
        if ( $action === 'activate' ) {
            flush_rewrite_rules( false );
            wp_redirect( get_settings_page_url() );
            die;
        }
    }
}

/**
 * @return string
 */
function get_settings_page_url() {
    return add_query_arg( 'page', WP_OMNIVIDEO_SLUG, admin_url( 'options-general.php' ) );
}

/**
 * @param array $actions
 *
 * @return array
 */
function add_settings_plugin_action( $actions ) {
    array_unshift( $actions, sprintf( '<a href="%s">%s</a>', get_settings_page_url(), __( 'Settings', 'wp-omnivideo' ) ) );

    return $actions;
}

/**
 * @param string $slug
 * @param string $name
 * @param array  $args
 *
 * @return false|void
 * @see \get_template_part()
 */
function get_template_part( $slug, $name = null, $args = [] ) {
    do_action( "get_template_part_{$slug}", $slug, $name, $args );

    $templates = [];
    $name      = (string) $name;
    if ( '' !== $name ) {
        $templates[] = "{$slug}-{$name}.php";
    }

    $templates[] = "{$slug}.php";

    do_action( 'get_template_part', $slug, $name, $templates, $args );

    if ( ! locate_template( $templates, true, false, $args ) ) {
        return false;
    }
}

/**
 * @param string|array $template_names
 * @param bool         $load
 * @param bool         $require_once
 * @param array        $args
 *
 * @return string|null
 * @see \locate_template()
 */
function locate_template( $template_names, $load = false, $require_once = true, $args = [] ) {
    $located = null;
    foreach ( (array) $template_names as $template_name ) {
        if ( ! $template_name ) {
            continue;
        }

        // prevent to locate admin templates from other places
        if ( 'admin/' === substr( $template_name, 0, 6 ) ) {
            if ( file_exists( dirname( WP_OMNIVIDEO_FILE ) . '/template-parts/' . $template_name ) ) {
                $located = dirname( WP_OMNIVIDEO_FILE ) . '/template-parts/' . $template_name;
                break;
            }
            continue;
        }

        if ( file_exists( get_stylesheet_directory() . '/' . WP_OMNIVIDEO_SLUG . '/' . $template_name ) ) {
            $located = get_stylesheet_directory() . '/' . WP_OMNIVIDEO_SLUG . '/' . $template_name;
            break;
        } elseif ( file_exists( get_template_directory() . '/' . WP_OMNIVIDEO_SLUG . '/' . $template_name ) ) {
            $located = get_template_directory() . '/' . WP_OMNIVIDEO_SLUG . '/' . $template_name;
            break;
        } elseif ( file_exists( dirname( WP_OMNIVIDEO_FILE ) . '/template-parts/' . $template_name ) ) {
            $located = dirname( WP_OMNIVIDEO_FILE ) . '/template-parts/' . $template_name;
            break;
        }
    }

    /**
     * Allows to change template location of the plugin
     *
     * @since 1.0
     */
    $located = (string) apply_filters( 'wp_omnivideo/locate_template/located', $located, $template_name, $args );
    if ( ! file_exists( $located ) ) {
        trigger_error( 'Unable to locate template file "' . $located . '" from template names "' . implode( '", "', $template_names ) . '"' );

        return null;
    }

    if ( $load && '' !== $located ) {
        load_template( $located, $require_once, $args );
    }

    return $located;
}

/**
 * @param callable $fn
 *
 * @return false|string
 * @throws \Exception
 */
function ob_get_content( $fn ) {
    try {
        $ob_level = ob_get_level();
        ob_start();
        ob_implicit_flush( false );

        $args = func_get_args();
        call_user_func_array( $fn, array_slice( $args, 1 ) );

        return ob_get_clean();

    } catch ( \Exception $e ) {
        while ( ob_get_level() > $ob_level ) {
            if ( ! @ob_end_clean() ) {
                ob_clean();
            }
        }
        throw $e;
    }
}

/**
 * @param string $text
 * @param string $link_text
 *
 * @return string
 */
function get_partner_link( $text, $link_text ) {
    $link = base64_encode( 'https://wpshop.ru/plugins/omnivideo?partner=' . \Wpshop\OmniVideo\container()->get( 'config' )['partner_id'] . '&utm_source=site_partner&utm_medium=omnivideo&utm_campaign=' . home_url() );

    $link = sprintf(
        '<span data-href="%s" class="omnivideo-pseudo js-omnivideo-pseudo" data-target="_blank">%s</span>',
        $link,
        $link_text
    );

    if ( false !== mb_strpos( $text, '%s', 0, 'UTF-8' ) ) {
        $text = sprintf( $text, $link );
    }

    $text = str_replace( '{{link}}', $link, $text );

    return $text;
}

function sanitize_shortcode_attrs( $str ) {
    $atts = shortcode_parse_atts( $str );

    // clear params without values
    $atts = array_filter( $atts, function ( $key ) {
        return ! is_integer( $key );
    }, ARRAY_FILTER_USE_KEY );

    $parts = [];
    foreach ( $atts as $key => $value ) {
        if ( is_numeric( $value ) && false === strpos( $value, '.' ) ) {
            $value = intval( $value );
        } else {
            $value = '"' . addcslashes( $value, '"\\/' ) . '"';
        }
        $parts[] = "{$key}={$value}";
    }

    return implode( ' ', $parts );
}
