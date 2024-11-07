<?php

namespace Wpshop\OmniVideo;

use Wpshop\OmniVideo\Admin\Settings;

class Blocks {

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var VideoBlock
     */
    protected $video_block;

    /**
     * @param Settings   $settings
     * @param VideoBlock $video_block
     */
    public function __construct(
        Settings $settings,
        VideoBlock $video_block
    ) {
        $this->settings    = $settings;
        $this->video_block = $video_block;
    }

    /**
     * @return void
     */
    public function init() {
        add_action( 'init', [ $this, '_register_blocks' ] );
    }

    /**
     * @return void
     */
    public function _register_blocks() {
        if ( ! $this->settings->verify() ) {
            return;
        }

        register_block_type(
            plugin_dir_path( WP_OMNIVIDEO_FILE ) . 'blocks/build/omnivideo',
            [
                'render_callback' => [ $this, '_render_omnivideo' ],
                'description'     => __( 'Output video from different platforms in one block.', 'wp-omnivideo' ),
                'textdomain'      => 'wp-omnivideo',

                'attributes' => [
                    'title'           => [
                        'type' => 'string',
                    ],
                    'titleTag'        => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'description'     => [
                        'type' => 'string',
                    ],
                    'poster'          => [
                        'type'    => 'string',
                        'default' => '',
                    ],
                    'blockStyle'      => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'icon'            => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'platforms'       => [
                        'type'    => 'object',
                        'default' => [
                            "youtube"   => "",
                            "vk"        => "",
                            "dzen"      => "",
                            "rutube"    => "",
                            "mp4"       => "",
                            "vimeo"     => "",
                            "ok"        => "",
                            "mailru"    => "",
                            "kinescope" => "",
                        ],
                    ],
                    'defaultPlatform' => [
                        'type' => 'string',
                    ],
                    'platformOrder'   => [
                        'type'    => 'string',
                        'default' => '',
                    ],
                    'showTitles'      => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'showIcons'       => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'showPartner'     => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                    'pseudoLink'      => [
                        'type'    => 'string',
                        'default' => '__default__',
                    ],
                ],
            ]
        );

        wp_set_script_translations(
            'wpshop-omnivideo-editor-script',
            'wp-omnivideo',
            plugin_dir_path( WP_OMNIVIDEO_FILE ) . 'languages'
        );

    }

    /**
     * @param array $attributes
     *
     * @return false|string
     */
    public function _render_omnivideo( $attributes ) {
        $platforms   = $attributes['platforms'] ?? '';
        $description = $attributes['description'] ?? '';

        unset( $attributes['platforms'], $attributes['description'] );

        /**
         * Map format:
         * 'keyInJsScope' => ['key_in_php_scope', default value (null for prevent use default), cast function]
         */
        foreach (
            [
                'titleTag'        => [ 'title_tag', '__default__', null ],
                'poster'          => [ 'poster', '', null ],
                'showTitles'      => [ 'show_titles', '__default__', 'absint' ],
                'showIcons'       => [ 'show_icons', '__default__', 'absint' ],
                'showPartner'     => [ 'show_partner', '__default__', 'absint' ],
                'defaultPlatform' => [ 'default', null, null ],
                'platformOrder'   => [ 'order', '', null ],
                'pseudoLink'      => [ 'pseudo', '__default__', 'absint' ],
                'blockStyle'      => [ 'style', '__default__', null ],
                'icon'            => [ 'icon', '__default__', null ],
            ] as $key => $mapped_item
        ) {
            [ $mapped_key, $default_value, $cast_fn ] = $mapped_item;

            if ( null !== $default_value && $attributes[ $key ] === $default_value ) {
                $attributes[ $key ] = $this->settings->get_value( $mapped_key );
            }

            if ( is_callable( $cast_fn ) ) {
                $attributes[ $key ] = call_user_func( $cast_fn, $attributes[ $key ] );
            }

            if ( array_key_exists( $key, $attributes ) && $key !== $mapped_key ) {
                $attributes[ $mapped_key ] = $attributes[ $key ];
                unset( $attributes[ $key ] );
            }
        }

        $attributes = array_merge( $attributes, $platforms );

        return $this->video_block->generate_block( $attributes, $description );
    }
}
