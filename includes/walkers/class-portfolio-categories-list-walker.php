<?php
/**
 * A3_Portfolio_Cat_List_Walker class.
 *
 * @extends Walker
 */

namespace A3Rev\Portfolio\Walker;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Cat_List extends \Walker {

	var $tree_type = 'portfolio_cat';
	var $db_fields = array ( 'parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

	/**
	 * @see Walker::start_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category. Used for tab indentation.
	 * @param array $args Will only append content if style argument value is 'list'.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category. Used for tab indentation.
	 * @param array $args Will only append content if style argument value is 'list'.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $category Category data object.
	 * @param int $depth Depth of category in reference to parents.
	 * @param array $args
	 */
	function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$current_cat_name = '';
		$output .= '<li class="cat-item cat-item-' . $cat->term_id;

		if ( $args['current_category'] == $cat->term_id ) {
			$output .= ' current-cat';
			$current_cat_name = 'current-cat-name';
		}

		if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat->term_id, $args['current_category_ancestors'] ) )
			$output .= ' current-cat-parent';

		$output .=  '"><a class="cat-name '.$current_cat_name.'" href="' . get_term_link( (int) $cat->term_id, 'portfolio_cat' ) . '">' . $cat->name . '</a>';

		if ( $args['show_count'] )
			$output .= ' <span class="count">(' . $cat->count . ')</span>';

		if ($args['show_portfolio'] == 'yes') {
			$output .= $this->get_portfolio($args['current_portfolio'], $cat->term_id, $cat->slug, $args['portfolio_orderby']);
		}
	}

	/**
	 * @see Walker::end_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Not used.
	 * @param int $depth Depth of category. Not used.
	 * @param array $args Only uses 'list' for whether should append to output.
	 */
	function end_el( &$output, $cat, $depth = 0 , $args = array() ) {

		$output .= "</li>\n";

	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		if ( !$element )
			return;

		if ( empty( $depth ) ) {
			$depth = 0;
		}

		if ( ! $args[0]['show_children_only'] || ( $args[0]['show_children_only'] && ( $element->parent == 0 || $args[0]['current_category'] == $element->parent || in_array( $element->parent, $args[0]['current_category_ancestors'] ) ) ) ) {

			$id_field = $this->db_fields['id'];

			//display this element
			if ( is_array( $args[0] ) )
				$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);

			$id = $element->$id_field;

			// descend only when the depth is right and there are childrens for this element
			if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

				foreach( $children_elements[ $id ] as $child ){

					if ( !isset($newlevel) ) {
						$newlevel = true;
						//start the child delimiter
						$cb_args = array_merge( array(&$output, $depth), $args);
						call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
					}
					$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
				}
				unset( $children_elements[ $id ] );
			}

			if ( isset($newlevel) && $newlevel ){
				//end the child delimiter
				$cb_args = array_merge( array(&$output, $depth), $args);
				call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
			}

			//end this element
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'end_el'), $cb_args);

		}
	}

	function get_portfolio($current_portfolio, $catid, $catslug, $orderby) {
		$output = '';
		$portfolio_results = self::get_portfolios($catid, $orderby, -1, 0);
		if ( count($portfolio_results) > 0) {
			$output .= '<ul class="portfolio-list children">';
			foreach ($portfolio_results as $portfolio) {
				$current_portfolio_name = '';
				$output .= '<li class="portfolio-item portfolio-item-' . $portfolio->ID;
				if ( $current_portfolio == $portfolio->ID ) {
					$output .= ' current-portfolio';
					$current_portfolio_name = 'current-portfolio-name';
				}
				$output .=  '"><a class="portfolio-name '.$current_portfolio_name.'" href="' . get_permalink($portfolio->ID) . '">' . esc_attr($portfolio->post_title) . '</a>';
				$output .= "</li>";
			}
			$output .= '</ul>';
		}

		return $output;
	}

	function get_portfolios($catid = 0, $orderby='title date', $number = -1, $offset = 0) {
		$args = array(
			'numberposts'	=> $number,
			'offset'		=> $offset,
			'orderby'		=> $orderby,
			'order'			=> 'ASC',
			'post_type'		=> 'a3-portfolio',
			'post_status'	=> 'publish'
		);

		if ( 'date_asc' === $orderby ) {
			$args['orderby'] = 'date';
		} elseif ( 'date_desc' === $orderby ) {
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
		}

		if ($catid > 0) {
			$args['tax_query'] = array(
						array(
							'taxonomy'			=> 'portfolio_cat',
							'field'				=> 'id',
							'terms'				=> $catid,
							'include_children'	=> false
						)
			);
		}
		$portfolio_results = get_posts($args);
		if ( $portfolio_results && is_array($portfolio_results) && count($portfolio_results) > 0) {
			return $portfolio_results;
		} else {
			return array();
		}
	}

}