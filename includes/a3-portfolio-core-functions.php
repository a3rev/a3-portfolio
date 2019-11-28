<?php
/**
 * a3 Portfolios Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function a3_portfolio_create_page( $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$portfolio_page_name = $wpdb->get_var( "SELECT post_name FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%{$page_content}%'  AND `post_type` = 'page' AND `post_status` = 'publish' LIMIT 1" );

	if ( $portfolio_page_name != NULL )
		return;

	$page_data = array(
		'post_status' 		=> 'publish',
		'post_type' 		=> 'page',
		'post_author' 		=> 1,
		'post_name' 		=> $slug,
		'post_title' 		=> $page_title,
		'post_content' 		=> $page_content,
		'post_parent' 		=> $post_parent,
		'comment_status' 	=> 'closed'
	);
	$page_id = wp_insert_post( $page_data );

	if ( class_exists('SitePress') ) {
		global $sitepress;
		$source_lang_code = $sitepress->get_default_language();
		$wpdb->query( "UPDATE ".$wpdb->prefix . "icl_translations SET trid=".$page_id." WHERE element_id=".$page_id." AND language_code='".$source_lang_code."' AND element_type='post_page' " );
	}

	return $page_id;
}

function a3_portfolio_create_page_wpml( $trid, $lang_code, $source_lang_code, $slug, $page_title = '', $page_content = '' ) {
	global $wpdb;

	$element_id = $wpdb->get_var( "SELECT ID FROM " . $wpdb->posts . " AS p INNER JOIN " . $wpdb->prefix . "icl_translations AS ic ON p.ID = ic.element_id WHERE p.post_content LIKE '%$page_content%' AND p.post_type = 'page' AND p.post_status = 'publish' AND ic.trid=".$trid." AND ic.language_code = '".$lang_code."' AND ic.element_type = 'post_page' ORDER BY p.ID ASC LIMIT 1" );

	if ( $element_id != NULL ) :
		return $element_id;
	endif;

	$page_data = array(
		'post_date'			=> gmdate( 'Y-m-d H:i:s' ),
		'post_modified'		=> gmdate( 'Y-m-d H:i:s' ),
		'post_status' 		=> 'publish',
		'post_type' 		=> 'page',
		'post_author' 		=> 1,
		'post_name' 		=> $slug,
		'post_title' 		=> $page_title,
		'post_content' 		=> $page_content,
		'comment_status' 	=> 'closed'
	);
	$wpdb->insert( $wpdb->posts , $page_data);
	$element_id = $wpdb->insert_id;

	//$element_id = wp_insert_post( $page_data );

	$wpdb->insert( $wpdb->prefix . "icl_translations", array(
			'element_type'			=> 'post_page',
			'element_id'			=> $element_id,
			'trid'					=> $trid,
			'language_code'			=> $lang_code,
			'source_language_code'	=> $source_lang_code,
		) );

	return $element_id;
}

function a3_portfolio_auto_create_page_for_wpml(  $trid, $slug, $page_title = '', $page_content = '' ) {
	if ( class_exists('SitePress') ) {
		global $sitepress;
		$active_languages = $sitepress->get_active_languages();
		if ( is_array($active_languages)  && count($active_languages) > 0 ) {
			$source_lang_code = $sitepress->get_default_language();
			foreach ( $active_languages as $language ) {
				if ( $language['code'] == $source_lang_code ) continue;
				a3_portfolio_create_page_wpml( $trid, $language['code'], $source_lang_code, $slug.'-'.$language['code'], $page_title.' '.$language['display_name'], $page_content );
			}
		}
	}
}

function a3_portfolio_set_global_page() {
	global $wpdb, $portfolio_page_name, $portfolio_page_id;

	$portfolio_page_id = get_option( 'portfolio_page_id' );

	$page_data = null;
	if ( $portfolio_page_id != false ) {
		$page_data = $wpdb->get_row( $wpdb->prepare( "SELECT ID, post_name FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE %s AND `ID` = %d AND `post_type` = 'page' AND `post_status` = 'publish' LIMIT 1", '%[portfoliopage]%', $portfolio_page_id ) );
	}

	if ( $page_data == null ) {
		$page_data = $wpdb->get_row( "SELECT ID, post_name FROM `" . $wpdb->posts . "` WHERE `post_content` LIKE '%[portfoliopage]%' AND `post_type` = 'page' AND `post_status` = 'publish' ORDER BY ID DESC LIMIT 1" );
		if ( $page_data ) {
			update_option( 'portfolio_page_id', $page_data->ID );
		}
	}

	if ( $page_data == null ) {
		$portfolio_page_id_created = a3_portfolio_create_page( esc_sql( 'portfolios' ), '', __('Portfolios', 'a3-portfolio' ), '[portfoliopage]' );
		update_option( 'portfolio_page_id', $portfolio_page_id_created );
		$page_data = $wpdb->get_row( $wpdb->prepare( "SELECT ID, post_name FROM `" . $wpdb->posts . "` WHERE `ID` = %d LIMIT 1", $portfolio_page_id_created ) );
	}

	$portfolio_page_id = $page_data->ID;
	$portfolio_page_name = $page_data->post_name;

	// For WPML
	if ( class_exists('SitePress') ) {
		global $sitepress;
		$translation_page_data = null;
		$translation_page_data = $wpdb->get_row( $wpdb->prepare( "SELECT element_id FROM " . $wpdb->prefix . "icl_translations WHERE trid = %d AND element_type='post_page' AND language_code = %s LIMIT 1", $portfolio_page_id , $sitepress->get_current_language() ) );
		if ( $translation_page_data != null ) {
			$portfolio_page_id = $translation_page_data->element_id;
			$portfolio_page_wpml = get_post( $portfolio_page_id );
			$portfolio_page_name = $portfolio_page_wpml->post_name;
		}
	}
}

/**
 * Get permalink settings for Portfolio independent of the user locale.
 *
 */
function a3_portfolio_get_permalink_structure() {
	if ( function_exists( 'switch_to_locale' ) && did_action( 'admin_init' ) ) {
		switch_to_locale( get_locale() );
	}

	$permalinks = wp_parse_args( (array) get_option( 'a3_portfolio_permalinks', array() ), array(
		'portfolio_base'         => '',
		'category_base'          => '',
		'tag_base'               => '',
		'use_verbose_page_rules' => false,
	) );

	// Ensure rewrite slugs are set.
	$permalinks['portfolio_rewrite_slug'] = untrailingslashit( empty( $permalinks['portfolio_base'] ) ? _x( 'a3-portfolio', 'slug', 'a3-portfolio' )        : $permalinks['portfolio_base'] );
	$permalinks['category_rewrite_slug']  = untrailingslashit( empty( $permalinks['category_base'] ) ? _x( 'portfolio-category', 'slug', 'a3-portfolio' )   : $permalinks['category_base'] );
	$permalinks['tag_rewrite_slug']       = untrailingslashit( empty( $permalinks['tag_base'] ) ? _x( 'portfolio-tag', 'slug', 'a3-portfolio' )             : $permalinks['tag_base'] );

	if ( function_exists( 'restore_current_locale' ) && did_action( 'admin_init' ) ) {
		restore_current_locale();
	}
	return $permalinks;
}

/**
 * Recursively get page children.
 * @param  int $page_id
 * @return int[]
 */
function a3_portfolio_get_page_children( $page_id ) {
	$page_ids = get_posts( array(
		'post_parent' => $page_id,
		'post_type'   => 'page',
		'numberposts' => -1,
		'post_status' => 'any',
		'fields'      => 'ids',
	) );

	if ( ! empty( $page_ids ) ) {
		foreach ( $page_ids as $page_id ) {
			$page_ids = array_merge( $page_ids, a3_portfolio_get_page_children( $page_id ) );
		}
	}

	return $page_ids;
}

function a3_portfolio_get_gallery( $portfolio_id ) {
	if ( $portfolio_id < 1 ) return false;

	$thumb_id = get_post_thumbnail_id( $portfolio_id );

	$portfolio_gallery = get_post_meta( $portfolio_id, '_a3_portfolio_image_gallery', true );
	if ( empty( $portfolio_gallery ) ) {
		// Backwards compat
		$query_args = apply_filters( 'a3_portfolio_get_gallery_query_args', array(
			'post_parent'    => $portfolio_id,
			'numberposts'    => -1,
			'post_type'      => 'attachment',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_mime_type' => 'image',
			'fields'         => 'ids',
			'meta_value'     => 0
			) );
		$attachment_ids = get_posts( $query_args );
		if ( $thumb_id ) {
			$attachment_ids = array_diff( $attachment_ids, array( $thumb_id ) );
		}
		$portfolio_gallery = implode( ',', $attachment_ids );
	}

	$portfolio_gallery = explode( ',', $portfolio_gallery );
	if ( $thumb_id ) {
		$portfolio_gallery = array_diff( $portfolio_gallery, array( $thumb_id ) );
		$portfolio_gallery = array_merge( array( $thumb_id ), $portfolio_gallery );
	}

	$portfolio_gallery = array_map( 'trim', array_filter( $portfolio_gallery ) );

	if ( ! is_array( $portfolio_gallery ) || count( $portfolio_gallery ) < 1 ) {
		$portfolio_gallery = false;
	}

	return apply_filters( 'a3_portfolio_get_portfolio_gallery', $portfolio_gallery, $portfolio_id );
}

function a3_portfolio_get_thumbnail_image( $portfolio_id = 0 , $size = 'thumbnail', $attr = array() ) {
	$image = '';

	$portfolio_gallery = a3_portfolio_get_gallery( $portfolio_id );

	if ( $portfolio_gallery ) {
		$image = wp_get_attachment_image( $portfolio_gallery[0], $size, true );
	} else {
		$image = '<span class="a3-portfolio-no-thumbnail"></span>';
	}

	return apply_filters( 'a3_portfolio_get_thumbnail_image', $image, $portfolio_id );
}

function a3_portfolio_get_all_categories() {
	$portfolio_cats = array();
	$all_portfolio_cats = get_terms( 'portfolio_cat', array( 'hide_empty' => true ) );

	if ( ! empty( $all_portfolio_cats ) && ! is_wp_error( $all_portfolio_cats ) ) {
		foreach ( $all_portfolio_cats as $cat ) {
			//$cat->name = $append_str . $cat->name;
			$portfolio_cats[$cat->term_id] = $cat;
		}
	}

	return apply_filters( 'a3_portfolio_get_all_categories', $portfolio_cats );
}

function a3_portfolio_get_parent_category_visiable() {
	global $a3_portfolio_category_taxonomy;

	$portfolio_cats = array();
	$all_portfolio_cats = get_terms( 'portfolio_cat', array( 'hide_empty' => true, 'parent' => 0 ) );

	if ( ! empty( $all_portfolio_cats ) && ! is_wp_error( $all_portfolio_cats ) ) {
		foreach ( $all_portfolio_cats as $cat ) {
			$active_portfolio_taxonomy = $a3_portfolio_category_taxonomy->get_a3_portfolio_category_meta( $cat->term_id, 'active_portfolio_taxonomy' );
			if ( '' == $active_portfolio_taxonomy || 1 == $active_portfolio_taxonomy ) {
				$portfolio_cats[$cat->term_id] = $cat;
			}
		}
	}

	return apply_filters( 'a3_portfolio_get_parent_category_visiable', $portfolio_cats );
}

/**
 * Get all portfolio cats
 */
function a3_portfolio_get_all_categories_visiable( $parent = 0, $append_str = '', $check_visiable = true ) {
	global $a3_portfolio_category_taxonomy;

	$portfolio_cats = array();
	$all_portfolio_cats = get_terms( 'portfolio_cat', array( 'hide_empty' => true, 'parent' => $parent ) );

	if ( ! empty( $all_portfolio_cats ) && ! is_wp_error( $all_portfolio_cats ) ) {
		foreach ( $all_portfolio_cats as $cat ) {
			if ( $parent == 0 && $check_visiable ) {
				$active_portfolio_taxonomy = $a3_portfolio_category_taxonomy->get_a3_portfolio_category_meta( $cat->term_id, 'active_portfolio_taxonomy' );
				if ( '' != $active_portfolio_taxonomy && 1 != $active_portfolio_taxonomy ) continue;
			}
			$new_append_str = $append_str;
			$cat->name = $new_append_str . $cat->name;
			$new_append_str .= $append_str;
			$portfolio_cats[$cat->term_id] = $cat;

			$portfolio_cats = array_merge( $portfolio_cats, a3_portfolio_get_all_categories_visiable( $cat->term_id, $new_append_str , $check_visiable ) );
		}
	}

	return apply_filters( 'a3_portfolio_get_all_categories_visiable', $portfolio_cats, $parent, $append_str, $check_visiable );
}

/**
 * Get all portfolio tags
 */
function a3_portfolio_get_all_tags() {
	$portfolio_tags = array();
	$all_portfolio_tags = get_terms( 'portfolio_tag', array( 'hide_empty' => true ) );

	if ( ! empty( $all_portfolio_tags ) && ! is_wp_error( $all_portfolio_tags ) ) {
		foreach ( $all_portfolio_tags as $tag ) {
			//$cat->name = $append_str . $cat->name;
			$portfolio_tags[$tag->term_id] = $tag;
		}
	}

	return apply_filters( 'a3_portfolio_get_all_tags', $portfolio_tags );
}


function a3_portfolio_no_image( $file = 'no-image.png' ) {
	return apply_filters( 'a3_portfolio_no_image', A3_PORTFOLIO_TEMPLATE_IMAGES_URL . '/' . $file, $file );
}

function a3_portfolio_get_item_class( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) return '';

	$item_class = '';
	$comma = '';
	$portfolio_terms = get_the_terms( $portfolio_id, 'portfolio_cat' );

	if ( $portfolio_terms ) {
		foreach ( $portfolio_terms as $term ) {
			$item_class .= $comma . $term->slug;
			$comma = ' ';
		}
	}

	// Get Attribute Class
	if ( function_exists( 'a3_portofolio_get_portfolio_att_term_classes') ) {
		$item_class .= ' ' . a3_portofolio_get_portfolio_att_term_classes( $portfolio_id );
	}

	return $item_class;
}

function a3_portfolio_blue_message_dismiss() {
	check_ajax_referer( 'a3_portfolio_blue_message_dismiss', 'security' );
	$session_name   = $_REQUEST['session_name'];
	if ( !isset($_SESSION) ) { @session_start(); }
	$_SESSION[$session_name] = 1 ;
	die();
}

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
