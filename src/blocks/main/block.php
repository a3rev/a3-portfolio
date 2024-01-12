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

class Main {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}

		register_block_type(
			__DIR__ . '/block.json',
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	public function render( $attributes, $content, $block ) {

		extract( $attributes );

		if ( ! isset( $enableCustomColumns ) || ! $enableCustomColumns ) {
			$customColumns = 0;
		}

		ob_start();
		$inline_css = a3_portfolio_generate_sticker_inline_css( $attributes );
	    if (  ! empty( $inline_css ) ) {
	        echo '<style>'. $inline_css .'</style>';
	    }
	    
		echo a3_portfolio_get_main_page( 'none', $customColumns, $numberItems, $showNavBar, $attributes );
		$output = ob_get_clean();

		$class_name = 'wp-block-a3-portfolios-'. ( $blockID ?? '' );
		if ( isset( $attributes['className'] ) ) {
			$class_name .= ' ' . $attributes['className'];
		}

		$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class_name ) );

		return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $output );
	}
}
