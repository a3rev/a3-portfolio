<?php
/**
 * The template for displaying all single portfolio.
 *
 * Override this template by copying it to yourtheme/portfolios/single-portfolio.php
 *
 * @author 		A3 Rev
 * @version     1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

$portfolio_id = 0;
if ( is_singular( 'a3-portfolio' ) ) {
	$portfolio_id = get_the_ID();
}

$layout_column = a3_portfolio_single_get_layout_column();

?>

<?php if ( have_posts() ) : ?>

	<div class="single-a3-portfolio <?php echo a3_portfolio_single_get_layout_column_class(); ?> single-a3-portfolio-<?php echo $portfolio_id; ?>">

	    <?php
			/**
			 * a3_portfolio_before_single_content hook
			 *
			 * @hooked a3_portfolio_custom_single_style - 5
			 */
			do_action( 'a3_portfolio_before_single_content', $portfolio_id );
		?>

	    <div class="a3-portfolio-single-wrap">

		    <?php

				while ( have_posts() ) : the_post();

					a3_portfolio_get_template( 'content-single-portfolio.php', array( 'portfolio_id' => $portfolio_id, 'layout_column' => $layout_column ) );

				endwhile;

			?>

		</div>

		<?php
			/**
			 * a3_portfolio_after_single_content hook
			 *
			 * @hooked a3_portfolio_single_scripts - 5
			 */
			do_action( 'a3_portfolio_after_single_content', $portfolio_id );
		?>

	</div>

<?php endif; ?>