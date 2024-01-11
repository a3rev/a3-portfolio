<?php
/**
 * The Template for displaying Portfolio Tag page
 *
 * Override this template by copying it to yourtheme/portfolios/taxonomy-portfolio_tag.php
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

    <div class="a3-portfolio-container" data-container-id="<?php echo esc_attr( $container_id ); ?>">
      <div style="clear:both"></div>

      <?php
      /**
       * a3_portfolio_before_tag_content hook
       *
       * @hooked a3_portfolio_tag_nav_bar - 10
       */
      do_action( 'a3_portfolio_before_tag_content' );
    ?>

      <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

      <?php
        /**
         * a3_portfolio_before_tag_loop hook
         *
         */
        do_action( 'a3_portfolio_before_tag_loop' );
      ?>

        <?php

        while ( have_posts() ) : the_post();

          a3_portfolio_get_template( 'content-portfolio.php' );

        endwhile;

      ?>

      <?php
        /**
         * a3_portfolio_after_tag_loop hook
         *
         */
        do_action( 'a3_portfolio_after_tag_loop' );
      ?>

    </div>

    <div style="clear:both"></div>

    <?php
      /**
       * a3_portfolio_after_tag_content hook
       *
       */
      do_action( 'a3_portfolio_after_tag_content' );
    ?>

  </div>

  <div style="clear:both"></div>

<?php endif; ?>