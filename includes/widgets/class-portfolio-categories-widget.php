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
class A3_Portfolio_Categories_Widget extends WP_Widget {
	var $cat_ancestors;
	var $current_cat;
	var $current_portfolio;

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_portfolio_categories',
			'description' => __( 'A list or dropdown of Portfolio Categories, Sub Categories and Portfolio items in each.', 'a3-portfolio' )
		);
		parent::__construct('widget_a3_portfolio_categories', __('a3 Portfolios Categories', 'a3-portfolio' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title             = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$category_orderby  = $instance['category_orderby'];
		$portfolio_orderby = $instance['portfolio_orderby'];
		$dropdown          = $instance['dropdown'];
		$hierarchy         = $instance['hierarchy'];
		$show_portfolio    = $instance['show_portfolio'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo $this->show_portfolio_categories($category_orderby, $portfolio_orderby, $dropdown, $hierarchy, $show_portfolio);
		echo $after_widget;
	}

	function portfolio_walk_category_dropdown_tree() {
		$args = func_get_args();

		// the user's options are the third parameter
		if ( empty( $args[2]['walker']) || !is_a($args[2]['walker'], 'Walker' ) ) {
			$walker = new A3_Portfolio_Cat_List_Dropdown_Walker;
		} else {
			$walker = $args[2]['walker'];
		}

		return call_user_func_array( array( &$walker, 'walk' ), $args );
	}

	function portfolio_dropdown_categories( $args = array(), $deprecated_hierarchical = 1, $deprecated_orderby = '' ) {
		global $wp_query;
		$current_portfolio_cat = isset( $wp_query->query['portfolio_cat'] ) ? $wp_query->query['portfolio_cat'] : '';
		$defaults            = array(
			'pad_counts'         => 1,
			'show_counts'        => 1,
			'hierarchical'       => 1,
			'hide_empty'         => 1,
			'orderby'            => 'name',
			'selected'           => $current_portfolio_cat,
			'menu_order'         => false
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['orderby'] == 'order' ) {
			$args['menu_order'] = 'asc';
			$args['orderby']    = 'name';
		}

		$terms = get_terms( 'portfolio_cat', $args );

		if ( ! $terms ) {
			return;
		}

		$output  = "<select name='portfolio_cat' class='dropdown_portfolio_cat'>";
		$output .= '<option value="" ' .  selected( $current_portfolio_cat, '', false ) . '>' . __( 'Select a category', 'a3-portfolio' ) . '</option>';
		$output .= $this->portfolio_walk_category_dropdown_tree( $terms, 0, $args );
		$output .= "</select>";

		$output .= "
		<script>
		jQuery('.dropdown_portfolio_cat').change(function(){
			if(jQuery(this).val() != '') {
				location.href = jQuery(this).val();
			}
		});
		</script>
		";

		echo $output;
	}

	function show_portfolio_categories($category_orderby = 'order', $portfolio_orderby = 'menu_order', $dropdown = '', $hierarchy = '', $show_portfolio = '') {
		global $post;
		$cat_args = array('show_count' => 0, 'hierarchical' => false, 'taxonomy' => 'portfolio_cat');
		if ($dropdown == 'yes') $cat_args['dropdown'] = true ;
		if ($hierarchy == 'yes') $cat_args['hierarchical'] = true ;
		$cat_args['orderby'] = $category_orderby;

		global $wp_query, $post;

		$this->current_cat       = false;
		$this->current_portfolio = false;
		$this->cat_ancestors     = array();

		if ( is_tax('portfolio_cat') ) :

			$this->current_cat = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'portfolio_cat' );

		elseif ( is_singular('portfolio') ) :
			$this->current_portfolio = $post->ID;
			$portfolio_cat = wp_get_post_terms( $post->ID, 'portfolio_cat' );

			if ($portfolio_cat) :
				$this->current_cat = end($portfolio_cat);
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'portfolio_cat' );
			endif;

		endif;

		if ( $dropdown == 'yes' ) {
			include_once( A3_PORTFOLIO_DIR . '/includes/walkers/class-portfolio-categories-list-dropdown-walker.php' );
			$cat_args['walker'] 			= new A3_Portfolio_Cat_List_Dropdown_Walker;
			$cat_args['title_li'] 			= '';
			$cat_args['show_children_only']	= 0;
			$cat_args['current_portfolio']		= ( $this->current_portfolio != false ) ? $this->current_portfolio : '';
			$cat_args['show_portfolio']			= $show_portfolio;
			$cat_args['portfolio_orderby']		= $portfolio_orderby;
			$cat_args['pad_counts'] 		= 1;
			$cat_args['show_option_none'] 	= __('No portfolio categories exist.', 'a3-portfolio' );
			$cat_args['current_category']	= ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$cat_args['current_category_ancestors']	= $this->cat_ancestors;
			ob_start();
			$this->portfolio_dropdown_categories( apply_filters( 'portfolio_categories_widget_args', $cat_args ) );
			$result_html = ob_get_clean();

		} else {
			include_once( A3_PORTFOLIO_DIR . '/includes/walkers/class-portfolio-categories-list-walker.php' );
			$cat_args['walker'] 			= new A3_Portfolio_Cat_List_Walker;
			$cat_args['title_li'] 			= '';
			$cat_args['show_children_only']	= 0;
			$cat_args['current_portfolio']		= ( $this->current_portfolio != false ) ? $this->current_portfolio : '';
			$cat_args['show_portfolio']			= $show_portfolio;
			$cat_args['portfolio_orderby']		= $portfolio_orderby;
			$cat_args['pad_counts'] 		= 1;
			$cat_args['show_option_none'] 	= __('No portfolio categories exist.', 'a3-portfolio' );
			$cat_args['current_category']	= ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$cat_args['current_category_ancestors']	= $this->cat_ancestors;

			ob_start();
			echo '<div id="portfolio_categories_widget_container"><ul class="portfolio_categories_list">';

			wp_list_categories( apply_filters( 'portfolio_categories_widget_args', $cat_args ) );

			echo '</ul><style>.portfolio_categories_list li a.cat-name{font-weight:bold;}</style></div>';
			$result_html = ob_get_clean();
		}
		return $result_html;
	}

	function update( $new_instance, $old_instance ) {
			$instance                      	= $old_instance;
			$instance['title']             	= esc_attr($new_instance['title']);
			$instance['category_orderby']  	= $new_instance['category_orderby'];
			$instance['portfolio_orderby'] 	= $new_instance['portfolio_orderby'];
			if ( isset( $new_instance['dropdown'] ) )
			$instance['dropdown']          	= $new_instance['dropdown'];
			else
			$instance['dropdown'] 			= 'no';

			if ( isset( $new_instance['hierarchy'] ) )
			$instance['hierarchy']         	= $new_instance['hierarchy'];
			else
			$instance['hierarchy'] 			= 'no';

			if ( isset( $new_instance['show_portfolio'] ) )
			$instance['show_portfolio']    	= $new_instance['show_portfolio'];
			else
			$instance['show_portfolio'] 	= 'no';
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'category_orderby'  => 'order',
			'portfolio_orderby' => 'menu_order',
			'dropdown'          => 'yes',
			'hierarchy'         => 'yes',
			'show_portfolio'    => 'yes'
			) );
		$title             = esc_attr($instance['title']);
		$category_orderby  = $instance['category_orderby'];
		$portfolio_orderby = $instance['portfolio_orderby'];
		$dropdown          = $instance['dropdown'];
		$hierarchy         = $instance['hierarchy'];
		$show_portfolio    = $instance['show_portfolio'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'a3-portfolio' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
        <p>
        	<label for="<?php echo $this->get_field_id('category_orderby'); ?>"><?php _e('Category Order by:', 'a3-portfolio' ); ?></label>
        	<select name="<?php echo $this->get_field_name('category_orderby'); ?>" id="<?php echo $this->get_field_id('category_orderby'); ?>">
            	<option value="order" selected="selected"><?php _e('Category Order', 'a3-portfolio' ); ?></option>
                <option value="title" <?php selected( $category_orderby, 'title' ); ?>><?php _e('Category Name', 'a3-portfolio' ); ?></option>
            </select>
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id('portfolio_orderby'); ?>"><?php _e('Portfolio Order by:', 'a3-portfolio' ); ?></label>
        	<select name="<?php echo $this->get_field_name('portfolio_orderby'); ?>" id="<?php echo $this->get_field_id('portfolio_orderby'); ?>">
            	<option value="menu_order" selected="selected"><?php _e('Menu Order', 'a3-portfolio' ); ?></option>
                <option value="title" <?php selected( $portfolio_orderby, 'title'); ?>><?php _e('Portfolio Name', 'a3-portfolio' ); ?></option>
            </select>
        </p>
        <p>
        	<input type="checkbox" <?php checked( $dropdown, 'yes' ); ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" value="yes" />
        	<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown', 'a3-portfolio' ); ?></label>
        </p>
        <p>
        	<input type="checkbox" <?php checked( $hierarchy, 'yes' ); ?> id="<?php echo $this->get_field_id('hierarchy'); ?>" name="<?php echo $this->get_field_name('hierarchy'); ?>" value="yes" />
        	<label for="<?php echo $this->get_field_id('hierarchy'); ?>"><?php _e('Show hierarchy', 'a3-portfolio' ); ?></label>
        </p>
        <p>
        	<input type="checkbox" <?php checked( $show_portfolio, 'yes' ); ?> id="<?php echo $this->get_field_id('show_portfolio'); ?>" name="<?php echo $this->get_field_name('show_portfolio'); ?>" value="yes" />
        	<label for="<?php echo $this->get_field_id('show_portfolio'); ?>"><?php _e('Show Portfolio', 'a3-portfolio' ); ?></label>
        </p>
<?php
	}
}
?>
