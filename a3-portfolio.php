<?php
/*
Plugin Name: a3 Portfolio
Description: Creates a beautiful fully mobile responsive, fully customizable, Google images style portfolio to showcase your work.
Version: 2.8.0
Author: a3rev Software
Author URI: https://a3rev.com/
Requires at least: 4.6
Tested up to: 5.2.3
Text Domain: a3-portfolio
Domain Path: /languages
License: GPLv2 or later
	Copyright © 2011 a3 Revolution Software Development team
	a3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('A3_PORTFOLIO_FILE_PATH', dirname(__FILE__));
define('A3_PORTFOLIO_DIR_NAME', basename(A3_PORTFOLIO_FILE_PATH));
define('A3_PORTFOLIO_FOLDER', dirname(plugin_basename(__FILE__)));
define('A3_PORTFOLIO_NAME', plugin_basename(__FILE__));
define('A3_PORTFOLIO_URL', str_replace(array('http:','https:'), '', untrailingslashit(plugins_url('/', __FILE__))));
define('A3_PORTFOLIO_DIR', WP_PLUGIN_DIR . '/' . A3_PORTFOLIO_FOLDER);
define('A3_PORTFOLIO_JS_URL', A3_PORTFOLIO_URL . '/assets/js');
define('A3_PORTFOLIO_CSS_URL', A3_PORTFOLIO_URL . '/assets/css');
define('A3_PORTFOLIO_IMAGES_URL', A3_PORTFOLIO_URL . '/assets/images');
define('A3_PORTFOLIO_TEMPLATE_PATH', A3_PORTFOLIO_FILE_PATH . '/templates');
define('A3_PORTFOLIO_TEMPLATE_CSS_URL', A3_PORTFOLIO_URL . '/templates/css');
define('A3_PORTFOLIO_TEMPLATE_IMAGES_URL', A3_PORTFOLIO_URL . '/templates/images');

define( 'A3_PORTFOLIO_KEY', 'a3_portfolios' );
define( 'A3_PORTFOLIO_VERSION', '2.8.0' );
define( 'A3_PORTFOLIO_G_FONTS', true );

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/a3-portfolio/a3-portfolio-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/a3-portfolio-LOCALE.mo
 * 	 	- /wp-content/plugins/a3-portfolio/languages/a3-portfolio-LOCALE.mo (which if not found falls back to)
 */
function a3_portfolio_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'a3-portfolio' );

	load_textdomain( 'a3-portfolio', WP_LANG_DIR . '/a3-portfolio/a3-portfolio-' . $locale . '.mo' );
	load_plugin_textdomain( 'a3-portfolio', false, A3_PORTFOLIO_FOLDER.'/languages' );
}

include ( 'admin/plugin-init.php' );

// Compatibilities
include ( 'includes/compatibilities/divi-theme.php' );

/**
 * Call when the plugin is activated
 */
global $a3_portfolio;
register_activation_hook(__FILE__, array( $a3_portfolio, 'plugin_activated' ) );
?>