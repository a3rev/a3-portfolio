<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( 'Divi' != get_template() ) {
	return; // Exit if it's not Divi theme
}

add_filter( 'template_include', 'a3_portfolio_divi_theme_template_include', 101 );

function a3_portfolio_divi_theme_template_include( $template ) {

	if ( 'Divi' != get_template() ) {
		return $template;
	}

	if ( is_tax( 'portfolio_cat' ) || is_tax( 'portfolio_tag' ) ) {

		if ( is_archive() && is_viewing_portfolio_taxonomy() ) {

			if ( file_exists( get_stylesheet_directory() . '/taxonomy-portfolio_cat.php' ) ) {
				$template = get_stylesheet_directory() . '/taxonomy-portfolio_cat.php';
			} elseif ( file_exists( A3_PORTFOLIO_TEMPLATE_PATH . "/taxonomy-portfolio_cat_divi.php" ) ) {
				$template = A3_PORTFOLIO_TEMPLATE_PATH . "/taxonomy-portfolio_cat_divi.php";
			}

		}

	}

	return $template;
}