<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * a3_portfolio_get_main_page()
 *
 * @param $column int
 * @return html of main portfolios page
 */
function a3_portfolio_get_main_page( $type = 'none', $column = '', $number_items = -1, $show_navbar = true, $additional_params = array() ) {
	global $wpdb, $wp_query, $portfolio_query, $portfolio_query_vars;

	if ( $column < 1 || $column > 6 ) $column = a3_portfolio_get_col_per_row();

	if ( (int) $number_items < 1 ) $number_items = -1;

	$query_args = array(
		'post_type'           => 'a3-portfolio',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $number_items,
		'paged'               => '',
		'order'               => apply_filters('portfolio_order','DESC'),
		'orderby'             => 'post_date',
		'ignore_sticky_posts' => true,
	);

	// Don't show sticky posts first if type is recent
	if ( 'recent' == $type ) {
		$query_args['ignore_sticky_posts'] = true;
	} elseif ( 'sticky' == $type ) {
		$sticky                            = get_option( 'sticky_posts' );
		$query_args['post__in']            = $sticky;
		$query_args['ignore_sticky_posts'] = true;
	}

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query object

	$wp_query = new \WP_Query( $query_args );

	ob_start();

	remove_action( 'a3rev_loop_after', 'responsi_pagination', 10, 0 );

	if ( ! $show_navbar ) {
		remove_action( 'a3_portfolio_before_main_content', 'a3_portfolio_nav_bar', 10 );
	}
	remove_action( 'a3_portfolio_before_main_loop', 'a3_portfolio_main_query', 10 );
	remove_action( 'a3_portfolio_after_main_loop', 'a3_portfolio_get_portfolios_uncategorized', 10 );

	a3_portfolio_get_template( 'archive-portfolio.php', array(
		'container_id'		=> $additional_params['blockID'] ?? '',
		'type'              => $type, // none | recent | sticky
		'number_columns'    => $column,
		'number_items'      => $number_items,
		'show_navbar'       => $show_navbar,
		'additional_params' => $additional_params
	) ) ;

	$ouput = ob_get_clean();

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query objects back
	$GLOBALS['post'] = $wp_query->post;

	global $a3_portfolio_frontend_scripts;
	$a3_portfolio_frontend_scripts->a3_portfolio_main_page_scripts();

	return $ouput;
}

/**
 * a3_portfolio_get_item_ids_page()
 *
 * @param $item_ids array|string|int -1 to get all ids
 * @param $column int
 * @param $custom_style string
 * @return html of list card items
 */
function a3_portfolio_get_item_ids_page( $item_ids = -1, $column = '', $custom_style = '', $additional_params = array() ) {
	global $wpdb, $wp_query, $portfolio_query, $portfolio_query_vars;

	if ( $column < 1 || $column > 6 ) $column = a3_portfolio_get_col_per_row();

	$item_ids_get = array();
	if ( is_array( $item_ids ) ) {
		$item_ids_get = $item_ids;
	} elseif ( false !== stristr( $item_ids, ',' ) ) {
		$item_ids_a = explode( ',', $item_ids );
		foreach ( $item_ids_a as $item_id ) {
			$item_ids_get[] = (int) trim( $item_id );
		}
	} elseif ( (int) $item_ids > 0 ) {
		$item_ids_get[] = (int) trim( $item_ids );
	}

	if ( ( ! is_array( $item_ids_get ) || count( $item_ids_get ) < 1 || ( count( $item_ids_get ) == 1 && in_array( 0, $item_ids_get ) ) ) && is_singular( 'a3-portfolio' ) ) {
		$item_ids_get = array( $wp_query->post->ID );
	}

	// if don't parse any IDs then return main portfolios page
	if ( ! is_array( $item_ids_get ) || count( $item_ids_get ) < 1 ) {
		return a3_portfolio_get_main_page( 'none', $column, -1, true, $additional_params );
	}

	$query_args = array(
		'post_type'           => 'a3-portfolio',
		'post_status'         => 'publish',
		'orderby'             => 'post__in',
		'posts_per_page'      => -1,
		'paged'               => '',
		'post__in'            => $item_ids_get,
		'ignore_sticky_posts' => true,
	);

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query object

	$wp_query = new \WP_Query( $query_args );

	ob_start();

	remove_action( 'a3rev_loop_after', 'responsi_pagination', 10, 0 );

	a3_portfolio_get_template( 'shortcodes/portfolio-item-cards.php', array(
			'container_id'		=> $additional_params['blockID'] ?? '',
			'item_ids'          => $item_ids_get,
			'number_columns'    => $column,
			'custom_style'      => $custom_style,
			'additional_params' => $additional_params
		)
	) ;

	$ouput = ob_get_clean();

	// Reset Query
	wp_reset_query();

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query objects back
	$GLOBALS['post'] = $wp_query->post;

	global $a3_portfolio_frontend_scripts;
	$a3_portfolio_frontend_scripts->a3_portfolio_main_page_scripts();

	return $ouput;
}

/**
 * a3_portfolio_get_categories_page()
 *
 * @param $cat_ids array|string|int
 * @param $column int
 * @return html of list card items
 */
function a3_portfolio_get_categories_page( $cat_ids, $column = '', $number_items = -1, $show_navbar = true, $additional_params = array() ) {
	global $wpdb, $wp_query, $portfolio_query, $portfolio_query_vars;

	if ( $column < 1 || $column > 6 ) $column = a3_portfolio_get_col_per_row();

	if ( (int) $number_items < 1 ) $number_items = -1;

	$cat_ids_get = array();
	if ( is_array( $cat_ids ) ) {
		$cat_ids_get = $cat_ids;
	} elseif ( false !== stristr( $cat_ids, ',' ) ) {
		$cat_ids_a = explode( ',', $cat_ids );
		foreach ( $cat_ids_a as $cat_id ) {
			$cat_ids_get[] = (int) trim( $cat_id );
		}
	} elseif ( (int) $cat_ids > 0 ) {
		$cat_ids_get[] = (int) trim( $cat_ids );
	}

	if ( count( $cat_ids_get ) == 1 && in_array( 0, $cat_ids_get ) && taxonomy_exists( 'portfolio_cat' ) ) {
		$cat_ids_get = array( get_queried_object_id() );
	}

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query object

	$category_args = array(
		'post_type'      => 'a3-portfolio',
		'post_status'    => 'publish',
		'order'          => apply_filters( 'portfolio_order', 'DESC' ),
		'orderby'        => 'post_date',
		'posts_per_page' => (int) $number_items,
		'paged'          => '',
		'tax_query'      => array(
				array(
					'taxonomy'         => 'portfolio_cat',
					'field'            => 'id',
					'terms'            => $cat_ids_get,
					'include_children' => false,
					'operator'         => 'IN'
				)
			)
	);

	$wp_query = new \WP_Query( $category_args );

	ob_start();

	remove_action( 'a3rev_loop_after', 'responsi_pagination', 10, 0 );

	if ( ! $show_navbar ) {
		remove_action( 'a3_portfolio_custom_before_category_content', 'a3_portfolio_custom_category_nav_bar', 10 );
	}

	a3_portfolio_get_template( 'shortcodes/portfolio-categories.php', array(
		'container_id'		=> $additional_params['blockID'] ?? '',
		'cat_ids'           => $cat_ids_get,
		'number_columns'    => $column,
		'number_items'      => $number_items,
		'show_navbar'       => $show_navbar,
		'additional_params' => $additional_params
	) );

	$ouput = ob_get_clean();

	// Reset Query
	wp_reset_query();

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query objects back
	$GLOBALS['post'] = $wp_query->post;

	global $a3_portfolio_frontend_scripts;
	$a3_portfolio_frontend_scripts->a3_portfolio_main_page_scripts();

	return $ouput;
}

/**
 * a3_portfolio_get_tags_page()
 *
 * @param $tag_ids array|string|int
 * @param $column int
 * @return html of list card items
 */
function a3_portfolio_get_tags_page( $tag_ids, $column = '', $number_items = -1, $show_navbar = true, $additional_params = array() ) {
	global $wpdb, $wp_query, $portfolio_query, $portfolio_query_vars;

	if ( $column < 1 || $column > 6 ) $column = a3_portfolio_get_col_per_row();

	if ( (int) $number_items < 1 ) $number_items = -1;

	$tag_ids_get = array();
	if ( is_array( $tag_ids ) ) {
		$tag_ids_get = $tag_ids;
	} elseif ( false !== stristr( $tag_ids, ',' ) ) {
		$tag_ids_a = explode( ',', $tag_ids );
		foreach ( $tag_ids_a as $tag_id ) {
			$tag_ids_get[] = (int) trim( $tag_id );
		}
	} elseif ( (int) $tag_ids > 0 ) {
		$tag_ids_get[] = (int) trim( $tag_ids );
	}

	if ( count( $tag_ids_get ) == 1 && in_array( 0, $tag_ids_get ) && taxonomy_exists( 'portfolio_tag' ) ) {
		$tag_ids_get = array( get_queried_object_id() );
	}

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query object

	$category_args = array(
		'post_type'      => 'a3-portfolio',
		'post_status'    => 'publish',
		'order'          => apply_filters( 'portfolio_order', 'DESC' ),
		'orderby'        => 'post_date',
		'posts_per_page' => (int) $number_items,
		'paged'          => '',
		'tax_query'      => array(
				array(
					'taxonomy'         => 'portfolio_tag',
					'field'            => 'id',
					'terms'            => $tag_ids_get,
					'include_children' => false,
					'operator'         => 'IN'
				)
			)
	);

	$wp_query = new \WP_Query( $category_args );

	ob_start();

	remove_action( 'a3rev_loop_after', 'responsi_pagination', 10, 0 );

	if ( ! $show_navbar ) {
		remove_action( 'a3_portfolio_custom_before_tag_content', 'a3_portfolio_tag_nav_bar', 10 );
	}

	a3_portfolio_get_template( 'shortcodes/portfolio-tags.php', array(
		'container_id'      => $additional_params['blockID'] ?? '',
		'tag_ids'           => $tag_ids_get,
		'number_columns'    => $column,
		'number_items'      => $number_items,
		'show_navbar'       => $show_navbar,
		'additional_params' => $additional_params
	) );

	$ouput = ob_get_clean();

	// Reset Query
	wp_reset_query();

	list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query objects back
	$GLOBALS['post'] = $wp_query->post;

	global $a3_portfolio_frontend_scripts;
	$a3_portfolio_frontend_scripts->a3_portfolio_main_page_scripts();

	return $ouput;
}
