<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class A3_Portfolio_Backend_Scripts
{
	public function __construct() {
		if ( is_admin() ) {
			// Add custom style to dashboard
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

			// Add style for Portfolio Add-ons page
			if ( isset( $_GET['post_type'] ) && 'a3-portfolio' == $_GET['post_type'] ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'load_addons_page_scripts' ) );
			}

			// Add upgrade notice to Dashboard pages
			global $a3_portfolio_admin_init;
			add_filter( $a3_portfolio_admin_init->plugin_name . '_plugin_extension_boxes', array( $this, 'plugin_extension_box' ) );

			// Add text on right of Visit the plugin on Plugin manager page
			add_filter( 'plugin_row_meta', array( $this, 'plugin_extra_links'), 10, 2 );

			// Add extra link on left of Deactivate link on Plugin manager page
			add_action( 'plugin_action_links_'.A3_PORTFOLIO_NAME, array( $this, 'settings_plugin_links' ) );
		}
	}

	public function load_scripts() {
		// Add custom style to dashboard
		wp_enqueue_style( 'a3rev-wp-admin-style', A3_PORTFOLIO_CSS_URL . '/a3_wp_admin.css' );

		// Add admin sidebar menu css
		wp_enqueue_style( 'a3rev-admin-a3-portfolio-sidebar-menu-style', A3_PORTFOLIO_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public function load_addons_page_scripts() {
		wp_enqueue_style( 'a3-portfolio-addons-style', A3_PORTFOLIO_CSS_URL . '/a3.portfolio.addons.admin.css' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'a3-portfolio-addons-style-rtl', A3_PORTFOLIO_CSS_URL . '/a3.portfolio.addons.admin.rtl.css' );
		}
	}

	public function plugin_extension_box( $boxes = array() ) {
		$support_box = '<a href="https://wordpress.org/support/plugin/a3-portfolio/" target="_blank" alt="'.__('Go to Support Forum', 'a3_portfolios').'"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		/*$free_wordpress_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WordPress Plugins', 'a3_portfolios').'"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/free-wordpress-plugins.png" /></a>';

		$boxes[] = array(
			'content' => $free_wordpress_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_woocommerce_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WooCommerce Plugins', 'a3_portfolios').'"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/free-woocommerce-plugins.png" /></a>';

		$boxes[] = array(
			'content' => $free_woocommerce_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

        $review_box = '<div style="margin-bottom: 5px; font-size: 12px;"><strong>' . __('Is this plugin is just what you needed? If so', 'a3_portfolios') . '</strong></div>';
        $review_box .= '<a href="https://wordpress.org/support/view/plugin-reviews/a3-portfolio#postform" target="_blank" alt="'.__('Submit Review for Plugin on WordPress', 'a3_portfolios').'"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/a-5-star-rating-would-be-appreciated.png" /></a>';

        $boxes[] = array(
            'content' => $review_box,
            'css' => 'border: none; padding: 0; background: none;'
        );

        $connect_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via','a3_portfolios') . '</div>';
		$connect_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'a3_portfolios').'" style="margin-right: 5px;"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/follow-facebook.png" /></a> ';
		$connect_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'a3_portfolios').'"><img src="'.A3_PORTFOLIO_IMAGES_URL.'/follow-twitter.png" /></a>';

		$boxes[] = array(
			'content' => $connect_box,
			'css' => 'border-color: #3a5795;'
		);*/

		return $boxes;
	}

	public function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != A3_PORTFOLIO_NAME ) {
			return $links;
		}
		$links[] = '<a href="https://wordpress.org/support/plugin/a3-portfolio" target="_blank">'.__('Support', 'a3_portfolios').'</a>';
		return $links;
	}

	public function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="edit.php?post_type=a3-portfolio&page=a3-portfolios-settings">' . __( 'Settings', 'a3_portfolios' ) . '</a>' ), $actions );

		return $actions;
	}

}

global $a3_portfolio_backend_scripts;
$a3_portfolio_backend_scripts = new A3_Portfolio_Backend_Scripts();
?>