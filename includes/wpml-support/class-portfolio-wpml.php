<?php
/**
 * a3 Portfolio WPML Class
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class A3_Portfolio_WPML
{
	public $plugin_wpml_name = 'a3 Portfolios';

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		$this->wpml_ict_t();

	}

	/**
	 * Register WPML String when plugin loaded
	 */
	public function plugins_loaded() {
		$this->wpml_register_dynamic_string();
		$this->wpml_register_static_string();
	}

	/**
	 * Get WPML String when plugin loaded
	 */
	public function wpml_ict_t() {

		$plugin_name = 'a3_portfolios';

	}

	// Registry Dynamic String for WPML
	public function wpml_register_dynamic_string() {

		if ( function_exists('icl_register_string') ) {
		}
	}

	// Registry Static String for WPML
	public function wpml_register_static_string() {
		if ( function_exists('icl_register_string') ) {

			// Portfolio Global
			icl_register_string( $this->plugin_wpml_name, 'Mobile Navigation', __( 'Navigation', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'All Filter', __( 'All', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Launch Site Button Text', __( 'LAUNCH SITE', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Categories field', __( 'Categories', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Tags field', __( 'Tags', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'View More Button Text', __( 'View More', 'a3_portfolios' ) );

			// Portfolio Social
			icl_register_string( $this->plugin_wpml_name, 'Social - Twitter', __( 'Twitter', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Facebook', __( 'Facebook', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Google Plus', __( 'Google+', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Pinterest', __( 'Pinterest', 'a3_portfolios' ) );

			// Widget
			icl_register_string( $this->plugin_wpml_name, 'Recently Widget - No Portfolio', __( 'No Portfolio Recently Viewed !', 'a3_portfolios' ) );
			icl_register_string( $this->plugin_wpml_name, 'Recently Widget - Clear All', __( 'Clear All', 'a3_portfolios' ) );

			icl_register_string( $this->plugin_wpml_name, 'Attribute Filter Widget - Clear This Filter', __( 'Clear This Filter', 'a3_portfolios' ) );
		}
	}

}

global $a3_portfolio_wpml;
$a3_portfolio_wpml = new A3_Portfolio_WPML();

function a3_portfolio_ict_t_e( $name, $string ) {
	global $a3_portfolio_wpml;
	$string = ( function_exists('icl_t') ? icl_t( $a3_portfolio_wpml->plugin_wpml_name, $name, $string ) : $string );

	echo $string;
}

function a3_portfolio_ei_ict_t__( $name, $string ) {
	global $a3_portfolio_wpml;
	$string = ( function_exists('icl_t') ? icl_t( $a3_portfolio_wpml->plugin_wpml_name, $name, $string ) : $string );

	return $string;
}
?>