<?php
/**
 * The template for displaying portfolio categories meta in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/categories-meta.php
 *
 * @author 		A3 Rev
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
$comma = '';
?>

<?php if ( $portfolio_categories ) : ?>

	<div class="clear"></div>

	<div class="portfolio_item_categories">
		<span class="label"><?php echo a3_portfolio_ei_ict_t__( 'Categories field', __( 'Categories', 'a3-portfolio' ) ); ?> : </span><?php foreach ( $portfolio_categories as $term ) : ?><span class="item-block"><?php echo $comma; ?><a href="<?php echo get_term_link( $term, $term->slug ); ?>"><?php echo $term->name; ?></a>
		</span><?php $comma = ', '; ?><?php endforeach; ?>
	</div>

	<div class="clear"></div>

<?php endif; ?>
