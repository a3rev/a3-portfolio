<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
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

class A3_Portfolio_Global_Item_Expander_Settings extends A3_Portfolio_Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'global-item-expander';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_portfolio_global_item_expander_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_portfolio_global_item_expander_settings';
	
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
				'success_message'	=> __( 'Settings successfully saved.', 'a3_portfolios' ),
				'error_message'		=> __( 'Error: Settings can not save.', 'a3_portfolios' ),
				'reset_message'		=> __( 'Settings successfully reseted.', 'a3_portfolios' ),
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
		global $a3_portfolio_admin_interface;
		
		$a3_portfolio_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $a3_portfolio_admin_interface;
		
		$a3_portfolio_admin_interface->get_settings( $this->form_fields, $this->option_name );
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
			'name'				=> 'global-item-expander',
			'label'				=> __( 'Item Expander', 'a3_portfolios' ),
			'callback_function'	=> 'a3_portfolio_global_item_expander_settings_form',
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
		global $a3_portfolio_admin_interface;
		
		$output = '';
		$output .= $a3_portfolio_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
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
				'name'		=> __( 'Item Expander Post Meta', 'a3_portfolios' ),
				'desc'		=> __( 'Post meta shows under the post name on the expander', 'a3_portfolios' ),
                'type' 		=> 'heading',
                'id'		=> 'item_expander_post_meta_box',
                'is_box'	=> true,
           	),
     		array(
				'name' 		=> __( 'Post Author', 'a3_portfolios' ),
				'desc'		=> __( 'ON to show the Author inside Expander', 'a3_portfolios' ),
				'id' 		=> 'enable_expander_author',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),
			array(
				'name' 		=> __( 'Post Date', 'a3_portfolios' ),
				'desc'		=> __( 'ON to show the Date inside Expander', 'a3_portfolios' ),
				'id' 		=> 'enable_expander_date',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),
			array(
				'name' 		=> __( 'Post Meta Cats', 'a3_portfolios' ),
				'desc'		=> __( 'ON to show the Meta Cats inside Expander', 'a3_portfolios' ),
				'id' 		=> 'enable_expander_meta_cats',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),
			array(
				'name' 		=> __( 'Post Meta Tags', 'a3_portfolios' ),
				'desc'		=> __( 'ON to show the Meta Tags inside Expander', 'a3_portfolios' ),
				'id' 		=> 'enable_expander_meta_tags',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),

			array(
				'name'		=> __( 'Item Expander Social Share', 'a3_portfolios' ),
                'type' 		=> 'heading',
                'id'		=> 'item_expander_social_share_box',
                'is_box'	=> true,
           	),
			array(
				'name' 		=> __( 'Social Share', 'a3_portfolios' ),
				'desc'		=> __( 'ON to show the Social Share inside Expander', 'a3_portfolios' ),
				'id' 		=> 'enable_expander_social',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),

			array(
				'name'		=> __( 'Expander Top Alignment', 'a3_portfolios' ),
				'desc'		=> __( "By default, the item expander will open at the top if the screen or 0px from top. Use the setting below to adjust the distance that the expander opens from the top of the screen to match your requirements in PC's and Laptops", 'a3_portfolios' ),
                'type' 		=> 'heading',
                'id'		=> 'expander_top_alignment_box',
                'is_box'	=> true,
           	),
           	array(
				'name'		=> __( 'Expander Top Alignment', 'a3_portfolios' ),
				'desc' 		=> 'px ' . __( 'from the top of the screen', 'a3_portfolios' ),
				'id' 		=> 'desktop_top_alignment',
				'type' 		=> 'text',
				'css'		=> 'width: 80px;',
				'default'	=> 0
			),
     		array(
				'name' 		=> __( 'Apply to Mobile', 'a3_portfolios' ),
				'id' 		=> 'enable_mobile_top_alignment',
				'type' 		=> 'onoff_checkbox',
				'class'		=> 'enable_mobile_top_alignment',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_portfolios' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_portfolios' ),
			),
			array(
				'name'		=> '',
                'type' 		=> 'heading',
                'class'		=> 'enable_mobile_top_alignment_container',
           	),
			array(
				'name'		=> __( 'Expander Top Alignment', 'a3_portfolios' ),
				'desc' 		=> 'px ' . __( 'from the top of the screen', 'a3_portfolios' ),
				'id' 		=> 'mobile_top_alignment',
				'type' 		=> 'text',
				'css'		=> 'width: 80px;',
				'default'	=> 0
			),

			array(
				'name'		=> __( 'Expander Attribute Table', 'a3_portfolios' ),
				'type' 		=> 'heading',
				'id'		=> 'expander_attribute_table_position_box',
				'is_box'	=> true,
			),
			array(
				'name' => __( 'Attribute Position', 'a3_portfolios' ),
				'id' 		=> 'expander_attribute_position',
				'default'	=> 'above_description',
				'type' 		=> 'onoff_radio',
				'onoff_options' => array(
					array(
						'val' => 'above_description',
						'text' => __( 'Above Description', 'a3_portfolios' ),
						'checked_label'	=> __( 'ON', 'a3_portfolios' ),
						'unchecked_label' => __( 'OFF', 'a3_portfolios' ),
					),
					array(
						'val' => 'bottom_content',
						'text' => __( 'Bottom of Expander Content', 'a3_portfolios' ),
						'checked_label'	=> __( 'ON', 'a3_portfolios' ),
						'unchecked_label' => __( 'OFF', 'a3_portfolios' ),
					),
				),
			),

			array(
				'name'		=> __( 'Expander Image Gallery', 'a3_portfolios' ),
				'type' 		=> 'heading',
				'id'		=> 'expander_thumb_gallery_position_box',
				'is_box'	=> true,
			),
			array(
				'name' => __( 'Thumbnail Position', 'a3_portfolios' ),
				'id' 		=> 'expander_thumb_gallery_position',
				'default'	=> 'right_gallery',
				'type' 		=> 'onoff_radio',
				'onoff_options' => array(
					array(
						'val' => 'right_gallery',
						'text' => __( 'Right of the main gallery image (below item title)', 'a3_portfolios' ),
						'checked_label'	=> __( 'ON', 'a3_portfolios' ),
						'unchecked_label' => __( 'OFF', 'a3_portfolios' ),
					),
					array(
						'val' => 'below_gallery',
						'text' => __( 'Below main gallery image', 'a3_portfolios' ),
						'checked_label'	=> __( 'ON', 'a3_portfolios' ),
						'unchecked_label' => __( 'OFF', 'a3_portfolios' ),
					),
				),
			),

        ));
	}

	public function include_script() {
	?>
	<script>
		(function($) {
		$(document).ready(function() {

			if ( $("input.enable_mobile_top_alignment").is(':checked')) {
				$(".enable_mobile_top_alignment_container").slideDown();
			} else {
				$(".enable_mobile_top_alignment_container").slideUp();
			}

			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.enable_mobile_top_alignment', function( event, value, status ) {
				if ( status == 'true' ) {
					$(".enable_mobile_top_alignment_container").slideDown();
				} else {
					$(".enable_mobile_top_alignment_container").slideUp();
				}
			});
		});
		})(jQuery);
	</script>
	<?php
	}
}

global $a3_portfolio_global_item_expander_settings_panel;
$a3_portfolio_global_item_expander_settings_panel = new A3_Portfolio_Global_Item_Expander_Settings();

/**
 * a3_portfolio_global_item_expander_settings_form()
 * Define the callback function to show subtab content
 */
function a3_portfolio_global_item_expander_settings_form() {
	global $a3_portfolio_global_item_expander_settings_panel;
	$a3_portfolio_global_item_expander_settings_panel->settings_form();
}

?>
