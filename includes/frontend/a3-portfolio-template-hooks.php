<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WP Header
 *
 */
add_action( 'wp_head', 'a3_portfolio_header_meta', 4 );

/**
 * Category & Tag Before
 *
 */
add_action( 'a3_portfolio_before_category_content', 'a3_portfolio_term_description', 8 );
add_action( 'a3_portfolio_before_tag_content', 'a3_portfolio_term_description', 8 );

/**
 * Before Main Content
 *
 */
add_action( 'a3_portfolio_before_main_content', 'a3_portfolio_nav_bar', 10 );

/**
 * Before Main Loop
 *
 */
add_action( 'a3_portfolio_before_main_loop', 'a3_portfolio_main_query', 10 );

/**
 * After Main Loop
 *
 */
add_action( 'a3_portfolio_after_main_loop', 'a3_portfolio_get_portfolios_uncategorized', 10 );

/**
 * After Item Card Loop
 *
 */
add_action( 'a3_portfolio_after_loop_item_card', 'a3_portfolio_card_item_description', 2 );
add_action( 'a3_portfolio_after_loop_item_card', 'a3_portfolio_card_item_viewmore', 5 );

/**
 * Before Category Content
 *
 */
add_action( 'a3_portfolio_before_category_content', 'a3_portfolio_category_nav_bar', 10 );

/**
 * Before Shortcode Category Content
 *
 */
add_action( 'a3_portfolio_custom_before_category_content', 'a3_portfolio_custom_category_nav_bar', 10 );

/**
 * Before Tag Content
 *
 */
add_action( 'a3_portfolio_before_tag_content', 'a3_portfolio_tag_nav_bar', 10 );

/**
 * Before Shortcode Tag Content
 *
 */
add_action( 'a3_portfolio_custom_before_tag_content', 'a3_portfolio_tag_nav_bar', 10 );

/**
 * After Expander Item Main Gallery
 *
 */
add_action( 'a3_portfolio_after_item_expander_large_image_container', 'a3_portfolio_get_thumbs_below_gallery', 10 );

/**
 * Before Expander Item Content
 *
 */
add_action( 'a3_portfolio_before_item_expander_content', 'a3_portfolio_get_entry_metas', 5 );
add_action( 'a3_portfolio_before_item_expander_content', 'a3_portfolio_get_social_icons', 10 );
add_action( 'a3_portfolio_before_item_expander_content', 'a3_portfolio_get_thumbs_right_gallery', 20 );

/**
 * Before Expander Full Content
 *
 */
add_action( 'a3_portfolio_before_item_expander_full_content', 'a3_portfolio_get_expander_attribute_above_desc', 10 );

/**
 * After Expander Item Content
 *
 */
add_action( 'a3_portfolio_main_after_item_expander_content', 'a3_portfolio_main_get_categories_meta', 5 );
add_action( 'a3_portfolio_main_after_item_expander_content', 'a3_portfolio_main_get_tags_meta', 10 );
add_action( 'a3_portfolio_main_after_item_expander_content', 'a3_portfolio_main_get_launch_button', 20 );
add_action( 'a3_portfolio_main_after_item_expander_content', 'a3_portfolio_get_expander_attribute_bottom_content', 30 );

/**
 * Start of Large Image Container
 *
 */
add_action( 'a3_portfolio_expander_large_image_start', 'a3_portfolio_get_tags_sticker', 5, 3 );

/**
 * After Single Large Image Container
 *
 */
add_action( 'a3_portfolio_single_after_large_image_container', 'a3_portfolio_single_get_attribute_under_gallery', 10 );

/**
 * Before Single Full Content
 *
 */
add_action( 'a3_portfolio_single_before_full_content', 'a3_portfolio_single_get_attribute_above_desc', 10 );

/**
 * After Single Expander Item Content
 *
 */
add_action( 'a3_portfolio_single_after_item_expander_content', 'a3_portfolio_single_get_categories_meta', 5 );
add_action( 'a3_portfolio_single_after_item_expander_content', 'a3_portfolio_single_get_tags_meta', 10 );
add_action( 'a3_portfolio_single_after_item_expander_content', 'a3_portfolio_single_get_launch_button', 20 );
add_action( 'a3_portfolio_single_after_item_expander_content', 'a3_portfolio_single_get_attribute_bottom_content', 30 );

 /**
 * Footer
 *
 */
