<?php

namespace Wpshop\OmniVideo\Admin;

use Wpshop\Settings\AbstractSettings;
use Wpshop\Settings\MaintenanceInterface;
use function Wpshop\OmniVideo\get_template_part;

class Settings extends AbstractSettings {

    const TEXT_DOMAIN = 'wp-omnivideo';

    /**
     * @var string[]
     */
    protected $_defaults = [
        'title_tag'    => 'div',
        'poster'       => '',
        'style'        => 'standard',
        'icon'         => 'play',
        'show_titles'  => true,
        'show_icons'   => true,
        'show_partner' => false,
        'pseudo'       => false,
        'order'        => '',

        'auto_replace_enabled' => 0,
        'auto_replace_params'  => 'style="simple" icon="youtube" poster="auto"',

        'clear_database' => 0,
    ];

    /**
     * @var array
     */
    protected $sanitizers = [
        'title_tag'    => 'sanitize_text_field',
        'poster'       => 'sanitize_text_field',
        'style'        => 'sanitize_text_field',
        'icon'         => 'sanitize_text_field',
        'show_titles'  => 'absint',
        'show_icons'   => 'absint',
        'show_partner' => 'absint',
        'pseudo'       => 'absint',

        'auto_replace_enabled' => 'absint',
        'auto_replace_params'  => 'Wpshop\OmniVideo\sanitize_shortcode_attrs',

        'clear_database' => 'absint',
    ];

    /**
     * @param MaintenanceInterface $maintenance
     * @param string               $reg_option
     * @param string               $option
     */
    public function __construct( MaintenanceInterface $maintenance, $reg_option, $option ) {
        parent::__construct( $maintenance, $reg_option, $option );
        $this->sanitizers['order'] = function ( $str ) {
            $sources = [
                'youtube',
                'vk',
                'dzen',
                'rutube',
                'mp4',
                'vimeo',
                'ok',
                'mailru',
                'kinescope',
            ];

            $list = wp_parse_list( $str );
            $list = array_filter( $list, function ( $item ) use ( $sources ) {
                return in_array( $item, $sources );
            } );

            return implode( ', ', $list );
        };
    }

    /**
     * @return void
     */
    public function init() {
        parent::init();
        add_action( 'admin_menu', function () {
            add_options_page(
                __( 'OmniVideo Options', 'wp-omnivideo' ),
                __( 'OmniVideo', 'wp-omnivideo' ),
                'manage_options',
                WP_OMNIVIDEO_SLUG,
                function () {
                    get_template_part( 'admin/settings' );
                }
            );
        } );
    }

    /**
     * @return void
     */
    public function init_defaults() {
        $this->_defaults['partner_text'] = __( 'The block is displayed using the {{link}} plugin', 'wp-omnivideo' );
    }

    /**
     * @return void
     */
    public function setup_tabs() {
        $this->add_tab( 'settings', __( 'Settings', 'wp-omnivideo' ) );
    }

    /**
     * @inheridoc
     */
    public function get_tab_icons() {
        return array_merge( parent::get_tab_icons(), [
            'settings' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256.01 209.36c12.96 0 25.24 4.27 33.69 11.7 9.07 7.98 13.87 19.73 14.29 34.93-.42 15.21-5.23 26.96-14.3 34.94-8.46 7.44-20.74 11.7-33.69 11.7s-25.24-4.27-33.7-11.7c-9.07-7.98-13.87-19.73-14.29-34.93.42-15.21 5.23-26.96 14.3-34.93 8.46-7.44 20.74-11.7 33.7-11.7M332.28 0H179.72v78.23a189.922 189.922 0 0 0-36.88 21.69l-66.6-39.13L0 195.17l66.58 39.12a198.125 198.125 0 0 0 0 43.41L0 316.83l76.24 134.39 66.6-39.13a189.419 189.419 0 0 0 36.88 21.69v78.23h152.56v-78.23c13.05-5.8 25.41-13.08 36.88-21.69l66.6 39.13L512 316.83l-66.58-39.12c.79-7.23 1.19-14.5 1.19-21.71s-.4-14.48-1.19-21.71L512 195.17 435.76 60.79l-66.6 39.13a189.419 189.419 0 0 0-36.88-21.69V0ZM146.65 155.93c16.34-13.16 35.37-29.23 55.09-36.62l23.83-9.82V46.55h60.88v62.95l23.83 9.82c19.67 7.36 38.8 23.5 55.09 36.62l53.61-31.5 30.48 53.72-53.59 31.49c1.62 12.88 5.23 33.6 4.93 46.36.31 12.67-3.32 33.6-4.93 46.36l53.59 31.49-30.48 53.72-53.61-31.5c-16.34 13.16-35.37 29.23-55.09 36.62l-23.83 9.82v62.95h-60.88v-62.95l-23.83-9.82c-19.67-7.36-38.8-23.5-55.09-36.62l-53.61 31.5-30.48-53.72 53.59-31.49c-1.62-12.88-5.23-33.6-4.93-46.36-.31-12.67 3.32-33.6 4.93-46.36l-53.59-31.49 30.48-53.72 53.61 31.5ZM256 161.36c-47.46 0-94.92 31.55-96 94.63 1.07 63.1 48.53 94.64 96 94.64s94.92-31.55 96-94.64c-1.07-63.09-48.53-94.64-96-94.64Z" fill="currentColor"></path></svg>',
        ] );
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function doc_link( $type ) {
        switch ( $type ) {
            case 'doc':
                return 'https://support.wpshop.ru/docs/plugins/' . WP_OMNIVIDEO_SLUG;
            case 'faq':
                return 'https://support.wpshop.ru/fag_tag/' . WP_OMNIVIDEO_SLUG . '/';
            default:
                return null;
        }
    }

    /**
     * @inheridoc
     */
    protected static function get_template_parts_root() {
        return dirname( WP_OMNIVIDEO_FILE ) . '/template-parts/';
    }

    /**
     * @inheritвoc
     */
    public static function product_prefix() {
        return 'wp_omnivideo_';
    }
}
