<?php
/**
 * The Template for displaying Portfolio Shortcode of Categories
 *
 * Override this template by copying it to yourtheme/portfolios/shortcodes/portfolio-categories.php
 *
 * @author    A3 Rev
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
       * a3_portfolio_custom_before_category_content hook
       *
       * @hooked a3_portfolio_custom_category_nav_bar - 10
       */
      do_action( 'a3_portfolio_custom_before_category_content', $cat_ids );
    ?>

      <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

      <?php
        /**
         * a3_portfolio_shortcode_before_category_loop hook
         *
         */
        do_action( 'a3_portfolio_shortcode_before_category_loop', $cat_ids );
      ?>

        <?php

        while ( have_posts() ) : the_post();

          a3_portfolio_get_template( 'content-portfolio.php', $additional_params );

        endwhile;

      ?>

      <?php
        /**
         * a3_portfolio_shortcode_after_category_loop hook
         *
         */
        do_action( 'a3_portfolio_shortcode_after_category_loop', $cat_ids );
      ?>

    </div>

    <div style="clear:both"></div>

    <?php
      /**
       * a3_portfolio_shortcode_after_category_content hook
       *
       */
      do_action( 'a3_portfolio_shortcode_after_category_content', $cat_ids );
    ?>

  </div>

  <div style="clear:both"></div>

<?php endif; ?>