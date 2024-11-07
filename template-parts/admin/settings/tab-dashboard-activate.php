<?php

/**
 * @version 1.0.0
 */


use Wpshop\OmniVideo\Admin\Settings;

if ( ! defined( 'WPINC' ) ) {
    die;
}

$settings = \Wpshop\OmniVideo\container()->get( Settings::class );

?>

<div class="wpshop-settings-header">
    <?php $settings->render_header(
        __( 'License', 'wp-omnivideo' ),
        sprintf( __( 'To activate the plugin, enter the license key that you receive after payment in the letter or in <a href="%s" target="_blank" rel="noopener">personal account</a>.', 'wp-omnivideo' ), 'https://wpshop.ru/dashboard' )
    ); ?>
</div>

<div class="wpshop-settings-license">
    <?php if ( $error = $settings->get_reg_option()['license_error'] ): ?>
        <div class="error-message">
            <?php echo esc_html( $error ) ?>
        </div>
    <?php endif ?>
    <form class="wpshop-settings-license__form" action="" method="post" name="registration">
        <?php $settings->render_reg_input(); ?>
    </form>
</div>
