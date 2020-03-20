<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

namespace A3Rev\Portfolio\Blocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Categories {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );	
	}

	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}

		// Create Dynamic Block via PHP render callback
		$block_args = array(
			'attributes'      => array(
				'blockID' => array(
					'type' => 'string'
				),
				'catIDs' => array(
					'type'    => 'array',
					'items'   => [
						'type' => 'integer',
					],
				),
				'enableCustomColumns'	=> array(
					'type'    => 'boolean',
					'default' => false,
				),
				'customColumns'	=> array(
					'type'    => 'number',
					'default' => a3_portfolio_get_col_per_row(),
				),
				'numberItems'	=> array(
					'type'    => 'string',
					'default' => '',
				),
				'showNavBar'	=> array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
			'render_callback' 	=> array( $this, 'render' )
		);

		register_block_type( 'a3-portfolio/categories', $block_args );
	}

	public function render( $attributes ) {

		extract( $attributes );

		if ( ! isset( $catIDs ) || empty( $catIDs ) ) {
			return '';
		}

		if ( ! isset( $enableCustomColumns ) || ! $enableCustomColumns ) {
			$customColumns = 0;
		}

		ob_start();
		echo a3_portfolio_get_categories_page( $catIDs, $customColumns, $numberItems, $showNavBar );
		$output = ob_get_clean();

		return $output;
	}
}
