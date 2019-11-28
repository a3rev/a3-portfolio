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

namespace A3Rev\Portfolio\Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Attribute_Filter extends \WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_portfolio_attribute_filter',
			'description' => __( 'Use this widget to filter the portfolios from attribute is selected.', 'a3-portfolio' )
		);
		parent::__construct('widget_a3_portfolio_attribute_filter', __('a3 Portfolios Attribute Filter', 'a3-portfolio' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title        = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$attribute_id = (int) $instance['attribute_id'];

		global $post;
		global $portfolio_page_id;

		$show_this_widget = false;
		if ( is_viewing_portfolio_taxonomy() ) $show_this_widget = true;
		if ( $portfolio_page_id == $post->ID && stristr( $post->post_content, '[portfoliopage') !== false ) $show_this_widget = true;

		if ( ! $show_this_widget ) return;

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $attribute_id > 0 ) {
			$attribute = a3_portfolio_attribute_data_by_id( $attribute_id );
			if ( $attribute ) {
				$taxonomy = a3_portfolio_attribute_taxonomy_name( $attribute->attribute_name );
				if ( taxonomy_exists( $taxonomy ) ) {
					$orderby = $attribute->attribute_orderby;
					$terms = get_terms( $taxonomy, 'orderby='.$orderby.'&hide_empty=1' );

					a3_portfolio_get_template( 'widgets/attribute-filter-widget.php', array( 'taxonomy' => $taxonomy, 'attribute' => $attribute, 'terms' => $terms ) );
				}
			}
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance                 = $old_instance;
		$instance['title']        = esc_attr($new_instance['title']);
		$instance['attribute_id'] = $new_instance['attribute_id'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'        => '',
			'attribute_id' => 0,
			) );
		$title        = esc_attr($instance['title']);
		$attribute_id = $instance['attribute_id'];

		$attribute_taxonomies = a3_portfolio_get_attribute_taxonomies();
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'a3-portfolio' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
        <p>
        	<label for="<?php echo $this->get_field_id('attribute_id'); ?>"><?php _e('Attribute Filter:', 'a3-portfolio' ); ?></label>
        	<select name="<?php echo $this->get_field_name('attribute_id'); ?>" id="<?php echo $this->get_field_id('attribute_id'); ?>">
            	<option value="0" selected="selected"><?php _e('Select attribute to filter', 'a3-portfolio' ); ?></option>
         	<?php
         		if ( $attribute_taxonomies && is_array( $attribute_taxonomies ) && count( $attribute_taxonomies ) > 0 ) {
         			foreach ( $attribute_taxonomies as $tax ) {
         				if ( 'select' != $tax->attribute_type ) continue;

         				$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
         	?>
                <option value="<?php echo $tax->attribute_id; ?>" <?php selected( $attribute_id, $tax->attribute_id ); ?>><?php echo $label; ?></option>
            <?php
            		}
            	}
            ?>
            </select>
        </p>
<?php
	}
}
