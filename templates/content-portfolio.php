<?php
/**
 * The template for displaying portfolio content within loops.
 *
 * Override this template by copying it to yourtheme/portfolios/content-portfolio.php
 *
 * @author 		A3 Rev
 * @version     2.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $portfolio_gallery;

$portfolio_id = get_the_ID();

$item_class = a3_portfolio_get_item_class( $portfolio_id );

if ( $item_class == '' ) {
	$item_class = 'uncategorized';
}

$portfolio_gallery       = a3_portfolio_get_gallery( $portfolio_id );
$enableCardSticker       = $enableCardSticker ?? false;
$cardStickerPosition     = $cardStickerPosition ?? 'under-image';
$enableDropDownSticker   = $enableDropDownSticker ?? false;
$dropDownStickerPosition = $dropDownStickerPosition ?? 'top-right';
?>
<div class="a3-portfolio-item-load a3-portfolio-item <?php echo $item_class; ?>" data-index="<?php echo $post->post_name; ?>">

	<?php do_action( 'a3_portfolio_before_loop_item', $portfolio_id ); ?>

	<div class="a3-portfolio-item-container">

		<div class="a3-portfolio-item-block">

			<?php do_action( 'a3_portfolio_before_loop_item_card', $portfolio_id ); ?>

			<?php a3_portfolio_card_get_first_thumb_image( $portfolio_id, $portfolio_gallery, $enableCardSticker, $cardStickerPosition ); ?>
			<?php a3_portfolio_card_get_item_title( $portfolio_id ); ?>

			<?php
			/**
			 * a3_portfolio_after_loop_item_card hook
			 *
			 * @hooked a3_portfolio_card_item_description - 2
			 * @hooked a3_portfolio_card_item_viewmore - 5
			 */
			do_action( 'a3_portfolio_after_loop_item_card', $portfolio_id );
			?>

		</div>

	</div>

	<?php do_action( 'a3_portfolio_after_loop_item', $portfolio_id ); ?>

	<?php do_action( 'a3_portfolio_before_item_expander', $portfolio_id ); ?>

	<div class="a3-portfolio-item-expander-content" style="position: absolute;visibility: hidden;">

		<div class="a3-portfolio-item-image-container" data-portfolioId="<?php echo $portfolio_id; ?>">

			<?php do_action( 'a3_portfolio_before_item_expander_large_image_container', $portfolio_id, $enableDropDownSticker, $dropDownStickerPosition ); ?>

			<?php a3_portfolio_get_large_image_container( $portfolio_id, $portfolio_gallery, $enableDropDownSticker, $dropDownStickerPosition ); ?>

			<?php
				/**
				 * a3_portfolio_after_item_expander_large_image_container hook
				 *
				 * @hooked a3_portfolio_get_thumbs_below_gallery - 10
				 */
				do_action( 'a3_portfolio_after_item_expander_large_image_container', $portfolio_id, $enableDropDownSticker, $dropDownStickerPosition );
			?>

		</div>

		<div class="a3-portfolio-item-content-container">

			<?php do_action( 'a3_portfolio_before_item_expander_title', $portfolio_id ); ?>

			<h2>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php do_action( 'a3_portfolio_after_item_expander_title', $portfolio_id ); ?>

			<?php
				/**
				 * a3_portfolio_before_item_expander_content hook
				 *
				 * @hooked a3_portfolio_get_entry_metas - 5
				 * @hooked a3_portfolio_get_social_icons - 10
				 * @hooked a3_portfolio_get_thumbs_right_gallery - 20
				 */
				do_action( 'a3_portfolio_before_item_expander_content', $portfolio_id );
			?>

			<?php
				/**
				 * a3_portfolio_before_item_expander_content hook
				 *
				 * @hooked a3_portfolio_get_expander_attribute_above_desc - 10
				 */
				do_action( 'a3_portfolio_before_item_expander_full_content', $portfolio_id );
			?>

			<div class="a3-portfolio-item-content-text">
				<?php echo do_shortcode( wpautop( get_the_content() ) ); ?>
			</div>

			<?php
				/**
				 * a3_portfolio_after_item_expander_full_content hook
				 *
				 */
				do_action( 'a3_portfolio_after_item_expander_full_content', $portfolio_id );
			?>

			<?php
				/**
				 * a3_portfolio_after_item_expander_content hook
				 *
				 * @hooked a3_portfolio_main_get_categories_meta - 5
				 * @hooked a3_portfolio_main_get_tags_meta - 10
				 * @hooked a3_portfolio_main_get_launch_button - 20
				 * @hooked a3_portfolio_get_expander_attribute_bottom_content - 30
				 */
				do_action( 'a3_portfolio_main_after_item_expander_content', $portfolio_id );
			?>

		</div>

		<div class="clear"></div>

	</div>

	<?php do_action( 'a3_portfolio_after_item_expander', $portfolio_id ); ?>

</div>
