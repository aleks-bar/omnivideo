<?php

namespace Wpshop\OmniVideo;

class PosterManager {

    /**
     * @param array $atts
     *
     * @return string
     */
    public function get_poster_url( $atts ) {
        $provider = '';
        $video_id = '';

        if ( ! empty( $atts['youtube'] ) ) {
            $provider = 'youtube';
            $video_id = $this->get_youtube_video_id( $atts['youtube'] );
        } elseif ( ! empty( $atts['vimeo'] ) ) {
            $provider = 'vimeo';
            $video_id = $this->get_vimeo_video_id( $atts['vimeo'] );
        }

        // Проверка, если постер уже сохранен
        $poster_info = $this->get_poster_from_option( $provider, $video_id );

        if ( ! empty( $poster_info['image_url'] ) ) {
            return $poster_info['image_url'];
        }

        /**
         * Allows to disable auto poster for an individual platform
         *
         * @since 1.0
         */
        $auto_poster_enabled = apply_filters( 'omnivideo/auto_poster/enabled', [
            'youtube' => true,
            'vimeo'   => true,
        ] );

        if ( empty( $auto_poster_enabled[ $provider ] ) ) {
            return ''; // Если авто получение постера отключено, возвращаем пустое значение
        }

        $poster_url = '';
        if ( $provider === 'youtube' ) {
            $poster_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
        } elseif ( $provider === 'vimeo' ) {
            $poster_url = $this->get_vimeo_poster( $video_id );
        }

        if ( $poster_url ) {
            $media_id = $this->save_poster_to_media( $poster_url, $provider, $video_id );
            if ( $media_id ) {
                $image_url = wp_get_attachment_url( $media_id );
                $this->update_poster_option( $provider, $video_id, $media_id, $image_url );

                return $image_url;
            }
        }

        return '';
    }

    /**
     * @param string $url
     *
     * @return mixed|string
     */
    private function get_youtube_video_id( $url ) {
        if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches ) ) {
            return $matches[1];
        }

        return '';
    }

    /**
     * @param string $url
     *
     * @return mixed|string
     */
    protected function get_vimeo_video_id( $url ) {
        if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d+)/', $url, $matches ) ) {
            return $matches[1];
        }

        return '';
    }

    /**
     * @param string $video_id
     *
     * @return mixed|string
     */
    protected function get_vimeo_poster( $video_id ) {
        $api_url  = "https://vimeo.com/api/v2/video/{$video_id}.json";
        $response = wp_remote_get( $api_url );

        if ( is_wp_error( $response ) ) {
            return '';
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        return $data[0]['thumbnail_large'] ?? '';
    }

    /**
     * @param string $url
     * @param string $provider
     * @param string $video_id
     *
     * @return int|\WP_Error|null
     */
    protected function save_poster_to_media( $url, $provider, $video_id ) {
        // Проверка доступности функции
        $upload_dir = wp_upload_dir();

        if ( ! function_exists( 'media_sideload_image' ) ) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if ( ! function_exists( 'media_sideload_image' ) || ! wp_mkdir_p( $upload_dir['path'] ) ) {
            return null;
        }

        // Устанавливаем ограничение времени ожидания
        $response = wp_remote_get( $url, [ 'timeout' => 5 ] );

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return null;
        }

        $image_data = wp_remote_retrieve_body( $response );
        $filename   = $provider . '-' . $video_id . '.jpg';

        $file = [
            'name'     => $filename,
            'tmp_name' => wp_tempnam( $filename ),
        ];

        // Сохраняем изображение во временный файл
        file_put_contents( $file['tmp_name'], $image_data );

        // Загрузка изображения в медиафайлы
        $attachment_id = media_handle_sideload( $file, 0 );

        // Удаляем временный файл
        if ( file_exists( $file['tmp_name'] ) ) {
            unlink( $file['tmp_name'] );
        }

        if ( is_wp_error( $attachment_id ) ) {
            return null;
        }

        return $attachment_id;
    }

    /**
     * @param string $provider
     * @param string $video_id
     * @param int    $media_id
     * @param string $image_url
     *
     * @return void
     */
    protected function update_poster_option( $provider, $video_id, $media_id, $image_url ) {
        $option_name = 'omnivideo_posters';
        $posters     = get_option( $option_name, [] );

        $posters[ $provider ][ $video_id ] = [
            'media_id'  => $media_id,
            'image_url' => $image_url,
        ];

        update_option( $option_name, $posters );
    }

    /**
     * @param string $provider
     * @param string $video_id
     *
     * @return string|null
     */
    protected function get_poster_from_option( $provider, $video_id ) {
        $option_name = 'omnivideo_posters';
        $posters     = get_option( $option_name, [] );

        return $posters[ $provider ][ $video_id ] ?? null;
    }
}
