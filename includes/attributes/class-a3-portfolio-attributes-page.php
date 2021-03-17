<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'A3_Portfolio_Attributes_Page' ) && ! class_exists( '\A3Rev\Portfolio\Attributes') ) :

class Attributes
{

	var $parent_page = 'a3-portfolio';
	var $attribute_page = 'portfolio-attributes';

	public function __construct() {
		if ( is_admin() ) {
			// Add Portfolio Meta submenu page
			add_action( 'admin_menu', array( $this, 'admin_menu'), 1 );
		}
	}

	public function admin_menu() {
	    if ( current_user_can( 'manage_options' ) )
	    	add_submenu_page( 'edit.php?post_type='.$this->parent_page, __( 'Portfolio Attributes', 'a3-portfolio' ), __( 'Attributes', 'a3-portfolio' ), 'manage_options', $this->attribute_page, array( $this, 'output' ) );
	}

	public function output() {
		$result = '';
		$action = '';

		// Action to perform: add, edit, delete or none
		if ( ! empty( $_POST['add_new_attribute'] ) ) {
			$action = 'add';
		} elseif ( ! empty( $_POST['save_attribute'] ) && ! empty( $_GET['edit'] ) ) {
			$action = 'edit';
		} elseif ( ! empty( $_GET['delete'] ) ) {
			$action = 'delete';
		}

		switch ( $action ) {
			case 'add' :
				$result = $this->process_add_attribute();
			break;
			case 'edit' :
				$result = $this->process_edit_attribute();
			break;
			case 'delete' :
				$result = $this->process_delete_attribute();
			break;
		}

		if ( is_wp_error( $result ) ) {
			echo '<div class="error below-h2" id="message"><p>' . $result->get_error_message() . '</p></div>';
		}

		// Show admin interface
		if ( ! empty( $_GET['edit'] ) ) {
			$this->edit_attribute();
		} else {
			$this->add_attribute();
		}
	}

	/**
	 * Get and sanitize posted attribute data.
	 * @return array
	 */
	private function get_posted_attribute() {
		$attribute = array(
			'attribute_label'   => isset( $_POST['attribute_label'] )   ? sanitize_text_field( wp_unslash( $_POST['attribute_label'] ) ) : '',
			'attribute_name'    => isset( $_POST['attribute_name'] )    ? a3_portfolio_sanitize_taxonomy_name( wp_unslash( $_POST['attribute_name'] ) ) : '',
			'attribute_type'    => isset( $_POST['attribute_type'] )    ? sanitize_text_field( $_POST['attribute_type'] ) : 'select',
			'attribute_orderby' => isset( $_POST['attribute_orderby'] ) ? sanitize_text_field( $_POST['attribute_orderby'] ) : '',
		);

		if ( empty( $attribute['attribute_type'] ) ) {
			$attribute['attribute_type'] = 'select';
		}
		if ( empty( $attribute['attribute_label'] ) ) {
			$attribute['attribute_label'] = ucfirst( $attribute['attribute_name'] );
		}
		if ( empty( $attribute['attribute_name'] ) ) {
			$attribute['attribute_name'] = a3_portfolio_sanitize_taxonomy_name( $attribute['attribute_label'] );
		}

		return $attribute;
	}

	/**
	 * See if an attribute name is valid.
	 * @param  string $attribute_name
	 * @return bool|WP_error result
	 */
	private function valid_attribute_name( $attribute_name ) {
		if ( strlen( $attribute_name ) >= 28 ) {
			return new \WP_Error( 'error', sprintf( __( 'Slug "%s" is too long (28 characters max). Shorten it, please.', 'a3-portfolio' ), sanitize_title( $attribute_name ) ) );
		}

		if ( a3_portfolio_check_if_attribute_name_is_reserved( $attribute_name ) ) {
			return new \WP_Error( 'error', sprintf( __( 'Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'a3-portfolio' ), sanitize_title( $attribute_name ) ) );
		}

		return true;
	}

	/**
	 * Add an attribute.
	 * @return bool|WP_Error
	 */
	private function process_add_attribute() {
		global $wpdb;
		check_admin_referer( 'a3-portfolio-add-new_attribute' );

		$attribute = $this->get_posted_attribute();

		if ( empty( $attribute['attribute_name'] ) || empty( $attribute['attribute_label'] ) ) {
			return new \WP_Error( 'error', __( 'Please, provide an attribute name and slug.', 'a3-portfolio' ) );
		} elseif ( ( $valid_attribute_name = $this->valid_attribute_name( $attribute['attribute_name'] ) ) && is_wp_error( $valid_attribute_name ) ) {
			return $valid_attribute_name;
		} elseif ( taxonomy_exists( a3_portfolio_attribute_taxonomy_name( $attribute['attribute_name'] ) ) ) {
			return new \WP_Error( 'error', sprintf( __( 'Slug "%s" is already in use. Change it, please.', 'a3-portfolio' ), sanitize_title( $attribute['attribute_name'] ) ) );
		}

		$wpdb->insert( $wpdb->prefix . 'a3_portfolio_attributes', $attribute );

		do_action( 'a3_portfolio_attribute_added', $wpdb->insert_id, $attribute );

		flush_rewrite_rules();

		return true;
	}

	/**
	 * Edit an attribute.
	 * @return bool|WP_Error
	 */
	private function process_edit_attribute() {
		global $wpdb;
		$attribute_id = absint( $_GET['edit'] );
		check_admin_referer( 'a3-portfolio-save-attribute_' . $attribute_id );

		$attribute = $this->get_posted_attribute();

		if ( empty( $attribute['attribute_name'] ) || empty( $attribute['attribute_label'] ) ) {
			return new \WP_Error( 'error', __( 'Please, provide an attribute name and slug.', 'a3-portfolio' ) );
		} elseif ( ( $valid_attribute_name = $this->valid_attribute_name( $attribute['attribute_name'] ) ) && is_wp_error( $valid_attribute_name ) ) {
			return $valid_attribute_name;
		}

		$taxonomy_exists    = taxonomy_exists( a3_portfolio_attribute_taxonomy_name( $attribute['attribute_name'] ) );
		$old_attribute_name = $wpdb->get_var( "SELECT attribute_name FROM {$wpdb->prefix}a3_portfolio_attributes WHERE attribute_id = $attribute_id" );
		if ( $old_attribute_name != $attribute['attribute_name'] && a3_portfolio_sanitize_taxonomy_name( $old_attribute_name ) != $attribute['attribute_name'] && $taxonomy_exists ) {
			return new \WP_Error( 'error', sprintf( __( 'Slug "%s" is already in use. Change it, please.', 'a3-portfolio' ), sanitize_title( $attribute['attribute_name'] ) ) );
		}

		$wpdb->update( $wpdb->prefix . 'a3_portfolio_attributes', $attribute, array( 'attribute_id' => $attribute_id ) );

		do_action( 'a3_portfolio_attribute_updated', $attribute_id, $attribute, $old_attribute_name );

		if ( $old_attribute_name != $attribute['attribute_name'] && ! empty( $old_attribute_name ) ) {
			// Update taxonomies in the wp term taxonomy table
			$wpdb->update(
				$wpdb->term_taxonomy,
				array( 'taxonomy' => a3_portfolio_attribute_taxonomy_name( $attribute['attribute_name'] ) ),
				array( 'taxonomy' => 'a3pa_' . $old_attribute_name )
			);
		}

		echo '<div class="updated"><p>' . __( 'Attribute updated successfully', 'a3-portfolio' ) . '</p></div>';

		flush_rewrite_rules();

		return true;
	}

	/**
	 * Delete an attribute.
	 * @return bool
	 */
	private function process_delete_attribute() {
		global $wpdb;
		$attribute_id = absint( $_GET['delete'] );
		check_admin_referer( 'a3-portfolio-delete-attribute_' . $attribute_id );

		$attribute_name = $wpdb->get_var( "SELECT attribute_name FROM {$wpdb->prefix}a3_portfolio_attributes WHERE attribute_id = $attribute_id" );

		if ( $attribute_name && $wpdb->query( "DELETE FROM {$wpdb->prefix}a3_portfolio_attributes WHERE attribute_id = $attribute_id" ) ) {

			$taxonomy = a3_portfolio_attribute_taxonomy_name( $attribute_name );

			if ( taxonomy_exists( $taxonomy ) ) {
				$terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
				foreach ( $terms as $term ) {
					wp_delete_term( $term->term_id, $taxonomy );
				}
			}

			do_action( 'a3_portfolio_attribute_deleted', $attribute_id, $attribute_name, $taxonomy );

			return true;
		}

		return false;
	}

	/**
	 * Edit Attribute admin panel.
	 *
	 * Shows the interface for changing an attributes type between select and text.
	 */
	public function edit_attribute() {
		global $wpdb;

		$edit = absint( $_GET['edit'] );

		$attribute_to_edit = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "a3_portfolio_attributes WHERE attribute_id = '$edit'" );

		?>
		<div class="wrap a3-portfolio-attribute-wrap">
			<div class="icon32 icon32-a3-portfolio-attribute" id="icon-a3-portfolio-attribute"><br/></div>
			<h1><?php _e( 'Edit Portfolio Attribute', 'a3-portfolio' ) ?></h1>

			<?php

				if ( ! $attribute_to_edit ) {
					echo '<div id="message" class="error"><p>' . __( 'Error: non-existing attribute ID.', 'a3-portfolio' ) . '</p></div>';
				} else {
					$att_type    = $attribute_to_edit->attribute_type;
					$att_label   = $attribute_to_edit->attribute_label;
					$att_name    = $attribute_to_edit->attribute_name;
					$att_orderby = $attribute_to_edit->attribute_orderby;

				?>

				<form action="edit.php?post_type=<?php echo $this->parent_page; ?>&amp;page=<?php echo $this->attribute_page; ?>&amp;edit=<?php echo absint( $edit ); ?>" method="post">
					<table class="form-table">
						<tbody>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_label"><?php _e( 'Name', 'a3-portfolio' ); ?></label>
								</th>
								<td>
									<input name="attribute_label" id="attribute_label" type="text" value="<?php echo esc_attr( $att_label ); ?>" />
									<p class="description"><?php _e( 'Name for the attribute (shown on the front-end).', 'a3-portfolio' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_name"><?php _e( 'Slug', 'a3-portfolio' ); ?></label>
								</th>
								<td>
									<input name="attribute_name" id="attribute_name" type="text" value="<?php echo esc_attr( $att_name ); ?>" maxlength="28" />
									<p class="description"><?php _e( 'Unique slug/reference for the attribute; must be shorter than 28 characters.', 'a3-portfolio' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_type"><?php _e( 'Type', 'a3-portfolio' ); ?></label>
								</th>
								<td>
									<select name="attribute_type" id="attribute_type">
										<?php foreach ( a3_portfolio_get_attribute_types() as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $att_type, $key ); ?>><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>

										<?php

											/**
											 * Deprecated action in favor of product_attributes_type_selector filter.
											 *
											 * @deprecated 2.4.0
											 */
											do_action( 'a3_portfolio_admin_attribute_types' );
										?>
									</select>
									<p class="description"><?php _e( 'Determines how you select attributes for Portfolio. Under Admin Panel -> Portfolio -> Portfolio Item Data -> Attributes -> Values, <strong>Text</strong> allows manual entry whereas <strong>select</strong> allows pre-configured terms in a drop-down list.', 'a3-portfolio' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_orderby"><?php _e( 'Default sort order', 'a3-portfolio' ); ?></label>
								</th>
								<td>
									<select name="attribute_orderby" id="attribute_orderby">
										<option value="menu_order" <?php selected( $att_orderby, 'menu_order' ); ?>><?php _e( 'Custom ordering', 'a3-portfolio' ); ?></option>
										<option value="name" <?php selected( $att_orderby, 'name' ); ?>><?php _e( 'Name', 'a3-portfolio' ); ?></option>
										<option value="id" <?php selected( $att_orderby, 'id' ); ?>><?php _e( 'Term ID', 'a3-portfolio' ); ?></option>
									</select>
									<p class="description"><?php _e( 'Determines the sort order of the terms on the frontend Portfolio pages. If using custom ordering, you can drag and drop the terms in this attribute.', 'a3-portfolio' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" name="save_attribute" id="submit" class="button-primary" value="<?php esc_attr_e( 'Update', 'a3-portfolio' ); ?>"></p>
					<?php wp_nonce_field( 'a3-portfolio-save-attribute_' . $edit ); ?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Add Attribute admin panel.
	 *
	 * Shows the interface for adding new attributes.
	 */
	public function add_attribute() {
		?>
		<style>
		.attributes-table .attribute-actions .configure-terms {
			position: relative;
			padding-left: 20px;
		}
		.attributes-table .attribute-actions .configure-terms:after {
		    content: "\f111";
		    font-family: Dashicons;
		    height: 100%;
		    position: absolute;
		    text-indent: 0;
		    width: 100%;
		    left: 5px;
		    top: 1px;
		}
		</style>
		<div class="wrap a3-portfolio-attribute-wrap">
			<div class="icon32 icon32-a3-portfolio-attribute" id="icon-a3-portfolio-attribute"><br/></div>
			<h1><?php _e( 'Portfolio Attributes', 'a3-portfolio' ); ?></h1>
			<br class="clear" />
			<div id="col-container">
				<div id="col-right">
					<div class="col-wrap">
						<table class="widefat attributes-table wp-list-table ui-sortable" style="width:100%">
							<thead>
								<tr>
									<th scope="col"><?php _e( 'Name', 'a3-portfolio' ); ?></th>
									<th scope="col"><?php _e( 'Slug', 'a3-portfolio' ); ?></th>
									<th scope="col"><?php _e( 'Type', 'a3-portfolio' ); ?></th>
									<th scope="col"><?php _e( 'Order by', 'a3-portfolio' ); ?></th>
									<th scope="col" colspan="2"><?php _e( 'Terms', 'a3-portfolio' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ( $attribute_taxonomies = a3_portfolio_get_attribute_taxonomies() ) :
										foreach ( $attribute_taxonomies as $tax ) :
											?><tr>

												<td>
													<strong><a href="edit-tags.php?taxonomy=<?php echo esc_html( a3_portfolio_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=<?php echo $this->parent_page; ?>"><?php echo esc_html( $tax->attribute_label ); ?></a></strong>

													<div class="row-actions"><span class="edit"><a href="<?php echo esc_url( add_query_arg( 'edit', $tax->attribute_id, 'edit.php?post_type='.$this->parent_page.'&amp;page='.$this->attribute_page ) ); ?>"><?php _e( 'Edit', 'a3-portfolio' ); ?></a> | </span><span class="delete"><a class="delete" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'delete', $tax->attribute_id, 'edit.php?post_type='.$this->parent_page.'&amp;page='.$this->attribute_page ), 'a3-portfolio-delete-attribute_' . $tax->attribute_id ) ); ?>"><?php _e( 'Delete', 'a3-portfolio' ); ?></a></span></div>
												</td>
												<td><?php echo esc_html( $tax->attribute_name ); ?></td>
												<td><?php echo esc_html( ucfirst( $tax->attribute_type ) ); ?></td>
												<td><?php
													switch ( $tax->attribute_orderby ) {
														case 'name' :
															_e( 'Name', 'a3-portfolio' );
														break;
														case 'id' :
															_e( 'Term ID', 'a3-portfolio' );
														break;
														default:
															_e( 'Custom ordering', 'a3-portfolio' );
														break;
													}
												?></td>
												<td class="attribute-terms"><?php
													$taxonomy = a3_portfolio_attribute_taxonomy_name( $tax->attribute_name );

													if ( taxonomy_exists( $taxonomy ) ) {
														$orderby = $tax->attribute_orderby;
														$terms = get_terms( $taxonomy, 'orderby='.$orderby.'&hide_empty=0' );

														$terms_string = implode( ', ', wp_list_pluck( $terms, 'name' ) );
														if ( $terms_string ) {
															echo $terms_string;
														} else {
															echo '<span class="na">&ndash;</span>';
														}
													} else {
														echo '<span class="na">&ndash;</span>';
													}
												?></td>
												<td class="attribute-actions">
													<?php if ( 'select' == $tax->attribute_type ) { ?>
													<a href="edit-tags.php?taxonomy=<?php echo esc_html( a3_portfolio_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=<?php echo $this->parent_page; ?>" class="button alignright configure-terms"><?php _e( 'Terms', 'a3-portfolio' ); ?></a>
													<?php } ?>
												</td>
											</tr><?php
										endforeach;
									else :
										?><tr><td colspan="6"><?php _e( 'No attributes currently exist.', 'a3-portfolio' ) ?></td></tr><?php
									endif;
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="col-left">
					<div class="col-wrap">
						<div class="form-wrap">
							<h3><?php _e( 'Add New Attribute', 'a3-portfolio' ); ?></h3>
							<p><?php _e( 'Attributes let you define extra Portfolio data, such as size or colour. You can use these attributes in the Portfolio Attribute Filter Widget. Please note: you cannot rename an attribute later on.', 'a3-portfolio' ); ?></p>
							<form action="edit.php?post_type=<?php echo $this->parent_page; ?>&amp;page=<?php echo $this->attribute_page; ?>" method="post">
								<div class="form-field">
									<label for="attribute_label"><?php _e( 'Name', 'a3-portfolio' ); ?></label>
									<input name="attribute_label" id="attribute_label" type="text" value="" />
									<p class="description"><?php _e( 'Name for the attribute (shown on the front-end).', 'a3-portfolio' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_name"><?php _e( 'Slug', 'a3-portfolio' ); ?></label>
									<input name="attribute_name" id="attribute_name" type="text" value="" maxlength="28" />
									<p class="description"><?php _e( 'Unique slug/reference for the attribute; must be shorter than 28 characters.', 'a3-portfolio' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_type"><?php _e( 'Type', 'a3-portfolio' ); ?></label>
									<select name="attribute_type" id="attribute_type">
										<?php foreach ( a3_portfolio_get_attribute_types() as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>

										<?php

											/**
											 * Deprecated action in favor of product_attributes_type_selector filter.
											 *
											 * @deprecated 2.4.0
											 */
											do_action( 'a3_portfolio_admin_attribute_types' );
										?>
									</select>
									<p class="description"><?php _e( 'Determines how you select attributes for Portfolio. Under Admin Panel -> Portfolio -> Portfolio Item Data -> Attributes -> Values, <strong>Text</strong> allows manual entry whereas <strong>select</strong> allows pre-configured terms in a drop-down list.', 'a3-portfolio' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_orderby"><?php _e( 'Default sort order', 'a3-portfolio' ); ?></label>
									<select name="attribute_orderby" id="attribute_orderby">
										<option value="menu_order"><?php _e( 'Custom ordering', 'a3-portfolio' ); ?></option>
										<option value="name"><?php _e( 'Name', 'a3-portfolio' ); ?></option>
										<option value="id"><?php _e( 'Term ID', 'a3-portfolio' ); ?></option>
									</select>
									<p class="description"><?php _e( 'Determines the sort order of the terms on the frontend Portfolio pages. If using custom ordering, you can drag and drop the terms in this attribute.', 'a3-portfolio' ); ?></p>
								</div>

								<p class="submit"><input type="submit" name="add_new_attribute" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Add Attribute', 'a3-portfolio' ); ?>"></p>
								<?php wp_nonce_field( 'a3-portfolio-add-new_attribute' ); ?>
							</form>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			/* <![CDATA[ */

				jQuery( 'a.delete' ).on('click', function() {
					if ( window.confirm( '<?php _e( "Are you sure you want to delete this attribute?", 'a3-portfolio' ); ?>' ) ) {
						return true;
					}
					return false;
				});

			/* ]]> */
			</script>
		</div>
		<?php
	}
}

endif;
