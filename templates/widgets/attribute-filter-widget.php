<?php
/**
 * The template for displaying Attribute Filter Widget.
 *
 * Override this template by copying it to yourtheme/portfolios/widgets/attribute-filter-widget.php
 *
 * @author 		A3 Rev
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php if ( $terms ) : ?>

<?php
	/**
	 * a3_portfolio_before_attribute_filter_widget hook
	 *
	 * @hooked a3_portfolio_attribute_filter_widget_scripts - 10
	 */
	do_action( 'a3_portfolio_before_attribute_filter_widget', $taxonomy, $attribute, $terms );
?>

<div class="a3-portfolio-attribute-filter-container">

	<ul class="attribute-filter">

	    <?php
			/**
			 * a3_portfolio_before_attribute_filter_item hook
			 *
			 */
			do_action( 'a3_portfolio_before_attribute_filter_item', $taxonomy, $attribute, $terms );
		?>

	    <?php
			foreach ( $terms as $term ) :
		?>

		<li class="_<?php echo esc_attr( $term->slug ); ?>">
			<a data-filter=".<?php echo esc_attr( $term->slug ); ?>" href="#"><?php echo $term->name; ?></a>
		</li>

		<?php
			endforeach;
		?>

		<?php
			/**
			 * a3_portfolio_after_attribute_filter_item hook
			 *
			 */
			do_action( 'a3_portfolio_after_attribute_filter_item', $taxonomy, $attribute, $terms );
		?>

		<li class="remove-filter">
			<a class="<?php echo apply_filters( 'a3_portfolio_clear_filter_button_class', 'remove-filter-button' ); ?>" data-filter="" href="#"><?php echo apply_filters( 'a3_portfolio_clear_filter_button_text', a3_portfolio_ei_ict_t__( 'Attribute Filter Widget - Clear This Filter', __( 'Clear This Filter', 'a3-portfolio' ) ) ); ?></a>
		</li>

	</ul>

</div>

<?php
	/**
	 * a3_portfolio_after_attribute_filter_widget hook
	 *
	 */
	do_action( 'a3_portfolio_after_attribute_filter_widget', $taxonomy, $attribute, $terms );
?>

<?php endif; ?>