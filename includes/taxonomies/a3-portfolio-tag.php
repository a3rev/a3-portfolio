<?php

namespace A3Rev\Portfolio\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tag
{

	public function __construct() {
		add_action( 'portfolio_tag_pre_add_form', array( $this, 'portfolio_tag_description' ) );
	}

	public function portfolio_tag_description() {
		echo wpautop( sprintf( __( 'Use the a3 Portfolios Tag Cloud <a href="%s">widget</a> for navigation.', 'a3-portfolio' ), 'widgets.php' ) );
	}

}
