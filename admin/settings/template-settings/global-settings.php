<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\Portfolio\FrameWork\Settings {

use A3Rev\Portfolio\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Portfolio General Settings

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

class Global_Panel extends FrameWork\Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'global-settings';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_portfolio_global_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_portfolio_global_settings';
	
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
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Settings successfully saved.', 'a3-portfolio' ),
				'error_message'		=> __( 'Error: Settings can not save.', 'a3-portfolio' ),
				'reset_message'		=> __( 'Settings successfully reseted.', 'a3-portfolio' ),
			);
					
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'after_save_settings' ) );

		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );

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
	/* after_save_settings()
	/* Process when clean on deletion option is un selected */
	/*-----------------------------------------------------------------------------------*/
	public function after_save_settings() {
		if ( ( isset( $_POST['bt_save_settings'] ) || isset( $_POST['bt_reset_settings'] ) ) && get_option( $this->plugin_name . '_clean_on_deletion' ) == 0  )  {
			$uninstallable_plugins = (array) get_option('uninstall_plugins');
			unset($uninstallable_plugins[ $this->plugin_path ]);
			update_option('uninstall_plugins', $uninstallable_plugins);
		}
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
			'name'				=> 'global-settings',
			'label'				=> __( 'Global Settings', 'a3-portfolio' ),
			'callback_function'	=> 'a3_portfolio_global_settings_form',
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
            	'name' 		=> __( 'Plugin Framework Global Settings', 'a3-portfolio' ),
            	'id'		=> 'plugin_framework_global_box',
                'type' 		=> 'heading',
                'first_open'=> true,
                'is_box'	=> true,
           	),

           	array(
           		'name'		=> __( 'Customize Admin Setting Box Display', 'a3-portfolio' ),
           		'desc'		=> __( 'By default each admin panel will open with all Setting Boxes in the CLOSED position.', 'a3-portfolio' ),
                'type' 		=> 'heading',
           	),
           	array(
				'type' 		=> 'onoff_toggle_box',
			),
			array(
           		'name'		=> __( 'Google Fonts', 'a3-portfolio' ),
           		'desc'		=> __( 'By Default Google Fonts are pulled from a static JSON file in this plugin. This file is updated but does not have the latest font releases from Google.', 'a3-portfolio' ),
                'type' 		=> 'heading',
           	),
           	array(
                'type' 		=> 'google_api_key',
           	),
           	array(
            	'name' 		=> __( 'House Keeping', 'a3-portfolio' ),
                'type' 		=> 'heading',
            ),
			array(
				'name' 		=> __( 'Clean up on Deletion', 'a3-portfolio' ),
				'desc' 		=> __( 'On deletion (not deactivate) the plugin will completely remove all tables and data it created, leaving no trace it was ever here.', 'a3-portfolio' ),
				'id' 		=> $this->plugin_name . '_clean_on_deletion',
				'type' 		=> 'onoff_checkbox',
				'default'	=> '0',
				'separate_option'	=> true,
				'free_version'		=> true,
				'checked_value'		=> '1',
				'unchecked_value'	=> '0',
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),
			array(
				'name' 		=> __( 'Image Watermarks', 'a3-portfolio' ),
                'type' 		=> 'heading',
                'desc' 		=> __( 'To add Watermarks to the Portfolio item images, please use the free <a target="_blank" href="https://wordpress.org/plugins/easy-watermark/">Easy Watermark plugin</a>. It is tested 100% compatible with a3 Portfolios.', 'a3-portfolio' ),
           	),

			array(
				'name'		=> __( 'Portfolio Item Images', 'a3-portfolio' ),
				'desc' 		=> __( 'These settings affect the display and dimensions of images in your portfolio - the display on the front-end will still be affected by CSS styles. After changing these settings you may need to regenerate your thumbnails.', 'a3-portfolio' ),
                'type' 		=> 'heading',
                'id'		=> 'portfolio_item_images_box',
                'is_box'	=> true,
           	),
           	array(
				'name' 		=> __( 'Item Card Images', 'a3-portfolio' ),
                'id' 		=> 'item_card_image_size',
				'type' 		=> 'array_textfields',
				'ids'		=> array(
	 								array(  'id' 		=> 'item_card_image_width',
	 										'name' 		=> 'x',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 400 ),

	 								array(  'id' 		=> 'item_card_image_height',
	 										'name' 		=> 'px',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 400 ),
	 							)
			),
			array(
				'name' 		=> '',
				'desc'		=> __( 'Hard Crop?', 'a3-portfolio' ),
				'id' 		=> 'item_card_image_crop',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),

			array(
				'name' 		=> '',
                'type' 		=> 'heading',
           	),
			array(
				'name' 		=> __( 'Gallery Images', 'a3-portfolio' ),
                'id' 		=> 'gallery_image_size',
				'type' 		=> 'array_textfields',
				'ids'		=> array(
	 								array(  'id' 		=> 'gallery_image_width',
	 										'name' 		=> 'x',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 800 ),

	 								array(  'id' 		=> 'gallery_image_height',
	 										'name' 		=> 'px',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 600 ),
	 							)
			),
			array(
				'name' 		=> '',
				'desc'		=> __( 'Hard Crop?', 'a3-portfolio' ),
				'id' 		=> 'gallery_image_crop',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'no',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),

			array(
				'name' 		=> '',
                'type' 		=> 'heading',
           	),
			array(
				'name' 		=> __( 'Gallery Thumbnails', 'a3-portfolio' ),
                'id' 		=> 'gallery_thumbnail_size',
				'type' 		=> 'array_textfields',
				'ids'		=> array(
	 								array(  'id' 		=> 'gallery_thumbnail_width',
	 										'name' 		=> 'x',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 75 ),

	 								array(  'id' 		=> 'gallery_thumbnail_height',
	 										'name' 		=> 'px',
	 										'class' 	=> '',
	 										'css'		=> 'width:40px;',
	 										'default'	=> 75 ),
	 							)
			),
			array(
				'name' 		=> '',
				'desc'		=> __( 'Hard Crop?', 'a3-portfolio' ),
				'id' 		=> 'gallery_thumbnail_crop',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),

			array(
				'name' 		=> '',
                'type' 		=> 'heading',
           	),
			array(
				'name' 		=> __('Item Post Gallery', 'a3-portfolio' ),
				'desc'		=> __( 'Enable Lightbox for Item Post Gallery Images', 'a3-portfolio' ),
				'id' 		=> 'item_post_gallery_lightbox',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 'yes',
				'checked_value'		=> 'yes',
				'unchecked_value'	=> 'no',
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),

        ));
	}

	public function include_script() {
	?>
	<?php
	}
}

}

namespace {

/**
 * a3_portfolio_cards_settings_form()
 * Define the callback function to show subtab content
 */
function a3_portfolio_global_settings_form() {
	global $a3_portfolio_global_settings_panel;
	$a3_portfolio_global_settings_panel->settings_form();
}

}
