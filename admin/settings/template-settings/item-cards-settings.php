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

class Item_Cards extends FrameWork\Admin_UI
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
	public $option_name = 'a3_portfolio_item_cards_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_portfolio_item_cards_settings';
	
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
				'success_message'	=> __( 'Item Cards Settings successfully saved.', 'a3-portfolio' ),
				'error_message'		=> __( 'Error: Item Cards Settings can not save.', 'a3-portfolio' ),
				'reset_message'		=> __( 'Item Cards Settings successfully reseted.', 'a3-portfolio' ),
			);
					
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

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
			'name'				=> 'item-cards',
			'label'				=> __( 'Item Cards', 'a3-portfolio' ),
			'callback_function'	=> 'a3_portfolio_item_cards_settings_form',
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
				'name' 		=> __( 'Item Cards Per Row', 'a3-portfolio' ),
                'type' 		=> 'heading',
                'id'		=> 'item_cards_per_row_box',
                'is_box'	=> true,
           	),
			array(  
				'name' 		=> __( 'Maximum Items Per Row', 'a3-portfolio' ),
				'desc' 		=> __( 'Maximum Items to show per row in larger screens.', 'a3-portfolio' ),
				'id' 		=> 'portfolio_items_per_row',
				'type' 		=> 'slider',
				'default'	=> 4,
				'min'		=> 1,
				'max'		=> 6,
				'increment'	=> 1,
			),

			array(
				'name' 		=> __( 'Item Card Image', 'a3-portfolio' ),
				'type' 		=> 'heading',
				'id'		=> 'item_card_image_box',
                'is_box'	=> true,
           	),
           	array(
				'name' 		=> __( 'Image Display Height', 'a3-portfolio' ),
				'id' 		=> 'portfolio_card_image_height',
				'class'		=> 'portfolio_card_image_height',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'dynamic',
				'checked_value'		=> 'fixed',
				'unchecked_value'	=> 'dynamic',
				'checked_label'		=> __( 'FIXED', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'DYNAMIC', 'a3-portfolio' ),
			),
			array(
				'name'		=> '',
                'type' 		=> 'heading',
                'class'		=> 'portfolio_card_image_height_fixed_container',
           	),
           	array(
				'name' 		=> __( 'Image Height as a % of Width', 'a3-portfolio' ),
				'id' 		=> 'portfolio_card_image_height_fixed',
				'type' 		=> 'slider',
				'default'	=> 80,
				'min'		=> 50,
				'max'		=> 100,
				'increment'	=> 1,
			),
			array(
				'name'		=> '',
                'type' 		=> 'heading',
                'class'		=> 'portfolio_card_image_link_opens_container',
           	),
           	array(
				'name' 		=> __( 'Image Link Opens', 'a3-portfolio' ),
				'id' 		=> 'cards_image_opens',
				'class'		=> 'cards_image_opens',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'item_expander',
				'checked_value'		=> 'item_post',
				'unchecked_value'	=> 'item_expander',
				'checked_label'		=> __( 'Item Post', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'Item Expander', 'a3-portfolio' ),
			),

			array(
				'name'		=> __( 'Item Card Title', 'a3-portfolio' ),
				'desc' 		=> '',
				'id'		=> 'cards_title_type_heading',
                'type' 		=> 'heading',
                'is_box'	=> true,
           	),
           	array(
           		'name'		=> '',
				'class'		=> 'cards_title_type_heading',
                'type' 		=> 'heading',
           	),
     		array(
				'name' 		=> __( 'Item Title Position', 'a3-portfolio' ),
				'id' 		=> 'cards_title_type',
				'class'		=> 'cards_title_type',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'hover',
				'checked_value'		=> 'hover',
				'unchecked_value'	=> 'under',
				'checked_label'		=> __( 'On Hover', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'Under Image', 'a3-portfolio' ),
			),
			array(
                'type' 		=> 'heading',
           	),
			array(
				'name' 		=> __( 'Title Link Opens', 'a3-portfolio' ),
				'id' 		=> 'cards_title_opens',
				'class'		=> 'cards_title_opens',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'item_post',
				'checked_value'		=> 'item_post',
				'unchecked_value'	=> 'item_expander',
				'checked_label'		=> __( 'Item Post', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'Item Expander', 'a3-portfolio' ),
			),

     		array(
				'name'		=> __( 'Item Card Description', 'a3-portfolio' ),
                'type' 		=> 'heading',
                'id'		=> 'item_card_description_box',
                'is_box'	=> true,
           	),
     		array(
				'name' 		=> __( 'Item Card Description', 'a3-portfolio' ),
				'desc'		=> __( 'ON to show the description on the Item Card under the image', 'a3-portfolio' ),
				'id' 		=> 'enable_cards_description',
				'type' 		=> 'onoff_checkbox',
				'class'		=> 'enable_cards_description',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),
			array(
				'name'		=> '',
                'type' 		=> 'heading',
                'class'		=> 'cards_description_container',
           	),
     		array(
				'name' 		=> __( 'Description Height', 'a3-portfolio' ),
				'id' 		=> 'cards_description_line_height',
				'type' 		=> 'select',
				'default'	=> '2',
				'options'	=> array(
					'1'		=> __( '1 Row', 'a3-portfolio' ),
					'2'		=> __( '2 Rows', 'a3-portfolio' ),
					'3'		=> __( '3 Rows', 'a3-portfolio' ),
					'4'		=> __( '4 Rows', 'a3-portfolio' ),
					'5'		=> __( '5 Rows', 'a3-portfolio' ),
					'6'		=> __( '6 Rows', 'a3-portfolio' ),
				),
				'css' 		=> 'width:160px;',
			),

			array(
				'name'		=> __( 'View More Feature', 'a3-portfolio' ),
                'type' 		=> 'heading',
                'id'		=> 'item_card_view_more_feature_box',
                'is_box'	=> true,
           	),
     		array(
				'name' 		=> __( 'Item Card View More', 'a3-portfolio' ),
				'desc'		=> __( 'ON to show the view more button on the Item Card under the description', 'a3-portfolio' ),
				'id' 		=> 'enable_cards_viewmore',
				'class'		=> 'enable_cards_viewmore',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-portfolio' ),
			),
			array(
				'name'		=> '',
                'type' 		=> 'heading',
                'class'		=> 'item_card_view_more_link_opens_container',
           	),
           	array(
				'name' 		=> __( 'View More Link Opens', 'a3-portfolio' ),
				'id' 		=> 'cards_viewmore_opens',
				'class'		=> 'cards_viewmore_opens',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'item_expander',
				'checked_value'		=> 'item_post',
				'unchecked_value'	=> 'item_expander',
				'checked_label'		=> __( 'Item Post', 'a3-portfolio' ),
				'unchecked_label' 	=> __( 'Item Expander', 'a3-portfolio' ),
			),

        ));
	}

	public function include_script() {
	?>
	<script>
		(function($) {
		$(document).ready(function() {

			if ( $("input.portfolio_card_image_height").is(':checked')) {
				$(".portfolio_card_image_height_fixed_container").slideDown();
			} else {
				$(".portfolio_card_image_height_fixed_container").slideUp();
			}

			if ( $("input.cards_title_type:checked").val() == 'hover') {
				$(".portfolio_card_image_link_opens_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}

			if ( $("input.enable_cards_description").is(':checked')) {
				$(".cards_description_container").slideDown();
			} else {
				$(".cards_description_container").slideUp();
			}

			if ( $("input.enable_cards_viewmore:checked").val() != 1) {
				$(".item_card_view_more_link_opens_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.portfolio_card_image_height', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".portfolio_card_image_height_fixed_container").slideDown();
				} else {
					$(".portfolio_card_image_height_fixed_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.cards_title_type', function( event, value, status ) {
				$(".portfolio_card_image_link_opens_container").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".portfolio_card_image_link_opens_container").slideDown();
				} else {
					$(".portfolio_card_image_link_opens_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.enable_cards_description', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".cards_description_container").slideDown();
				} else {
					$(".cards_description_container").slideUp();
				}
			});

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.enable_cards_viewmore', function( event, value, status ) {
				$(".item_card_view_more_link_opens_container").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".item_card_view_more_link_opens_container").slideDown();
				} else {
					$(".item_card_view_more_link_opens_container").slideUp();
				}
			});
		});
		})(jQuery);
	</script>
	<?php
	}
}

}

namespace {

/**
 * a3_portfolio_item_cards_settings_form()
 * Define the callback function to show subtab content
 */
function a3_portfolio_item_cards_settings_form() {
	global $a3_portfolio_item_cards_settings_panel;
	$a3_portfolio_item_cards_settings_panel->settings_form();
}

}
