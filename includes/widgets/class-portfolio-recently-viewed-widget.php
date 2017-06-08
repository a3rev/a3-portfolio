<?php
/**
 * Portfolio Categories Widget
 *
 * Table Of Contents
 *
 * __construct()
 * widget()
 * portfolio_onsale_results()
 * update()
 * form()
 */
class A3_Portfolio_Recently_Viewed_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_portfolio_recently_viewed',
			'description' => __( 'Display a list of recently viewed portfolio items.', 'a3_portfolios')
		);
		parent::__construct('widget_a3_portfolio_recently_viewed', __('a3 Portfolios Recently Viewed','a3_portfolios'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo $this->show_portfolio_recently_viewed();
		echo $after_widget;
	}

	function show_portfolio_recently_viewed() {
		global $post;
		$result_html = '';

		$current_lang = '';
		if ( class_exists('SitePress') ) {
			$current_lang = ICL_LANGUAGE_CODE;
		}

		do_action( 'a3_portfolio_before_recently_widget' );

		if ( isset($_COOKIE['portfolio_recentviews' . $current_lang ]) && is_array(json_decode($_COOKIE['portfolio_recentviews' . $current_lang ])) && count(json_decode($_COOKIE['portfolio_recentviews' . $current_lang ])) > 0 ) {

			$result_html .= '<div class="portfolio_recently_viewed_container">
			<div class="blockui-waiting"></div>
			<ul class="portfolio_recently_viewed">';
			$portfolio_recently_viewed = array_reverse(json_decode($_COOKIE['portfolio_recentviews' . $current_lang ]));
			foreach ( $portfolio_recently_viewed as $portfolio_id ) {
				$_blank = a3_portfolio_get_image_blank();

				$portfolio_gallery = a3_portfolio_get_gallery( $portfolio_id );

				if ( $portfolio_gallery ) {
					$thumb        = wp_get_attachment_image_src( $portfolio_gallery[0], 'portfolio-gallery-thumbnail-image', true );
					$thumb_srcset = wp_get_attachment_image_srcset( $portfolio_gallery[0], 'portfolio-gallery-thumbnail-image' );
					$thumb_sizes = wp_get_attachment_image_srcset( $portfolio_gallery[0], 'portfolio-gallery-thumbnail-image' );
					if ( $thumb_srcset === false ) {
						$thumb_srcset = '';
					} else {
						$thumb_srcset = 'srcset="' . esc_attr( $thumb_srcset ) . '"';
					}
					if ( $thumb_sizes === false ) {
						$thumb_sizes  = '';
					} else {
						$thumb_sizes  = 'sizes="' . esc_attr( $thumb_sizes ) . '"';
					}
					$img = '<img
						class="a3_porfolio_thumb_widget_lazy thumbnail"
						src="'.$thumb[0].'"
						'.$thumb_srcset.'
						'.$thumb_sizes.'
					/>';
				} else {
					$thumb = a3_portfolio_no_image();
					$img = '<img
						class="a3_porfolio_thumb_widget_lazy thumbnail"
						src="'.$thumb.'"
					/>';
				}

				$result_html .= '<li class="portfolio_recently_item portfolio_recently_item_'.$portfolio_id.'"><a href="'.get_permalink( $portfolio_id ).'">'.$img.'</a><div class="portfolio_name"><a href="'.get_permalink( $portfolio_id ).'">'.get_the_title( $portfolio_id ).'</a></div><span class="remove_portfolio_item" data-id="'.$portfolio_id.'"><i class="fa fa-times a3-portfolio-icon-close"></i></span></li>';
			}

			$result_html .= '</ul>';
			$result_html .= '<div style="clear:both"></div><div class="portfolio_recently_button_container"><a href="#" class="clear_all_portfolio_recently">' . a3_portfolio_ei_ict_t__( 'Recently Widget - Clear All', __( 'Clear All', 'a3_portfolios' ) ). '</a></div><div style="clear:both"></div>';
			$result_html .= '</div>';

		} else {
			$result_html = '<div class="portfolio_recently_viewed_container">' . a3_portfolio_ei_ict_t__( 'Recently Widget - No Portfolio', __( 'No Portfolio Recently Viewed !', 'a3_portfolios' ) ). '</div>';
		}

		do_action( 'a3_portfolio_after_recently_widget' );

		return $result_html;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = esc_attr($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = esc_attr($instance['title']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'a3_portfolios'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
<?php
	}
}
?>
