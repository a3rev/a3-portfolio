<?php

namespace A3Rev\Portfolio\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Category
{
	public $table_name = 'a3_portfolio_categorymeta';

	public function __construct() {
		add_action( 'init', array( $this, 'plugin_init' ), 0 );

		// Portfolio Custom Taxonomy
		// Term ordering - only when sorting by term_order
		add_filter( 'terms_clauses', array( $this, 'portfolio_terms_clauses'), 10, 3 );
		add_action( 'init', array( $this, 'set_metadata_wpdbfix' ), 0 );
		add_action( 'switch_blog', array( $this, 'set_metadata_wpdbfix' ), 0 );
		add_action( 'portfolio_cat_pre_add_form', array( $this, 'portfolio_cat_description' ) );

		if ( is_admin() ) {
			// Ajax Update Portfolio Item Meta Order
			add_action( 'wp_ajax_portfolio_update_taxonomy_order', array( $this, 'portfolio_update_taxonomy_order' ) );
			add_action( 'wp_ajax_nopriv_portfolio_update_taxonomy_order', array( $this, 'portfolio_update_taxonomy_order' ) );

			// AJAX update taxonomy
			add_action('wp_ajax_a3_portfolio_update_taxonomy_custom_meta', array( $this, 'a3_portfolio_update_taxonomy_custom_meta' ) );
			add_action('wp_ajax_nopriv_a3_portfolio_update_taxonomy_custom_meta', array( $this, 'a3_portfolio_update_taxonomy_custom_meta' ) );
		}
	}

	public function plugin_init() {
		$this->include_script();

		add_action( "create_term", array( $this, 'create_term' ), 5, 3 );
		add_action( "delete_term", array( $this, 'delete_term' ), 5 );
		add_action( 'portfolio_cat_add_form_fields', array( $this, 'a3_portfolio_taxonomy_add_new_meta_field' ), 11 );
		add_action( 'portfolio_cat_edit_form', array( $this, 'a3_portfolio_taxonomy_edit_meta_field' ), 11, 2 );
		add_action( 'edited_portfolio_cat', array( $this, 'a3_portfolio_save_taxonomy_custom_meta' ), 11, 2 );
		add_action( 'create_portfolio_cat', array( $this, 'a3_portfolio_save_taxonomy_custom_meta' ), 11, 2 );
		add_action( 'delete_portfolio_cat', array( $this, 'a3_portfolio_delete_taxonomy_custom_meta' ), 11, 2 );

		// Add columns
		add_filter( 'manage_edit-portfolio_cat_columns', array( $this, 'portfolio_cat_columns' ) );
		add_filter( 'manage_portfolio_cat_custom_column', array( $this, 'portfolio_cat_column' ), 10, 3 );
	}

	/**
	 * Include script and style to show plugin framework for Category page.
	 */
	public function include_script( ) {
		if ( ! in_array( basename( $_SERVER['PHP_SELF'] ), array( 'edit-tags.php', 'term.php' ) ) ) return;
		if ( ! isset( $_REQUEST['taxonomy'] ) || ! in_array( $_REQUEST['taxonomy'], array( 'portfolio_cat' ) ) ) return;

		add_action( 'admin_footer', array( $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface'], 'admin_script_load' ) );
		add_action( 'admin_footer', array( $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface'], 'admin_css_load' ) );
		add_action( 'admin_footer', array( $this, 'include_style' ) );
		add_action( 'admin_footer', array( $this, 'portfolio_term_ordering_validate_script' ), 11 );
	}

	public function include_style( ) {
		?>
        <style>
		div.a3rev_panel_container {
			margin-bottom:20px;
		}
		.a3rev_panel_container label {
			padding: 0 !important;	
		}
		.onoff_child{display:none;}
		</style>
	<?php
    }

    public function portfolio_term_ordering_validate_script() {
    	$suffix	= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'a3-portfolio-term-validate-admin-script', A3_PORTFOLIO_JS_URL . '/a3.portfolio.term.validate.admin' . $suffix . '.js', array('jquery'), A3_PORTFOLIO_VERSION );
		wp_enqueue_script( 'a3-portfolio-term-validate-admin-script' );
	}

	public function portfolio_cat_description() {
		echo wpautop( sprintf( __( 'Create and manage Portfolio Categories here. Category names are used to create the main Portfolio Nav Bar. Turn the Nav Bar feature ON for each category that you want to show on the Portfolio Main Nav Bar. Use drag and drop in categories list to set category position on the Portfolio Nav Bar. Child categories auto show on their Parent Category page Nav Bar. There is an a3 Portfolio Categories <a href="%s">widget</a> for navigation.', 'a3-portfolio' ), 'widgets.php' ) );
	}

	public function set_metadata_wpdbfix() {
		global $wpdb;
		$wpdb->a3_portfolio_categorymeta = $wpdb->prefix . $this->table_name;
		$wpdb->tables[] = $this->table_name;
	}

	/**
	 * Update category meta
	 */
	public function update_a3_portfolio_category_meta( $term_id, $meta_key, $meta_value, $prev_value = '' ) {
		return update_metadata( 'a3_portfolio_category', $term_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Get category meta
	 */
	public function get_a3_portfolio_category_meta( $term_id, $meta_key, $single = true ) {
		return get_metadata( 'a3_portfolio_category', $term_id, $meta_key, $single );
	}

	/**
	 * Delete category meta
	 */
	public function delete_a3_portfolio_category_meta( $term_id, $meta_key, $meta_value = '', $delete_all = false ) {
		return delete_metadata( 'a3_portfolio_category', $term_id, $meta_key, $meta_value, $delete_all );
	}

	public function create_term( $term_id, $tt_id = '', $taxonomy = '' ) {
		$this->update_a3_portfolio_category_meta( $term_id, 'order', 0 );
	}

	public function delete_term( $term_id ) {
		$term_id = (int) $term_id;

		if ( ! $term_id )
			return;

		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->a3_portfolio_categorymeta} WHERE `a3_portfolio_category_id` = " . $term_id );
	}

	// Addnew term page
	public function a3_portfolio_taxonomy_add_new_meta_field() {

		$suffix	= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// this will add the custom meta field to the add new term page
		$portfolio_taxonomy_order = wp_create_nonce("portfolio-taxonomy-order");
		if ( ( ( ! empty( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], array( 'portfolio_cat' ) ) ) ) && ! isset( $_GET['orderby'] ) ) {
			wp_enqueue_script( 'a3-portfolio-term-admin-script', A3_PORTFOLIO_JS_URL . '/a3.portfolio.term.admin' . $suffix . '.js', array('jquery-ui-sortable'), '1.0.0' );

			$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';

			$portfolio_term_order_params = array(
				'taxonomy' 			=>  $taxonomy
			);

			wp_localize_script( 'a3-portfolio-term-admin-script', 'a3_portfolio_term_admin_params', $portfolio_term_order_params );
		}
		?>
		<div class="a3rev_panel_container a3rev_portfolio_panel_container">

			<?php ob_start(); ?>
			<div class="form-field">
				<input type="hidden" name="have_portfolio_category_field" value="yes"  />
				<input class="a3rev-ui-onoff_checkbox" type="checkbox" checked="checked" name="active_portfolio_taxonomy" id="active_portfolio_taxonomy" value="1" /> <label for="active_portfolio_taxonomy"><?php _e( 'ON to show this category on the Portfolio main Nav Bar.', 'a3-portfolio' ); ?></label>
			</div>

			<?php
			$settings_html = ob_get_clean();
			$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface']->panel_box( $settings_html, array(
				'name' 		=> __( 'Portfolio Nav Bar Item', 'a3-portfolio' ),
				'id'		=> 'portfolio_navbar_item',
				'is_box'	=> true,
			) );
			?>
		</div>
		<div style="clear: both;"></div>
	<?php
	}

	// Edit term page
	public function a3_portfolio_taxonomy_edit_meta_field( $term, $taxonomy ) {

		$active_portfolio_taxonomy = get_metadata( 'a3_portfolio_category', $term->term_id, 'active_portfolio_taxonomy', true );

	    $checked = '';
	    if ( '' == $active_portfolio_taxonomy || 1 == $active_portfolio_taxonomy ) {
	        $checked = 'checked="checked" ';
	    }

	    ?>
		<div class="a3rev_panel_container">
	    <?php ob_start(); ?>
	    <table class="form-table">
		    <tr class="form-field a3rev_panel_container a3rev_portfolio_panel_container">
		    <th scope="row" valign="top"><label for="active_portfolio_taxonomy"><?php _e( 'Portfolio Nav Bar Item', 'a3-portfolio' ); ?></label></th>
		        <td>
				<input type="hidden" name="have_portfolio_category_field" value="yes"  />
		        <input class="a3rev-ui-onoff_checkbox" <?php echo $checked ;?> type="checkbox" name="active_portfolio_taxonomy" id="active_portfolio_taxonomy" value="1" /> <label for="active_portfolio_taxonomy"><?php _e( 'ON to show this category on the Portfolio main Nav Bar.', 'a3-portfolio' ); ?></label>
		        </td>
		    </tr>
	    </table>
	    <?php
		$settings_html = ob_get_clean();
		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface']->panel_box( $settings_html, array(
			'name' 		=> __( 'Portfolio Nav Bar Item', 'a3-portfolio' ),
			'id'		=> 'portfolio_navbar_item',
			'is_box'	=> true,
		) );
		?>
		</div>
		<div style="clear: both;"></div>
	<?php
	}

	// Save extra taxonomy fields callback function.
	public function a3_portfolio_save_taxonomy_custom_meta( $term_id, $tt_id ) {
		if ( isset( $_REQUEST['have_portfolio_category_field'] ) ) {
			$meta_value = 0;
			if( isset($_POST['active_portfolio_taxonomy']) ){
				 $meta_value = 1;
			}
			$this->update_a3_portfolio_category_meta($term_id, 'active_portfolio_taxonomy', $meta_value);
		}
	}

	// Save extra taxonomy fields callback function.
	public function a3_portfolio_update_taxonomy_custom_meta() {
		$this->update_a3_portfolio_category_meta( absint( $_POST['tax_id'] ), 'active_portfolio_taxonomy', sanitize_text_field( $_POST['pri_navbar'] ) );
	}

	/**
	 * Compare column added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function portfolio_cat_columns( $columns ) {
		$have_description_column = false;
		$new_columns          = array();
		if ( is_array( $columns ) && count( $columns ) > 0 ) {
			foreach ( $columns as $column_key => $column_name ) {
				$new_columns[$column_key] = $column_name;
				if ( $column_key == 'description' ) {
					$have_description_column = true;
					$new_columns['primary_nav_bar'] = __( 'Main Nav Bar', 'a3-portfolio' );
				}
			}
			if ( ! $have_description_column ) {
				$new_columns['primary_nav_bar'] = __( 'Main Nav Bar', 'a3-portfolio' );
			} else {
				unset( $new_columns['description'] );
			}
			unset( $new_columns['slug'] );
			$columns = $new_columns;
		}

		return $columns;
	}

	/**
	 * Compare column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function portfolio_cat_column( $columns, $column, $id ) {
		$term = get_term_by('ID', $id, 'portfolio_cat');
		if ( $column == 'primary_nav_bar' ) {
			if( $term &&  $term->parent == 0) {
				$checked = '';
				$active_portfolio_taxonomy = get_metadata( 'a3_portfolio_category', $id, 'active_portfolio_taxonomy', true );
				$checked = '';
				if ( '' == $active_portfolio_taxonomy || 1 == $active_portfolio_taxonomy ) {
					$checked = 'checked="checked"';
				}
				?>
				<div class="a3rev_panel_container a3rev_panel_container_on_table" style="border: medium none; margin: 0px auto; visibility: visible; height: auto; overflow: inherit; text-align: center; padding: 3px 3px; display: inline-block; vertical-align: middle;">
					<input data-id="<?php echo $id;?>" class="a3rev-ui-onoff_checkbox a3rev_panel_container_on_table_onoff" type="checkbox" <?php echo $checked; ?> name="active_portfolio_taxonomy[<?php echo $id;?>]" id="active_portfolio_taxonomy_<?php echo $id;?>" value="1" />
				</div>
				<?php
			}

		}

		return $columns;
	}

	// Delete extra taxonomy fields callback function.
	public function a3_portfolio_delete_taxonomy_custom_meta( $term_id ) {
		$this->delete_a3_portfolio_category_meta( $term_id, 'active_portfolio_taxonomy');
		$this->delete_a3_portfolio_category_meta( $term_id, '_order');
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

		$this->update_a3_portfolio_category_meta( $term_id, $meta_name, $index );

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

		// No sorting when orderby is non default
		if ( isset( $args['orderby'] ) && $args['orderby'] != 'name' ) {
			return $clauses;
		}

		// No sorting in admin when sorting by a column
		if ( is_admin() && isset( $_GET['orderby'] ) ) {
			return $clauses;
		}

		// wordpress should give us the taxonomies asked when calling the get_terms function. Only apply to categories and pa_ attributes
		$found = false;
		foreach ( (array) $taxonomies as $taxonomy ) {
			if ( in_array( $taxonomy, array( 'portfolio_cat' ) ) ) {
				$found = true;
				break;
			}
		}
		if ( ! $found ) {
			return $clauses;
		}

		// Meta name
		$meta_name = 'order';

		if ( !is_admin() ) {
		}

		// query fields
		if ( strpos( 'COUNT(*)', $clauses['fields'] ) === false )  {
			$clauses['fields']  .= ', tm.* ';
		}

		//query join
		$clauses['join'] .= " LEFT JOIN {$wpdb->a3_portfolio_categorymeta} AS tm ON (t.term_id = tm.a3_portfolio_category_id AND tm.meta_key = '". $meta_name ."') ";

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
