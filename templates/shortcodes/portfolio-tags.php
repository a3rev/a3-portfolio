<?php
/**
 * The Template for displaying Portfolio Shortcode of Tags
 *
 * Override this template by copying it to yourtheme/portfolios/shortcodes/portfolio-tags.php
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

    <div class="a3-portfolio-container" data-container-id="<?php echo esc_attr( $container_id ); ?>" data-column="<?php echo $number_columns; ?>">

      <div style="clear:both"></div>

      <?php
      /**
       * a3_portfolio_custom_before_tag_content hook
       *
       * @hooked a3_portfolio_tag_nav_bar - 10
       */
      do_action( 'a3_portfolio_custom_before_tag_content', $tag_ids );
    ?>

      <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

      <?php
        /**
         * a3_portfolio_shortcode_before_category_loop hook
         *
         */
        do_action( 'a3_portfolio_shortcode_before_tag_loop', $tag_ids );
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
        do_action( 'a3_portfolio_shortcode_after_tag_loop', $tag_ids );
      ?>

    </div>

    <div style="clear:both"></div>

    <?php
      /**
       * a3_portfolio_after_tag_content hook
       *
       */
      do_action( 'a3_portfolio_after_tag_content', $tag_ids );
    ?>

  </div>

  <div style="clear:both"></div>

<?php endif; ?>