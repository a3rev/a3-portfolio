<?php
/**
 * The template for displaying portfolio large image container in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/large-image-container.php
 *
 * @author 		A3 Rev
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$the_caption = '';
if ( is_array( $gallery ) ) $the_caption = get_post_field( 'post_excerpt', $gallery[0] );
?>

<div class="active item">

	<?php do_action( 'a3_portfolio_expander_large_image_start', $portfolio_id, $enableSticker, $stickerPosition ); ?>

	<div class="a3-portfolio-loading"></div>

	<?php a3_portfolio_get_first_large_image( $gallery ); ?>

	<div style="clear:both"></div>

	<div class="caption_text_container">

	<?php if ( ! is_wp_error( $the_caption ) && $the_caption != '' ) : ?>

		<div class="portfolio_caption_text"><?php echo $the_caption; ?></div>

	<?php endif; ?>

	</div>

	<?php do_action( 'a3_portfolio_expander_large_image_end', $portfolio_id, $enableSticker, $stickerPosition ); ?>

	<div style="clear:both"></div>

</div>