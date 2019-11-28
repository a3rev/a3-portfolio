<?php
/**
 * a3 Portfolio WPML Class
 *
 */

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPML
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

		$plugin_name = A3_PORTFOLIO_KEY;

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
			icl_register_string( $this->plugin_wpml_name, 'Mobile Navigation', __( 'Navigation', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'All Filter', __( 'All', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Launch Site Button Text', __( 'LAUNCH SITE', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Categories field', __( 'Categories', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Tags field', __( 'Tags', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'View More Button Text', __( 'View More', 'a3-portfolio' ) );

			// Portfolio Social
			icl_register_string( $this->plugin_wpml_name, 'Social - Twitter', __( 'Twitter', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Facebook', __( 'Facebook', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Google Plus', __( 'Google+', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Social - Pinterest', __( 'Pinterest', 'a3-portfolio' ) );

			// Widget
			icl_register_string( $this->plugin_wpml_name, 'Recently Widget - No Portfolio', __( 'No Portfolio Recently Viewed !', 'a3-portfolio' ) );
			icl_register_string( $this->plugin_wpml_name, 'Recently Widget - Clear All', __( 'Clear All', 'a3-portfolio' ) );

			icl_register_string( $this->plugin_wpml_name, 'Attribute Filter Widget - Clear This Filter', __( 'Clear This Filter', 'a3-portfolio' ) );
		}
	}

}
