<?php
class A3_Portfolio {

	/**
	* Default contructor
	*/
	public function __construct() {
		
		// Include a3 framework files
		$this->includes_framework();
		//add_action( 'plugins_loaded', array( $this, 'includes_framework' ), 1 );

		// Include required files
		$this->includes();

		a3_portfolio_set_global_page();

		add_action( 'init', array( $this, 'plugin_init' ), 8 );

		// Register Widgets
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
	}

	public function includes_framework() {
		include( 'admin-ui.php' );
		include( 'admin-interface.php' );

		do_action( 'a3_portfolios_before_include_admin_page' );

		include( 'admin-pages/admin-settings-page.php' );
		include( 'admin-pages/admin-shortcodes-page.php' );

		// Remove hook from shortcode addon
		global $a3_portfolio_shortcodes_addon;
		if ( $a3_portfolio_shortcodes_addon ) {
			remove_action( 'a3_portfolios_after_include_admin_page', array( $a3_portfolio_shortcodes_addon, 'includes_admin_page' ) );
		}

		do_action( 'a3_portfolios_after_include_admin_page' );

		include( 'admin-init.php' );

		global $a3_portfolio_admin_init;
		$a3_portfolio_admin_init->init();

	}

	public function includes() {

		do_action( 'a3_portfolios_before_include_files' );

		include( A3_PORTFOLIO_DIR . '/includes/attributes/a3-portfolio-attribute-functions.php' );
		include( A3_PORTFOLIO_DIR . '/includes/class-a3-portfolio-ajax.php' );
		include( A3_PORTFOLIO_DIR . '/includes/wpml-support/class-portfolio-wpml.php' );
		include( A3_PORTFOLIO_DIR . '/includes/taxonomies/a3-portfolio-cat.php' );
		include( A3_PORTFOLIO_DIR . '/includes/attributes/a3-portfolio-attribute-taxonomies.php' );
		include( A3_PORTFOLIO_DIR . '/includes/post-types/a3-portfolio-post-types.php' );
		include( A3_PORTFOLIO_DIR . '/includes/frontend/a3-portfolio-template-functions.php' );
		include( A3_PORTFOLIO_DIR . '/includes/frontend/class-a3-portfolio-template-loader.php' );
		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-core-functions.php' );
		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-shortcode-functions.php' );

		if ( is_admin() ) {
			include( A3_PORTFOLIO_DIR . '/includes/backend/class-a3-portfolio-backend-scripts.php' );
			include( A3_PORTFOLIO_DIR . '/includes/attributes/class-a3-portfolio-attributes-page.php' );
			include( A3_PORTFOLIO_DIR . '/includes/taxonomies/a3-portfolio-tag.php' );
			include( A3_PORTFOLIO_DIR . '/includes/addons/class-a3-portfolio-addons-page.php' );
			include( A3_PORTFOLIO_DIR . '/includes/meta-boxes/a3-portfolio-data-metabox.php' );
			include( A3_PORTFOLIO_DIR . '/includes/post-types/a3-portfolio-duplicate.php' );
		}

		if ( is_admin() ) {
			$current_url = add_query_arg( 'custom-portfolio', false );
			if ( ! defined( 'A3_PORTFOLIO_SHORTCODES_KEY' ) && ( false !== stristr( $current_url, 'post.php' ) || false !== stristr( $current_url, 'edit-tags.php' ) ) ) {
				include( A3_PORTFOLIO_DIR . '/includes/backend/class-a3-portfolio-shortcodes-hooks.php' );
			}
		}

		include( A3_PORTFOLIO_DIR . '/includes/widgets/class-portfolio-recently-viewed-widget.php' );
		include( A3_PORTFOLIO_DIR . '/includes/widgets/class-portfolio-categories-widget.php' );
		include( A3_PORTFOLIO_DIR . '/includes/widgets/class-portfolio-tags-widget.php' );
		include( A3_PORTFOLIO_DIR . '/includes/widgets/class-portfolio-attribute-filter-widget.php' );
		include( A3_PORTFOLIO_DIR . '/includes/cookies/class-a3-portfolio-cookies.php' );

		include( A3_PORTFOLIO_DIR . '/includes/frontend/class-a3-portfolio-frontend-scripts.php' );
		include( A3_PORTFOLIO_DIR . '/includes/frontend/a3-portfolio-template-hooks.php' );
		include( A3_PORTFOLIO_DIR . '/includes/a3-portfolio-shortcodes.php' );

		if ( ! is_admin() && ! defined( 'A3_PORTFOLIO_SHORTCODES_KEY' ) ) {
			include( A3_PORTFOLIO_DIR . '/includes/shortcodes/class-shortcodes-display.php' );
		}

		// Include Permalinks Structure
		include( A3_PORTFOLIO_DIR . '/includes/backend/class-a3-portfolio-permalinks-structure.php' );

		// Gutenberg Blocks
		include( A3_PORTFOLIO_DIR . '/src/blocks.php' );

		do_action( 'a3_portfolios_after_include_files' );
	}

	public function plugin_activated(){
		update_option( 'a3_portfolio_version', A3_PORTFOLIO_VERSION );

		// Install Database
		include ( A3_PORTFOLIO_DIR . '/includes/class-a3-portfolio-data.php' );
		global $a3_portfolio_data;
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
			global $a3_portfolio_admin_init;
			$a3_portfolio_admin_init->set_default_settings();

			delete_metadata( 'user', 0, $a3_portfolio_admin_init->plugin_name . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );

			delete_option( 'a3_portfolio_just_installed' );
		}

		a3_portfolio_plugin_textdomain();

		// Upgrade Plugin
		$this->upgrade_plugin();
	}

	public function register_widget() {
		register_widget( 'A3_Portfolio_Categories_Widget' );
		register_widget( 'A3_Portfolio_Tags_Widget' );
		register_widget( 'A3_Portfolio_Recently_Viewed_Widget' );
		register_widget( 'A3_Portfolio_Attribute_Filter_Widget' );
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
			include( A3_PORTFOLIO_DIR . '/includes/class-a3-portfolio-data.php' );
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

global $a3_portfolio;
$a3_portfolio = new A3_Portfolio();
?>