<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\Portfolio\FrameWork\Pages {

use A3Rev\Portfolio\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Portfolio Settings Page

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

class Settings extends FrameWork\Admin_UI
{
	/**
	 * @var string
	 */
	private $menu_slug = 'a3-portfolios-settings';

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
			'page_title'		=> __('General Settings', 'a3-portfolio' ),
			'menu_title'		=> __('General Settings', 'a3-portfolio' ),
			'capability'		=> 'manage_options',
			'menu_slug'			=> $this->menu_slug,
			'function'			=> 'a3_portfolio_settings_page_show',
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
		global $a3_portfolio_global_settings_tab;
		$a3_portfolio_global_settings_tab = new FrameWork\Tabs\Global_Settings();

		global $a3_portfolio_item_cards_tab;
		$a3_portfolio_item_cards_tab = new FrameWork\Tabs\Item_Cards();

		global $a3_portfolio_global_item_expander_tab;
		$a3_portfolio_global_item_expander_tab = new FrameWork\Tabs\Item_Expander();

		global $a3_portfolio_item_posts_title_tab;
		$a3_portfolio_item_posts_title_tab = new FrameWork\Tabs\Item_Posts();
	}

	/*-----------------------------------------------------------------------------------*/
	/* admin_settings_page() */
	/* Show Settings Page */
	/*-----------------------------------------------------------------------------------*/
	public function admin_settings_page() {
		$GLOBALS[$this->plugin_prefix.'admin_init']->admin_settings_page( $this->page_data() );
	}

}

}

// global code
namespace {

/**
 * a3_portfolio_settings_page_show()
 * Define the callback function to show page content
 */
function a3_portfolio_settings_page_show() {
	global $a3_portfolio_settings_page;
	$a3_portfolio_settings_page->admin_settings_page();
}

function callback_a3_portfolio_settings_page_show() {
	global $a3_portfolio_global_settings_tab;
	$a3_portfolio_global_settings_tab->tab_manager();
}

}
