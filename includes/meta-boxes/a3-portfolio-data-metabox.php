<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * a3 Portfolios Metaboxes Class
 *
 *
 */
class Metabox
{
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}
	}

	public function add_meta_box( $post_type ) {
    	$post_types = array('a3-portfolio');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'a3_portfolio_data_meta_box'
				,__( 'Portfolio Item Meta', 'a3-portfolio' )
				,array( $this, 'output' )
				,$post_type
				,'normal'
				,'high'
			);
		}
	}

	public function include_js() {
		global $post;

		$suffix	= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'jquery-blockui', A3_PORTFOLIO_JS_URL . '/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );

		add_action( 'admin_footer', array( $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface'], 'admin_script_load' ) );
		add_action( 'admin_footer', array( $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface'], 'admin_css_load' ) );
		wp_enqueue_style( 'a3_portfolio-metabox-admin-style', A3_PORTFOLIO_CSS_URL . '/a3.portfolio.metabox.admin.css' );
		wp_enqueue_script( 'a3-portfolio-metabox-admin-script', A3_PORTFOLIO_JS_URL . '/a3.portfolio.metabox.admin' . $suffix . '.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable' ) );
		wp_enqueue_media();

		if ( is_rtl() ) {
			wp_enqueue_style( 'a3_portfolio-metabox-admin-style-rtl', A3_PORTFOLIO_CSS_URL . '/a3.portfolio.metabox.admin.rtl.css' );
		}

		$params = array(
			'select_attribute_message' => __( 'Please select an attribute for add', 'a3-portfolio' ),
			'remove_attribute'         => __( 'Remove this attribute?', 'a3-portfolio' ),
			'new_attribute_prompt'     => __( 'Enter a name for the new attribute term:', 'a3-portfolio' ),
			'ajax_url'                 => admin_url( 'admin-ajax.php' ),
			'add_attribute_nonce'      => wp_create_nonce( 'add-attribute' ),
			'save_attributes_nonce'    => wp_create_nonce( 'save-attributes' ),
			'post_id'                  => isset( $post->ID ) ? $post->ID : '',
		);

		wp_localize_script( 'a3-portfolio-metabox-admin-script', 'a3_portfolio_admin_meta_boxes', $params );

		do_action( 'a3_portfolio_metabox_include_scripts' );
	}

	/**
	 * Output the metabox
	 */
	public function output( $post ) {
		global $a3_portfolio_item_cards_settings, $a3_portfolio_item_posts_settings;
		$thepostid = $post->ID;
		$this->include_js();

		$card_desc = esc_html( get_post_meta( $thepostid, '_a3_portfolio_card_desc', true ) );

		$layout_column = trim( get_post_meta( $thepostid, '_a3_portfolio_meta_layout_column', true ) );
		if ( '' == $layout_column ){
			$layout_column = $a3_portfolio_item_posts_settings['portfolio_single_layout_column'];
		}
		if ( '' == $layout_column ) $layout_column = 2;

		$wide = trim( get_post_meta( $thepostid, '_a3_portfolio_meta_gallery_wide', true ) );
		if ( '' == $wide ){
			$wide = $a3_portfolio_item_posts_settings['portfolio_inner_container_single_main_image_width'];
		}
		if ( '' == $wide ) $wide = 70;

		$thumb_pos = trim( get_post_meta( $thepostid, '_a3_portfolio_meta_thumb_position', true ) );
		if ( '' == $thumb_pos ){
			$thumb_pos = $a3_portfolio_item_posts_settings['portfolio_inner_container_single_thumb_position'];
		}

		$button_visit = trim( get_post_meta( $thepostid, '_a3_portfolio_launch_site_url', true ) );

		$button_text = trim( esc_attr(  get_post_meta( $thepostid, '_a3_portfolio_launch_button_text', true ) ) );

		$button_text = apply_filters( 'a3_portfolio_backend_launch_button_text', $button_text, $thepostid );

		if ( '' == $button_text ) {
			$button_text = a3_portfolio_ei_ict_t__( 'Launch Site Button Text', __( 'LAUNCH SITE', 'a3-portfolio' ) );
		}
		$launch_open_type = trim( esc_attr(  get_post_meta( $thepostid, '_a3_portfolio_launch_open_type', true ) ) );

		$viewmore_button_text = trim( esc_attr(  get_post_meta( $thepostid, '_a3_portfolio_viewmore_button_text', true ) ) );

		$viewmore_button_text = apply_filters( 'a3_portfolio_backend_viewmore_button_text', $viewmore_button_text, $thepostid );

		if ( '' == $viewmore_button_text ) {
			$viewmore_button_text = apply_filters( 'a3_portfolio_viewmore_button_text', a3_portfolio_ei_ict_t__( 'View More Button Text', __( 'View More', 'a3-portfolio' ) ), $thepostid );
		}

		?>
		<div class="a3rev_panel_container a3-metabox-panel-wrap">

			<div class="a3-metabox-tabs-back"></div>

			<ul class="a3-metabox-data-tabs" style="display:none;">
				<?php
					$portfolio_data_tabs = apply_filters( 'a3_portfolio_metabox_data_tabs', array(
						'portfolio_gallery' => array(
							'label'  => __( 'Portfolio Gallery', 'a3-portfolio' ),
							'target' => 'portfolio_gallery_panel',
							'class'  => array(),
						),
						'card_description' => array(
							'label'  => __( 'Card Description', 'a3-portfolio' ),
							'target' => 'portfolio_card_description_panel',
							'class'  => array(),
						),
						'single_layout' => array(
							'label'  => __( 'Layout', 'a3-portfolio' ),
							'target' => 'portfolio_single_layout_panel',
							'class'  => array(),
						),
						'portfolio_attributes' => array(
							'label'  => __( 'Attributes', 'a3-portfolio' ),
							'target' => 'portfolio_attributes_panel',
							'class'  => array(),
						),
						'portfolio_button' => array(
							'label'  => __( 'Button', 'a3-portfolio' ),
							'target' => 'portfolio_button_panel',
							'class'  => array(),
						),
						'portfolio_sticky' => array(
							'label'  => __( 'Sticky', 'a3-portfolio' ),
							'target' => 'portfolio_sticky_panel',
							'class'  => array(),
						),
					), $post );

					if ( ! $a3_portfolio_item_cards_settings['enable_cards_description'] ) {
						$portfolio_data_tabs['card_description']['css'] = 'display: none;';
					}

					foreach ( $portfolio_data_tabs as $key => $tab ) {
						?><li
						class="<?php echo $key; ?>_options <?php echo $key; ?>_tab <?php echo implode( ' ' , $tab['class'] ); ?>"
						<?php if ( isset( $tab['css'] ) ) : ?>style="<?php echo $tab['css']; ?>"<?php endif; ?>
						>
							<a class="a3-portfolio-metabox-icon" href="#<?php echo $tab['target']; ?>"><?php echo esc_html( $tab['label'] ); ?></a>
						</li><?php
					}

					do_action( 'a3_portfolio_metabox_write_panel_tabs', $post );
				?>
			</ul>
			<div id="portfolio_gallery_panel" class="a3-metabox-panel a3-metabox-options-panel">
				<div id="portfolio_images_container">
					<ul class="portfolio_images">
						<?php
							$portfolio_gallery = a3_portfolio_get_gallery( $thepostid );

							if ( $portfolio_gallery ) {
								$portfolio_gallery = array_diff( $portfolio_gallery, array( get_post_thumbnail_id() ) );
								foreach ( $portfolio_gallery as $attachment_id ) {
						?>
						<li class="image" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?> ">
							<?php echo wp_get_attachment_image( $attachment_id, 'thumbnail' ); ?>
							<ul class="actions">
								<li><a href="#" class="delete tips" data-tip="<?php echo __( 'Delete image', 'a3-portfolio' ); ?>"><?php echo __( 'Delete image', 'a3-portfolio' ); ?></a></li>
							</ul>
						</li>
						<?php
								}
							}
						?>
					</ul>

					<input type="hidden" id="portfolio_image_gallery" name="portfolio_image_gallery" value="<?php if ( $portfolio_gallery ) echo esc_attr( implode( ',', $portfolio_gallery ) ); ?>" />

				</div>
				<p class="add_portfolio_images hide-if-no-js">
					<a href="#" data-choose="<?php _e( 'Add Images to Portfolio Gallery', 'a3-portfolio' ); ?>" data-update="<?php _e( 'Add to gallery', 'a3-portfolio' ); ?>" data-delete="<?php _e( 'Delete image', 'a3-portfolio' ); ?>" data-text="<?php _e( 'Delete', 'a3-portfolio' ); ?>"><?php _e( 'Add portfolio gallery images', 'a3-portfolio' ); ?></a>
				</p>
				<?php do_action( 'a3_portfolio_metabox_portfolio_gallery_panel', $post ); ?>
			</div>

			<div id="portfolio_card_description_panel" class="a3-metabox-panel a3-metabox-options-panel">
				<div class="options_group">
					<table class="form-table">
						<tr>
							<td class="forminp forminp-text">
								<textarea
									name="_a3_portfolio_card_desc"
									id="_a3_portfolio_card_desc"
									class="a3rev-ui-textarea"
	                                placeholder="<?php _e( 'Leave this empty and the card description will be pulled from the first lines of the item description', 'a3-portfolio' ); ?>"
								><?php echo $card_desc; ?></textarea>
								<div><span class="description"><?php _e( 'Note! The number of rows set for Item Card Description text Height on General Settings still applies to custom description entered here', 'a3-portfolio' ); ?></span></div>
	                        </td>
						</tr>
						<?php do_action( 'a3_portfolio_metabox_portfolio_card_description_options' ); ?>
					</table>
					<?php do_action( 'a3_portfolio_metabox_portfolio_card_description_panel' ); ?>
				</div>
			</div>

			<div id="portfolio_single_layout_panel" class="a3-metabox-panel a3-metabox-options-panel">
				<div class="options_group">
					<table class="form-table">
						<tr>
							<td>
								<label for="_a3_portfolio_meta_layout_column"><?php echo __( 'Post Display', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-switcher_checkbox">
		                        <input
									name="_a3_portfolio_meta_layout_column"
	                                id="_a3_portfolio_meta_layout_column"
									class="a3rev-ui-onoff_checkbox a3_portfolio_meta_layout_column"
	                                checked_label="<?php echo __( '1 Column', 'a3-portfolio' ); ?>"
	                                unchecked_label="<?php echo __( '2 Columns', 'a3-portfolio' ); ?>"
	                                type="checkbox"
									value="1"
									<?php checked( $layout_column, 1 ); ?>
									/>
	                        </td>
						</tr>
						<tr class="portfolio_single_2_column_container">
							<td>
								<label for="_a3_portfolio_meta_gallery_wide"><?php echo __( 'Main Image Width', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-slider">
	                        <div class="a3rev-ui-slide-container">
	                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
	                                <div class="a3rev-ui-slide" id="_a3_portfolio_meta_gallery_wide_id_div" min="30" max="80" inc="1"></div>
	                            </div></div>
	                            <div class="a3rev-ui-slide-result-container">
	                                <input
	                                    readonly="readonly"
	                                    name="_a3_portfolio_meta_gallery_wide"
	                                    id="_a3_portfolio_meta_gallery_wide_id"
	                                    type="text"
	                                    value="<?php echo esc_attr( $wide ); ?>"
	                                    class="a3rev-ui-slider"
	                                    /> %
								</div>
	                        </div>
	                        </td>
						</tr>
						<tr valign="top" class="portfolio_single_2_column_container">
							<td>
								<label for="_a3_portfolio_meta_thumb_position"><?php echo __( 'Gallery Thumbnail Position', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-select">
								<select
									name="_a3_portfolio_meta_thumb_position"
									id="_a3_portfolio_meta_thumb_position"
									style="width: 100px;"
									class="a3rev-ui-select chzn-select"
									>
									<option value="right" selected="selected"><?php echo __( 'Right', 'a3-portfolio' ); ?></option>
									<option value="below" <?php
											selected( $thumb_pos, 'below' );
									?>><?php echo __( 'Below', 'a3-portfolio' ); ?></option>
							   </select>
							</td>
						</tr>
						<?php do_action( 'a3_portfolio_metabox_single_layout_options', $post ); ?>
					</table>
					<?php do_action( 'a3_portfolio_metabox_single_layout_panel', $post ); ?>
				</div>
			</div>

			<div id="portfolio_attributes_panel" class="a3-metabox-panel a3-metabox-wrapper">

				<?php
					global $a3_portfolio_attributes;
					if ( $a3_portfolio_attributes && is_array( $a3_portfolio_attributes ) && count( $a3_portfolio_attributes ) > 0 ) {
				?>
				<p class="toolbar toolbar-top">
					<a href="#" class="a3-metabox-icon close_all"><?php _e( 'Close all', 'a3-portfolio' ); ?></a><a href="#" class="a3-metabox-icon expand_all"><?php _e( 'Expand all', 'a3-portfolio' ); ?></a>
					<select name="attribute_taxonomy" class="attribute_taxonomy">
						<option value=""><?php _e( 'Select portfolio attribute', 'a3-portfolio' ); ?></option>
						<?php
							// Array of defined attribute taxonomies
							$attribute_taxonomies = a3_portfolio_get_attribute_taxonomies();

							if ( $attribute_taxonomies ) {
								foreach ( $attribute_taxonomies as $tax ) {
									$attribute_taxonomy_name = a3_portfolio_attribute_taxonomy_name( $tax->attribute_name );
									$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
									echo '<option value="' . absint( $tax->attribute_id ) . '">' . esc_html( $label ) . '</option>';
								}
							}
						?>
					</select>
					<button type="button" class="button add_attribute"><?php _e( 'Add', 'a3-portfolio' ); ?></button>
				</p>
				<div class="portfolio_attributes a3-metabox-items">
					<?php
						// Portfolio attributes - taxonomies and custom, ordered, with visibility and variation attributes set
						$attributes = maybe_unserialize( get_post_meta( $thepostid, '_portfolio_attributes', true ) );

						// Output All Set Attributes
						if ( ! empty( $attributes ) ) {

							$position = 0;
							foreach ( $attributes as $attribute_id => $attribute ) {
								$taxonomy      = '';
								$metabox_class = array();

								$taxonomy = a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id );
								if ( '' != $taxonomy ) {
									if ( ! taxonomy_exists( $taxonomy ) ) {
										continue;
									}

									if ( ! isset( $attributes['is_visible_expander'] ) ) {
										$attributes['is_visible_expander'] = apply_filters( 'a3_portfolio_attribute_default_visible_expander', 1 );
									}
									if ( ! isset( $attributes['is_visible_post'] ) ) {
										$attributes['is_visible_post'] = apply_filters( 'a3_portfolio_attribute_default_visible_post', 1 );
									}

									$attribute_taxonomy = $a3_portfolio_attributes[ $taxonomy ];
									$metabox_class[]    = 'taxonomy';
									$metabox_class[]    = $taxonomy;
									$attribute_label    = a3_portfolio_attribute_label( $taxonomy );

									include( 'views/html-portfolio-attribute.php' );

									$position++;
								}
							}
						}
					?>
				</div>
				<p class="toolbar">
					<a href="#" class="a3-metabox-icon close_all"><?php _e( 'Close all', 'a3-portfolio' ); ?></a><a href="#" class="a3-metabox-icon expand_all"><?php _e( 'Expand all', 'a3-portfolio' ); ?></a>
					<button type="button" class="button save_attributes button-primary"><?php _e( 'Save Attributes', 'a3-portfolio' ); ?></button>
				</p>
				<?php
				} else {
				?>
				<div class="a3-metabox-items"><p class=""><?php echo sprintf( __( 'Please create <a href="%s">Portfolio Attribute</a>', 'a3-portfolio' ), admin_url('edit.php?post_type=a3-portfolio&page=portfolio-attributes') ); ?></p></div>
				<?php
				}
				?>
				<?php do_action( 'a3_portfolio_metabox_portfolio_attributes_panel' ); ?>
			</div>

			<div id="portfolio_button_panel" class="a3-metabox-panel a3-metabox-options-panel">
				<div class="options_group">
					<table class="form-table">
						<tr>
							<td colspan="2">
								<strong><?php echo __( 'View More Button', 'a3-portfolio' ); ?></strong>
							</td>
						</tr>
						<tr valign="top">
							<td>
								<label for="_a3_portfolio_viewmore_button_text"><?php echo __( 'Button Text', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-text">
		                        <input
									name="_a3_portfolio_viewmore_button_text"
									id="_a3_portfolio_viewmore_button_text"
									type="text"
									value="<?php echo esc_attr( $viewmore_button_text ); ?>"
									class="a3rev-ui-text"
									/>
	                        </td>
						</tr>
					</table>
				</div>
				<div class="options_group">
					<table class="form-table">
						<tr>
							<td colspan="2">
								<strong><?php echo __( 'Launch Button', 'a3-portfolio' ); ?></strong>
							</td>
						</tr>
						<tr>
							<td>
								<label for="_a3_portfolio_launch_site_url"><?php echo __( 'Link URL', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-text">
		                        <input
									name="_a3_portfolio_launch_site_url"
									id="_a3_portfolio_launch_site_url"
									type="text"
									value="<?php echo esc_attr( $button_visit ); ?>"
									class="a3rev-ui-text"
	                                placeholder="http://"
									/>
	                        </td>
						</tr>
						<tr valign="top">
							<td>
								<label for="_a3_portfolio_launch_button_text"><?php echo __( 'Button Text', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-text">
		                        <input
									name="_a3_portfolio_launch_button_text"
									id="_a3_portfolio_launch_button_text"
									type="text"
									value="<?php echo esc_attr( $button_text ); ?>"
									class="a3rev-ui-text"
									/>
	                        </td>
						</tr>
						<tr valign="top">
							<td>
								<label for="_a3_portfolio_launch_open_type"><?php echo __( 'Open Type', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-onoff_checkbox">
								<input
									name="_a3_portfolio_launch_open_type"
	                                id="_a3_portfolio_launch_open_type"
	                                class="a3rev-ui-onoff_checkbox"
	                                checked_label="<?php echo __( 'ON', 'a3-portfolio' ); ?>"
	                                unchecked_label="<?php echo __( 'OFF', 'a3-portfolio' ); ?>"
	                                type="checkbox"
									value="_blank"
									<?php checked( $launch_open_type, '_blank' ); ?>
									/> <label for="_a3_portfolio_launch_open_type"><span class="description" style="margin-left:5px;"><?php echo __( 'ON for Open in new window', 'a3-portfolio' ); ?></span></label>
	                        </td>
						</tr>
					</table>
				</div>
				<?php do_action( 'a3_portfolio_metabox_portfolio_button_options' ); ?>
				<?php do_action( 'a3_portfolio_metabox_portfolio_button_panel' ); ?>
			</div>

			<div id="portfolio_sticky_panel" class="a3-metabox-panel a3-metabox-options-panel">
				<div class="options_group">
					<table class="form-table">
						<tr valign="top">
							<td>
								<label for="_a3_portfolio_sticky"><?php echo __( 'Make Item Sticky', 'a3-portfolio' ); ?></label>
							</td>
							<td class="forminp forminp-onoff_checkbox">
								<input
									name="sticky"
	                                id="_a3_portfolio_sticky"
	                                class="a3rev-ui-onoff_checkbox"
	                                checked_label="<?php echo __( 'ON', 'a3-portfolio' ); ?>"
	                                unchecked_label="<?php echo __( 'OFF', 'a3-portfolio' ); ?>"
	                                type="checkbox"
									value="sticky"
									<?php checked( is_sticky( $thepostid ) ); ?>
									/> <label for="_a3_portfolio_sticky"><span class="description" style="margin-left:5px;"><?php echo sprintf( __( 'For theme with Sticky post functionality and for Sticky feature in <a href="%s" target="_blank" >a3 Portfolio Shortcodes</a> plugin', 'a3-portfolio' ), 'http://a3rev.com/shop/a3-portfolio-shortcodes/' ); ?></span></label>
	                        </td>
						</tr>
					</table>
				</div>
			</div>

			<?php do_action( 'a3_portfolio_metabox_panels', $post ); ?>
			<?php
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'a3_portfolio_metabox_action', 'a3_portfolio_metabox_nonce_field' );
			?>
			<div class="clear"></div>

		</div>
		<div style="clear: both;"></div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['a3_portfolio_metabox_nonce_field'] ) || ! check_admin_referer( 'a3_portfolio_metabox_action', 'a3_portfolio_metabox_nonce_field' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
		// so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		update_post_meta( $post_id, '_a3_portfolio_card_desc', sanitize_textarea_field( $_POST['_a3_portfolio_card_desc'] ) );

		$layout_column = 2;
		if ( isset( $_POST['_a3_portfolio_meta_layout_column'] ) ) {
			$layout_column = sanitize_text_field( $_POST['_a3_portfolio_meta_layout_column'] );
		}
		update_post_meta( $post_id, '_a3_portfolio_meta_layout_column', $layout_column );

		update_post_meta( $post_id, '_a3_portfolio_meta_gallery_wide', sanitize_text_field( $_POST['_a3_portfolio_meta_gallery_wide'] ) );
		update_post_meta( $post_id, '_a3_portfolio_meta_thumb_position', sanitize_text_field( $_POST['_a3_portfolio_meta_thumb_position'] ) );
		update_post_meta( $post_id, '_a3_portfolio_launch_site_url', sanitize_url( $_POST['_a3_portfolio_launch_site_url'] ) );
		update_post_meta( $post_id, '_a3_portfolio_launch_button_text', sanitize_text_field( $_POST['_a3_portfolio_launch_button_text'] ) );

		$launch_open_type = '';
		if ( isset( $_POST['_a3_portfolio_launch_open_type'] ) ) {
			$launch_open_type = sanitize_text_field( $_POST['_a3_portfolio_launch_open_type'] );
		}
		update_post_meta( $post_id, '_a3_portfolio_launch_open_type', $launch_open_type );

		update_post_meta( $post_id, '_a3_portfolio_viewmore_button_text', sanitize_text_field( $_POST['_a3_portfolio_viewmore_button_text'] ) );

		$attachment_ids = array_map( 'absint', array_filter( explode( ',', $_POST['portfolio_image_gallery'] ) ) );
		update_post_meta( $post_id, '_a3_portfolio_image_gallery', implode( ',', $attachment_ids ) );

		// Save Attributes
		$attributes = array();

		if ( isset( $_POST['attribute_names'] ) && isset( $_POST['attribute_values'] ) ) {

			$attribute_names  = $_POST['attribute_names'];
			$attribute_values = $_POST['attribute_values'];

			if ( isset( $_POST['attribute_visibility'] ) ) {
				$attribute_visibility = $_POST['attribute_visibility'];
			}

			if ( is_array( $attribute_names ) && count( $attribute_names ) ) {
				foreach ( $attribute_names as $attribute_id => $attribute_name ) {
					$is_visible_expander     = isset( $attribute_visibility[ $attribute_id ]['expander'] ) ? 1 : 0;
					$is_visible_post         = isset( $attribute_visibility[ $attribute_id ]['post'] ) ? 1 : 0;

					$is_attribute_have_terms = false;

					if ( isset( $attribute_values[ $attribute_id ] ) ) {

						// Select based attributes - Format values (posted values are slugs)
						if ( is_array( $attribute_values[ $attribute_id ] ) ) {
							$values                  = array_map( 'sanitize_title', $attribute_values[ $attribute_id ] );
							// Remove empty items in the array
							$values                  = array_filter( $values, 'strlen' );
							$is_attribute_have_terms = true;

						// Text based attributes - Posted values are term names - don't change to slugs
						} else {
							$values = stripslashes( strip_tags( $attribute_values[ $attribute_id ] ) );
						}

					} else {
						$values = '';
					}

					// Update post terms
					if ( $is_attribute_have_terms && taxonomy_exists( $attribute_name ) ) {

						foreach( $values as $key => $value ) {
							$term = get_term_by( 'slug', trim( $value ), $attribute_name );

							if ( $term ) {
								$values[ $key ] = intval( $term->term_id );
							} else {
								$term = wp_insert_term( trim( $value ), $attribute_name );
								if ( isset( $term->term_id ) ) {
									$values[ $key ] = intval($term->term_id);
								}
							}
						}

						wp_set_object_terms( $post_id, $values, $attribute_name );
					}

					if ( ! empty( $values ) ) {
						// Add attribute to array, but don't set values
						$attributes[ $attribute_id ] = array(
							'value'               => '',
							'is_visible_expander' => $is_visible_expander,
							'is_visible_post'     => $is_visible_post,
						);

						if ( ! $is_attribute_have_terms ) {
							$attributes[ $attribute_id ]['value'] = $values;
						}

					}
				}
			}
		}

		/**
		 * Unset removed attributes by looping over previous values and
		 * unsetting the terms.
		 */
		$old_attributes = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_portfolio_attributes', true ) ) );

		if ( $old_attributes ) {
			foreach ( $old_attributes as $attribute_id => $value ) {
				$taxonomy = a3_portfolio_attribute_taxonomy_name_by_id( $attribute_id );
				if ( empty( $attributes[ $attribute_id ] ) && taxonomy_exists( $taxonomy ) ) {
					wp_set_object_terms( $post_id, array(), $attribute_id );
				}
			}
		}

		/**
		 * After removed attributes are unset, we can set the new attribute data.
		 */
		update_post_meta( $post_id, '_portfolio_attributes', $attributes );

		do_action( 'a3_portfolio_metabox_save', $post_id );
	}

}
