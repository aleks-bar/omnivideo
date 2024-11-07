<?php

/**
 * @version 1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

use Wpshop\OmniVideo\Admin\Settings;
use function Wpshop\OmniVideo\container;


$settings = container()->get( Settings::class );

?>

<div class="wpshop-settings-header">
    <?php $settings->render_header(
        __( 'General Settings', 'wp-omnivideo' ),
        '',
        $settings->doc_link( 'doc' ) . '/settings/#settings'
    ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_select( 'title_tag', __( 'Title Tag', 'wp-omnivideo' ), [
        'div' => 'div',
        'h1'  => 'h1',
        'h2'  => 'h2',
        'h3'  => 'h3',
    ] ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_input(
        'poster',
        __( 'Poster', 'wp-omnivideo' ),
        [],
        __( 'Video Cover. You can specify a full link to the image that will be displayed instead of the video.', 'wp-omnivideo' ) . ' ' .
        __( 'You can also specify <code>auto</code> to automatically download the cover art from the platform (works if the shortcode has YouTube or Vimeo links specified).', 'wp-omnivideo' )
    ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_select( 'style', __( 'Style', 'wp-omnivideo' ), [
        'standard' => __( 'Standard (standard)', 'wp-omnivideo' ),
        'simple'   => __( 'Simple (simple)', 'wp-omnivideo' ),
        'simple-2' => __( 'Simple 2 (simple-2)', 'wp-omnivideo' ),
        'simple-3' => __( 'Simple 3 (simple-3)', 'wp-omnivideo' ),
        'cover'    => __( 'Cover (cover)', 'wp-omnivideo' ),
        'sunset'    => __( 'Sunset (sunset)', 'wp-omnivideo' ),
        'pills'    => __( 'Pills (pills)', 'wp-omnivideo' ),
        //        'dark'     => __( 'Dark', 'wp-omnivideo' ),

    ] ); ?>
</div>
<div class="wpshop-settings-form-row">
    <?php $settings->render_select( 'icon', __( 'Icon', 'wp-omnivideo' ), [
        ''          => __( 'Without Icon', 'wp-omnivideo' ),
        'play'      => __( 'Play', 'wp-omnivideo' ),
        'play-2'    => __( 'Play 2', 'wp-omnivideo' ),
        'play-3'    => __( 'Play 3', 'wp-omnivideo' ),
        'play-4'    => __( 'Play 4', 'wp-omnivideo' ),
        'youtube'   => __( 'Youtube ', 'wp-omnivideo' ),
        'vk'        => __( 'VK', 'wp-omnivideo' ),
        'dzen'      => __( 'Dzen', 'wp-omnivideo' ),
        'rutube'    => __( 'Rutube', 'wp-omnivideo' ),
        'mp4'       => __( 'mp4', 'wp-omnivideo' ),
        'vimeo'     => __( 'Vimeo', 'wp-omnivideo' ),
        'ok'        => __( 'OK', 'wp-omnivideo' ),
        'mailru'    => __( 'Mail.ru', 'wp-omnivideo' ),
        'kinescope' => __( 'Kinescope', 'wp-omnivideo' ),

    ] ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_input(
        'order',
        __( 'Order', 'wp-omnivideo' ),
        [],
        __( 'Comma separated values', 'wp-omnivideo' ) . '. ' . __( 'List of available platforms:', 'wp-omnivideo' ) . ' <code>youtube, vk, dzen, rutube, mp4, vimeo, ok, mailru, kinescope</code>'
    ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox( 'show_titles', __( 'Show Titles', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox( 'show_icons', __( 'Show Icons', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox(
        'pseudo',
        __( 'Use Pseudo Links', 'wp-omnivideo' ),
        [],
        __( 'Replaces links to video platforms with &lt;span&gt; pseudo elements. This is useful if you don\'t want external links to video platforms on your page.', 'wp-omnivideo' )
    ); ?>
</div>


<div class="wpshop-settings-header">
    <?php $settings->render_header( __( 'Auto Replace', 'wp-omnivideo' ), __( 'Once the setting is activated, all previously inserted YouTube links will start working through the OmniVideo block. By default, WordPress embeds all videos via iframe. To avoid unnecessary external requests and problems with platform inaccessibility, you can replace all videos embedded in your content with OmniVideo blocks.', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox( 'auto_replace_enabled', __( 'Enable', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_input(
        'auto_replace_params',
        __( 'Shortcode Params', 'wp-omnivideo' ),
        [],
        __( 'The parameters set here are applied as shortcode parameters when auto-replacing embedded video.', 'wp-omnivideo' )
    ); ?>
</div>

<div class="wpshop-settings-header">
    <?php $settings->render_header(
        __( 'Partner Program', 'wp-omnivideo' ),
        __( 'You can enable the output of a small affiliate pseudo link to the OmniVideo plugin after the block. You get up to 25% royalties from each sale. This is not a real link, it doesn\'t take up page weight and is closed from indexing.', 'wp-omnivideo' )
    ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox( 'show_partner', __( 'Enable', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_input( 'partner_text', __( 'Text', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-header">
    <?php $settings->render_header( __( 'Additional Settings', 'wp-omnivideo' ) ); ?>
</div>

<div class="wpshop-settings-form-row">
    <?php $settings->render_checkbox(
        'clear_database',
        __( 'Clear database on the plugin deleting', 'wp-omnivideo' ),
        [],
        __( 'All data related to the plugin will be deleted from the database.', 'wp-omnivideo' ) ); ?>
</div>
