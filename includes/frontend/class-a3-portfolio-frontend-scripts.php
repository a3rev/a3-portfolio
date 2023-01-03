<?php

namespace A3Rev\Portfolio\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Scripts
{
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( $this, 'check_jquery' ), 25 );
		add_action( 'wp_print_scripts', array( $this, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( $this, 'localize_printed_scripts' ), 5 );

		// Include custom Portfolio style
		add_action( 'a3_portfolio_before_include_scripts', array( $this, 'a3_portfolio_custom_style' ), 5 );

		// Include custom Portfolio Single style
		add_action( 'a3_portfolio_before_single_content', array( $this, 'a3_portfolio_custom_single_style' ), 5 );

		// Include Portfolio Single scripts
		add_action( 'a3_portfolio_after_single_content', array( $this, 'a3_portfolio_single_scripts' ), 5 );

		// Include Portfolio Widget scripts
		add_action( 'a3_portfolio_before_recently_widget', array( $this, 'a3_portfolio_widget_scripts' ), 5 );

		// Include Portfolio Attribute Filter Widget scripts
		add_action( 'a3_portfolio_before_attribute_filter_widget', array( $this, 'a3_portfolio_attribute_filter_widget_scripts' ), 10 );

	}

	/**
	 * Get styles for the frontend
	 * @return array
	 */
	public function get_styles() {

		return apply_filters( 'a3_portfolio_enqueue_styles', array(
			'a3-portfolio-layout-css' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.layout.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3-portfolio-widgets-css' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.widget.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3-portfolio-attribute-filter-widget-css' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.filter.widget.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3-portfolio-general-css' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3_colorbox_style' => array(
				'src'     => a3_portfolio_get_css_file_url( 'colorbox/colorbox.css' ),
				'deps'    => '',
				'version' => '1.4.4',
				'media'   => 'all'
			),
		) );
	}

	/**
	 * Get RTL styles for the frontend
	 * @return array
	 */
	public function get_styles_rtl() {

		return apply_filters( 'a3_portfolio_enqueue_styles_rtl', array(
			'a3-portfolio-layout-css-rtl' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.layout.rtl.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3-portfolio-widgets-css-rtl' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.widget.rtl.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
			'a3-portfolio-general-css-rtl' => array(
				'src'     => a3_portfolio_get_css_file_url( 'a3.portfolio.rtl.css' ),
				'deps'    => '',
				'version' => A3_PORTFOLIO_VERSION,
				'media'   => 'all'
			),
		) );
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		global $post;
		global $portfolio_page_id;

		$suffix	= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		do_action( 'a3_portfolio_before_include_scripts' );

		// Register any scripts for later use, or used as dependencies
		wp_register_script( 'jquery-cookie', A3_PORTFOLIO_JS_URL . '/jquery.cookie.js', array( 'jquery' ), '1.4.1', true );
    	wp_register_script( 'jquery-mobile-a3-portfolio', A3_PORTFOLIO_JS_URL . '/jquery.mobile.custom' . $suffix . '.js', array( 'jquery' ), '1.4.4', true );
    	wp_register_script( 'jquery-imagesloaded', A3_PORTFOLIO_JS_URL . '/imagesloaded.pkgd' . $suffix . '.js', array( 'jquery' ), '3.1.8', true );
    	wp_register_script( 'colorbox_script', A3_PORTFOLIO_JS_URL . '/colorbox/jquery.colorbox'.$suffix.'.js', array( 'jquery' ), '1.4.4', true );

		wp_register_script( 'a3-portfolio-script', apply_filters( 'a3_portfolio_script_url', A3_PORTFOLIO_JS_URL . '/a3.portfolio' . $suffix . '.js' ), array( 'jquery', 'jquery-masonry', 'jquery-cookie', 'jquery-mobile-a3-portfolio', 'jquery-imagesloaded' ), A3_PORTFOLIO_VERSION, true );
		wp_register_script( 'a3-portfolio-widgets-script', apply_filters( 'a3_portfolio_widgets_script_url', A3_PORTFOLIO_JS_URL . '/a3.portfolio.widget' . $suffix . '.js' ), array( 'jquery', 'jquery-cookie' ), A3_PORTFOLIO_VERSION, true );
		wp_register_script( 'a3-portfolio-single-script', apply_filters( 'a3_portfolio_single_script_url', A3_PORTFOLIO_JS_URL . '/a3.portfolio.single' . $suffix . '.js' ), array( 'jquery', 'jquery-cookie', 'jquery-mobile-a3-portfolio', 'jquery-imagesloaded', 'colorbox_script' ), A3_PORTFOLIO_VERSION, true );

		// CSS Styles
		$enqueue_styles = self::get_styles();

		if ( $enqueue_styles ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				wp_register_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}

		// RTL CSS Styles
		$enqueue_styles_rtl = self::get_styles_rtl();

		if ( $enqueue_styles_rtl ) {
			foreach ( $enqueue_styles_rtl as $handle => $args ) {
				wp_register_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}

		if ( is_viewing_portfolio_taxonomy() || ( $post && ( $portfolio_page_id == $post->ID || stristr( $post->post_content, '[portfoliopage') !== false ) ) ) {
			$this->a3_portfolio_main_page_scripts();
		}

		do_action( 'a3_portfolio_after_include_scripts' );
	}

	/**
	 * Localize scripts only when enqueued
	 */
	public function localize_printed_scripts() {
		global $wp;

		$rtl	= is_rtl() ? 1 : 0;
		$current_lang = '';
		if ( class_exists('SitePress') ) {
			$current_lang = ICL_LANGUAGE_CODE;
		}

		if ( wp_script_is( 'a3-portfolio-widgets-script' ) ) {
			wp_localize_script( 'a3-portfolio-widgets-script', 'a3_portfolio_widgets_script_params', apply_filters( 'a3_portfolio_widgets_script_params', array(
				'ajax_url'         => admin_url( 'admin-ajax.php', 'relative' ),
				'no_porfolio_text' => a3_portfolio_ei_ict_t__( 'Recently Widget - No Portfolio', __( 'No Portfolio Recently Viewed !', 'a3-portfolio' ) ),
				'lang'             => $current_lang
			) ) );
		}
		if ( wp_script_is( 'a3-portfolio-script' ) ) {
			$number_columns                 = a3_portfolio_get_col_per_row();
			$card_image_height_fixed        = a3_portfolio_card_image_height_fixed();
			$desktop_expander_top_alignment = a3_portfolio_get_desktop_expander_top_alignment();
			$mobile_expander_top_alignment  = a3_portfolio_get_mobile_expander_top_alignment();
			wp_localize_script( 'a3-portfolio-script', 'a3_portfolio_script_params', apply_filters( 'a3_portfolio_script_params', array(
				'ajax_url'                       => admin_url( 'admin-ajax.php', 'relative' ),
				'have_filters_script'            => false,
				'number_columns'                 => $number_columns,
				'card_image_height_fixed'        => $card_image_height_fixed,
				'desktop_expander_top_alignment' => $desktop_expander_top_alignment,
				'mobile_expander_top_alignment'  => $mobile_expander_top_alignment,
				'expander_template'              => a3_portfolio_expander_template(),
				'rtl'                            => $rtl,
				'lang'                           => $current_lang
			) ) );
		}
		if ( wp_script_is( 'a3-portfolio-single-script' ) ) {
			wp_localize_script( 'a3-portfolio-single-script', 'a3_portfolio_single_script_params', apply_filters( 'a3_portfolio_single_script_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'lang'     => $current_lang
			) ) );
		}
	}

	/**
	 * Enqueue for Main Portfolip page.
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_main_page_scripts() {
		do_action( 'a3_portfolio_before_portfolio_enqueue_lib_scripts' );

		wp_enqueue_script( 'a3-portfolio-script' );

		do_action( 'a3_portfolio_after_portfolio_enqueue_lib_scripts' );

		do_action( 'a3_portfolio_before_portfolio_enqueue_styles' );

		wp_enqueue_style( 'a3-portfolio-general-css' );
		wp_enqueue_style( 'a3-portfolio-layout-css' );

		do_action( 'a3_portfolio_after_portfolio_enqueue_styles' );

		if ( is_rtl() ) {
			do_action( 'a3_portfolio_before_portfolio_enqueue_styles_rtl' );

			wp_enqueue_style( 'a3-portfolio-general-css-rtl' );
			wp_enqueue_style( 'a3-portfolio-layout-css-rtl' );

			do_action( 'a3_portfolio_after_portfolio_enqueue_styles_rtl' );
		}
	}

	/**
	 * Get custom styles for the Portfolip page
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_custom_style() {
		global $a3_portfolio_item_cards_settings;

		if ( ! $a3_portfolio_item_cards_settings['enable_cards_description'] ) return;

		$cards_description_line_height = (int)$a3_portfolio_item_cards_settings['cards_description_line_height'];
		$cards_description_line_height = $cards_description_line_height + ( $cards_description_line_height * 0.2 );

		$custom_style = '
<style type="text/css">
	body .a3-portfolio-item-block .a3-portfolio-card-description div {
		height: '.$cards_description_line_height.'em !important;
	}
</style>';

		echo apply_filters( 'a3_portfolio_custom_style', $custom_style );
	}

	/**
	 * Get styles for the Single Portfolip page
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_custom_single_style( $portfolio_id ) {
		global $a3_portfolio_item_posts_settings;

		$gallery_wide = get_post_meta( $portfolio_id, '_a3_portfolio_meta_gallery_wide', true );
		if ( empty( $gallery_wide ) || $gallery_wide == '' ) {
			$gallery_wide = $a3_portfolio_item_posts_settings['portfolio_inner_container_single_main_image_width'];
		}
		$gallery_wide = intval( $gallery_wide );
		$content_data_wide = intval(100 - 2 - $gallery_wide);

		$custom_style = '
<style type="text/css">
@media only screen and (min-width: 768px) {
	body div.single-a3-portfolio-' . $portfolio_id . ' .a3-portfolio-item-image-container {
		width: ' . $gallery_wide . '%;
	    float: '. ( is_rtl() ? 'right': 'left' ) .';
	    '. ( is_rtl() ? 'margin-left': 'margin-right' ) .': 2%;
	    margin-bottom: 0px;
	}
	body div.single-a3-portfolio-' . $portfolio_id . ' .a3-portfolio-item-content-container{
		width: ' . $content_data_wide . '%;
	    float: '. ( is_rtl() ? 'right': 'left' ) .';
	}
}
</style>';

		echo apply_filters( 'a3_portfolio_custom_single_style', $custom_style, $portfolio_id );
	}

	/**
	 * Enqueue for Single Portfolip page.
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_single_scripts() {
		do_action( 'a3_portfolio_before_single_enqueue_styles' );

		wp_enqueue_style( 'a3_colorbox_style' );
		wp_enqueue_style( 'a3-portfolio-general-css' );
		wp_enqueue_style( 'a3-portfolio-layout-css' );

		do_action( 'a3_portfolio_after_single_enqueue_styles' );

		if ( is_rtl() ) {
			do_action( 'a3_portfolio_before_single_enqueue_styles_rtl' );

			wp_enqueue_style( 'a3-portfolio-general-css-rtl' );
			wp_enqueue_style( 'a3-portfolio-layout-css-rtl' );

			do_action( 'a3_portfolio_after_single_enqueue_styles_rtl' );
		}

		do_action( 'a3_portfolio_before_single_enqueue_lib_scripts' );

		wp_enqueue_script( 'a3-portfolio-single-script' );

		do_action( 'a3_portfolio_after_single_enqueue_lib_scripts' );
	}

	/**
	 * Enqueue for Portfolio Widget.
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_widget_scripts() {
		wp_enqueue_style( 'a3-portfolio-widgets-css' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'a3-portfolio-widgets-css-rtl' );
		}

		do_action( 'a3_portfolio_before_widget_enqueue_lib_scripts' );

		do_action( 'a3_portfolio_after_widget_enqueue_lib_scripts' );

		wp_enqueue_script( 'a3-portfolio-widgets-script' );
	}

	/**
	 * Enqueue for Portfolio Widget.
	 *
	 * @access public
	 * @return void
	 */
	public function a3_portfolio_attribute_filter_widget_scripts() {
		wp_enqueue_style( 'a3-portfolio-attribute-filter-widget-css' );
	}

	/**
	 * WC requires jQuery 1.8 since it uses functions like .on() for events and .parseHTML.
	 * If, by the time wp_print_scrips is called, jQuery is outdated (i.e not
	 * using the version in core) we need to deregister it and register the
	 * core version of the file.
	 *
	 * @access public
	 * @return void
	 */
	public function check_jquery() {
		global $wp_scripts;

		// Enforce minimum version of jQuery
		if ( ! empty( $wp_scripts->registered['jquery']->ver ) && ! empty( $wp_scripts->registered['jquery']->src ) && 0 >= version_compare( $wp_scripts->registered['jquery']->ver, '1.8' ) ) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', '/wp-includes/js/jquery/jquery.js', array(), '1.8' );
			wp_enqueue_script( 'jquery' );
		}
	}
}
