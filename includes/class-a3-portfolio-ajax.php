<?php
/**
 * a3 Portfolios Ajax Class
 *
 * ajax functions available on both the front-end and admin.
 *
 */

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( $this, 'do_ajax' ), 0 );
		$this->add_ajax_events();
	}

	/**
	 * Set a3 Portfolio AJAX constant and headers.
	 */
	public function define_ajax() {
		if ( ! empty( $_GET['a3-portfolio-ajax'] ) ) {
			if ( ! defined( 'DOING_AJAX' ) ) {
				define( 'DOING_AJAX', true );
			}
			// Turn off display_errors during AJAX events to prevent malformed JSON
			if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
				@ini_set( 'display_errors', 0 );
			}
			$GLOBALS['wpdb']->hide_errors();
		}
	}

	/**
	 * Send headers for a3 Portfolio Ajax Requests
	 * @since 2.5.0
	 */
	private function ajax_headers() {
		send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();
		status_header( 200 );
	}

	/**
	 * Check for a3 Portfolio Ajax request and fire action.
	 */
	public function do_ajax() {
		global $wp_query;

		if ( ! empty( $_GET['a3-portfolio-ajax'] ) ) {
			$wp_query->set( 'a3-portfolio-ajax', sanitize_text_field( $_GET['a3-portfolio-ajax'] ) );
		}

		if ( $action = $wp_query->get( 'a3-portfolio-ajax' ) ) {
			$this->ajax_headers();
			do_action( 'a3_portfolio_ajax_' . sanitize_text_field( $action ) );
			die();
		}
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public function add_ajax_events() {
		$ajax_events = array(
			'add_attribute'                                    => false,
			'add_new_attribute'                                => false,
			'save_attributes'                                  => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_a3_portfolio_' . $ajax_event, array( $this, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_a3_portfolio_' . $ajax_event, array( $this, $ajax_event ) );

				// a3 Portfolio AJAX can be used for frontend ajax requests
				add_action( 'a3_portfolio_ajax_' . $ajax_event, array( $this, $ajax_event ) );
			}
		}
	}

	/**
	 * Add an attribute row.
	 */
	public function add_attribute() {
		ob_start();

		check_ajax_referer( 'add-attribute', 'security' );

		global $a3_portfolio_attributes;

		$thepostid     = 0;
		$attribute_id  = absint( $_POST['attribute_id'] );
		$taxonomy      = a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id );
		$position      = 0;
		$metabox_class = array();
		$attribute     = array(
			'value'               => '',
			'is_visible_expander' => apply_filters( 'a3_portfolio_attribute_default_visible_expander', 1 ),
			'is_visible_post'     => apply_filters( 'a3_portfolio_attribute_default_visible_post', 1 ),
		);

		$attribute_taxonomy = $a3_portfolio_attributes[ $taxonomy ];
		$metabox_class[]    = 'taxonomy';
		$metabox_class[]    = $taxonomy;
		$attribute_label    = a3_portfolio_attribute_label( $taxonomy );

		include( 'meta-boxes/views/html-portfolio-attribute.php' );
		die();
	}

	/**
	 * Add a new attribute via ajax function.
	 */
	public function add_new_attribute() {
		ob_start();

		check_ajax_referer( 'add-attribute', 'security' );

		$attribute_id = absint( $_POST['attribute_id'] );
		$taxonomy     = a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id );
		$term         = sanitize_text_field( $_POST['term'] );

		if ( taxonomy_exists( $taxonomy ) ) {

			$result = wp_insert_term( $term, $taxonomy );

			if ( is_wp_error( $result ) ) {
				wp_send_json( array(
					'error' => $result->get_error_message()
				) );
			} else {
				$term = get_term_by( 'id', $result['term_id'], $taxonomy );
				wp_send_json( array(
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'slug'    => $term->slug
				) );
			}
		}

		die();
	}

	/**
	 * Save attributes via ajax.
	 */
	public function save_attributes() {

		check_ajax_referer( 'save-attributes', 'security' );

		// Get post data
		parse_str( $_POST['data'], $data );
		$post_id = absint( $_POST['post_id'] );

		// Save Attributes
		$attributes = array();

		if ( isset( $data['attribute_names'] ) ) {

			$attribute_names  = array_map( 'stripslashes', $data['attribute_names'] );
			$attribute_values = isset( $data['attribute_values'] ) ? $data['attribute_values'] : array();

			if ( isset( $data['attribute_visibility'] ) ) {
				$attribute_visibility = $data['attribute_visibility'];
			}

			if ( is_array( $attribute_names ) && count( $attribute_names ) ) {
				foreach ( $attribute_names as $attribute_id => $attribute_name ) {
					$is_visible_expander     = isset( $attribute_visibility[ $attribute_id ]['expander'] ) ? 1 : 0;
					$is_visible_post         = isset( $attribute_visibility[ $attribute_id ]['post'] ) ? 1 : 0;

					$is_attribute_have_terms = false;

					if ( isset( $attribute_values[ $attribute_id ] ) ) {

						// Select based attributes - Format values (posted values are slugs)
						if ( is_array( $attribute_values[ $attribute_id ] ) ) {
							$values                  = array_map( 'sanitize_title', $attribute_values[ $attribute_id ] );
							// Remove empty items in the array
							$values                  = array_filter( $values, 'strlen' );
							$is_attribute_have_terms = true;

						// Text based attributes - Posted values are term names, wp_set_object_terms wants ids or slugs.
						} else {
							$values = stripslashes( strip_tags( $attribute_values[ $attribute_id ] ) );
						}

					} else {
						$values = '';
					}

					// Update post terms
					if ( $is_attribute_have_terms && taxonomy_exists( $attribute_name ) ) {
						wp_set_object_terms( $post_id, $values, $attribute_name );
					}

					if ( ! empty( $values ) ) {
						// Add attribute to array, but don't set values
						$attributes[ $attribute_id ] = array(
							'value'               => '',
							'is_visible_expander' => $is_visible_expander,
							'is_visible_post'     => $is_visible_post,
						);

						if ( ! $is_attribute_have_terms ) {
							$attributes[ $attribute_id ]['value'] = $values;
						}
					}

				}
			}
		}

		/**
		 * Unset removed attributes by looping over previous values and
		 * unsetting the terms.
		 */
		$old_attributes = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_portfolio_attributes', true ) ) );

		if ( $old_attributes ) {
			foreach ( $old_attributes as $attribute_id => $value ) {
				$taxonomy = a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id );
				if ( empty( $attributes[ $attribute_id ] ) && taxonomy_exists( $taxonomy ) ) {
					wp_set_object_terms( $post_id, array(), $taxonomy );
				}
			}
		}

		update_post_meta( $post_id, '_portfolio_attributes', $attributes );

		die();
	}
}
