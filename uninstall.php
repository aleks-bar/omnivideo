<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

use function Wpshop\OmniVideo\uninstall;

require __DIR__ . '/vendor/autoload.php';

const WP_OMNIVIDEO_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'wp-omnivideo.php';
const WP_OMNIVIDEO_SLUG = 'omnivideo';

uninstall();
