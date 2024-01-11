<?php

namespace A3Rev;

class Portfolio {

	/**
	* Default contructor
	*/
	public function __construct() {
		
		// Include a3 framework files
		$this->includes_framework();
		//add_action( 'plugins_loaded', array( $this, 'includes_framework' ), 1 );

		// Include required files
		$this->includes();

		add_action( 'plugins_loaded', function() {
			if ( ! defined( 'A3_PORTFOLIO_TRAVIS' ) ) {
				a3_portfolio_set_global_page();
			}
		} );

		add_action( 'init', array( $this, 'plugin_init' ), 8 );

		// Register Widgets
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	public function includes_framework() {
		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_interface'] = new Portfolio\FrameWork\Admin_Interface();

		do_action( 'a3_portfolios_before_include_admin_page' );

		global $a3_portfolio_settings_page;
		$a3_portfolio_settings_page = new Portfolio\FrameWork\Pages\Settings();

		global $a3_portfolio_shortcodes_page;
		$a3_portfolio_shortcodes_page = new Portfolio\FrameWork\Pages\Shortcodes();

		// Remove hook from shortcode addon
		global $a3_portfolio_shortcodes_addon;
		if ( $a3_portfolio_shortcodes_addon ) {
			remove_action( 'a3_portfolios_after_include_admin_page', array( $a3_portfolio_shortcodes_addon, 'includes_admin_page' ) );
		}

		do_action( 'a3_portfolios_after_include_admin_page' );

		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_init'] = new Portfolio\FrameWork\Admin_Init();

		$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_init']->init();

	}

	public function includes() {

		do_action( 'a3_portfolios_before_include_files' );

		include( A3_PORTFOLIO_DIR . '/includes/attributes/a3-portfolio-attribute-functions.php' );

		new Portfolio\BlockTemplatesController();

		new Portfolio\AJAX();

		global $a3_portfolio_wpml;
		$a3_portfolio_wpml = new Portfolio\WPML();

		global $a3_portfolio_category_taxonomy;
		$a3_portfolio_category_taxonomy = new Portfolio\Taxonomy\Category();

		global $a3_portfolio_attribute_taxonomies;
		$a3_portfolio_attribute_taxonomies = new Portfolio\Attribute_Taxonomies();

		global $a3_portfolio_post_types;
		$a3_portfolio_post_types = new Portfolio\Post_Types();

		include( A3_PORTFOLIO_DIR . '/includes/frontend/a3-portfolio-template-functions.php' );

		global $a3_portfolio_template_loader;
		$a3_portfolio_template_loader = new Portfolio\Frontend\Template_Loader();

		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-core-functions.php' );
		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-shortcode-functions.php' );

		if ( is_admin() ) {
			global $a3_portfolio_backend_scripts;
			$a3_portfolio_backend_scripts = new Portfolio\Backend\Scripts();

			new Portfolio\Attributes();

			global $a3_portfolio_tag_taxonomy;
			$a3_portfolio_tag_taxonomy = new Portfolio\Taxonomy\Tag();

			new Portfolio\Addons();
			new Portfolio\Metabox();
			new Portfolio\Duplicate();
		}

		if ( is_admin() ) {
			$current_url = add_query_arg( 'custom-portfolio', false );
			if ( ! defined( 'A3_PORTFOLIO_SHORTCODES_KEY' ) && ! class_exists( 'A3_Portfolio_Shortcodes_Backend_Hooks' ) && ( false !== stristr( $current_url, 'post.php' ) || false !== stristr( $current_url, 'edit-tags.php' ) ) ) {

				global $a3_portfolio_shortcodes_backend_hooks;
				$a3_portfolio_shortcodes_backend_hooks = new Portfolio\Backend\Shortcode\Hooks();
			}
		}

		global $a3_portfolio_cookies;
		$a3_portfolio_cookies = new Portfolio\Cookies();

		global $a3_portfolio_frontend_scripts;
		$a3_portfolio_frontend_scripts = new Portfolio\Frontend\Scripts();

		include( A3_PORTFOLIO_DIR . '/includes/frontend/a3-portfolio-template-hooks.php' );
		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-shortcodes.php' );

		if ( ! is_admin() && ! defined( 'A3_PORTFOLIO_SHORTCODES_KEY' ) && ! class_exists( 'A3_Portfolio_Shortcode_Display' ) ) {
			global $a3_portfolio_shortcode_display;
			$a3_portfolio_shortcode_display = new Portfolio\Shortcode\Display();
		}

		// Include Permalinks Structure
		global $a3_portfolio_permalinks_structure;
		$a3_portfolio_permalinks_structure = new Portfolio\Backend\Permalinks\Structure();

		// Gutenberg Blocks
		new Portfolio\Blocks();

		global $a3_portfolio_blocks_styles;
		$a3_portfolio_blocks_styles = new Portfolio\Blocks\Styles();

		do_action( 'a3_portfolios_after_include_files' );
	}

	public function plugin_activated(){
		update_option( 'a3_portfolio_version', A3_PORTFOLIO_VERSION );

		// Install Database
		$a3_portfolio_data = new Portfolio\Data();
		$a3_portfolio_data->install_database();

		$portfolio_page_id_created = a3_portfolio_create_page( _x('portfolios', 'page_slug', 'a3-portfolio' ), '', __('Portfolios', 'a3-portfolio' ), '[portfoliopage]' );
		update_option( 'portfolio_page_id', $portfolio_page_id_created );

		// Create Portfolio page for languages support by WPML
		a3_portfolio_auto_create_page_for_wpml( $portfolio_page_id_created, _x('portfolios', 'page_slug', 'a3-portfolio' ), __('Portfolios', 'a3-portfolio' ), '[portfoliopage]' );

		update_option('a3_portfolio_just_installed', 'yes' );

	}

	public function plugin_init() {
		global $a3_portfolio_post_types;

		// Add Portfolio Image Sizes
		$a3_portfolio_post_types->register_image_sizes();

		// Register Post Type
		$a3_portfolio_post_types->register_post_type();

		if ( 'yes' === get_option( 'a3_portfolio_just_installed', 'no' ) ) {
			// Set Settings Default from Admin Init
			$GLOBALS[A3_PORTFOLIO_PREFIX.'admin_init']->set_default_settings();

			delete_metadata( 'user', 0, $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_init']->plugin_name . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

			delete_option( 'a3_portfolio_just_installed' );
		}

		a3_portfolio_plugin_textdomain();

		// Upgrade Plugin
		$this->upgrade_plugin();
	}

	public function register_widget() {
		register_widget( '\A3Rev\Portfolio\Widget\Categories' );
		register_widget( '\A3Rev\Portfolio\Widget\Tags' );
		register_widget( '\A3Rev\Portfolio\Widget\Recently_Viewed' );
		register_widget( '\A3Rev\Portfolio\Widget\Attribute_Filter' );
	}

	public static function upgrade_plugin() {

		// Upgrade to 1.6.0
		if ( version_compare( get_option('a3_portfolio_version'), '1.6.0') === -1 ) {
			update_option('a3_portfolio_version', '1.6.0');
			update_option('a3_portfolios_style_version', time() );
		}

		// Upgrade to 2.0.0
		if ( version_compare( get_option('a3_portfolio_version'), '2.0.0' ) === -1 ) {
			update_option('a3_portfolio_version', '2.0.0');
			include( A3_PORTFOLIO_DIR. '/includes/updates/update-2.0.0.php' );
		}

		// Upgrade to 2.1.0
		if ( version_compare( get_option('a3_portfolio_version'), '2.1.0' ) === -1 ) {
			update_option('a3_portfolio_version', '2.1.0');
			include( A3_PORTFOLIO_DIR. '/includes/updates/update-2.1.0.php' );
		}

		// Upgrade to 2.2.0
		if ( version_compare( get_option('a3_portfolio_version'), '2.2.0' ) === -1 ) {
			update_option('a3_portfolio_version', '2.2.0');
			include( A3_PORTFOLIO_DIR. '/includes/updates/update-2.2.0.php' );
		}

		// Upgrade to 2.4.0
		if ( version_compare( get_option('a3_portfolio_version'), '2.4.0' ) === -1 ) {
			update_option('a3_portfolio_version', '2.4.0');
			include( A3_PORTFOLIO_DIR. '/includes/updates/update-2.4.0.php' );
		}

		update_option( 'a3_portfolio_version', A3_PORTFOLIO_VERSION );
	}
}
