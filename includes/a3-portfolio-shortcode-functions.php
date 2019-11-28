<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * a3_portfolios()
 * Portfolios Template Tag
 *
 * @return html
 */
function a3_portfolios( $column = '', $number_items = 'all', $show_navbar = 1, $echo = true ) {
	global $a3_portfolio_shortcode_display;

	$attributes = 'column="'.esc_attr( $column ).'" number_items="'.esc_attr( $number_items ).'" show_navbar="'.esc_attr( $show_navbar ).'"';
	$output = $a3_portfolio_shortcode_display->parse_shortcode_portfolios( $attributes );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * a3_portfolio_recent()
 * Portfolios Template Tag
 *
 * @return html
 */
function a3_portfolio_recent( $column = '', $number_items = 'all', $show_navbar = 1, $echo = true ) {
	global $a3_portfolio_shortcode_display;

	$attributes = 'column="'.esc_attr( $column ).'" number_items="'.esc_attr( $number_items ).'" show_navbar="'.esc_attr( $show_navbar ).'"';
	$output = $a3_portfolio_shortcode_display->parse_shortcode_recent_portfolios( $attributes );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * a3_portfolio_sticky()
 * Portfolios Template Tag
 *
 * @return html
 */
function a3_portfolio_sticky( $column = '', $number_items = 'all', $show_navbar = 1, $echo = true ) {
	global $a3_portfolio_shortcode_display;

	$attributes = 'column="'.esc_attr( $column ).'" number_items="'.esc_attr( $number_items ).'" show_navbar="'.esc_attr( $show_navbar ).'"';
	$output = $a3_portfolio_shortcode_display->parse_shortcode_sticky_portfolios( $attributes );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * a3_portfolio_categories()
 * Portfolio Categories Template Tag
 *
 * @return html
 */
function a3_portfolio_categories( $cat_ids='', $column='', $number_items = 'all', $show_navbar = 1, $echo = true ) {
	global $a3_portfolio_shortcode_display;

	$attributes = 'ids="'.esc_attr( $cat_ids ).'" column="'.esc_attr( $column ).'" number_items="'.esc_attr( $number_items ).'" show_navbar="'.esc_attr( $show_navbar ).'"';
	$output = $a3_portfolio_shortcode_display->parse_shortcode_portfolio_categories( $attributes );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * a3_portfolio_tags()
 * Portfolio Tags Template Tag
 *
 * @return html
 */
function a3_portfolio_tags( $tag_ids='', $column='', $number_items = 'all', $show_navbar = 1, $echo = true ) {
	global $a3_portfolio_shortcode_display;

	$attributes = 'ids="'.esc_attr( $tag_ids ).'" column="'.esc_attr( $column ).'" number_items="'.esc_attr( $number_items ).'" show_navbar="'.esc_attr( $show_navbar ).'"';
	$output = $a3_portfolio_shortcode_display->parse_shortcode_portfolio_tags( $attributes );

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * a3_portfolio_items()
 * Portfolio Card Items Template Tag
 *
 * @return html
 */
function a3_portfolio_items( $ids='', $column='', $custom_style='', $echo = true ) {
	$portfolios_html = a3_portfolio_shortcode_item_cards_page( $ids, $column, $custom_style );

	$output = '';
	if ( trim( $portfolios_html ) != '' ) {
		global $a3_portfolio_frontend_scripts;
		$a3_portfolio_frontend_scripts->a3_portfolio_main_page_scripts();
	}

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}
