<?php
/**
 * The template for displaying portfolio tags meta in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/tags-meta.php
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

<?php if ( $portfolio_tags ) : ?>

	<div class="clear"></div>
	
	<div class="portfolio_item_tags">
		<span class="label"><?php echo a3_portfolio_ei_ict_t__( 'Tags field', __( 'Tags', 'a3-portfolio' ) ); ?> : </span><?php foreach ( $portfolio_tags as $term ) : ?>
		<span class="item-block"><?php echo $comma; ?><a href="<?php echo get_term_link( $term, $term->slug ); ?>"><?php echo $term->name; ?></a></span>
		<?php $comma = ', '; ?>
		<?php endforeach; ?>
	</div>

	<div class="clear"></div>

<?php endif; ?>
