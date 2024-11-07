<?php

namespace Wpshop\Settings;

class SettingsRenderer {

    const VERSION = '1.3';

    /**
     * @var AbstractSettings
     */
    protected $settings;

    /**
     * @var string
     */
    protected $textdomain;

    /**
     * @param AbstractSettings $settings
     * @param string           $textdomain
     */
    public function __construct( AbstractSettings $settings, $textdomain ) {
        $this->settings   = $settings;
        $this->textdomain = $textdomain;
    }

    /**
     * @return void
     */
    public function render_reg_input() {
        $this->settings->register_reg_settings();;
        ?>
        <input name="<?php echo $this->settings->get_reg_input_name( 'license' ) ?>" type="text" value="" placeholder="XX0000-000000-000000000000000000-0000" class="wpshop-settings-text">
        <button type="submit" class="wpshop-settings-button"><?php echo __( 'Activate', $this->textdomain ) ?></button>
        <?php
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $doc_link
     *
     * @return void
     */
    public function render_header( $title, $description = '', $doc_link = '' ) {
        ?>
        <div class="wpshop-settings-header__title">
            <span><?php echo $title ?></span>
            <?php if ( $doc_link ): ?>
                <a href="<?php echo esc_attr( $doc_link ) ?>" target="_blank" rel="noopener" class="wpshop-settings-help-ico">?</a>
            <?php endif ?>
        </div>
        <?php if ( $description ): ?>
            <div class="wpshop-settings-header__description">
                <?php echo $description ?>
            </div>
        <?php endif;
    }

    /**
     * @param string $title
     * @param array  $args
     *
     * @return void
     */
    public function render_label( $title, $args ) {
        ?>
        <div class="wpshop-settings-form-row__label">
            <label for="<?php echo esc_attr( $args['id'] ?? '' ) ?>"><?php echo $title ?></label>
        </div>
        <?php
    }

    /**
     * @param string $description
     * @param string $additional_classes
     *
     * @return void
     */
    public function render_input_description( $description = '', $additional_classes = '' ) {
        if ( ! $description ) {
            return;
        }
        ?>
        <div class="wpshop-settings-form-description<?php echo $additional_classes ? ' ' . $additional_classes : '' ?>">
            <?php echo $description ?>
        </div>
        <?php
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $doc_link
     *
     * @return void
     */
    public function render_subheader( $title, $description = '', $doc_link = '' ) {
        ?>
        <div class="wpshop-settings-subheader">
            <div class="wpshop-settings-subheader__title">
                <span><?php echo $title ?></span>
                <?php if ( $doc_link ): ?>
                    <a href="<?php echo esc_attr( $doc_link ) ?>" target="_blank" rel="noopener" class="wpshop-settings-help-ico">?</a>
                <?php endif ?>
            </div>
            <?php if ( $description ): ?>
                <div class="wpshop-settings-subheader__description">
                    <?php echo $description ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * @param string $name input name
     * @param string $title
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_input( $name, $title, array $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $this->render_label( $title, $args );
        ?>
        <div class="wpshop-settings-form-row__body">
            <?php $this->render_input_field( $name, $args ); ?>
            <?php $this->render_input_description( $description ); ?>
        </div>
        <?php
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return void
     */
    public function render_input_field( $name, array $args = [] ) {
        $args = wp_parse_args( $args, [
            'type' => 'text',
            'id'   => uniqid( "{$name}." ),
        ] );

        $input_name = $this->settings->get_input_name( $name );
        $attributes = [];
        foreach ( [ 'type', 'min', 'max', 'step', 'placeholder' ] as $attr ) {
            if ( array_key_exists( $attr, $args ) ) {
                $attributes[] = "$attr=\"{$args[$attr]}\"";
            }
        }
        $attributes = implode( ' ', $attributes );
        $attributes = $attributes ? " $attributes" : '';

        $classes = array_key_exists( 'classes', $args ) ? $args['classes'] : [];
        $classes = is_array( $classes ) ? $classes : [ $classes ];
        array_unshift( $classes, 'wpshop-settings-text' );
        ?>
        <input class="<?php echo implode( ' ', $classes ) ?>"
               id="<?php echo esc_attr( $args['id'] ) ?>"
               name="<?php echo esc_attr( $input_name ) ?>"
               value="<?php echo esc_attr( $this->settings->get_value( $name ) ) ?>"<?php echo $attributes ?>>
        <?php
    }

    /**
     * @param string $name
     * @param string $title
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_password_input( $name, $title, array $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $this->render_label( $title, $args );
        ?>
        <div class="wpshop-settings-form-row__body">
            <?php $this->render_password_input_field( $name, $args ); ?>
            <?php $this->render_input_description( $description ); ?>
        </div>
        <?php
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return void
     */
    public function render_password_input_field( $name, array $args = [] ) {
        $args = wp_parse_args( $args, [
            'type'              => 'text',
            'id'                => uniqid( "{$name}." ),
            'placeholder'       => '',
            'placeholder_value' => '*****',
        ] );

        $input_name = $this->settings->get_input_name( $name );

        $classes = array_key_exists( 'classes', $args ) ? $args['classes'] : [];
        $classes = is_array( $classes ) ? $classes : [ $classes ];
        array_unshift( $classes, 'wpshop-settings-text' );

        ?>
        <input class="<?php echo implode( ' ', $classes ) ?>"
               id="<?php echo esc_attr( $args['id'] ) ?>"
               name="<?php echo esc_attr( $input_name ) ?>"
               value=""
               placeholder="<?php echo ! $this->settings->get_value( $name ) ? $args['placeholder'] : $args['placeholder_value'] ?>">
        <?php
    }


    /**
     * @param string $name input name
     * @param string $title
     * @param array  $options
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_select( $name, $title, array $options, array $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );
        $this->render_label( $title, $args );
        ?>
        <div class="wpshop-settings-form-row__body">
            <?php $this->render_select_field( $name, $options, $args ); ?>
            <?php $this->render_input_description( $description ); ?>
        </div>
        <?php
    }

    /**
     * @param string $name
     * @param array  $options
     * @param array  $args
     *
     * @return void
     */
    public function render_select_field( $name, array $options, array $args = [] ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $input_name = $this->settings->get_input_name( $name );

        $classes = array_key_exists( 'classes', $args ) ? $args['classes'] : [];
        $classes = is_array( $classes ) ? $classes : [ $classes ];
        array_unshift( $classes, 'wpshop-settings-select' );

        ?>
        <select id="<?php echo esc_attr( $args['id'] ) ?>"
                name="<?php echo esc_attr( $input_name ) ?>"
                class="<?php echo implode( ' ', $classes ) ?>">
            <?php foreach ( $options as $value => $label ): ?>
                <option value="<?php echo esc_attr( $value ) ?>"<?php selected( $this->settings->get_value( $name ), $value ) ?>><?php echo $label ?></option>
            <?php endforeach ?>
        </select>
        <?php
    }

    /**
     * @param string $name input name
     * @param string $label
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_checkbox( $name, $label = '', array $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );
        ?>
        <label for="<?php echo esc_attr( $args['id'] ) ?>" class="wpshop-settings-form-label">
            <?php $this->render_checkbox_field( $name, $label, $args ); ?>
        </label>
        <?php
        $this->render_input_description( $description, 'wpshop-settings-form-description--switch-box' );
    }

    /**
     * @param string $name
     * @param string $label
     * @param array  $args
     *
     * @return void
     */
    public function render_checkbox_field( $name, $label = '', array $args = [] ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $input_name = $this->settings->get_input_name( $name );
        $classes    = implode( ' ', (array) ( $args['classes'] ?? [] ) );
        $classes    = $classes ? " $classes" : '';

        $data_attributes = [];
        foreach ( $args as $key => $value ) {
            if ( substr( $key, 0, 5 ) === 'data-' ) {
                $data_attributes[] = "$key=\"$value\"";
            }
        }
        $data_attributes = implode( ' ', $data_attributes );
        $data_attributes = $data_attributes ? " $data_attributes" : '';
        ?>
        <input type="hidden" name="<?php echo $input_name ?>" value="0">
        <input type="checkbox"
               class="wpshop-settings-switch-box<?php echo $classes ?>"
               name="<?php echo esc_attr( $input_name ) ?>"
               id="<?php echo esc_attr( $args['id'] ) ?>"
            <?php echo $data_attributes ?>
               value="1"<?php checked( $this->settings->get_value( $name ) ) ?>>
        <?php echo esc_html( $label ) ?>
        <?php
    }

    /**
     * @param string $name
     * @param string $title
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_textarea( $name, $title, $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $this->render_label( $title, $args );
        ?>
        <div class="wpshop-settings-form-row__body">
            <?php $this->render_textarea_field( $name, $args ) ?>
            <?php $this->render_input_description( $description ); ?>
        </div>
        <?php
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return void
     */
    public function render_textarea_field( $name, array $args = [] ) {
        $args = wp_parse_args( $args, [
            'cols' => '',
            'rows' => 5,
            'id'   => uniqid( "{$name}." ),
        ] );

        $input_name = $this->settings->get_input_name( $name );


        $classes = array_key_exists( 'classes', $args ) ? $args['classes'] : [];
        $classes = is_array( $classes ) ? $classes : [ $classes ];
        array_unshift( $classes, 'wpshop-settings-text' );
        ?>
        <textarea class="<?php echo implode( ' ', $classes ) ?>"
                  name="<?php echo esc_attr( $input_name ) ?>"
                  id="<?php echo esc_attr( $args['id'] ) ?>"
                  cols="<?php echo esc_attr( $args['cols'] ) ?>"
                  rows="<?php echo esc_attr( $args['rows'] ) ?>"><?php echo esc_textarea( (string) $this->settings->get_value( $name ) ) ?></textarea>
        <?php
    }

    /**
     * @param string $name input name
     * @param string $label
     * @param array  $args
     * @param string $description
     *
     * @return void
     */
    public function render_color_picker( $name, $label, array $args = [], $description = '' ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $this->render_label( $label, $args );
        ?>
        <div class="wpshop-settings-form-row__body">
            <?php $this->render_color_picker_field( $name, $args ); ?>
            <?php $this->render_input_description( $description ); ?>
        </div>
        <?php
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @return void
     */
    public function render_color_picker_field( $name, array $args = [] ) {
        $args = wp_parse_args( $args, [
            'id' => uniqid( "{$name}." ),
        ] );

        $input_name = $this->settings->get_input_name( $name );
        ?>
        <input type="text"
               id="<?php echo esc_attr( $args['id'] ) ?>"
               name="<?php echo $input_name ?>"
               value="<?php echo esc_attr( $this->settings->get_value( $name ) ) ?>"
               data-default-color="<?php echo esc_attr( $args['default'] ?? '' ) ?>"
               class="js-wpshop-settings-color-picker">
        <?php
    }

    /**
     * @param callable $cb
     *
     * @return void
     */
    public function wrap_form( $cb ) {
        $has_cap = current_user_can( 'manage_options' );

        if ( $has_cap && $this->settings->verify() ) {
            ?>
            <form action="options.php" method="post">
            <?php
        }

        $cb( $this->settings, $this );

        if ( $has_cap && $this->settings->verify() ) {
            ?>
            <div class="wpshop-settings-container__footer js-wpshop-settings-container-save">
                <?php $this->settings->register_settings(); ?>
                <button type="submit" class="wpshop-settings-button"><?php echo __( 'Save', $this->textdomain ) ?></button>
            </div>
            </form>
            <?php
        }
    }
}
