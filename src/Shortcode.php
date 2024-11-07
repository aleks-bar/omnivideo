<?php

namespace Wpshop\OmniVideo;

use Wpshop\OmniVideo\Admin\Settings;

class Shortcode {

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var VideoBlock
     */
    protected $video_block;

    /**
     * @param Settings $settings
     */
    public function __construct( Settings $settings, VideoBlock $video_block ) {
        $this->settings    = $settings;
        $this->video_block = $video_block;
    }

    /**
     * @return void
     */
    public function init() {
        add_shortcode( 'omnivideo', [ $this, 'render' ] );

        if ( $this->settings->verify() ) {
            add_filter( 'render_block', [ $this, '_replace_youtube_block' ], 10, 3 );
            add_filter( 'embed_oembed_html', [ $this, '_replace_youtube_oembed' ], 10, 4 );
        }
    }

    /**
     * @param array  $atts
     * @param string $content
     * @param string $shortcode_tag
     *
     * @return false|string
     * @see add_shortcode()
     */
    public function render( $atts, $content, $shortcode_tag ) {
        if ( ! $this->settings->verify() ) {
            return '';
        }

        $atts = shortcode_atts( [
            'title'        => '',
            'title_tag'    => $this->settings->get_value( 'title_tag' ), // тега заголовка
            'youtube'      => '',
            'vk'           => '',
            'dzen'         => '',
            'mp4'          => '',
            'rutube'       => '',
            'ok'           => '',
            'mailru'       => '',
            'vimeo'        => '',
            'kinescope'    => '',
            'default'      => '', // плеер выбранный по умолчанию, например youtube, vk
            'poster'       => $this->settings->get_value( 'poster' ), // картинка-постер, auto
            'style'        => $this->settings->get_value( 'style' ), // simple, simple-2, simple-3 cover, dark
            'icon'         => $this->settings->get_value( 'icon' ), // 'Без иконки', play, play-2, play-3, play-4
            'show_titles'  => $this->settings->get_value( 'show_titles' ), // показывать названия платформ
            'show_icons'   => $this->settings->get_value( 'show_icons' ), // показывать иконки
            'show_partner' => $this->settings->get_value( 'show_partner' ), // показывать партнерскую ссылку
            'pseudo'       => $this->settings->get_value( 'pseudo' ), // псевдо-ссылки вместо обычных
            'order'        => $this->settings->get_value( 'order' ),
        ], $atts, $shortcode_tag );

        return $this->video_block->generate_block( $atts, $content );
    }

    /**
     * @param string    $block_content
     * @param array     $parsed_block
     * @param \WP_Block $wp_block
     *
     * @return mixed|string
     */
    public function _replace_youtube_block( $block_content, $parsed_block, $wp_block = null ) {
        if ( ! $this->settings->get_value( 'auto_replace_enabled' ) ) {
            return $block_content;
        }

        if ( is_admin() ) {
            return $block_content;
        }

        if ( 'core/embed' === $parsed_block['blockName'] ) {
            if ( isset( $parsed_block['attrs']['type'] ) && $parsed_block['attrs']['type'] === 'video' &&
                 isset( $parsed_block['attrs']['providerNameSlug'] ) && $parsed_block['attrs']['providerNameSlug'] === 'youtube' &&
                 ! empty( $parsed_block['attrs']['url'] )
            ) {

                preg_match( '/<figcaption\s+class="wp-element-caption">(.+?)<\/figcaption>/uis', $block_content, $matches );
                $title = $matches[1] ?? '';

                $params = 'title="' . $title . '" style="simple" icon="youtube" poster="auto" ' . $this->settings->get_value( 'auto_replace_params' ) . ' youtube="' . $parsed_block['attrs']['url'] . '"';
                $params = sanitize_shortcode_attrs( $params );

                $block_content = '[omnivideo ' . $params . ']';
            }
        }

        return $block_content;
    }

    /**
     * @param string $html
     * @param string $url
     * @param array  $attr
     * @param int    $post_ID
     *
     * @return mixed|string
     */
    public function _replace_youtube_oembed( $html, $url, $attr, $post_ID ) {
        if ( ! $this->settings->get_value( 'auto_replace_enabled' ) ) {
            return $html;
        }

        if ( is_admin() ) {
            return $html;
        }

        if ( strpos( $url, 'youtube.com' ) !== false ||
             strpos( $url, 'youtu.be' ) !== false
        ) {
            $params = 'style="simple" icon="youtube" poster="auto" ' . $this->settings->get_value( 'auto_replace_params' ) . ' youtube="' . esc_url( $url ) . '"';
            $params = sanitize_shortcode_attrs( $params );

            return '[omnivideo ' . $params . ']';
        }

        return $html;
    }
}
