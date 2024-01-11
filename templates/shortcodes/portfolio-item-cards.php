<?php
/**
 * The Template for displaying Portfolio Shortcode of Item Cards
 *
 * Override this template by copying it to yourtheme/portfolios/shortcodes/portfolio-item-cards.php
 *
 * @author    A3 Rev
 * @version     1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>

    <div class="a3-portfolio-container" data-container-id="<?php echo esc_attr( $container_id ); ?>" data-column="<?php echo $number_columns; ?>" style="<?php echo $custom_style; ?>">

      <div style="clear:both"></div>

      <?php
      /**
       * a3_portfolio_shortcode_before_item_cards_content hook
       *
       */
      do_action( 'a3_portfolio_shortcode_before_item_cards_content', $item_ids );
    ?>

      <div class="a3-portfolio-box-content a3-portfolio-box-content-col<?php echo $number_columns; ?>">

      <?php
        /**
         * a3_portfolio_shortcode_before_item_cards_loop hook
         *
         */
        do_action( 'a3_portfolio_shortcode_before_item_cards_loop', $item_ids );
      ?>

        <?php

        while ( have_posts() ) : the_post();

          a3_portfolio_get_template( 'content-portfolio.php', $additional_params );

        endwhile;

      ?>

      <?php
        /**
         * a3_portfolio_shortcode_after_item_cards_loop hook
         *
         */
        do_action( 'a3_portfolio_shortcode_after_item_cards_loop', $item_ids );
      ?>

    </div>

    <div style="clear:both"></div>

    <?php
      /**
       * a3_portfolio_shortcode_after_item_cards_content hook
       *
       */
      do_action( 'a3_portfolio_shortcode_after_item_cards_content', $item_ids );
    ?>

  </div>

<?php endif; ?>