<?php
/**
 * PHPUnit bootstrap file.
 *
 * Loads the real WordPress test environment, since the code under test
 * (includes/style-builder.php) relies on core functions such as
 * esc_attr() and safecss_filter_attr() that are meaningless to test
 * without WordPress actually loaded.
 *
 * @package NExT_Reading_Progress
 */

// Composer autoloader (PHPUnit Polyfills).
require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

$wp_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $wp_tests_dir ) {
	$wp_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $wp_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin under test.
 */
function next_reading_progress_manually_load_plugin() {
	require dirname( __DIR__, 2 ) . '/next-reading-progress.php';
}
tests_add_filter( 'muplugins_loaded', 'next_reading_progress_manually_load_plugin' );

require $wp_tests_dir . '/includes/bootstrap.php';
