<?php
/**
 * The template for displaying portfolio social icons in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/social-icons.php
 *
 * @author 		A3 Rev
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$portfolio_data    = get_post( $portfolio_id );
$postlink          = esc_url( get_permalink( $portfolio_id ) );
$portfolio_gallery = a3_portfolio_get_gallery( $portfolio_id );
$postimage         = a3_portfolio_get_first_thumb_image_url( $portfolio_id, $portfolio_gallery, 'portfolio-gallery-image', false );

$desc              = stripslashes( $portfolio_data->post_content );
$desc              = strip_tags( $desc );
$desc              = htmlspecialchars( $desc );
// Clean double quotes
$desc              = str_replace( '"', '', $desc );
$desc              = preg_replace( '/([\n \t\r]+)/', ' ', $desc );
$desc              = preg_replace( '/( +)/', ' ', $desc );

// Remove shortcode
$pattern           = get_shortcode_regex();
//var_dump($pattern);
$desc              = preg_replace( '#' . $pattern . '#s', '', $desc );
?>
<div style="clear:both"></div>

<div class="social-actions-single">

	<div style="clear:both"></div>

	<?php do_action( 'a3_portfolio_before_social_icons', $portfolio_id ); ?>

	<span class="social-action social-action-twitter">

		<a onclick="window.open(this.href, 'popupwindow', 'width=550,height=420,scrollbars,resizable'); return false;"
			href="https://twitter.com/share?url=<?php echo $postlink; ?>&counturl=<?php echo $postlink; ?>&text=<?php echo get_the_title( $portfolio_id ); ?>"><?php echo a3_portfolio_ei_ict_t__( 'Social - Twitter', __( 'Twitter', 'a3-portfolio' ) ); ?></a>

	</span>

	<span class="social-action social-action-facebook">

		<a onclick="window.open(this.href, 'popupwindow', 'width=655,height=380,scrollbars,resizable'); return false;"
			data-url="<?php echo $postlink; ?>"
			href="https://www.facebook.com/sharer.php?u=<?php echo urlencode( $postlink ); ?>&picture=<?php echo urlencode( $postimage ); ?>&description=<?php echo $desc; ?>"
		><?php echo a3_portfolio_ei_ict_t__( 'Social - Facebook', __( 'Facebook', 'a3-portfolio' ) ); ?></a>

	</span>

	<span class="social-action social-action-gplus">

		<a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"
			href="https://plus.google.com/share?url=<?php echo $postlink; ?>" ><?php echo a3_portfolio_ei_ict_t__( 'Social - Google Plus', __( 'Google+', 'a3-portfolio' ) ); ?></a>

	</span>

	<span class="social-action social-action-pinterest_mod">

		<a onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo $postlink; ?>&media=<?php echo urlencode( $postimage ); ?>&description=<?php echo get_the_title( $portfolio_id ); ?>', 'popupwindow', 'width=670,height=300,scrollbars,resizable'); return false;"
			href="#"><?php echo a3_portfolio_ei_ict_t__( 'Social - Pinterest', __( 'Pinterest', 'a3-portfolio' ) ); ?></a>

	</span>

	<?php do_action( 'a3_portfolio_after_social_icons', $portfolio_id ); ?>

	<div style="clear:both"></div>

</div>

<div style="clear:both"></div>