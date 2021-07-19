<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * A3 Portfolio Shortcode Embed
 *
 * Table Of Contents
 *
 * A3_Portfolio_Shortcode()
 * init()
 * add_shortcode_button()
 * generator_popup()
 * parse_shortcode_portfolio_item()
 */

namespace A3Rev\Portfolio\Shortcode;

if ( ! class_exists( 'A3_Portfolio_Shortcode_Display' ) && ! class_exists( '\A3Rev\Portfolio\Shortcode\Display' ) ) {

class Display
{

	public function __construct() {
		// Portfolio Single Shortcode
		add_shortcode( 'a3_portfolio_item', array( $this, 'parse_shortcode_portfolio_items') );

		// Portfolio Category Shortcode
		add_shortcode( 'a3_portfolio_category', array( $this, 'parse_shortcode_portfolio_categories') );

		// Portfolio Tag Shortcode
		add_shortcode( 'a3_portfolio_tag', array( $this, 'parse_shortcode_portfolio_tags') );

		// Portfolios Shortcode
		add_shortcode( 'a3_portfolios', array( $this, 'parse_shortcode_portfolios') );

		// Portfolios Recent Shortcode
		add_shortcode( 'a3_portfolio_recent', array( $this, 'parse_shortcode_recent_portfolios') );

		// Portfolios Recent Shortcode
		add_shortcode( 'a3_portfolio_sticky', array( $this, 'parse_shortcode_sticky_portfolios') );
	}

	public function parse_shortcode_recent_portfolios( $attributes = array() ) {
		if ( is_admin() ) {
			return '';
		}

		$attributes['type'] = 'recent';

		return $this->parse_shortcode_portfolios( $attributes );
	}

	public function parse_shortcode_sticky_portfolios( $attributes = array() ) {
		if ( is_admin() ) {
			return '';
		}

		$attributes['type'] = 'sticky';

		return $this->parse_shortcode_portfolios( $attributes );
	}

	public function parse_shortcode_portfolios( $attributes = array() ) {
		if ( is_admin() ) {
			return '';
		}

		$attr = shortcode_atts(
			array(
				'type'         => 'none', // none | recent | sticky
				'column'       => '',
				'number_items' => 'all',
				'show_navbar'  => 1,
			), $attributes );

		// XSS ok
		$column = esc_attr( $attr['column'] );

		if ( 'all' == $attr['number_items'] ) {
			$number_items = -1;
		} else {
			$number_items = (int) $attr['number_items'];
		}

		if ( 1 == (int) $attr['show_navbar'] ) {
			$show_navbar = true;
		} else {
			$show_navbar = false;
		}

		$output = a3_portfolio_get_main_page( $attr['type'], $column, $number_items, $show_navbar );

		return $output;
	}

	public function parse_shortcode_portfolio_categories( $attributes ) {
		if ( is_admin() ) {
			return '';
		}

		$attr = shortcode_atts(
			array(
				'ids'          => '',
				'column'       => '',
				'number_items' => 'all',
				'show_navbar'  => 1,
			), $attributes );

		// XSS ok
		$column = esc_attr( $attr['column'] );

		if ( 'all' == $attr['number_items'] ) {
			$number_items = -1;
		} else {
			$number_items = (int) $attr['number_items'];
		}

		if ( 1 == (int) $attr['show_navbar'] ) {
			$show_navbar = true;
		} else {
			$show_navbar = false;
		}

		$output = a3_portfolio_get_categories_page( $attr['ids'], $column, $number_items, $show_navbar );

		return $output;
	}

	public function parse_shortcode_portfolio_tags( $attributes ) {
		if ( is_admin() ) {
			return '';
		}
		
		$attr = shortcode_atts(
			array(
				'ids'          => '',
				'column'       => '',
				'number_items' => 'all',
				'show_navbar'  => 1,
			), $attributes );

		// XSS ok
		$column = esc_attr( $attr['column'] );

		if ( 'all' == $attr['number_items'] ) {
			$number_items = -1;
		} else {
			$number_items = (int) $attr['number_items'];
		}

		if ( 1 == (int) $attr['show_navbar'] ) {
			$show_navbar = true;
		} else {
			$show_navbar = false;
		}

		$output = a3_portfolio_get_tags_page( $attr['ids'], $column, $number_items, $show_navbar );

		return $output;
	}

	public function parse_shortcode_portfolio_items( $attributes ) {
		if ( is_admin() ) {
			return '';
		}

		$attr = shortcode_atts(
			array(
				'ids'            => '',
				'align'          => 'none',
				'width'          => '300px',
				'column'         => '',
				'padding_top'    => 0,
				'padding_bottom' => 0,
				'padding_left'   => 0,
				'padding_right'  => 0,
			), $attributes );

		// XSS ok
		$align          = esc_attr( $attr['align'] );
		$width          = esc_attr( $attr['width'] );
		$column         = esc_attr( $attr['column'] );
		$padding_top    = esc_attr( $attr['padding_top'] );
		$padding_bottom = esc_attr( $attr['padding_bottom'] );
		$padding_left   = esc_attr( $attr['padding_left'] );
		$padding_right  = esc_attr( $attr['padding_right'] );

		$custom_style = '';
		$custom_style .= 'width:'.$width.'; max-width:100%; box-sizing: border-box; ';
		$custom_style .= 'padding-top:'.(int)$padding_top.'px; ';
		$custom_style .= 'padding-bottom:'.(int)$padding_bottom.'px; ';
		$custom_style .= 'padding-left:'.(int)$padding_left.'px; ';
		$custom_style .= 'padding-right:'.(int)$padding_right.'px; ';

		if ( 'center' == trim( $align ) ) $custom_style .= 'float:none; margin:auto; display:table; ';
		elseif ( 'left-wrap' == trim( $align ) ) $custom_style .= 'float:left; ';
		elseif ( 'right-wrap' == trim( $align ) ) $custom_style .= 'float:right; ';
		elseif ( 'left-nowrap' == trim( $align ) ) $custom_style .= 'float:left; ';
		elseif ( 'right-nowrap' == trim( $align ) ) $custom_style .= 'float:right; ';
		else $custom_style .= 'float:'.trim( $align ).'; ';

		$output = a3_portfolio_get_item_ids_page( $attr['ids'], $column, $custom_style );

		return $output;
	}
}

}
