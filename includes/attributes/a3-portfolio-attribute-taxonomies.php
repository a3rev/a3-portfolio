<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'A3_Portfolio_Attribute_Taxonomies' ) && ! class_exists( '\A3Rev\Portfolio\Attribute_Taxonomies' ) ) :

class Attribute_Taxonomies
{

	public function __construct() {
		add_action( 'init', array( $this, 'plugin_init' ), 9 );

		add_action( "create_term", array( $this, 'create_term' ), 5, 3 );

		// Portfolio Custom Taxonomy
		// Term ordering - only when sorting by term_order
		add_filter( 'terms_clauses', array( $this, 'portfolio_terms_clauses'), 10, 3 );

		if ( is_admin() ) {
			// Ajax Update Portfolio Attribute Meta Order
			add_action( 'wp_ajax_portfolio_update_taxonomy_order', array( $this, 'portfolio_update_taxonomy_order' ) );
			add_action( 'wp_ajax_nopriv_portfolio_update_taxonomy_order', array( $this, 'portfolio_update_taxonomy_order' ) );
		}
	}

	public function plugin_init() {
		$this->include_script();
	}

	/**
	 * Include script and style to show plugin framework for Category page.
	 */
	public function include_script( ) {
		if ( ! in_array( basename( $_SERVER['PHP_SELF'] ), array( 'edit-tags.php', 'term.php' ) ) ) return;
		if ( ! isset( $_REQUEST['taxonomy'] ) || ! taxonomy_is_portfolio_attribute( $_REQUEST['taxonomy'] ) ) return;

		$suffix	= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'a3-portfolio-term-admin-script', A3_PORTFOLIO_JS_URL . '/a3.portfolio.term.admin' . $suffix . '.js', array('jquery-ui-sortable'), '1.0.0' );

		$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';

		$portfolio_term_order_params = array(
			'taxonomy' 			=>  $taxonomy
		);

		wp_localize_script( 'a3-portfolio-term-admin-script', 'a3_portfolio_term_admin_params', $portfolio_term_order_params );
	}

	public function create_term( $term_id, $tt_id = '', $taxonomy = '' ) {
		global $a3_portfolio_attributes;
		if ( taxonomy_is_portfolio_attribute( $taxonomy ) ) {
			update_term_meta( $term_id, 'order', 0 );
		}
	}

	public function portfolio_update_taxonomy_order(){
		global $wpdb;
		$id       = absint( $_POST['id'] );
		$next_id  = isset( $_POST['nextid'] ) && (int) $_POST['nextid'] ? absint( $_POST['nextid'] ) : null;
		$taxonomy = isset( $_POST['thetaxonomy'] ) ? sanitize_text_field( $_POST['thetaxonomy'] ) : null;
		$term     = get_term_by('id', $id, $taxonomy);

		if ( ! $id || ! $term || ! $taxonomy ) {
			die(0);
		}

		$this->portfolio_reorder_terms( $term, $next_id, $taxonomy );

		$children = get_terms( $taxonomy, "child_of=$id&menu_order=ASC&hide_empty=0" );

		if ( $term && sizeof( $children ) ) {
			echo 'children';
			die();
		}
	}

	public function portfolio_reorder_terms( $the_term, $next_id, $taxonomy, $index = 0, $terms = null ) {

		if( ! $terms ) $terms = get_terms($taxonomy, 'menu_order=ASC&hide_empty=0&parent=0' );
		if( empty( $terms ) ) return $index;

		$id	= $the_term->term_id;

		$term_in_level = false; // flag: is our term to order in this level of terms

		foreach ($terms as $term) {

			if( $term->term_id == $id ) { // our term to order, we skip
				$term_in_level = true;
				continue; // our term to order, we skip
			}
			// the nextid of our term to order, lets move our term here
			if(null !== $next_id && $term->term_id == $next_id) {
				$index++;
				$index = $this->portfolio_set_term_order($id, $index, $taxonomy, true);
			}

			// set order
			$index++;
			$index = $this->portfolio_set_term_order($term->term_id, $index, $taxonomy);

			// if that term has children we walk through them
			$children = get_terms($taxonomy, "parent={$term->term_id}&menu_order=ASC&hide_empty=0");
			if( !empty($children) ) {
				$index = $this->portfolio_reorder_terms( $the_term, $next_id, $taxonomy, $index, $children );
			}
		}

		// no nextid meaning our term is in last position
		if( $term_in_level && null === $next_id )
			$index = $this->portfolio_set_term_order($id, $index+1, $taxonomy, true);

		return $index;
	}

	public function portfolio_set_term_order( $term_id, $index, $taxonomy, $recursive = false ) {

		$term_id 	= (int) $term_id;
		$index 		= (int) $index;
		$meta_name = 'order';

		update_term_meta( $term_id, $meta_name, $index );

		if( ! $recursive ) return $index;

		$children = get_terms($taxonomy, "parent=$term_id&menu_order=ASC&hide_empty=0");

		foreach ( $children as $term ) {
			$index ++;
			$index = $this->portfolio_set_term_order($term->term_id, $index, $taxonomy, true);
		}

		clean_term_cache( $term_id, $taxonomy );

		return $index;
	}

	public function portfolio_terms_clauses( $clauses, $taxonomies, $args ) {
		global $wpdb;

		// No sorting when menu_order is false
		if ( isset( $args['menu_order'] ) && $args['menu_order'] == false ) {
			return $clauses;
		}

		// No sorting when orderby is not like menu_order
		if ( isset( $args['orderby'] ) && $args['orderby'] != 'menu_order' ) {
			return $clauses;
		}

		// No sorting in admin when sorting by a column
		if ( is_admin() && isset( $_GET['orderby'] ) ) {
			return $clauses;
		}

		// wordpress should give us the taxonomies asked when calling the get_terms function. Only apply to categories and a3pa_ attributes
		$found = false;
		foreach ( (array) $taxonomies as $taxonomy ) {
			if ( taxonomy_is_portfolio_attribute( $taxonomy ) ) {
				$found = true;
				break;
			}
		}
		if ( ! $found ) {
			return $clauses;
		}

		// Meta name
		$meta_name = 'order';

		// query fields
		if ( strpos( 'COUNT(*)', $clauses['fields'] ) === false )  {
			$clauses['fields']  .= ', tm.* ';
		}

		//query join
		$clauses['join'] .= " LEFT JOIN {$wpdb->termmeta} AS tm ON (t.term_id = tm.term_id AND tm.meta_key = '". $meta_name ."') ";

		// default to ASC
		if ( ! isset( $args['menu_order'] ) || ! in_array( strtoupper($args['menu_order']), array('ASC', 'DESC')) ) {
			$args['menu_order'] = 'ASC';
		}

		$order = "ORDER BY tm.meta_value+0 " . $args['menu_order'];

		if ( $clauses['orderby'] ):
			$clauses['orderby'] = str_replace('ORDER BY', $order . ',', $clauses['orderby'] );
		else:
			$clauses['orderby'] = $order;
		endif;

		return $clauses;
	}
}

endif;
