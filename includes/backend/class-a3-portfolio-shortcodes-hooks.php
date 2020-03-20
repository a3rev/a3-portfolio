<?php

namespace A3Rev\Portfolio\Backend\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'A3_Portfolio_Shortcodes_Backend_Hooks' ) && ! class_exists( '\A3Rev\Portfolio\Backend\Shortcode\Hooks' ) ) {

class Hooks
{
	public function __construct() {
		add_action( 'init', array( $this, 'plugin_init' ), 1 );
	}

	public function plugin_init() {
		if ( is_admin() ) {
			add_action( 'media_buttons', array( $this, 'add_shortcode_button' ), 100 );
			add_action( 'admin_footer', array( $this, 'generator_popup' ) );

			// Add columns
			add_filter( 'manage_edit-portfolio_cat_columns', array( $this, 'portfolio_cat_columns' ), 11 );
			add_filter( 'manage_portfolio_cat_custom_column', array( $this, 'portfolio_cat_column' ), 11, 3 );
		}
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
				if ( $column_key == 'name' ) {
					$new_columns['shortcode'] = __( 'Shortcode', 'a3_portfolio_shortcodes' );
				}
			}
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
		$term = get_term_by( 'ID', $id, 'portfolio_cat' );

		if ( $column == 'shortcode' ) {
			$columns .= '<input readonly="readonly" style="font-size:11px; width:90%;" type="text" value=\'[a3_portfolio_category ids="'.$id.'"]\' />';
		}

		return $columns;
	}

	public function add_shortcode_button() {
		$is_post_edit_page = in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );
        if ( ! $is_post_edit_page ) return;

		echo '<a href="#TB_inline?width=640&height=500&inlineId=a3-portfolio-wrap" class="thickbox button a3-portfolio-add-shortcode" title="' . __( 'Insert shortcode', 'a3_portfolio_shortcodes' ) . '"><span class="a3-portfolio-add-shortcode_icon"></span>'.__( 'Portfolio', 'a3_portfolio_shortcodes' ).'</a>';
	}

	public function generator_popup() {
		$is_post_edit_page = in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) );
        if ( ! $is_post_edit_page ) return;

		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface']->admin_script_load();
		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface']->admin_css_load();

		$list_portfolios = get_posts( array(
			'posts_per_page'		=> -1,
			'orderby'				=> 'title',
			'order'					=> 'ASC',
			'post_type'				=> 'a3-portfolio',
			'post_status'			=> 'publish',
		));

		$all_cats      = a3_portfolio_get_all_categories_visiable( 0, '– ', false );
		$all_tags      = a3_portfolio_get_all_tags();
		$global_column = a3_portfolio_get_col_per_row();

		?>
		<div id="a3-portfolio-wrap" style="visibility: visible; height: 0px; overflow: hidden;">
			<div class="a3rev_panel_container">
        	<fieldset class="a3_portfolio_shortcode_object">
        		<legend style="font-weight:bold; font-size:14px;">
        			<?php _e( 'Insert Portfolio Items', 'a3_portfolio_shortcodes' ); ?>
        		</legend>
	            <div id="a3-portfolio-content" class="a3-portfolio-content a3-portfolio-shortcode-popup-container" style="text-align:left;">
	                <div class="a3_portfolio_shortcode_row">
	                	<label class="a3_portfolio_shortcode_field" for="a3_portfolio_id"><?php _e( 'Select Portfolio Items', 'a3_portfolio_shortcodes' ); ?>:</label>
	                    <select style="width:400px" class="a3rev-ui-multiselect chzn-select" id="a3_portfolio_id" name="a3_portfolio_id" multiple="multiple" size="5">
	                    <?php
						if ( is_array( $list_portfolios ) && count( $list_portfolios ) > 0 ) {
							foreach ( $list_portfolios as $portfolio ) {
						?>
	                    	<option value="<?php echo esc_attr( $portfolio->ID ); ?>" ><?php echo $portfolio->post_title; ?></option>
	                    <?php

							}
						}
						wp_reset_postdata();
	                    ?>
	                    </select>
	                </div>
	                <div class="a3_portfolio_shortcode_row">
	                	<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_align"><?php _e('Container Alignment', 'a3_portfolio_shortcodes'); ?>:</label>
	                	<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_align" id="a3_portfolio_enable_align" value="1" />
	                	<span class="description"><?php _e('Align Items Cards within the content.', 'a3_portfolio_shortcodes'); ?></span>
	                </div>
	                <div class="a3_portfolio_shortcode_row a3_portfolio_align_container">
		                	<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
		                	<select style="width:120px" class="a3rev-ui-select chzn-select" id="a3_portfolio_align" name="a3_portfolio_align">
		                		<option value="none" selected="selected"><?php _e('Select Option', 'a3_portfolio_shortcodes'); ?></option>
		                		<option value="left-wrap"><?php _e('Left - wrap', 'a3_portfolio_shortcodes'); ?></option>
		                		<option value="left-nowrap"><?php _e('Left - no wrap', 'a3_portfolio_shortcodes'); ?></option>
		                		<option value="center"><?php _e('Center', 'a3_portfolio_shortcodes'); ?></option>
		                		<option value="right-wrap"><?php _e('Right - wrap', 'a3_portfolio_shortcodes'); ?></option>
		                		<option value="right-nowrap"><?php _e('Right - no wrap', 'a3_portfolio_shortcodes'); ?></option>
		                	</select>
		                	<span class="description"><?php _e('Wrap is text wrap like images', 'a3_portfolio_shortcodes'); ?></span>
		                </div>
					<div class="a3_portfolio_shortcode_row forminp-onoff_radio">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_fixed_width"><?php _e('Set as px wide', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="radio" class="a3rev-ui-onoff_radio" checked="checked" name="a3_portfolio_width_type" id="a3_portfolio_enable_fixed_width" value="fixed" checkbox-disabled="true" />
						<span class="description"><?php _e('Set maximum container width in px.', 'a3_portfolio_shortcodes'); ?></span>
					</div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_fixed_width_container">
							<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
							<input type="text" class="a3rev-ui-text" size="10" id="a3_portfolio_fixed_width" name="a3_portfolio_fixed_width" value="600" style="width:50px;" />px
						</div>
					<div class="a3_portfolio_shortcode_row forminp-onoff_radio">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_percent_width"><?php _e('Set as % wide', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="radio" class="a3rev-ui-onoff_radio" name="a3_portfolio_width_type" id="a3_portfolio_enable_percent_width" value="percent" />
						<span class="description"><?php _e('Set as a % wide of the content width.', 'a3_portfolio_shortcodes'); ?></span>
					</div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_percent_width_container">
							<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
							<input type="text" class="a3rev-ui-text" size="10" id="a3_portfolio_percent_width" name="a3_portfolio_percent_width" value="30" style="width:50px;" />%
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_column"><?php _e('Portfolio Cards / Row', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_column" id="a3_portfolio_enable_column" value="1" />
	                	<span class="description"><?php _e('OFF to use global Columns from Settings Panel.', 'a3_portfolio_shortcodes'); ?></span>
                    </div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_column_container">
						<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
						<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="a3_portfolio_column_div" min="1" max="6" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="a3_portfolio_column"
                                    id="a3_portfolio_column"
                                    type="text"
                                    value="<?php echo esc_attr( $global_column ); ?>"
                                    class="a3rev-ui-slider"
                                    /> <?php _e('columns', 'a3_portfolio_shortcodes'); ?>
							</div>
                        </div>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_padding"><?php _e('Container Padding', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_padding" id="a3_portfolio_enable_padding" value="1" />
	                	<span class="description"><?php _e('Add padding around the shortcode container border.', 'a3_portfolio_shortcodes'); ?></span>
                    </div>
                    <div class="a3_portfolio_shortcode_row a3_portfolio_padding_container">
                        <label for="a3_portfolio_padding_top"><?php _e('Above', 'a3_portfolio_shortcodes'); ?>:</label><input style="width:50px;" size="10" id="a3_portfolio_padding_top" name="a3_portfolio_padding_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="a3_portfolio_padding_bottom"><?php _e('Below', 'a3_portfolio_shortcodes'); ?>:</label> <input style="width:50px;" size="10" id="a3_portfolio_padding_bottom" name="a3_portfolio_padding_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="a3_portfolio_padding_left"><?php _e('Left', 'a3_portfolio_shortcodes'); ?>:</label> <input style="width:50px;" size="10" id="a3_portfolio_padding_left" name="a3_portfolio_padding_left" type="text" value="0" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="a3_portfolio_padding_right"><?php _e('Right', 'a3_portfolio_shortcodes'); ?>:</label> <input style="width:50px;" size="10" id="a3_portfolio_padding_right" name="a3_portfolio_padding_right" type="text" value="0" />px
	                </div>
				</div>
	            <div style="clear:both;height:0px"></div>
	            <p>
	            	<input type="button" class="button button-primary" value="<?php _e( 'Insert Shortcode', 'a3_portfolio_shortcodes' ); ?>" onclick="a3_portfolio_item_add_shortcode();" />
	            	<input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e( 'Cancel', 'a3_portfolio_shortcodes' ); ?>" />
				</p>
            </fieldset>

            <fieldset class="a3_portfolio_shortcode_object">
            	<legend style="font-weight:bold; font-size:14px;">
            		<?php _e( 'Insert Portfolio Recent & Sticky', 'a3_portfolio_shortcodes' ); ?>
            	</legend>
            	<div id="a3-portfolio-content" class="a3-portfolio-content a3-portfolio-shortcode-popup-container" style="text-align:left;">
	                <div class="a3_portfolio_shortcode_row">
	                	<label class="a3_portfolio_shortcode_field" for="a3_portfolio_type"><?php _e('Portfolio Type', 'a3_portfolio_shortcodes'); ?>:</label>
	                	<select style="width:120px" class="a3rev-ui-select chzn-select" id="a3_portfolio_type" name="a3_portfolio_type">
	                		<option value="recent" selected="selected"><?php _e('Recent', 'a3_portfolio_shortcodes'); ?></option>
	                		<option value="sticky"><?php _e('Sticky', 'a3_portfolio_shortcodes'); ?></option>
	                	</select>
	                </div>
	                <div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_rs_column"><?php _e('Portfolio Cards / Row', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_rs_column" id="a3_portfolio_enable_rs_column" value="1" />
	                	<span class="description"><?php _e('OFF to use global Columns from Settings Panel.', 'a3_portfolio_shortcodes'); ?></span>
                    </div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_rs_column_container">
						<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
						<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="a3_portfolio_rs_column_div" min="1" max="6" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="a3_portfolio_rs_column"
                                    id="a3_portfolio_rs_column"
                                    type="text"
                                    value="<?php echo esc_attr( $global_column ); ?>"
                                    class="a3rev-ui-slider"
                                    /> <?php _e('columns', 'a3_portfolio_shortcodes'); ?>
							</div>
                        </div>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_rs_items"><?php _e('Number of Items', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="text" class="a3rev-ui-text" size="10" id="a3_portfolio_rs_items" name="a3_portfolio_rs_items" value="" style="width:50px;" />
						<span class="description"><?php _e('Leave empty for get all items.', 'a3_portfolio_shortcodes'); ?></span>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_rs_navbar"><?php _e('Show Nav Bar', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_rs_navbar" id="a3_portfolio_enable_rs_navbar" value="1" />
					</div>
				</div>
	            <div style="clear:both;height:0px"></div>
	            <p>
	            	<input type="button" class="button button-primary" value="<?php _e( 'Insert Shortcode', 'a3_portfolio_shortcodes' ); ?>" onclick="a3_portfolio_rs_add_shortcode();" />
	            	<input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e( 'Cancel', 'a3_portfolio_shortcodes' ); ?>" />
				</p>
            </fieldset>

            <fieldset class="a3_portfolio_shortcode_object">
            	<legend style="font-weight:bold; font-size:14px;">
            		<?php _e( 'Insert Portfolio Categories', 'a3_portfolio_shortcodes' ); ?>
            	</legend>
            	<div id="a3-portfolio-content" class="a3-portfolio-content a3-portfolio-shortcode-popup-container" style="text-align:left;">
	                <div class="a3_portfolio_shortcode_row">
	                	<label class="a3_portfolio_shortcode_field" for="a3_portfolio_category_id"><?php _e( 'Select Portfolio Categories', 'a3_portfolio_shortcodes' ); ?>:</label>
	                    <select style="width:400px" class="a3rev-ui-multiselect chzn-select" id="a3_portfolio_category_id" name="a3_portfolio_category_id" multiple="multiple" size="5" >
	                    <?php
						if ( is_array( $all_cats ) && count( $all_cats ) > 0 ) {
							foreach ( $all_cats as $cat ) {
						?>
	                    	<option value="<?php echo esc_attr( $cat->term_id ); ?>" ><?php echo $cat->name; ?></option>
	                    <?php

							}
						}
	                    ?>
	                    </select>
	                </div>
	                <div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_cat_column"><?php _e('Portfolio Cards / Row', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_cat_column" id="a3_portfolio_enable_cat_column" value="1" />
	                	<span class="description"><?php _e('OFF to use global Columns from Settings Panel.', 'a3_portfolio_shortcodes'); ?></span>
                    </div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_cat_column_container">
						<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
						<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="a3_portfolio_cat_column_div" min="1" max="6" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="a3_portfolio_cat_column"
                                    id="a3_portfolio_cat_column"
                                    type="text"
                                    value="<?php echo esc_attr( $global_column ); ?>"
                                    class="a3rev-ui-slider"
                                    /> <?php _e('columns', 'a3_portfolio_shortcodes'); ?>
							</div>
                        </div>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_cat_items"><?php _e('Number of Items', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="text" class="a3rev-ui-text" size="10" id="a3_portfolio_cat_items" name="a3_portfolio_cat_items" value="" style="width:50px;" />
						<span class="description"><?php _e('Leave empty for get all items.', 'a3_portfolio_shortcodes'); ?></span>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_cat_navbar"><?php _e('Show Nav Bar', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_cat_navbar" id="a3_portfolio_enable_cat_navbar" value="1" />
					</div>
				</div>
	            <div style="clear:both;height:0px"></div>
	            <p>
	            	<input type="button" class="button button-primary" value="<?php _e( 'Insert Shortcode', 'a3_portfolio_shortcodes' ); ?>" onclick="a3_portfolio_category_add_shortcode();" />
	            	<input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e( 'Cancel', 'a3_portfolio_shortcodes' ); ?>" />
				</p>
            </fieldset>

            <fieldset class="a3_portfolio_shortcode_object">
            	<legend style="font-weight:bold; font-size:14px;">
            		<?php _e( 'Insert Portfolio Tags', 'a3_portfolio_shortcodes' ); ?>
            	</legend>
            	<div id="a3-portfolio-content" class="a3-portfolio-content a3-portfolio-shortcode-popup-container" style="text-align:left;">
	                <div class="a3_portfolio_shortcode_row">
	                	<label class="a3_portfolio_shortcode_field" for="a3_portfolio_tag_id"><?php _e( 'Select Portfolio Tags', 'a3_portfolio_shortcodes' ); ?>:</label>
	                    <select style="width:400px" class="a3rev-ui-multiselect chzn-select" id="a3_portfolio_tag_id" name="a3_portfolio_tag_id" multiple="multiple" size="5" >
	                    <?php
						if ( is_array( $all_tags ) && count( $all_tags ) > 0 ) {
							foreach ( $all_tags as $tag ) {
						?>
	                    	<option value="<?php echo esc_attr( $tag->term_id ); ?>" ><?php echo $tag->name; ?></option>
	                    <?php

							}
						}
	                    ?>
	                    </select>
	                </div>
	                <div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_tag_column"><?php _e('Portfolio Cards / Row', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_tag_column" id="a3_portfolio_enable_tag_column" value="1" />
	                	<span class="description"><?php _e('OFF to use global Columns from Settings Panel.', 'a3_portfolio_shortcodes'); ?></span>
                    </div>
					<div class="a3_portfolio_shortcode_row a3_portfolio_tag_column_container">
						<label class="a3_portfolio_shortcode_no_field">&nbsp;</label>
						<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="a3_portfolio_tag_column_div" min="1" max="6" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="a3_portfolio_tag_column"
                                    id="a3_portfolio_tag_column"
                                    type="text"
                                    value="<?php echo esc_attr( $global_column ); ?>"
                                    class="a3rev-ui-slider"
                                    /> <?php _e('columns', 'a3_portfolio_shortcodes'); ?>
							</div>
                        </div>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_tag_items"><?php _e('Number of Items', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="text" class="a3rev-ui-text" size="10" id="a3_portfolio_tag_items" name="a3_portfolio_tag_items" value="" style="width:50px;" />
						<span class="description"><?php _e('Leave empty for get all items.', 'a3_portfolio_shortcodes'); ?></span>
					</div>
					<div class="a3_portfolio_shortcode_row">
						<label class="a3_portfolio_shortcode_field" for="a3_portfolio_enable_tag_navbar"><?php _e('Show Nav Bar', 'a3_portfolio_shortcodes'); ?>:</label>
						<input type="checkbox" class="a3rev-ui-onoff_checkbox" name="a3_portfolio_enable_tag_navbar" id="a3_portfolio_enable_tag_navbar" value="1" />
					</div>
				</div>
	            <div style="clear:both;height:0px"></div>
	            <p>
	            	<input type="button" class="button button-primary" value="<?php _e( 'Insert Shortcode', 'a3_portfolio_shortcodes' ); ?>" onclick="a3_portfolio_tag_add_shortcode();" />
	            	<input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e( 'Cancel', 'a3_portfolio_shortcodes' ); ?>" />
				</p>
            </fieldset>
            </div>
		</div>
		<style>
			.a3_portfolio_shortcode_field, .a3_portfolio_shortcode_no_field {
				width: 180px;
				display: inline-block;
				float: left;
				clear: left;
				margin-bottom: 10px;
			}
			.a3_portfolio_shortcode_row {
				width: 100%;
				float: left;
				clear: both;
				margin: 5px 0;
			}
			.a3_portfolio_shortcode_object {
				border:1px solid #DFDFDF;
				padding:0 20px;
				background: #FFF;
				margin-top:0px;
				margin-bottom: 40px;
			}
			@media only screen and (max-width: 640px) {
				.a3_portfolio_shortcode_field {
					width: 100%;
				}
				.a3_portfolio_shortcode_row {
					margin: 10px 0;
				}
				.a3_portfolio_shortcode_no_field {
					display: none;
				}
			}
		</style>
		<script type="text/javascript">
		(function($) {
		$(document).ready(function() {

			if ( $("input#a3_portfolio_enable_align").is(':checked')) {
				$(".a3_portfolio_align_container").slideDown();
			} else {
				$(".a3_portfolio_align_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_fixed_width").is(':checked')) {
				$(".a3_portfolio_fixed_width_container").slideDown();
			} else {
				$(".a3_portfolio_fixed_width_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_percent_width").is(':checked')) {
				$(".a3_portfolio_percent_width_container").slideDown();
			} else {
				$(".a3_portfolio_percent_width_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_column").is(':checked')) {
				$(".a3_portfolio_column_container").slideDown();
			} else {
				$(".a3_portfolio_column_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_padding").is(':checked')) {
				$(".a3_portfolio_padding_container").slideDown();
			} else {
				$(".a3_portfolio_padding_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_rs_column").is(':checked')) {
				$(".a3_portfolio_rs_column_container").slideDown();
			} else {
				$(".a3_portfolio_rs_column_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_cat_column").is(':checked')) {
				$(".a3_portfolio_cat_column_container").slideDown();
			} else {
				$(".a3_portfolio_cat_column_container").slideUp();
			}

			if ( $("input#a3_portfolio_enable_tag_column").is(':checked')) {
				$(".a3_portfolio_tag_column_container").slideDown();
			} else {
				$(".a3_portfolio_tag_column_container").slideUp();
			}

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_align', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_align_container").slideDown();
				} else {
					$(".a3_portfolio_align_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_radio-switch", 'input[name="a3_portfolio_width_type"]', function( event, value, status ) {
				if ( value == 'fixed' ) {
					$(".a3_portfolio_fixed_width_container").slideDown();
					$(".a3_portfolio_percent_width_container").slideUp();
				} else {
					$(".a3_portfolio_fixed_width_container").slideUp();
					$(".a3_portfolio_percent_width_container").slideDown();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_column', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_column_container").slideDown();
				} else {
					$(".a3_portfolio_column_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_padding', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_padding_container").slideDown();
				} else {
					$(".a3_portfolio_padding_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_rs_column', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_rs_column_container").slideDown();
				} else {
					$(".a3_portfolio_rs_column_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_cat_column', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_cat_column_container").slideDown();
				} else {
					$(".a3_portfolio_cat_column_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '#a3_portfolio_enable_tag_column', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".a3_portfolio_tag_column_container").slideDown();
				} else {
					$(".a3_portfolio_tag_column_container").slideUp();
				}
			});
		});
		})(jQuery);
		</script>
        <script type="text/javascript">
		function a3_portfolio_item_add_shortcode(){
			var selected_portfolio_id = jQuery("#a3_portfolio_id").val();
			if (selected_portfolio_id == '') {
				alert('<?php _e( 'Please select Portfolio Items', 'a3_portfolio_shortcodes' ); ?>');
				return false
			}

			var container_align          = jQuery("#a3_portfolio_align").val();
			var fixed_width              = jQuery("#a3_portfolio_fixed_width").val();
			var percent_width            = jQuery("#a3_portfolio_percent_width").val();
			var container_column         = jQuery("#a3_portfolio_column").val();
			var container_padding_top    = jQuery("#a3_portfolio_padding_top").val();
			var container_padding_bottom = jQuery("#a3_portfolio_padding_bottom").val();
			var container_padding_left   = jQuery("#a3_portfolio_padding_left").val();
			var container_padding_right  = jQuery("#a3_portfolio_padding_right").val();

			var win = window.dialogArguments || opener || parent || top;

			var shortcode = '[a3_portfolio_item ids="' + selected_portfolio_id + '" ';
			if ( jQuery("input#a3_portfolio_enable_align").is(':checked')) {
				shortcode += 'align="' + container_align + '" ';
			}
			if ( jQuery("input#a3_portfolio_enable_fixed_width").is(':checked')) {
				shortcode += 'width="' + fixed_width + 'px" ';
			} else {
				shortcode += 'width="' + percent_width + '%" ';
			}
			if ( jQuery("input#a3_portfolio_enable_column").is(':checked')) {
				shortcode += 'column="' + container_column + '" ';
			}
			if ( jQuery("input#a3_portfolio_enable_padding").is(':checked')) {
				shortcode += 'padding_top="' + container_padding_top + '" ';
				shortcode += 'padding_bottom="' + container_padding_bottom + '" ';
				shortcode += 'padding_left="' + container_padding_left + '" ';
				shortcode += 'padding_right="' + container_padding_right + '" ';
			}
			shortcode += ']';
			win.send_to_editor(shortcode);
		}
		function a3_portfolio_rs_add_shortcode(){

			var display_type     = jQuery("#a3_portfolio_type").val();
			var container_column = jQuery("#a3_portfolio_rs_column").val();
			var number_items     = jQuery("#a3_portfolio_rs_items").val();
			var show_navbar      = 0;

			var win = window.dialogArguments || opener || parent || top;
			var shortcode = '[a3_portfolio_' + display_type + ' ';
			if ( jQuery("input#a3_portfolio_enable_rs_column").is(':checked')) {
				shortcode += 'column="' + container_column + '" ';
			}

			shortcode += 'number_items="' + number_items + '" ';

			if ( jQuery("input#a3_portfolio_enable_rs_navbar").is(':checked')) {
				show_navbar = 1;
			}
			shortcode += 'show_navbar="' + show_navbar + '" ';

			shortcode += ']';
			win.send_to_editor(shortcode);
		}
		function a3_portfolio_category_add_shortcode(){
			var selected_portfolio_category_id = jQuery("#a3_portfolio_category_id").val();
			if (selected_portfolio_category_id == '') {
				alert('<?php _e( 'Please select Portfolio Categories', 'a3_portfolio_shortcodes' ); ?>');
				return false
			}

			var container_column = jQuery("#a3_portfolio_cat_column").val();
			var number_items     = jQuery("#a3_portfolio_cat_items").val();
			var show_navbar      = 0;

			var win = window.dialogArguments || opener || parent || top;
			var shortcode = '[a3_portfolio_category ids="' + selected_portfolio_category_id + '" ';
			if ( jQuery("input#a3_portfolio_enable_cat_column").is(':checked')) {
				shortcode += 'column="' + container_column + '" ';
			}

			shortcode += 'number_items="' + number_items + '" ';

			if ( jQuery("input#a3_portfolio_enable_cat_navbar").is(':checked')) {
				show_navbar = 1;
			}
			shortcode += 'show_navbar="' + show_navbar + '" ';

			shortcode += ']';
			win.send_to_editor(shortcode);
		}
		function a3_portfolio_tag_add_shortcode(){
			var selected_portfolio_tag_id = jQuery("#a3_portfolio_tag_id").val();
			if (selected_portfolio_tag_id == '') {
				alert('<?php _e( 'Please select Portfolio Tags', 'a3_portfolio_shortcodes' ); ?>');
				return false
			}

			var container_column = jQuery("#a3_portfolio_tag_column").val();
			var number_items     = jQuery("#a3_portfolio_tag_items").val();
			var show_navbar      = 0;

			var win = window.dialogArguments || opener || parent || top;
			var shortcode = '[a3_portfolio_tag ids="' + selected_portfolio_tag_id + '" ';
			if ( jQuery("input#a3_portfolio_enable_tag_column").is(':checked')) {
				shortcode += 'column="' + container_column + '" ';
			}

			shortcode += 'number_items="' + number_items + '" ';

			if ( jQuery("input#a3_portfolio_enable_tag_navbar").is(':checked')) {
				show_navbar = 1;
			}
			shortcode += 'show_navbar="' + show_navbar + '" ';

			shortcode += ']';
			win.send_to_editor(shortcode);
		}
		</script>
		<?php
	}

}

}
