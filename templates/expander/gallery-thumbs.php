<?php
/**
 * The template for displaying portfolio gallery thumbnails container in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/gallery-thumbs.php
 *
 * @author 		A3 Rev
 * @version     2.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'a3_portfolio_before_item_expander_gallery_thumbs_container', $portfolio_id );

if ( ! is_array( $gallery ) || count( $gallery ) <= 1 ) return;

$active_class = '';
$class = '';
$i = 0;
$j = 0;

$lightbox_list = '';

global $a3_portfolio_global_settings;
$gallery_thumbnail_width = 150;
$gallery_thumbnail_height = 150;
if ( (int) trim( $a3_portfolio_global_settings['gallery_thumbnail_width'] ) > 0 ) {
	$gallery_thumbnail_width = (int) $a3_portfolio_global_settings['gallery_thumbnail_width'];
}
if ( (int) trim( $a3_portfolio_global_settings['gallery_thumbnail_height'] ) > 0 ) {
	$gallery_thumbnail_height = (int) $a3_portfolio_global_settings['gallery_thumbnail_height'];
}
?>
<style>
.a3-portfolio-gallery-thumbs-container .pg_grid_content {
	width: <?php echo $gallery_thumbnail_width; ?>px !important;
	height: <?php echo $gallery_thumbnail_height; ?>px !important;
}
</style>

<div class="a3-portfolio-gallery-thumbs-container">

<?php foreach ( $gallery as $attachment_id ) : ?>

	<?php
		$thumb        = wp_get_attachment_image_src( $attachment_id, 'portfolio-gallery-thumbnail-image', true );
		$large_url    = wp_get_attachment_image_src( $attachment_id, 'portfolio-gallery-image', true );
		$large_srcset = wp_get_attachment_image_srcset( $attachment_id, 'portfolio-gallery-image' );
		$the_caption  = get_post_field( 'post_excerpt', $attachment_id );

		if ( $large_srcset === false ) {
			$large_srcset = '';
		} else {
			$large_srcset = 'data-osrcset="' . esc_attr( $large_srcset ) . '"';
		}
	?>

	<?php
		$j++;
		if ( $j == 1 ) {
			$class = 'first';
		}
		if($j == 4 || ($j == count( $gallery ) - 1 && count( $gallery ) > 1)){
			$class = 'last';
			$j = 0;
		}

		if ( $i == 0 ) {
			$active_class = 'current_img';
		} else {
			$active_class = '';
		}
		$i++;

		if ( $include_lightbox_script ) {
			$full_url = wp_get_attachment_image_src( $attachment_id, 'large', true );
			$lightbox_list .= '<a class="a3_portfolio_lightbox_'.$portfolio_id.' a3_portfolio_lightbox_'.$portfolio_id.'_'.$i.'" href="'.$full_url[0].'" rel="a3_portfolio_lightbox_'.$portfolio_id.'"></a>';
		}
	?>

	<div class="pg_grid <?php echo $active_class; ?> <?php echo $class; ?>" id="<?php echo esc_attr( $attachment_id ); ?>">
		<div data-bg="<?php echo $thumb[0]; ?>"
			data-originalfull="<?php echo $large_url[0]; ?>"
			<?php echo $large_srcset; ?>
			data-caption="<?php echo $the_caption; ?>"
			class="a3-portfolio-gallery-thumb-lazy pg_grid_content"
			item_id="<?php echo $i; ?>"
			style="background:url(<?php echo $image_blank; ?>) no-repeat center center;"></div>
	</div>

<?php endforeach; ?>

<?php if ( $include_lightbox_script ) { ?>
<div style="display: none !important;">
<?php echo $lightbox_list; ?>
</div>
<script type="text/javascript">
	(function($){
		$(function(){
			$(document).on("click", ".a3-portfolio-image-gallery", function(ev) {
				var a3_gallery_item_id = $(this).attr("item_id");
				$(".a3_portfolio_lightbox_<?php echo $portfolio_id; ?>").colorbox({ current:"", rel:"a3_portfolio_lightbox_<?php echo $portfolio_id; ?>", maxWidth:"100%" });
				$(".a3_portfolio_lightbox_<?php echo $portfolio_id; ?>_" + a3_gallery_item_id).colorbox({ current:"", open:true, maxWidth:"100%" });
				ev.preventDefault();
			});
		});
	})(jQuery);
</script>
<?php } ?>

</div>

<?php
do_action( 'a3_portfolio_after_item_expander_gallery_thumbs_container', $portfolio_id );
?>