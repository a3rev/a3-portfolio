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

class Items {

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

		if ( ! isset( $itemIDs ) || empty( $itemIDs ) ) {
			return '';
		}

		if ( ! isset( $enableCustomColumns ) || ! $enableCustomColumns ) {
			$customColumns = 0;
		}

		if ( ! isset( $align ) ) {
			$align = 'none';
		}

		if ( in_array( $align, array( 'left', 'right' ) ) ) {
			if ( ! isset( $alignWrap ) ) {
				$alignWrap = false;
			}
		}

		// Validate Max Width
        if ( ! isset( $widthUnit ) ) {
            $widthUnit = 'px';
        }
        if ( ! isset( $width ) ) {
            $width = 300;
        }
		if ( 'px' === $widthUnit && $width < 300 ) {
			$width = 300;
		} else if ( 'px' !== $widthUnit && $width > 100 ) {
			$width = 100;
		}

		// Validate Padding Unit
		if ( ! isset( $paddingUnit ) ) {
            $paddingUnit = 'px';
        }

		$style = '';

		if ( 'center' === $align ) $style .= 'float:none;margin:auto;display:table;';
		else $style .= 'float:'.$align.';';

		$style .= 'width:100%;max-width:'.$width.$widthUnit.';';

		if ( isset( $paddingLeft ) && ! empty( $paddingLeft ) ) {
			$style .= 'padding-left:'.$paddingLeft.$paddingUnit.';';
		}
		if ( isset( $paddingTop ) && ! empty( $paddingTop ) ) {
			$style .= 'padding-top:'.$paddingTop.$paddingUnit.';';
		}
		if ( isset( $paddingRight ) && ! empty( $paddingRight ) ) {
			$style .= 'padding-right:'.$paddingRight.$paddingUnit.';';
		}
		if ( isset( $paddingBottom ) && ! empty( $paddingBottom ) ) {
			$style .= 'padding-bottom:'.$paddingBottom.$paddingUnit.';';
		}

		ob_start();
		$inline_css = a3_portfolio_generate_sticker_inline_css( $attributes );
	    if (  ! empty( $inline_css ) ) {
	        echo '<style>'. $inline_css .'</style>';
	    }

		echo a3_portfolio_get_item_ids_page( $itemIDs, $customColumns, $style, $attributes );
		$output = ob_get_clean();

		$class_name = 'wp-block-a3-portfolios-'. ( $blockID ?? '' );
		if ( isset( $attributes['className'] ) ) {
			$class_name .= ' ' . $attributes['className'];
		}

		$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class_name ) );

		return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $output );
	}
}
