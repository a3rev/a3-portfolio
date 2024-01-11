<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * a3_portfolio_get_css_file_url( $file )
 *
 * @param $file string filename
 * @return URL to the file
 */
function a3_portfolio_get_css_file_url( $file = '' ) {
	// If we're not looking for a file, do not proceed
	if ( empty( $file ) )
		return;

	// Look for file in stylesheet
	if ( file_exists( get_stylesheet_directory() . '/portfolios/' . $file ) ) {
		$file_url = get_stylesheet_directory_uri() . '/portfolios/' . $file;

	// Look for file in stylesheet
	} elseif ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
		$file_url = get_stylesheet_directory_uri() . '/' . $file;

	// Look for file in template
	} elseif ( file_exists( get_template_directory() . '/portfolios/' . $file ) ) {
		$file_url = get_template_directory_uri() . '/portfolios/' . $file;

	// Look for file in template
	} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
		$file_url = get_template_directory_uri() . '/' . $file;

	// Backwards compatibility
	} else {
		$file_url = A3_PORTFOLIO_CSS_URL. '/' . $file;
	}

	$file_url = str_replace( array( 'http:', 'https:' ), '', $file_url );

	return apply_filters( 'a3_portfolio_get_css_file_url', $file_url, $file );
}

/**
 * Get templates passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @return void
 */
function a3_portfolio_get_template( $template_name, $args = array() ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$template_file_path = a3_portfolio_get_template_file_path( $template_name );

	if ( empty( $template_file_path ) ) {
		return;
	}

	if ( ! file_exists( $template_file_path ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file_path ), '1.0.0' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin
	$template_file_path = apply_filters( 'a3_portfolio_get_template', $template_file_path, $template_name, $args );

	do_action( 'a3_portfolio_before_template_part', $template_name, $template_file_path, $args );

	include( $template_file_path );

	do_action( 'a3_portfolio_after_template_part', $template_name, $template_file_path, $args );
}

/**
 * a3_portfolio_get_template_file_path( $file )
 *
 * This is the load order:
 *
 *		yourtheme					/	portfolio	/	$file
 *		yourtheme					/	$file
 *		A3_PORTFOLIO_TEMPLATE_PATH	/	$file
 *
 * @access public
 * @param $file string filename
 * @return PATH to the file
 */
function a3_portfolio_get_template_file_path( $file = '' ) {
	// If we're not looking for a file, do not proceed
	if ( empty( $file ) )
		return;

	// Look for file in stylesheet
	if ( file_exists( get_stylesheet_directory() . '/portfolios/' . $file ) ) {
		$file_path = get_stylesheet_directory() . '/portfolios/' . $file;

	// Look for file in stylesheet
	} elseif ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
		$file_path = get_stylesheet_directory() . '/' . $file;

	// Look for file in template
	} elseif ( file_exists( get_template_directory() . '/portfolios/' . $file ) ) {
		$file_path = get_template_directory() . '/portfolios/' . $file;

	// Look for file in template
	} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
		$file_path = get_template_directory() . '/' . $file;

	// Get default template
	} else {
		$file_path = A3_PORTFOLIO_TEMPLATE_PATH . '/' . $file;
	}

	// Return filtered result
	return apply_filters( 'a3_portfolio_get_template_file_path', $file_path, $file );
}

/**
 * a3_portfolio_get_per_page()
 *
 * @return number
 */
function a3_portfolio_get_per_page() {
	$post_per_page = 1000000;
	return apply_filters( 'a3_portfolio_get_per_page', $post_per_page );
}

/**
 * a3_portfolio_get_col_per_row()
 *
 * @return number
 */
function a3_portfolio_get_col_per_row() {
	global $a3_portfolio_item_cards_settings;
	$number_columns = 3;
	if ( $a3_portfolio_item_cards_settings['portfolio_items_per_row'] >= 1 ) {
		$number_columns = $a3_portfolio_item_cards_settings['portfolio_items_per_row'];
	}
	return apply_filters( 'a3_portfolio_get_col_per_row', $number_columns );
}

/**
 * a3_portfolio_get_col_per_row()
 *
 * @return number
 */
function a3_portfolio_card_image_height_fixed() {
	global $a3_portfolio_item_cards_settings;
	$portfolio_card_image_height_fixed = 60;
	if ( isset( $a3_portfolio_item_cards_settings['portfolio_card_image_height'] ) && $a3_portfolio_item_cards_settings['portfolio_card_image_height'] != 'fixed' ) {
		$portfolio_card_image_height_fixed = false;
	} elseif ( isset( $a3_portfolio_item_cards_settings['portfolio_card_image_height_fixed'] ) && $a3_portfolio_item_cards_settings['portfolio_card_image_height_fixed'] >= 50 ) {
		$portfolio_card_image_height_fixed = (int) $a3_portfolio_item_cards_settings['portfolio_card_image_height_fixed'];
	}

	return apply_filters( 'a3_portfolio_card_image_height_fixed', $portfolio_card_image_height_fixed );
}

/**
 * a3_portfolio_get_desktop_expander_top_alignment()
 *
 * @return number
 */
function a3_portfolio_get_desktop_expander_top_alignment() {
	global $a3_portfolio_global_item_expander_settings;
	$top_alignment = 0;
	if ( (int) $a3_portfolio_global_item_expander_settings['desktop_top_alignment'] != 0 ) {
		$top_alignment = (int) $a3_portfolio_global_item_expander_settings['desktop_top_alignment'];
	}
	return apply_filters( 'a3_portfolio_get_desktop_expander_top_alignment', $top_alignment );
}

/**
 * a3_portfolio_get_mobile_expander_top_alignment()
 *
 * @return number
 */
function a3_portfolio_get_mobile_expander_top_alignment() {
	global $a3_portfolio_global_item_expander_settings;
	$top_alignment = 0;
	if ( $a3_portfolio_global_item_expander_settings['enable_mobile_top_alignment'] && (int) $a3_portfolio_global_item_expander_settings['mobile_top_alignment'] != 0 ) {
		$top_alignment = (int) $a3_portfolio_global_item_expander_settings['mobile_top_alignment'];
	}
	return apply_filters( 'a3_portfolio_get_mobile_expander_top_alignment', $top_alignment );
}

/**
 * is_viewing_portfolio_taxonomy()
 *
 * @return boolean
 */
function is_viewing_portfolio_taxonomy(){
	global $wp_query;
	$wp_query->posts_per_page = 1;
	$is_viewing = false;
	if ( ( isset( $wp_query->query_vars['taxonomy'] ) && 'portfolio_cat' == $wp_query->query_vars['taxonomy'] ) || isset( $wp_query->query_vars['portfolio_cat'] ) || isset( $wp_query->query_vars['portfolio_tag'] ) ) {
		$is_viewing = true;
	}

	return apply_filters( 'is_viewing_portfolio_taxonomy', $is_viewing );
}

/**
 * a3_portfolio_expander_template()
 *
 * @return html ouput
 */
function a3_portfolio_expander_template() {
	$expander_template = '
<div class="a3-portfolio-expander-popup">
	<div class="a3-portfolio-loading"></div>
	<div class="a3-portfolio-controller">
		<div class="closebutton"><i class="a3-portfolio-icon-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></i></div>
	</div>
	<div style="clear:both;"></div>
	<div class="inner a3-portfolio-inner-container">
		<div class="a3-portfolio-inner-wrap">
		</div>
	</div>
	<div class="closebutton"><i class="a3-portfolio-icon-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg></i></div>
</div>';

	return $expander_template = apply_filters( 'a3_portfolio_expander_template', $expander_template );
}

/**
 * a3_portfolio_header_meta()
 *
 * @return html ouput
 */
function a3_portfolio_header_meta() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}

/**
 * a3_portfolio_term_description()
 *
 * @return html ouput
 */
function a3_portfolio_term_description() {
	global $wp_query;
	$term_desc = '';
	if ( isset( $wp_query->query_vars['portfolio_cat'] ) || isset($wp_query->query_vars['portfolio_tag'] ) ) {
		if ( isset( $wp_query->query_vars['portfolio_cat'] ) ) {
			if ( term_description() ) {
				$term_desc = '<div class="porfolio-term-description">'.term_description().'</div>';
			}
		}
		if ( isset( $wp_query->query_vars['portfolio_tag'] ) ) {
			$category = get_term_by('slug',$wp_query->query_vars['portfolio_tag'],'portfolio_tag');
			if ( $category->description ) {
				$term_desc = '<div class="porfolio-term-description"><p>'.$category->description.'</p></div>';
			}
		}
	}

	echo apply_filters( 'a3_portfolio_term_description', $term_desc );
}

/**
 * a3_portfolio_nav_bar()
 *
 * @return void
 */
function a3_portfolio_nav_bar() {

	$menus = array();
	$top_cats = a3_portfolio_get_parent_category_visiable();
	if ( ! empty( $top_cats ) && ! is_wp_error( $top_cats ) ) {
		foreach ( $top_cats as $term ) {
			$menus[$term->slug] = $term->name;
		}
	}

	a3_portfolio_get_template( 'navbar/main-navbar.php', array( 'menus' => $menus ) );
}

/**
 * a3_portfolio_category_nav_bar()
 *
 * @return void
 */
function a3_portfolio_category_nav_bar( $term_id = null ) {
	if ( is_tax( 'portfolio_cat' ) ) {
		$term = get_term_by( 'slug', get_query_var('portfolio_cat'), 'portfolio_cat' );
		$term_id = $term->term_id;
	}

	$args = array(
		'parent' 		=> $term_id,
		'child_of'		=> $term_id,
		'menu_order'	=> 'ASC',
		'hide_empty'	=> 1,
		'hierarchical'	=> 1,
		'taxonomy'		=> 'portfolio_cat',
		'pad_counts'	=> 1
	);
	$menus = get_categories( $args );
	if ( ! $menus || ! is_array( $menus ) || count( $menus ) < 1 ) {
		$menus = false;
	}

	a3_portfolio_get_template( 'navbar/category-navbar.php', array( 'menus' => $menus ) );
}

/**
 * a3_portfolio_custom_category_nav_bar()
 *
 * @return void
 */
function a3_portfolio_custom_category_nav_bar( $cat_ids = array() ) {
	if ( is_array( $cat_ids ) && count( $cat_ids ) > 1 ) {
		$menus = array();
		foreach ( $cat_ids as $cat_id ) {
			$menus[] = get_term( (int)$cat_id, 'portfolio_cat' );
		}

		a3_portfolio_get_template( 'navbar/category-navbar.php', array( 'menus' => $menus ) );
	}
}

/**
 * a3_portfolio_tag_nav_bar()
 *
 * @return void
 */
function a3_portfolio_tag_nav_bar() {

	$menus = array();
	$all_cats = a3_portfolio_get_all_categories_visiable();
	if ( ! empty( $all_cats ) && ! is_wp_error( $all_cats ) ) {
		foreach ( $all_cats as $term ) {
			$menus[$term->slug] = $term->name;
		}
	}

	a3_portfolio_get_template( 'navbar/tag-navbar.php', array( 'menus' => $menus ) );
}

/**
 * a3_portfolio_main_query()
 *
 * @return void
 */
function a3_portfolio_main_query() {
	global $wp_query;

	$top_cat_ids = array_keys( a3_portfolio_get_parent_category_visiable() );

	// Just get portfolio of parent category
	$wp_query->query_vars['tax_query'] = array(
		array(
			'taxonomy'         => 'portfolio_cat',
			'field'            => 'id',
			'terms'            => $top_cat_ids,
			'include_children' => false,
			'operator'         => 'IN'
		)
	);

	$wp_query = new \WP_Query( $wp_query->query_vars );
}

/**
 * a3_portfolio_get_portfolios_uncategorized()
 *
 * @return void
 */
function a3_portfolio_get_portfolios_uncategorized() {
	a3_portfolio_main_uncategorized_query();

	while ( have_posts() ) : the_post();

		a3_portfolio_get_template( 'content-portfolio.php' );

	endwhile;
}

/**
 * a3_portfolio_main_uncategorized_query()
 *
 * @return void
 */
function a3_portfolio_main_uncategorized_query() {
	global $wp_query;

	$all_cat_ids = array_keys( a3_portfolio_get_all_categories() );

	// Just get portfolio of parent category
	$wp_query->query_vars['tax_query'] = array(
		array(
			'taxonomy'         => 'portfolio_cat',
			'field'            => 'id',
			'terms'            => $all_cat_ids,
			'include_children' => false,
			'operator'         => 'NOT IN'
		)
	);

	$wp_query = new \WP_Query( $wp_query->query_vars );
}

/**
 * a3_portfolio_get_image_blank()
 *
 * @return void
 */
function a3_portfolio_get_image_blank() {
	$image_blank = A3_PORTFOLIO_IMAGES_URL . '/_blank.gif';

	return apply_filters( 'a3_portfolio_get_image_blank', $image_blank );
}


/**
 * a3_portfolio_card_get_first_thumb_image()
 *
 * @return void
 */
function a3_portfolio_card_get_first_thumb_image( $portfolio_id = 0, $gallery = array(), $enableSticker = false, $stickerPosition = false, $echo = true ) {
	global $a3_portfolio_item_cards_settings;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$_blank = a3_portfolio_get_image_blank();

	$main_card_image = '';
	if ( $gallery ) {
		$card_url    = wp_get_attachment_image_src( $gallery[0], 'portfolio-item-card-image', true );
		$card_srcset = wp_get_attachment_image_srcset( $gallery[0], 'portfolio-item-card-image' );

		if ( $card_srcset === false ) {
			$card_srcset = '';
			$card_sizes  = '';
		} else {
			$card_srcset = 'srcset="' . esc_attr( $card_srcset ) . '"';
			$card_sizes  = 'sizes="(max-width: 300px) 100vw, 300px"';
		}

		if ( $card_url && $card_url[0] != '' ) {
			$alt = get_post_meta( $gallery[0], '_wp_attachment_image_alt', true );
			if ( empty( $alt ) ) {
				$alt = get_the_title( $portfolio_id );
			}

			$main_card_image = '<img
				class="a3-portfolio-thumb-lazy attachment-a3-portfolio wp-post-image"
				src="'.$card_url[0].'"
				alt="'.$alt.'"
				'.$card_srcset.'
				'.$card_sizes.'
			/>';
		}
	}

	if ( trim( $main_card_image ) == '' ) {
		$main_card_image = '<img
			class="a3-portfolio-thumb-lazy no-thumb"
			src="'.a3_portfolio_no_image().'"
		/>';
	}

	$image_container_class = '';
	if ( 'under' == $a3_portfolio_item_cards_settings['cards_title_type'] ) {
		if ( 'item_expander' == $a3_portfolio_item_cards_settings['cards_image_opens'] ) {
			$image_container_class = 'a3-portfolio-card-opens-expander';
		} else {
			$main_card_image = '<a href="'.get_permalink( $portfolio_id ).'">' .$main_card_image. '</a>';
		}
	}

	$item_title = a3_portfolio_card_get_item_title_overlay( $portfolio_id, false );

	$sticker_html = a3_portfolio_tags_sticker( $portfolio_id, $enableSticker, $stickerPosition, false );

	if ( 'under-image' !== $stickerPosition ) {
		$main_card_image = '<div class="a3-portfolio-card-image-container '.$image_container_class.'">' . $main_card_image . $item_title . $sticker_html . '</div>';
	} else {
		$main_card_image = '<div class="a3-portfolio-card-image-container '.$image_container_class.'">' . $main_card_image . $item_title . '</div>' . $sticker_html;
	}

	$main_card_image = apply_filters( 'a3_portfolio_card_get_first_thumb_image', $main_card_image, $portfolio_id );

	if ( $echo ) {
		echo $main_card_image;
	} else {
		return $main_card_image;
	}
}

/**
 * a3_portfolio_get_first_thumb_image_url()
 *
 * @return void
 */
function a3_portfolio_get_first_thumb_image_url( $portfolio_id = 0, $gallery = array(), $thumb = 'portfolio-item-card-image', $echo = true ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$_blank = a3_portfolio_get_image_blank();

	$main_card_image = '';
	if ( $gallery ) {
		$card_url = wp_get_attachment_image_src( $gallery[0], $thumb, true );
		if ( $card_url && $card_url[0] != '' ) {
			$main_card_image = $card_url[0];
		}
	}

	if ( trim( $main_card_image ) == '' ) {
		$main_card_image = a3_portfolio_no_image();
	}

	$main_card_image = apply_filters( 'a3_portfolio_get_first_thumb_image_url', $main_card_image, $portfolio_id );

	if ( $echo ) {
		echo $main_card_image;
	} else {
		return $main_card_image;
	}
}

/**
 * a3_portfolio_card_get_item_title()
 *
 * @return void
 */
function a3_portfolio_card_get_item_title( $portfolio_id = 0, $echo = true ) {
	global $a3_portfolio_item_cards_settings;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	if ( ! isset( $a3_portfolio_item_cards_settings['cards_title_type'] ) || $a3_portfolio_item_cards_settings['cards_title_type'] != 'under' ) {
		return '';
	}

	$container_class = 'a3-portfolio-card-title-under';
	$title_class = '';
	if ( isset( $a3_portfolio_item_cards_settings['cards_title_opens'] ) && $a3_portfolio_item_cards_settings['cards_title_opens'] == 'item_expander' ) {
		$title_class = 'a3-portfolio-card-opens-expander';
	}

	$container_class = apply_filters('a3_portfolio_cards_title_container_class', $container_class, $portfolio_id );
	$title_class     = apply_filters('a3_portfolio_cards_title_class', $title_class, $portfolio_id );

	$title_text = '<h3>' .get_the_title( $portfolio_id ) . '</h3>';
	$title_text = sprintf( '<a href="%s" class="%s">%s</a>', get_permalink( $portfolio_id ), esc_attr( $title_class ), $title_text );
	$item_title = sprintf( '<div class="%s">%s</div>', esc_attr( $container_class ), $title_text );
	$item_title = apply_filters( 'a3_portfolio_card_get_item_title', $item_title, $portfolio_id );

	if ( $echo ) {
		echo $item_title;
	} else {
		return $item_title;
	}
}

/**
 * a3_portfolio_card_get_item_title_overlay()
 *
 * @return void
 */
function a3_portfolio_card_get_item_title_overlay( $portfolio_id = 0, $echo = true ) {
	global $a3_portfolio_item_cards_settings;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	if ( isset( $a3_portfolio_item_cards_settings['cards_title_type'] ) && $a3_portfolio_item_cards_settings['cards_title_type'] == 'under' ) {
		return '';
	}

	$title_class = 'a3-portfolio-card-overlay';
	if ( isset( $a3_portfolio_item_cards_settings['cards_title_opens'] ) && $a3_portfolio_item_cards_settings['cards_title_opens'] == 'item_expander' ) {
		$title_class .= ' a3-portfolio-card-opens-expander';
	}

	$title_class     = apply_filters('a3_portfolio_cards_title_class', $title_class, $portfolio_id );

	$title_text = '<h3>' .get_the_title( $portfolio_id ) . '</h3>';
	$title_text = sprintf( '<a href="%s" class="%s">%s</a>', get_permalink( $portfolio_id ), esc_attr( $title_class ), $title_text );
	$item_title = apply_filters( 'a3_portfolio_card_get_item_title', $title_text, $portfolio_id );

	if ( $echo ) {
		echo $item_title;
	} else {
		return $item_title;
	}
}

function a3_portfolio_get_tags_sticker( $portfolio_id = 0, $enableSticker = false, $position = 'top-right' ) {
	if ( ! $enableSticker ) return;

	// Don't show Tags meta inside the content
	remove_action( 'a3_portfolio_main_after_item_expander_content', 'a3_portfolio_main_get_tags_meta', 10 );
	remove_action( 'a3_portfolio_single_after_item_expander_content', 'a3_portfolio_single_get_tags_meta', 10 );

	a3_portfolio_tags_sticker( $portfolio_id, $enableSticker, $position, true ); 
}

function a3_portfolio_tags_sticker( $portfolio_id = 0, $enableSticker = false, $position = 'top-right', $echo = true ) {
	if ( ! $enableSticker ) return;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	if ( empty( $portfolio_id ) ) {
		return;
	}
	
	$tags = get_the_terms( $portfolio_id, 'portfolio_tag' );
	if ( ! $tags || is_wp_error( $tags ) || count( $tags ) < 1 ) {
		return;
	}

	$tags_html = '';
	foreach ( $tags as $term ) {
		$text_color   = get_term_meta( $term->term_id, 'text-color', true );
		$bg_color     = get_term_meta( $term->term_id, 'bg-color', true );
		$border_color = get_term_meta( $term->term_id, 'border-color', true );

		$tag_style = '';
		if ( $text_color ) {
			$tag_style .= 'color:' . $text_color . ';';
		}
		if ( $bg_color ) {
			$tag_style .= 'background-color:' . $bg_color . ';';
		}
		if ( $border_color ) {
			$tag_style .= 'border-color:' . $border_color . ';';
		}

		
		$tags_html .= '<span class="a3-portfolio-tag-sticker" style="' . $tag_style. '">' . $term->name . '</span>';
	}

	$tags_html = apply_filters( 'a3_portfolio_tags_sticker', $tags_html, $portfolio_id );

	if ( $tags_html ) {
		$tags_html = '<div class="a3-portfolio-tags-sticker ' . $position . '">' . $tags_html . '</div>';
	}

	if ( $echo ) {
		echo $tags_html;
	} else {
		return $tags_html;
	}
}

function a3_portfolio_card_item_description( $portfolio_id = 0, $echo = true ) {
	global $a3_portfolio_item_cards_settings;

	$item_description = '';
	if ( $a3_portfolio_item_cards_settings['enable_cards_description'] ) {

		if ( $portfolio_id < 1 ) {
			$portfolio_id = get_the_ID();
		}

		$card_desc = trim( esc_html( get_post_meta( $portfolio_id, '_a3_portfolio_card_desc', true ) ) );
		if ( '' == $card_desc ) {
			$porfolio_data = get_post( $portfolio_id );
			$card_desc = strip_shortcodes( strip_tags( trim( $porfolio_data->post_content ) ) );
		}

		$item_description = '<div class="a3-portfolio-card-description"><div>' . $card_desc . '</div></div>';

		$item_description = apply_filters( 'a3_portfolio_card_get_item_description', $item_description, $portfolio_id );

	}

	if ( $echo ) {
		echo $item_description;
	} else {
		return $item_description;
	}
}

function a3_portfolio_card_item_viewmore( $portfolio_id = 0, $echo = true ) {
	global $a3_portfolio_item_cards_settings;

	$item_viewmore = '';
	if ( $a3_portfolio_item_cards_settings['enable_cards_viewmore'] ) {

		if ( $portfolio_id < 1 ) {
			$portfolio_id = get_the_ID();
		}

		$button_class  = apply_filters( 'a3_portfolio_viewmore_button_class', 'portfolio_viewmore_button', $portfolio_id );
		$button_text   = trim( get_post_meta( $portfolio_id, '_a3_portfolio_viewmore_button_text', true ) );
		if ( '' == $button_text ) {
			$button_text   = apply_filters( 'a3_portfolio_viewmore_button_text', a3_portfolio_ei_ict_t__( 'View More Button Text', __( 'View More', 'a3-portfolio' ) ), $portfolio_id );
		}

		if ( 'item_expander' == $a3_portfolio_item_cards_settings['cards_viewmore_opens'] ) {
			$button_class .= ' a3-portfolio-card-opens-expander';
		}

		$button_html   = apply_filters( 'a3_portfolio_viewmore_button_html', '<a href="'.get_permalink( $portfolio_id ).'" class="' . $button_class . '">' . $button_text . '</a>', $portfolio_id );

		$item_viewmore = '<div class="a3-portfolio-card-viewmore">' . $button_html . '</div>';

		$item_viewmore = apply_filters( 'a3_portfolio_card_item_viewmore', $item_viewmore, $portfolio_id );

	}

	if ( $echo ) {
		echo $item_viewmore;
	} else {
		return $item_viewmore;
	}
}

/**
 * a3_portfolio_get_large_image_container()
 *
 * @return void
 */
function a3_portfolio_get_large_image_container( $portfolio_id = 0, $gallery = '', $enableSticker = false, $stickerPosition = 'top-right' ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	a3_portfolio_get_template( 'expander/large-image-container.php', array( 'portfolio_id' => $portfolio_id, 'gallery' => $gallery, 'enableSticker' => $enableSticker, 'stickerPosition' => $stickerPosition ) );
}

/**
 * a3_portfolio_get_first_large_image()
 *
 * @return void
 */
function a3_portfolio_get_first_large_image( $gallery = array(), $echo = true ) {
	$_blank = a3_portfolio_get_image_blank();

	$main_large_image = '';
	if ( $gallery ) {
		$large_url    = wp_get_attachment_image_src( $gallery[0], 'portfolio-gallery-image', true );
		$large_srcset = wp_get_attachment_image_srcset( $gallery[0], 'portfolio-gallery-image' );
		$large_sizes  = wp_get_attachment_image_sizes( $gallery[0], 'portfolio-gallery-image' );

		if ( $large_srcset === false ) {
			$large_srcset = '';
		} else {
			$large_srcset = 'data-osrcset="' . esc_attr( $large_srcset ) . '"';
		}
		if ( $large_sizes === false ) {
			$large_sizes = '';
		} else {
			$large_sizes = 'sizes="' . esc_attr( $large_sizes ) . '"';
		}

		$large_image_class = 'a3-notlazy a3-portfolio-large-lazy portfolio_image';
		global $a3_portfolio_global_settings;
		if ( 'yes' == $a3_portfolio_global_settings['item_post_gallery_lightbox'] ) {
			$large_image_class .= ' a3-portfolio-image-gallery';
		}

		if ( $large_url && $large_url[0] != '' ) {
			$alt = get_post_meta( $gallery[0], '_wp_attachment_image_alt', true );
			if ( empty( $alt ) ) {
				$alt = '';
			}

			$the_caption = get_post_field( 'post_excerpt', $gallery[0] );
			$main_large_image = '<img
				class="'.$large_image_class.'"
				src="'.$_blank.'"
				alt="'.$alt.'"
				item_id="1"
				data-original="'.$large_url[0].'"
				data-caption="'.$the_caption.'"
				'.$large_srcset.'
				'.$large_sizes.'
			/>';
		}
	}

	if ( trim( $main_large_image ) == '' ) {
		$main_large_image = '<img
			class="a3-notlazy a3-portfolio-large-lazy
			no-thumb"
			src="'.$_blank.'"
			data-original="' . a3_portfolio_no_image('no-image-large.png') . '"
		/>';
	}

	$main_large_image = apply_filters( 'a3_portfolio_get_first_large_image', $main_large_image );

	if ( $echo ) {
		echo $main_large_image;
	} else {
		return $main_large_image;
	}
}

/**
 * a3_portfolio_get_entry_metas()
 *
 * @return void
 */
function a3_portfolio_get_entry_metas( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	a3_portfolio_get_template( 'expander/entry-metas.php', array( 'portfolio_id' => $portfolio_id ) );
}

/**
 * a3_portfolio_get_social_icons()
 *
 * @return void
 */
function a3_portfolio_get_social_icons( $portfolio_id = 0 ) {
	global $a3_portfolio_global_item_expander_settings;

	if ( ! $a3_portfolio_global_item_expander_settings['enable_expander_social'] ) return;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	a3_portfolio_get_template( 'expander/social-icons.php', array( 'portfolio_id' => $portfolio_id ) );
}

/**
 * a3_portfolio_get_gallery_thumbs()
 *
 * @return void
 */
function a3_portfolio_get_gallery_thumbs( $portfolio_id = 0, $gallery = '', $include_lightbox_script = false ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	global $a3_portfolio_global_settings;
	if ( $include_lightbox_script && 'yes' != $a3_portfolio_global_settings['item_post_gallery_lightbox'] ) {
		$include_lightbox_script = false;
	}

	$image_blank = a3_portfolio_get_image_blank();

	a3_portfolio_get_template( 'expander/gallery-thumbs.php', array( 'portfolio_id' => $portfolio_id, 'gallery' => $gallery, 'image_blank' => $image_blank, 'include_lightbox_script' => $include_lightbox_script ) );
}

/**
 * a3_portfolio_get_thumbs_below_gallery()
 *
 * @return void
 */
function a3_portfolio_get_thumbs_below_gallery( $portfolio_id = 0 ) {
	global $a3_portfolio_global_item_expander_settings;

	if ( 'below_gallery' != $a3_portfolio_global_item_expander_settings['expander_thumb_gallery_position'] ) return;

	global $portfolio_gallery;

	a3_portfolio_get_gallery_thumbs( $portfolio_id, $portfolio_gallery );
}

/**
 * a3_portfolio_get_thumbs_below_gallery()
 *
 * @return void
 */
function a3_portfolio_get_thumbs_right_gallery( $portfolio_id = 0 ) {
	global $a3_portfolio_global_item_expander_settings;

	if ( 'below_gallery' == $a3_portfolio_global_item_expander_settings['expander_thumb_gallery_position'] ) return;

	global $portfolio_gallery;

	a3_portfolio_get_gallery_thumbs( $portfolio_id, $portfolio_gallery );
}

/**
 * a3_portfolio_get_categories_meta()
 *
 * @return void
 */
function a3_portfolio_get_categories_meta( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$portfolio_terms = get_the_terms( $portfolio_id, 'portfolio_cat' );

	a3_portfolio_get_template( 'expander/categories-meta.php', array( 'portfolio_categories' => $portfolio_terms ) );
}

function a3_portfolio_main_get_categories_meta() {
	global $a3_portfolio_global_item_expander_settings;

	if ( ! $a3_portfolio_global_item_expander_settings['enable_expander_meta_cats'] ) return;

	a3_portfolio_get_categories_meta();
}

function a3_portfolio_single_get_categories_meta() {
	a3_portfolio_get_categories_meta();
}

/**
 * a3_portfolio_get_tags_meta()
 *
 * @return void
 */
function a3_portfolio_get_tags_meta( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$portfolio_tags = get_the_terms( $portfolio_id, 'portfolio_tag' );

	a3_portfolio_get_template( 'expander/tags-meta.php', array( 'portfolio_tags' => $portfolio_tags ) );
}

function a3_portfolio_main_get_tags_meta() {
	global $a3_portfolio_global_item_expander_settings;

	if ( ! $a3_portfolio_global_item_expander_settings['enable_expander_meta_tags'] ) return;

	a3_portfolio_get_tags_meta();
}

function a3_portfolio_single_get_tags_meta() {
	a3_portfolio_get_tags_meta();
}

/**
 * a3_portfolio_get_attribute_table()
 *
 * @return void
 */
function a3_portfolio_get_attribute_table( $portfolio_id = 0, $is_single = false ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$attributes_value = a3_portofolio_get_portfolio_attributes_value( $portfolio_id );

	$processed_attributes_value = array();

	if ( is_array( $attributes_value ) && count( $attributes_value ) > 0 ) {
		foreach ( $attributes_value as $attribute_id => $attribute ) {
			// Don't get if it's for single and don't set visible on single from current portfolio
			if ( $is_single && isset( $attribute['is_visible_post'] ) && 0 == $attribute['is_visible_post'] ) {
				continue;
			}

			// Don't get if it's for expander and don't set visible on expander from current portfolio
			if ( ! $is_single && isset( $attribute['is_visible_expander'] ) && 0 == $attribute['is_visible_expander'] ) {
				continue;
			}

			$processed_attributes_value[$attribute_id] = $attribute;
		}
	}

	a3_portfolio_get_template( 'global/attribute-table.php', array( 'portfolio_id' => $portfolio_id, 'attributes_value' => $processed_attributes_value ) );
}

/**
 * a3_portfolio_get_expander_attribute_table_before()
 *
 * @return void
 */
function a3_portfolio_get_expander_attribute_above_desc( $portfolio_id = 0 ) {
	global $a3_portfolio_global_item_expander_settings;

	if ( 'above_description' != $a3_portfolio_global_item_expander_settings['expander_attribute_position'] ) return;

	a3_portfolio_get_attribute_table( $portfolio_id, false );
}

/**
 * a3_portfolio_get_expander_attribute_table_after()
 *
 * @return void
 */
function a3_portfolio_get_expander_attribute_bottom_content( $portfolio_id = 0 ) {
	global $a3_portfolio_global_item_expander_settings;

	if ( 'bottom_content' != $a3_portfolio_global_item_expander_settings['expander_attribute_position'] ) return;

	a3_portfolio_get_attribute_table( $portfolio_id, false );
}

/**
 * a3_portfolio_get_expander_attribute_table_after()
 *
 * @return void
 */
function a3_portfolio_single_get_attribute_under_gallery( $portfolio_id = 0 ) {
	global $a3_portfolio_item_posts_settings;

	if ( 'under_gallery' != $a3_portfolio_item_posts_settings['single_attribute_position'] ) return;

	a3_portfolio_get_attribute_table( $portfolio_id, true );
}

/**
 * a3_portfolio_get_expander_attribute_table_after()
 *
 * @return void
 */
function a3_portfolio_single_get_attribute_above_desc( $portfolio_id = 0 ) {
	global $a3_portfolio_item_posts_settings;

	if ( 'above_description' != $a3_portfolio_item_posts_settings['single_attribute_position'] ) return;

	a3_portfolio_get_attribute_table( $portfolio_id, true );
}

/**
 * a3_portfolio_get_expander_attribute_table_after()
 *
 * @return void
 */
function a3_portfolio_single_get_attribute_bottom_content( $portfolio_id = 0 ) {
	global $a3_portfolio_item_posts_settings;

	if ( 'bottom_content' != $a3_portfolio_item_posts_settings['single_attribute_position'] ) return;

	a3_portfolio_get_attribute_table( $portfolio_id, true );
}

/**
 * a3_portfolio_get_launch_button()
 *
 * @return void
 */
function a3_portfolio_get_launch_button( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$button_class = 'portfolio_button';
	$button_text  = get_post_meta( $portfolio_id, '_a3_portfolio_launch_button_text', true );
	$button_link  = get_post_meta( $portfolio_id, '_a3_portfolio_launch_site_url', true );
	$open_type    = get_post_meta( $portfolio_id, '_a3_portfolio_launch_open_type', true );

	if ( empty( $button_text ) || $button_text == '' ) {
		$button_text = a3_portfolio_ei_ict_t__( 'Launch Site Button Text', __( 'LAUNCH SITE', 'a3-portfolio' ) );
	}

	$button_class = apply_filters( 'a3_portfolio_launch_button_class', $button_class, $portfolio_id );
	$button_text  = apply_filters( 'a3_portfolio_launch_button_text', $button_text, $portfolio_id );
	$button_link  = apply_filters( 'a3_portfolio_launch_site_url', $button_link, $portfolio_id );
	$open_type    = apply_filters( 'a3_portfolio_launch_open_type', $open_type, $portfolio_id );

	a3_portfolio_get_template( 'expander/launch-button.php', array( 'launch_site_url' => $button_link, 'button_text' => $button_text, 'open_type' => $open_type, 'button_class' => $button_class ) );
}

function a3_portfolio_main_get_launch_button() {
	a3_portfolio_get_launch_button();
}

function a3_portfolio_single_get_launch_button() {
	a3_portfolio_get_launch_button();
}

/**
 * a3_portfolio_single_get_layout_column()
 *
 * @return layout_column
 */
function a3_portfolio_single_get_layout_column( $portfolio_id = 0 ) {
	global $a3_portfolio_item_posts_settings;

	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	// Get column for single portfolio page
	$layout_column = get_post_meta( $portfolio_id, '_a3_portfolio_meta_layout_column', true );
	if ( empty( $layout_column ) || $layout_column == '' ) {
		$layout_column = $a3_portfolio_item_posts_settings['portfolio_single_layout_column'];
	}

	return apply_filters( 'a3_portfolio_single_get_layout_column', $layout_column, $portfolio_id );
}

/**
 * a3_portfolio_single_get_layout_column_class()
 *
 * @return string
 */
function a3_portfolio_single_get_layout_column_class( $portfolio_id = 0 ) {
	if ( $portfolio_id < 1 ) {
		$portfolio_id = get_the_ID();
	}

	$layout_column = a3_portfolio_single_get_layout_column( $portfolio_id );

	$a3_portfolio_single_column_class = 'single-a3-portfolio-2-column';
	if ( 1 == $layout_column ) {
		$a3_portfolio_single_column_class = 'single-a3-portfolio-1-column';
	}

	return apply_filters( 'a3_portfolio_single_get_layout_column_class', $a3_portfolio_single_column_class, $portfolio_id );
}

function a3_portfolio_generate_sticker_inline_css( $attributes ) {
	global $a3_portfolio_blocks_styles;

	$blockID           = $attributes['blockID'];
	$enableCardSticker = $attributes['enableCardSticker'] ?? false;
	$styleCardSticker  = $attributes['styleCardSticker'] ?? array();
	$enableDropDownSticker = $attributes['enableDropDownSticker'] ?? false;
	$styleExSticker  = $attributes['styleExSticker'] ?? array();

    $stylecss = '';

    if ( $enableCardSticker ) {
        $stylecss .= '
.wp-block-a3-portfolios-'.$blockID.':not(.a3-portfolio-expander-popup) .a3-portfolio-tags-sticker .a3-portfolio-tag-sticker {
    '. ( isset( $styleCardSticker['padding'] ) ? $a3_portfolio_blocks_styles->spacingPresetCssVar( $styleCardSticker['padding'], 'padding' ) : '') .'
    '. ( isset( $styleCardSticker['margin'] ) ? $a3_portfolio_blocks_styles->spacingPresetCssVar( $styleCardSticker['margin'], 'margin' ) : '') .'
    '. ( isset( $styleCardSticker['border'] ) ? $a3_portfolio_blocks_styles->borderPresetCssVar( $styleCardSticker['border'] ) : '') .'
    '. ( isset( $styleCardSticker['radius'] ) ? $a3_portfolio_blocks_styles->borderRadiusPresetCssVar( $styleCardSticker['radius'] . 'px' ) : '') .'
    '. ( ! empty( $styleCardSticker ) ? $a3_portfolio_blocks_styles->typographyPresetCssVar( $styleCardSticker ) : '' ) .'
}';
    }

    if ( $enableDropDownSticker ) {
        $stylecss .= '
.a3-portfolio-expander-popup[container-id="'.$blockID.'"] .a3-portfolio-tags-sticker .a3-portfolio-tag-sticker {
    '. ( isset( $styleExSticker['padding'] ) ? $a3_portfolio_blocks_styles->spacingPresetCssVar( $styleExSticker['padding'], 'padding' ) : '') .'
    '. ( isset( $styleExSticker['margin'] ) ? $a3_portfolio_blocks_styles->spacingPresetCssVar( $styleExSticker['margin'], 'margin' ) : '') .'
    '. ( isset( $styleExSticker['border'] ) ? $a3_portfolio_blocks_styles->borderPresetCssVar( $styleExSticker['border'] ) : '') .'
    '. ( isset( $styleExSticker['radius'] ) ? $a3_portfolio_blocks_styles->borderRadiusPresetCssVar( $styleExSticker['radius'] . 'px' ) : '') .'
    '. ( ! empty( $styleExSticker ) ? $a3_portfolio_blocks_styles->typographyPresetCssVar( $styleExSticker ) : '' ) .'
}';
    }

    if ( ! empty( $stylecss ) ) {
		$stylecss = $a3_portfolio_blocks_styles->minimizeCSSsimple( $stylecss );
	}

    return $stylecss; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
}
