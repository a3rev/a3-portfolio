<?php
/**
 * Server-side rendering of the `core/post-title` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/post-title` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the filtered post title for the current post wrapped inside "h1" tags.
 */
function a3_portfolio_render_block_item_tags( $attributes, $content, $block ) {
	extract( $attributes );

	$portfolio_id = ! empty( $attributes['itemID'] ) ? $attributes['itemID'] : '';
	$attributes['enableCardSticker'] = true;

	ob_start();
	$inline_css = a3_portfolio_generate_sticker_inline_css( $attributes );
    if (  ! empty( $inline_css ) ) {
        echo '<style>'. $inline_css .'</style>';
    }
    
	a3_portfolio_get_tags_sticker( $portfolio_id, true, 'under-image' );
	$output = ob_get_clean();

	$class_name = 'wp-block-a3-portfolios-'. ( $blockID ?? '' );
	if ( isset( $attributes['className'] ) ) {
		$class_name .= ' ' . $attributes['className'];
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $class_name ) );

	return sprintf( '<div %1$s>%2$s</div>', $wrapper_attributes, $output );
}

/**
 * Registers the `core/post-title` block on the server.
 */
function a3_portfolio_register_block_item_tags() {
	register_block_type(
		__DIR__ . '/block.json',
		array(
			'render_callback' => 'a3_portfolio_render_block_item_tags',
		)
	);
}
add_action( 'init', 'a3_portfolio_register_block_item_tags' );
