<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function a3_portfolio_sanitize_taxonomy_name( $taxonomy ) {
	return apply_filters( 'sanitize_taxonomy_name', urldecode( sanitize_title( $taxonomy ) ), $taxonomy );
}

function a3_portfolio_get_attribute_taxonomies() {
	global $wpdb;

	$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "a3_portfolio_attributes order by attribute_name ASC;" );

	return (array) array_filter( apply_filters( 'a3_portfolio_attributes', $attribute_taxonomies ) );
}

function a3_portfolio_attribute_taxonomy_name( $attribute_name ) {
	return 'a3pa_' . a3_portfolio_sanitize_taxonomy_name( $attribute_name );
}

function a3_portfolio_attribute_data_by_id( $attribute_id ) {
	global $wpdb;

	$attribute_data = $wpdb->get_row( $wpdb->prepare( "
		SELECT *
		FROM {$wpdb->prefix}a3_portfolio_attributes
		WHERE attribute_id = %d
	", $attribute_id ) );

	if ( $attribute_data && ! is_wp_error( $attribute_data ) ) {
		return $attribute_data;
	}

	return false;
}

function a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id ) {
	global $wpdb;

	$attribute_name = $wpdb->get_var( $wpdb->prepare( "
		SELECT attribute_name
		FROM {$wpdb->prefix}a3_portfolio_attributes
		WHERE attribute_id = %d
	", $attribute_id ) );

	if ( $attribute_name && ! is_wp_error( $attribute_name ) ) {
		return a3_portfolio_attribute_taxonomy_name( $attribute_name );
	}

	return '';
}

function a3_portfolio_attribute_label( $name ) {
	global $wpdb;

	$name = a3_portfolio_sanitize_taxonomy_name( str_replace( 'a3pa_', '', $name ) );

	$label = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_label FROM {$wpdb->prefix}a3_portfolio_attributes WHERE attribute_name = %s;", $name ) );

	if ( ! $label ) {
		$label = $name;
	}

	return apply_filters( 'a3_portfolio_attribute_label', $label, $name );
}

function a3_portfolio_attribute_orderby( $name ) {
	global $wpdb;

	$name = str_replace( 'a3pa_', '', sanitize_title( $name ) );

	$orderby = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_orderby FROM " . $wpdb->prefix . "a3_portfolio_attributes WHERE attribute_name = %s;", $name ) );

	return apply_filters( 'a3_portfolio_attribute_orderby', $orderby, $name );
}

/**
 * Get an array of attribute taxonomies.
 *
 * @return array
 */
function a3_portfolio_get_attribute_taxonomy_names() {
	$taxonomy_names = array();
	$attribute_taxonomies = a3_portfolio_get_attribute_taxonomies();
	if ( $attribute_taxonomies ) {
		foreach ( $attribute_taxonomies as $tax ) {
			$taxonomy_names[] = a3_portfolio_attribute_taxonomy_name( $tax->attribute_name );
		}
	}
	return $taxonomy_names;
}

/**
 * Get attribute types.
 *
 * @return array
 */
function a3_portfolio_get_attribute_types() {
	return (array) apply_filters( 'a3_portfolio_attributes_type_selector', array(
		'select' => __( 'Select', 'a3-portfolio' ),
		'text'   => __( 'Text', 'a3-portfolio' )
	) );
}

/**
 * Check if attribute name is reserved.
 * http://codex.wordpress.org/Function_Reference/register_taxonomy#Reserved_Terms.
 *
 * @param  string $attribute_name
 * @return bool
 */
function a3_portfolio_check_if_attribute_name_is_reserved( $attribute_name ) {
	// Forbidden attribute names
	$reserved_terms = array(
		'attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and',
		'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'cpage', 'day',
		'debug', 'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name',
		'nav_menu', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm',
		'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type',
		'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence',
		'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id',
		'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'type', 'w', 'withcomments', 'withoutcomments', 'year',
	);

	return in_array( $attribute_name, $reserved_terms );
}

function a3_portfolio_register_attribute_taxonomies() {
	global $a3_portfolio_attributes;

	$a3_portfolio_attributes = array();

	if ( $attribute_taxonomies = a3_portfolio_get_attribute_taxonomies() ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( $name = a3_portfolio_attribute_taxonomy_name( $tax->attribute_name ) ) {
				$label                          = ! empty( $tax->attribute_label ) ? $tax->attribute_label : $tax->attribute_name;
				$a3_portfolio_attributes[ $name ] = $tax;
				$taxonomy_data                  = array(
					'hierarchical'          => false,
					'update_count_callback' => '_update_post_term_count',
					'labels'                => array(
							'name'              => $label,
							'singular_name'     => $label,
							'search_items'      => sprintf( __( 'Search %s', 'a3-portfolio' ), $label ),
							'all_items'         => sprintf( __( 'All %s', 'a3-portfolio' ), $label ),
							'parent_item'       => sprintf( __( 'Parent %s', 'a3-portfolio' ), $label ),
							'parent_item_colon' => sprintf( __( 'Parent %s:', 'a3-portfolio' ), $label ),
							'edit_item'         => sprintf( __( 'Edit %s', 'a3-portfolio' ), $label ),
							'update_item'       => sprintf( __( 'Update %s', 'a3-portfolio' ), $label ),
							'add_new_item'      => sprintf( __( 'Add New %s', 'a3-portfolio' ), $label ),
							'new_item_name'     => sprintf( __( 'New %s', 'a3-portfolio' ), $label )
						),
					'show_ui'            => true,
					'show_in_quick_edit' => false,
					'show_in_menu'       => false,
					'show_in_nav_menus'  => false,
					'meta_box_cb'        => false,
					'query_var'          => false,
					'rewrite'            => false,
					'sort'               => false,
					'public'             => false,
					'show_in_nav_menus'  => false,
				);

				register_taxonomy( $name, apply_filters( "a3_portfolio_taxonomy_objects_{$name}", array( 'a3-portfolio' ) ), apply_filters( "a3_portfolio_taxonomy_args_{$name}", $taxonomy_data ) );
			}
		}
	}
}

/**
 * Returns true when the passed taxonomy name is a Portfolio attribute.
 * @uses   $a3_portfolio_attributes global which stores taxonomy names upon registration
 * @param  string $name of the attribute
 * @return bool
 */
function taxonomy_is_portfolio_attribute( $name ) {
	global $a3_portfolio_attributes;

	return taxonomy_exists( $name ) && array_key_exists( $name, (array) $a3_portfolio_attributes );
}

function a3_portofolio_get_portfolio_attributes_value( $portfolio_id ) {
	$attributes_value = array();

	$attributes_value = maybe_unserialize( get_post_meta( $portfolio_id, '_portfolio_attributes', true ) );
	if ( ! empty( $attributes_value ) ) {
		foreach ( $attributes_value as $attribute_id => $attribute ) {
			$attribute = a3_portfolio_attribute_data_by_id( $attribute_id );
			$attributes_value[$attribute_id]['label'] = $attribute->attribute_label;

			if ( ! $attribute ) continue;

			if ( 'select' != $attribute->attribute_type ) continue;

			$attributes_value[$attribute_id]['value'] = a3_portfolio_get_portfolio_attribute_value( $portfolio_id, $attribute_id );
		}
	}

	return $attributes_value;
}

function a3_portfolio_get_portfolio_attribute_terms( $portfolio_id, $attribute_id, $args = array() ) {
	$terms = array();
	$attribute = a3_portfolio_attribute_data_by_id( $attribute_id );

	if ( $attribute ) {
		$taxonomy = a3_portfolio_attribute_taxonomy_name( $attribute->attribute_name );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		if ( empty( $args['orderby'] ) ) {
			$args['orderby'] = $attribute->attribute_orderby;
		}

		$terms = array();

		// Support ordering by menu_order
		if ( ! empty( $args['orderby'] ) && $args['orderby'] === 'menu_order' ) {
			// wp_get_post_terms doesn't let us use custom sort order
			$args['include'] = wp_get_post_terms( $portfolio_id, $taxonomy, array( 'fields' => 'ids' ) );

			if ( ! empty( $args['include'] ) ) {
				// This isn't needed for get_terms
				unset( $args['orderby'] );

				// Set args for get_terms
				$args['menu_order'] = isset( $args['order'] ) ? $args['order'] : 'ASC';
				$args['hide_empty'] = isset( $args['hide_empty'] ) ? $args['hide_empty'] : 0;
				$args['fields']     = 'names';

				$terms              = get_terms( $taxonomy, $args );
			}
		} else {
			$terms = wp_get_post_terms( $portfolio_id, $taxonomy, $args );
		}
	}

	return apply_filters( 'a3_portfolio_get_portfolio_attribute_terms' , $terms, $portfolio_id, $attribute_id, $args );
}

function a3_portfolio_get_portfolio_attribute_value( $portfolio_id, $attribute_id, $args = array() ) {
	$attribue_value = '';
	$attribute = a3_portfolio_attribute_data_by_id( $attribute_id );

	if ( $attribute ) {
		$taxonomy = a3_portfolio_attribute_taxonomy_name( $attribute->attribute_name );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return '';
		}

		if ( empty( $args['orderby'] ) ) {
			$args['orderby'] = $attribute->attribute_orderby;
		}

		$terms = array();

		// Support ordering by menu_order
		if ( ! empty( $args['orderby'] ) && $args['orderby'] === 'menu_order' ) {
			// wp_get_post_terms doesn't let us use custom sort order
			$args['include'] = wp_get_post_terms( $portfolio_id, $taxonomy, array( 'fields' => 'ids' ) );

			if ( ! empty( $args['include'] ) ) {
				// This isn't needed for get_terms
				unset( $args['orderby'] );

				// Set args for get_terms
				$args['menu_order'] = isset( $args['order'] ) ? $args['order'] : 'ASC';
				$args['hide_empty'] = isset( $args['hide_empty'] ) ? $args['hide_empty'] : 0;
				$args['fields']     = 'names';

				$terms              = get_terms( $taxonomy, $args );
			}
		} else {
			$args['fields']     = 'names';
			$terms = wp_get_post_terms( $portfolio_id, $taxonomy, $args );
		}

		$attribue_value = implode( ', ', $terms );
	}

	return apply_filters( 'a3_portfolio_get_portfolio_attribute_value' , $attribue_value, $portfolio_id, $attribute_id, $args );
}

function a3_portofolio_get_portfolio_att_term_classes( $portfolio_id ) {
	$term_classes = array();

	$attributes = maybe_unserialize( get_post_meta( $portfolio_id, '_portfolio_attributes', true ) );
	if ( ! empty( $attributes ) ) {
		foreach ( $attributes as $attribute_id => $attribute_data ) {
			$attribute = a3_portfolio_attribute_data_by_id( $attribute_id );

			if ( ! $attribute ) continue;

			if ( 'select' != $attribute->attribute_type ) continue;

			$taxonomy = a3_portfolio_attribute_taxonomy_name( $attribute->attribute_name );
			if ( '' != $taxonomy ) {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					continue;
				}

				$portfolio_terms = get_the_terms( $portfolio_id, $taxonomy );

				if ( $portfolio_terms ) {
					foreach ( $portfolio_terms as $term ) {
						$term_classes[] = $term->slug;
					}
				}
			}
		}
	}

	return implode( ' ', $term_classes );
}
