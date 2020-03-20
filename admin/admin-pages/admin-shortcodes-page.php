<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\Portfolio\FrameWork\Pages {

use A3Rev\Portfolio\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Portfolio Shortcodes Settings Page

TABLE OF CONTENTS

- var menu_slug
- var page_data

- __construct()
- page_init()
- page_data()
- add_admin_menu()
- tabs_include()
- admin_settings_page()

-----------------------------------------------------------------------------------*/

class Shortcodes extends FrameWork\Admin_UI
{
	/**
	 * @var string
	 */
	private $menu_slug = 'a3-portfolio-shortcodes';

	/**
	 * @var array
	 */
	private $page_data;

	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->page_init();
		$this->tabs_include();
	}

	/*-----------------------------------------------------------------------------------*/
	/* page_init() */
	/* Page Init */
	/*-----------------------------------------------------------------------------------*/
	public function page_init() {

		add_filter( $this->plugin_name . '_add_admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* page_data() */
	/* Get Page Data */
	/*-----------------------------------------------------------------------------------*/
	public function page_data() {



		$page_data = array(
			'type'				=> 'submenu',
			'parent_slug'		=> 'edit.php?post_type=a3-portfolio',
			'page_title'		=> __('Shortcodes','a3_portfolio_shortcodes'),
			'menu_title'		=> __('Shortcodes','a3_portfolio_shortcodes'),
			'capability'		=> 'manage_options',
			'menu_slug'			=> $this->menu_slug,
			'function'			=> 'a3_portfolio_shortcodes_page_show',
			'admin_url'			=> 'edit.php?post_type=a3-portfolio',
			'callback_function' => '',
			'script_function' 	=> '',
			'view_doc'			=> '',
		);

		if ( $this->page_data ) return $this->page_data;
		return $this->page_data = $page_data;

	}

	/*-----------------------------------------------------------------------------------*/
	/* add_admin_menu() */
	/* Add This page to menu on left sidebar */
	/*-----------------------------------------------------------------------------------*/
	public function add_admin_menu( $admin_menu ) {

		if ( ! is_array( $admin_menu ) ) $admin_menu = array();
		$admin_menu[] = $this->page_data();

		return $admin_menu;
	}

	/*-----------------------------------------------------------------------------------*/
	/* tabs_include() */
	/* Include all tabs into this page
	/*-----------------------------------------------------------------------------------*/
	public function tabs_include() {
		global $a3_portfolio_shortcodes_general_tab;
		$a3_portfolio_shortcodes_general_tab = new FrameWork\Tabs\Shortcodes();
	}

	/*-----------------------------------------------------------------------------------*/
	/* admin_settings_page() */
	/* Show Settings Page */
	/*-----------------------------------------------------------------------------------*/
	public function admin_settings_page() {
		//$GLOBALS[$this->plugin_prefix.'admin_init']->admin_settings_page( $this->page_data() );

		global $a3_portfolio_shortcodes_panel;

		$a3_portfolio_shortcodes_panel->settings_form();
	}

}

}

// global code
namespace {

/**
 * a3_portfolio_shortcodes_page_show()
 * Define the callback function to show page content
 */
function a3_portfolio_shortcodes_page_show() {
	global $a3_portfolio_shortcodes_page;
	$a3_portfolio_shortcodes_page->admin_settings_page();
}

}
