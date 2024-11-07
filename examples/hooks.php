<?php


/**
 * Set style 'simple-2' for all auto replaced youtube blocks
 */
add_filter( 'omnivideo/block/atts', function ( $atts ) {
    if ( doing_action( 'render_block' ) ) {
        $atts['style'] = 'simple-2';
    }

    return $atts;
} );
