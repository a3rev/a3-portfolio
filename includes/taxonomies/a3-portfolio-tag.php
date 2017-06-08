<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class A3_Portfolio_Tag_Taxonomy
{

	public function __construct() {
		add_action( 'portfolio_tag_pre_add_form', array( $this, 'portfolio_tag_description' ) );
	}

	public function portfolio_tag_description() {
		echo wpautop( sprintf( __( 'Use the a3 Portfolios Tag Cloud <a href="%s">widget</a> for navigation.', 'a3_portfolios' ), 'widgets.php' ) );
	}

}

global $a3_portfolio_tag_taxonomy;
$a3_portfolio_tag_taxonomy = new A3_Portfolio_Tag_Taxonomy();
?>
