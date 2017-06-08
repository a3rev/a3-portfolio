<?php
/**
 * The Template for displaying Portfolio Category
 *
 * Override this template by copying it to yourtheme/portfolios/taxonomy-portfolio_cat.php
 *
 * @author    A3 Rev
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>

  <div style="clear:both"></div>

    <div class="a3-portfolio-container">
      <div style="clear:both"></div>

      <?php
      /**
       * a3_portfolio_before_category_content hook
       *
       * @hooked a3_portfolio_category_nav_bar - 10
       */
      do_action( 'a3_portfolio_before_category_content' );
    ?>

      <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

      <?php
        /**
         * a3_portfolio_before_category_loop hook
         *
         */
        do_action( 'a3_portfolio_before_category_loop' );
      ?>

        <?php

        while ( have_posts() ) : the_post();

          a3_portfolio_get_template( 'content-portfolio.php' );

        endwhile;

      ?>

      <?php
        /**
         * a3_portfolio_after_category_loop hook
         *
         */
        do_action( 'a3_portfolio_after_category_loop' );
      ?>

    </div>

    <div style="clear:both"></div>

    <?php
      /**
       * a3_portfolio_after_category_content hook
       *
       */
      do_action( 'a3_portfolio_after_category_content' );
    ?>

  </div>

  <div style="clear:both"></div>

<?php endif; ?>