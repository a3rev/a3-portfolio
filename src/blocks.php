<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

namespace A3Rev\Portfolio;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blocks {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );

		new Blocks\Main();
		new Blocks\Items();
		new Blocks\Categories();

		new Blocks\Tags();
		new Blocks\Sticky();
		new Blocks\Recent();

		require 'blocks/item-tags/block.php';

		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'cgb_editor_assets' ) );
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * @uses {wp-blocks} for block type registration & related functions.
	 * @uses {wp-element} for WP Element abstraction — structure of blocks.
	 * @uses {wp-i18n} to internationalize the block's text.
	 * @uses {wp-editor} for WP editor styles.
	 * @since 1.0.0
	 */
	function cgb_editor_assets() { // phpcs:ignore

		global $a3_portfolio_frontend_scripts;

		// CSS Styles
		$enqueue_styles = $a3_portfolio_frontend_scripts->get_styles();

		if ( $enqueue_styles ) {
			foreach ( $enqueue_styles as $handle => $args ) {
				wp_register_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}

		// RTL CSS Styles
		$enqueue_styles_rtl = $a3_portfolio_frontend_scripts->get_styles_rtl();

		if ( $enqueue_styles_rtl ) {
			foreach ( $enqueue_styles_rtl as $handle => $args ) {
				wp_register_style( $handle, $args['src'], $args['deps'], $args['version'], $args['media'] );
			}
		}

		wp_enqueue_script(
			'a3-portfolio-block-js', // Handle.
			plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), // Dependencies, defined above.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		$styles_dependens = array( 'a3-portfolio-general-css', 'a3-portfolio-layout-css' );
		if ( is_rtl() ) {
			$styles_dependens[] = 'a3-portfolio-general-css-rtl';
			$styles_dependens[] = 'a3-portfolio-layout-css-rtl';
		}
		if ( isset( $enqueue_styles['a3-portfolio-dynamic-stylesheet'] ) ) {
			$styles_dependens[] = 'a3-portfolio-dynamic-stylesheet';
		}

		// Styles.
		wp_enqueue_style(
			'a3-portfolio-block-editor-css', // Handle.
			plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
			array_merge( array( 'wp-edit-blocks' ), $styles_dependens ) // Dependency to include the CSS after it.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
		);

		$list_portfolios = get_posts( array(
			'posts_per_page'		=> -1,
			'orderby'				=> 'title',
			'order'					=> 'ASC',
			'post_type'				=> 'a3-portfolio',
			'post_status'			=> 'publish',
		));
		$all_cats      = a3_portfolio_get_all_categories_visiable( 0, '– ', false );
		$all_tags      = a3_portfolio_get_all_tags();
		
		$itemList = array();
		if ( is_array( $list_portfolios ) && count( $list_portfolios ) > 0 ) {
			foreach ( $list_portfolios as $portfolio ) {
				$itemList[] = array( 'label' => $portfolio->post_title, 'value' => $portfolio->ID );
			}
		}

		$catList = array();
		if ( is_array( $all_cats ) && count( $all_cats ) > 0 ) {
			foreach ( $all_cats as $cat ) {
				$catList[] = array( 'label' => $cat->name, 'value' => $cat->term_id );
			}
		}
		
		$tagList = array();
		if ( is_array( $all_tags ) && count( $all_tags ) > 0 ) {
			foreach ( $all_tags as $tag ) {
				$tagList[] = array( 'label' => $tag->name, 'value' => $tag->term_id );
			}
		}

		$global_column = a3_portfolio_get_col_per_row();

		wp_localize_script( 'a3-portfolio-block-js', 'a3_portfolio_blocks_vars', array( 
			'itemList'     => json_encode( $itemList ), 
			'catList'      => json_encode( $catList ), 
			'tagList'      => json_encode( $tagList ), 
			'globalColumn' => $global_column,
			'preview'      => A3_PORTFOLIO_JS_IMAGES_URL.  '/preview.jpg',
		) );
	}

	public function create_a3blocks_section() {

		add_filter( 'block_categories_all', function( $categories ) {

			$category_slugs = wp_list_pluck( $categories, 'slug' );

			if ( in_array( 'a3rev-blocks', $category_slugs ) ) {
				return $categories;
			}

			return array_merge(
				array(
					array(
						'slug' => 'a3rev-blocks',
						'title' => __( 'a3rev Blocks' ),
						'icon' => '',
					),
				),
				$categories
			);
		}, 2 );
	}

	public function register_block() {

		$this->create_a3blocks_section();

	}
}
