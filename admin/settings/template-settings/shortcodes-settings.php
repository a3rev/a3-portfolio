<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\Portfolio\FrameWork\Settings {

use A3Rev\Portfolio\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Portfolio Shortcodes Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class Shortcodes extends FrameWork\Admin_UI
{

	/**
	 * @var string
	 */
	private $parent_tab = 'general';

	/**
	 * @var array
	 */
	private $subtab_data;

	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_portfolio_shortcodes_setting';

	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_portfolio_shortcodes_setting';

	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;

	/**
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * @var array
	 */
	public $form_messages = array();

	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		//$this->subtab_init();

		$this->form_messages = array(
				'success_message'	=> __( 'Settings successfully saved.', 'a3_portfolio_shortcodes' ),
				'error_message'		=> __( 'Error: Settings can not save.', 'a3_portfolio_shortcodes' ),
				'reset_message'		=> __( 'Settings successfully reseted.', 'a3_portfolio_shortcodes' ),
			);

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );

		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );

		add_action( $this->plugin_name . '_settings_portfolio_shortcode_tag_embed_start', array( $this, 'portfolio_shortcode_tag_embed' ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {

		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );

	}

	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		$GLOBALS[$this->plugin_prefix.'admin_interface']->reset_settings( $this->form_fields, $this->option_name, false );
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		$GLOBALS[$this->plugin_prefix.'admin_interface']->get_settings( $this->form_fields, $this->option_name );
	}

	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array (
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {

		$subtab_data = array(
			'name'				=> 'shortcodes-setting',
			'label'				=> __( 'Portfolio Shortcodes', 'a3_portfolio_shortcodes' ),
			'callback_function'	=> 'a3_portfolio_shortcodes_setting_form',
		);

		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;

	}

	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {

		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();

		return $subtabs_array;
	}

	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		$output = '';
		$output .= $GLOBALS[$this->plugin_prefix.'admin_interface']->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );

		return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {

  		// Define settings
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(

     		array(
				'name' => __( 'Portfolio Shortcodes', 'a3_portfolio_shortcodes' ),
                'type' 		=> 'heading',
                'desc' 		=> __( 'From WordPress Visual editor menu on any page or post use the a3 Portfolio Button to insert Single Items, Portfolio Categories or Portfolio tags into any post or page.', 'a3_portfolio_shortcodes' ),
                'id'		=> ''
           	),

           	array(
				'name' => __( 'Embed Full Portfolio', 'a3_portfolio_shortcodes' ),
                'type' 		=> 'heading',
                'desc' 		=> __( 'Add the Portfolio to any post, page or widget area by shortcode or template tag. Embeds without the Portfolio title.', 'a3_portfolio_shortcodes' ),
                'id'		=> 'portfolio_shortcode_tag_embed'
           	),

        ));
	}

	public function portfolio_shortcode_tag_embed() {
	?>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolios_shortcode"><?php echo __( 'Shortcodes', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<p><span id="a3_portfolios_shortcode">[a3_portfolios column="3" number_items="all" show_navbar="1"]</span> : <span class="description"><strong><?php echo __( 'Show main portfolios', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
		<p><span id="a3_portfolios_shortcode">[a3_portfolio_recent column="3" number_items="all" show_navbar="1"]</span> : <span class="description"><strong><?php echo __( 'Show recent portfolios', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
		<p><span id="a3_portfolios_shortcode">[a3_portfolio_sticky column="3" number_items="all" show_navbar="1"]</span> : <span class="description"><strong><?php echo __( 'Show sticky portfolios', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
		<p><span id="a3_portfolios_shortcode">[a3_portfolio_category ids="1,2,3" column="3" number_items="all" show_navbar="1"]</span> : <span class="description"><strong><?php echo __( 'Show portfolios from category IDs', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
		<p><span id="a3_portfolios_shortcode">[a3_portfolio_tag ids="4,5,6" column="3" number_items="all" show_navbar="1"]</span> : <span class="description"><strong><?php echo __( 'Show portfolios from tag IDs', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
		<p><span id="a3_portfolios_shortcode">[a3_portfolio_item ids="1,2,3" column="3" width="100%"]</span> : <span class="description"><strong><?php echo __( 'Show portfolios from portfolio IDs', 'a3_portfolio_shortcodes' ); ?></strong></span></p>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolios_template_tag"><?php echo __( 'Portfolios Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolios_template_tag">&lt;?php a3_portfolios( $column, $number_items, $show_navbar ); ?&gt;</span></div>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolio_recent_template_tag"><?php echo __( 'Portfolio Recent Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolio_recent_template_tag">&lt;?php a3_portfolio_recent( $column, $number_items, $show_navbar ); ?&gt;</span></div>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolio_sticky_template_tag"><?php echo __( 'Portfolio Sticky Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolio_sticky_template_tag">&lt;?php a3_portfolio_sticky( $column, $number_items, $show_navbar ); ?&gt;</span></div>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolio_categories_template_tag"><?php echo __( 'Portfolio Categories Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolio_categories_template_tag">&lt;?php a3_portfolio_categories( $cat_ids, $column, $number_items, $show_navbar ); ?&gt;</span></div>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolio_tags_template_tag"><?php echo __( 'Portfolio Tags Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolio_tags_template_tag">&lt;?php a3_portfolio_tags( $tag_ids, $column, $number_items, $show_navbar ); ?&gt;</span></div>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc"><label for="a3_portfolio_items_template_tag"><?php echo __( 'Portfolio Card Items Template Tag', 'a3_portfolio_shortcodes' ); ?></label></th>
	<td class="forminp forminp-text">
		<div><span id="a3_portfolio_items_template_tag">&lt;?php a3_portfolio_items( $portfolio_ids, $column, $number_items, $show_navbar, $custom_style ); ?&gt;</span></div>
	</td>
</tr>
	<?php
	}

	public function include_script() {
	}
}

}

namespace {

/**
 * a3_portfolio_item_expander_settings_button_form()
 * Define the callback function to show subtab content
 */
function a3_portfolio_shortcodes_setting_form() {
	global $a3_portfolio_shortcodes_panel;
	$a3_portfolio_shortcodes_panel->settings_form();
}

}
