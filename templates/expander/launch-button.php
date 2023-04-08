<?php
/**
 * The template for displaying portfolio launch button in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/launch-button.php
 *
 * @author 		A3 Rev
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
?>

<?php if ( $launch_site_url != '' ) : ?>

<a href="<?php echo esc_url( $launch_site_url ); ?>" target="<?php echo esc_attr( $open_type ); ?>" class="<?php echo esc_attr( $button_class ); ?>"><?php echo $button_text; ?></a>

<?php endif; ?>