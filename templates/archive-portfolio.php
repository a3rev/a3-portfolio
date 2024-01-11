<?php
/**
 * The Template for displaying Portfolio archives, including the main portfolio page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/portfolios/archive-portfolio.php
 *
 * @author 		A3 Rev
 * @version     1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>

	<div style="clear:both"></div>

    <div class="a3-portfolio-container" data-container-id="<?php echo esc_attr( $container_id ); ?>" data-column="<?php echo esc_attr( $number_columns ); ?>">
	    <div style="clear:both"></div>

	    <?php
			/**
			 * a3_portfolio_before_main_content hook
			 *
			 * @hooked a3_portfolio_nav_bar - 10
			 */
			do_action( 'a3_portfolio_before_main_content' );
		?>

	    <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

			<?php
				/**
				 * a3_portfolio_before_main_loop hook
				 *
				 * @hooked a3_portfolio_main_query - 10
				 */
				do_action( 'a3_portfolio_before_main_loop' );
			?>

		    <?php

				while ( have_posts() ) : the_post();

					a3_portfolio_get_template( 'content-portfolio.php', $additional_params );

				endwhile;

			?>

			<?php
				/**
				 * a3_portfolio_after_main_loop hook
				 *
				 * @hooked a3_portfolio_get_portfolios_uncategorized - 10
				 */
				do_action( 'a3_portfolio_after_main_loop' );
			?>

		</div>

		<div style="clear:both"></div>

		<?php
			/**
			 * a3_portfolio_after_main_content hook
			 *
			 */
			do_action( 'a3_portfolio_after_main_content' );
		?>

	</div>

	<div style="clear:both"></div>

<?php endif; ?>