<?php

namespace Wpshop\OmniVideo;

use Wpshop\OmniVideo\Admin\Settings;

class VideoBlock {

    /**
     * @var PosterManager
     */
    protected $poster_manager;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param PosterManager $poster_manager
     * @param Settings      $settings
     */
    public function __construct(
        PosterManager $poster_manager,
        Settings $settings
    ) {
        $this->poster_manager = $poster_manager;
        $this->settings       = $settings;
    }

    /**
     * @param array  $atts
     * @param string $description
     *
     * @return false|string
     */
    public function generate_block( $atts, $description ) {
        $atts = wp_parse_args( $atts, [
            'title'        => '',
            'title_tag'    => 'div',
            'youtube'      => '',
            'vk'           => '',
            'dzen'         => '',
            'mp4'          => '',
            'rutube'       => '',
            'ok'           => '',
            'mailru'       => '',
            'vimeo'        => '',
            'kinescope'    => '',
            'default'      => '',
            'poster'       => '',
            'style'        => 'standard',
            'icon'         => 'play',
            'show_titles'  => true,
            'show_icons'   => true,
            'show_partner' => false,
            'order'        => '',
            'pseudo'       => false,
        ] );

        /**
         * Allows to change attribute values of the generate block method
         *
         * @since 1.2
         */
        $atts = apply_filters( 'omnivideo/block/atts', $atts );

        // Автоматическое получение постера, если poster="auto"
        if ( $atts['poster'] === 'auto' ) {
            $atts['poster'] = $this->poster_manager->get_poster_url( $atts );
        }

        $icons = include plugin_dir_path( WP_OMNIVIDEO_FILE ) . '/icons.php';

        return $this->generate_html( $atts, $icons, $description );
    }

    /**
     * @param array $atts
     * @param array $icons
     * @param string
     * $description
     *
     * @return false|string
     * @throws \Exception
     */
    private function generate_html( $atts, $icons, $description ) {
        return ob_get_content( function () use ( $atts, $icons, $description ) {
            $title     = $atts['title'];
            $title_tag = $atts['title_tag'];
            $default   = $atts['default'];
            $poster    = $atts['poster'];
            $style     = $atts['style'];
            switch ( $style ) {
                case 'simple-1':
                    $style = 'simple';
                    break;
                case 'cover-1':
                    $style = 'cover';
                    break;
                case '':
                case 'standard-1':
                    $style = 'standard';
                    break;
                case 'dark-1':
                    $style = 'dark';
                    break;
                case 'sunset-1':
                    $style = 'sunset';
                    break;
                case 'pills-1':
                    $style = 'pills';
                    break;
                default:
                    break;
            }
            $icon = $atts['icon'];
            if ( $icon === 'play-1' ) {
                $icon = 'play';
            }

            $show_titles = filter_var( $atts['show_titles'], FILTER_VALIDATE_BOOLEAN );
            $show_icons  = filter_var( $atts['show_icons'], FILTER_VALIDATE_BOOLEAN );
            $pseudo      = filter_var( $atts['pseudo'], FILTER_VALIDATE_BOOLEAN );

            $show_partner = filter_var( $atts['show_partner'], FILTER_VALIDATE_BOOLEAN );
            $partner_text = $this->settings->get_value( 'partner_text' );

            $order   = ! empty( $atts['order'] ) ? explode( ',', $atts['order'] ) : [];
            $sources = $this->get_ordered_sources(
                $order,
                $atts['youtube'],
                $atts['vk'],
                $atts['dzen'],
                $atts['rutube'],
                $atts['mp4'],
                $atts['vimeo'],
                $atts['ok'],
                $atts['mailru'],
                $atts['kinescope']
            );

            get_template_part(
                'omnivideo-block',
                '',
                compact(
                    'title',
                    'title_tag',
                    'description',
                    'default',
                    'poster',
                    'style',
                    'icon',
                    'icons',
                    'sources',
                    'show_titles',
                    'show_icons',
                    'pseudo',
                    'show_partner',
                    'partner_text'
                )
            );
        } );
    }

    /**
     * @param array  $order
     * @param string $youtube
     * @param string $vk
     * @param string $dzen
     * @param string $rutube
     * @param string $mp4
     * @param string $vimeo
     * @param string $ok
     * @param string $mailru
     * @param string $kinescope
     *
     * @return array
     */
    protected function get_ordered_sources( $order, $youtube, $vk, $dzen, $rutube, $mp4, $vimeo, $ok, $mailru, $kinescope ) {
        $available_sources = [
            'youtube'   => $youtube,
            'vk'        => $vk,
            'dzen'      => $dzen,
            'rutube'    => $rutube,
            'mp4'       => $mp4,
            'vimeo'     => $vimeo,
            'ok'        => $ok,
            'mailru'    => $mailru,
            'kinescope' => $kinescope,
        ];

        $ordered_sources = [];

        foreach ( $order as $source ) {
            $source = trim( $source );
            if ( array_key_exists( $source, $available_sources ) && ! empty( $available_sources[ $source ] ) ) {
                $ordered_sources[ $source ] = $available_sources[ $source ];
                unset( $available_sources[ $source ] );
            }
        }

        foreach ( $available_sources as $key => $value ) {
            if ( ! empty( $value ) ) {
                $ordered_sources[ $key ] = $value;
            }
        }

        return $ordered_sources;
    }


    /**
     * @param string $icon
     *
     * @return string
     */
    public function get_play_icon_svg( $icon ) {
        $svgs = [
            'play'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Zm-1.306-6.154C9.934 16.294 9 15.71 9 14.786V9.214c0-.924.934-1.507 1.694-1.059l4.72 2.787c.781.462.781 1.656 0 2.118l-4.72 2.787Z" fill="currentColor"></path></svg>',
            'play-2' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M2 7a5 5 0 0 1 5-5h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7Zm13.414 6.059c.781-.462.781-1.656 0-2.118l-4.72-2.787C9.934 7.706 9 8.29 9 9.214v5.573c0 .923.934 1.507 1.694 1.059l4.72-2.787Z" fill="currentColor"></path></svg>',
            'play-3' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10.69 15.85C9.93 16.3 9 15.71 9 14.79V9.22c0-.92.93-1.51 1.69-1.06l4.72 2.79c.78.46.78 1.66 0 2.12l-4.72 2.79v-.01ZM22 12c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2s10 4.48 10 10Zm-1.5 0c0-4.69-3.81-8.5-8.5-8.5-4.69 0-8.5 3.81-8.5 8.5 0 4.69 3.81 8.5 8.5 8.5 4.69 0 8.5-3.81 8.5-8.5Z" fill="currentColor"></path></svg>',
            'play-4' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.744 14.27 8.629 20.24C7 21.202 5 19.951 5 17.971V6.029c0-1.98 2-3.23 3.629-2.27l10.115 5.973c1.675.989 1.675 3.55 0 4.538Z" fill="currentColor"></path></svg>',
        ];

        return $svgs[ $icon ] ?? '';
    }
}
