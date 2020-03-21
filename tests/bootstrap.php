<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Sample_Plugin
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?";
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	update_option('a3_portfolio_just_installed', false);

	require dirname( dirname( __FILE__ ) ) . '/a3-portfolio.php';
	update_option('a3_portfolio_version', A3_PORTFOLIO_VERSION);
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

function _manual_install_data() {
	echo esc_html( 'Installing Plugin Data ...' . PHP_EOL );

	$a3_portfolio_data = new \A3Rev\Portfolio\Data();
	$a3_portfolio_data->install_database();

	update_option('a3_portfolio_just_installed', true);
}
tests_add_filter( 'setup_theme', '_manual_install_data' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
