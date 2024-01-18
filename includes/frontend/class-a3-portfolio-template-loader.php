<?php
/**
 * Template Loader
 */

namespace A3Rev\Portfolio\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Template_Loader
{
	public function __construct() {
		// Process template for Portfolio plugin
		add_filter( 'request', array( $this, 'filter_query_request' ) );
		add_filter( 'parse_query', array( $this, 'mark_portfolio_query' ), 12 );
		add_action( 'template_redirect', array( $this, 'start_the_query' ), 8 );
		add_filter( 'request', array( $this, 'portfolio_remove_page_from_query_string' ) );
		add_filter( 'archive_template', array( $this, 'portfolio_the_category_template' ) );
		if ( ! is_admin() ) {
			add_filter( 'the_title', array( $this, 'portfolio_the_title' ), 10, 2 );
		}

		// Stop filter by a3 Lazy Load plugin
		add_filter( 'a3_lazy_load_run_filter', array( $this, 'stop_a3_lazyload_plugin' ) );

		if (( ! function_exists('wp_is_block_theme') || ! wp_is_block_theme() ) &&
            ( ! function_exists('gutenberg_supports_block_templates') || ! gutenberg_supports_block_templates() )
        ) {
			add_action( 'wp_head', array( $this, 'a3_portfolio_filter_template' ), 1000 );
		} else {
			$this->a3_portfolio_filter_content_template();
		}
	}

	public function stop_a3_lazyload_plugin( $run_filter ) {
		global $post;
		global $portfolio_page_id;

		// stop run filter the images of a3 Lazy Load plugin
		if ( is_viewing_portfolio_taxonomy() || ( isset( $post ) && ( $portfolio_page_id == $post->ID || stristr( $post->post_content, '[portfoliopage') !== false ) ) ) {
			return false;
		}

		return $run_filter;
	}

	public function filter_query_request( $args ) {
		global $portfolio_page_name;
		if ( is_admin() )
			return $args;

		// Make sure no 404 error is thrown for any sub pages of products-page
		if ( ! empty( $args['portfolio_cat'] ) && 'page' != $args['portfolio_cat'] && ! term_exists($args['portfolio_cat'], 'portfolio_cat') ) {
			// Probably requesting a page that is a sub page of products page
			$pagename = $portfolio_page_name."/{$args['portfolio_cat']}";
			if ( isset($args['name']) ) {
				$pagename .= "/{$args['name']}";
			}
			$args             = array();
			$args['pagename'] = $pagename;
		}

		// When product page is set to display all products or a category, and pagination is enabled, $wp_query is messed up
		// and is_home() is true. This fixes that.
		if ( isset( $args['post_type'] ) && 'a3-portfolio' == $args['post_type'] && ! empty( $args['a3-portfolio'] ) && isset($args['portfolio_cat']) && 'page' == $args['portfolio_cat'] ) {
			$page             = $args['a3-portfolio'];
			$args             = array();
			$args['pagename'] = $portfolio_page_name;
			$args['page']     = $page;
		}
		return $args;
	}

	public function mark_portfolio_query( $query ) {
		if ( isset( $query->query_vars['post_type'] ) && ($query->query_vars['post_type'] == 'a3-portfolio') )
			$query->is_a3_portfolio = true;
		return $query;
	}

	/**
	 * start_the_query
	 */
	public function start_the_query() {

		global $wp_query, $portfolio_page_id, $portfolio_query, $portfolio_query_vars, $portfolio_page_name,$portfolio_term_ids;

		$number_portfolios = a3_portfolio_get_per_page();

		$is_404 = false;
		if ( null == $portfolio_query ) {
			if( ( $wp_query->is_404 && !empty($wp_query->query_vars['paged']) ) || (isset( $wp_query->query['pagename']) && strpos( $wp_query->query['pagename'] , $portfolio_page_name ) !== false ) && !isset($wp_query->post)){
				global $post;
				$is_404 = true;
				if ( !isset( $wp_query->query_vars['portfolio_cat'] ) )
					$wp_query = new \WP_Query('post_type=a3-portfolio&name='.$wp_query->query_vars['name']);

				if ( isset( $wp_query->post->ID ) ) {
					$post = $wp_query->post;
				} else {
					if ( ! is_array( $portfolio_query_vars ) ) {
						$portfolio_query_vars = array();
					}
					$portfolio_query_vars['portfolio_cat'] = $wp_query->query_vars['name'];
				}
			}
			if ( empty( $portfolio_query_vars ) ) {
				$portfolio_query_vars = array(
					'post_status' => 'publish, locked, private',
					'post_parent' => 0,
					'order'       => apply_filters('portfolio_order','DESC')
				);
				if($wp_query->query_vars['preview'])
					$portfolio_query_vars['post_status'] = 'any';

				$portfolio_query_vars['orderby'] = 'post_date';

				if ( isset( $wp_query->query_vars['portfolio_cat'] ) ) {
					$portfolio_query_vars['portfolio_cat'] = $wp_query->query_vars['portfolio_cat'];
					$portfolio_query_vars['taxonomy']      = get_query_var( 'taxonomy' );
					$portfolio_query_vars['term']          = get_query_var( 'term' );
				} else {
					$portfolio_query_vars['post_type'] = 'a3-portfolio';
					$portfolio_query_vars['pagename']  = $portfolio_page_name;
				}

				$portfolio_query_vars['nopaging']       = false;
				$portfolio_query_vars['posts_per_page'] = $number_portfolios;
				$portfolio_query_vars['paged']          = get_query_var('paged');
				if ( isset( $portfolio_query_vars['paged'] ) && empty( $portfolio_query_vars['paged'] ) ) {
					$portfolio_query_vars['paged'] = get_query_var('page');

				}

				add_filter( 'pre_get_posts', array( $this, 'generate_portfolio_query' ), 11 );

				$portfolio_query = new \WP_Query( $portfolio_query_vars );
			}
		}

		if (  $is_404 || ( ( isset($portfolio_query->post_count) && $portfolio_query->post_count == 0 ) && isset($portfolio_query_vars['portfolio_cat'] ) ) ) {

			$args = array_merge($portfolio_query->query, array('posts_per_page' => $number_portfolios, 'orderby' => 'post_date' ) );

			$wp_query = new \WP_Query($args);

			if ( empty( $portfolio_query->posts ) ) {
				$wp_query = new \WP_Query( 'page_id='.$portfolio_page_id);
			}
		}
		if ( isset( $wp_query->post->ID ) )
			$post_id = $wp_query->post->ID;
		else
			$post_id = 0;

		wp_reset_query();
	}

	public function portfolio_the_title( $title = '', $id = '' ) {
		global $wp_query, $portfolio_page_id, $portfolio_cat_id, $portfolio_tag_id;
		$post = get_post($id);

		remove_filter('the_title', array( $this, 'portfolio_the_title') );

		// If its the category page
		if ( is_viewing_portfolio_taxonomy() && isset( $wp_query->posts[0] ) && $wp_query->posts[0]->post_title == $post->post_title && $wp_query->is_archive && !is_admin() && (isset($wp_query->query_vars['portfolio_cat']) || isset($wp_query->query_vars['portfolio_tag']))) {
			if ( isset( $wp_query->query_vars['portfolio_cat'] ) ) {
				$category         = get_term_by('slug',$wp_query->query_vars['portfolio_cat'],'portfolio_cat');
				$portfolio_cat_id = $category->term_id;
			}
			if ( isset( $wp_query->query_vars['portfolio_tag'] ) ) {
				$category         = get_term_by('slug',$wp_query->query_vars['portfolio_tag'],'portfolio_tag');
				$portfolio_tag_id = $category->term_id;
			}
		}

		//if this is paginated products_page
		if ( $wp_query->in_the_loop && empty($category->name) && isset( $wp_query->query_vars['paged'] ) && $wp_query->query_vars['paged'] && isset( $wp_query->query_vars['page'] ) && $wp_query->query_vars['page'] && 'a3-portfolio' == $wp_query->query_vars['post_type']) {
			$post  = get_post( $portfolio_page_id );
			$title = $post->post_title;
		}

		if ( ! empty( $category->name ) )
			return '<div class="portfolio-title portfolio-page">'.$category->name.'</div><div style="clear:both;"></div>';
		else
			return $title;
	}

	public function portfolio_remove_page_from_query_string( $query_string ) {
		global $portfolio_page_id;
		$number_portfolios = a3_portfolio_get_per_page();
		$portfolio_page    = get_page( $portfolio_page_id );

		if ( ! isset( $query_string['portfolio_cat'] ) && ! isset( $query_string['portfolio_tag'] ) && false === strpos( implode( ' ', $query_string ), $portfolio_page->post_name ) ) { return $query_string; }

		if ( isset( $query_string['name'] ) && $query_string['name'] == 'page' && isset( $query_string['page'] ) ) {
			unset( $query_string['name'] );
			list( $delim, $page_index ) = explode( '/', $query_string['page'] );
			$query_string['paged']      = $page_index;
		}

		if ( isset( $query_string['a3-portfolio'] ) && 'page' == $query_string['a3-portfolio'] ) {
			$query_string['a3-portfolio'] = '';
		}

		if ( isset( $query_string['name'] ) && is_numeric( $query_string['name'] ) ) {
			$query_string['paged']          = $query_string['name'];
			$query_string['page']           = '/'.$query_string['name'];
			$query_string['posts_per_page'] = $number_portfolios;
		}

		if ( isset( $query_string['a3-portfolio'] ) && is_numeric( $query_string['a3-portfolio'] ) ) {
			unset( $query_string['a3-portfolio'] );
		}

		if ( isset( $query_string['portfolio_cat'] ) && 'page' == $query_string['portfolio_cat'] ) {
			unset( $query_string['portfolio_cat'] );
		}

		if ( isset( $query_string['portfolio_tag'] ) && 'page' == $query_string['portfolio_tag'] ) {
			unset( $query_string['portfolio_tag'] );
		}

		if ( isset( $query_string['name'] ) && is_numeric( $query_string['name'] ) ) {
			unset( $query_string['name'] );
		}

		if ( isset( $query_string['term'] ) && 'page' == $query_string['term'] )	{
			unset( $query_string['term'] );
			unset( $query_string['taxonomy'] );
		}

		return $query_string;
	}

	public function generate_portfolio_query( $query ) {
		global $wp_query;
		remove_filter( 'pre_get_posts', array( $this, 'generate_portfolio_query' ), 11 );
		$number_portfolios             = a3_portfolio_get_per_page();
		$query->query_vars['taxonomy'] = null;
		$query->query_vars['term']     = null;

		// default product selection
		if ( $query->query_vars['pagename'] != '' ) {
			$query->query_vars['post_type'] = 'a3-portfolio';
			$query->query_vars['pagename']  = '';
			$query->is_page                 = false;
			$query->is_tax                  = false;
			$query->is_archive              = true;
			$query->is_singular             = false;
			$query->is_single               = false;
		}

		if ( isset( $query->query_vars['a3-portfolio'] ) && $query->query_vars['a3-portfolio'] != null && $query->query_vars['name'] != null ) {
			unset( $query->query_vars['taxonomy'] );
			unset( $query->query_vars['term'] );
			$query->query_vars['post_type'] = 'a3-portfolio';
			$query->is_tax                  = false;
			$query->is_archive              = true;
			$query->is_singular             = false;
			$query->is_single               = false;
		}
		if ( isset( $wp_query->query_vars['portfolio_cat'] ) && ! isset( $wp_query->query_vars['a3-portfolio'] ) ) {
			$query->query_vars['portfolio_cat'] = $wp_query->query_vars['portfolio_cat'];
			$query->query_vars['taxonomy']      = $wp_query->query_vars['taxonomy'];
			$query->query_vars['term']          = $wp_query->query_vars['term'];
		}
		if ( isset( $wp_query->query_vars['portfolio_tag'] ) && ! isset( $wp_query->query_vars['a3-portfolio'] ) ) {
			$query->query_vars['portfolio_tag'] = $wp_query->query_vars['portfolio_tag'];
			$query->query_vars['taxonomy']      = $wp_query->query_vars['taxonomy'];
			$query->query_vars['term']          = $wp_query->query_vars['term'];
		}

		$query->query_vars['posts_per_page'] = $number_portfolios;
		if ( $number_portfolios < 0 ) {
			$query->query_vars['nopaging'] = 1;
		}

		if ( isset( $_GET['items_per_page'] ) ) {
			if ( is_numeric( $_GET['items_per_page'] ) ) {
				$query->query_vars['posts_per_page'] = absint( $_GET['items_per_page'] );
			} elseif ( $_GET['items_per_page'] == 'all' ) {
				$query->query_vars['posts_per_page'] = 1000000;
				$query->query_vars['nopaging']       = 1;
			}
		}

		return $query;
	}

	/**
	 * portfolio_the_category_template swaps the template used for product categories with pageif archive template is being used use
	 * @access public
	 *
	 * @since 3.8
	 * @param $template (string) template path
	 * @return $template (string)
	 */
	public function portfolio_the_category_template( $template ) {
		global $wp_query;

		//this bit of code makes sure we use a nice standard page template for our products
		if ( is_viewing_portfolio_taxonomy() && false !== strpos( $template, 'archive' ) ) {
			$template = str_ireplace( 'archive', 'page', $template );
		}
		return $template;
	}

	public function single_template( $content ) {
		global $wpdb, $post, $wp_query, $is_IE;

		//if we dont belong here exit out straight away
		if ( ! isset( $wp_query->is_a3_portfolio ) && ! isset( $wp_query->query_vars['a3-portfolio'] ) ) return $content;

		// If we are a single products page
		if ( $wp_query->post->post_type == 'a3-portfolio' && ! is_archive() && $wp_query->post_count <= 1 ) {

			remove_filter( 'the_content', array( $this, 'single_template'), 9 );
			remove_filter( 'the_content', 'wpautop' );
			if ( isset( $wp_query->query_vars['preview'] ) && $wp_query->query_vars['preview'] )
				$is_preview = 'true';
			else
				$is_preview = 'false';

			$portfolio_temp_query = new \WP_Query( array(
				'p'              => $wp_query->post->ID ,
				'post_type'      => 'a3-portfolio',
				'posts_per_page' => 1,
				'preview'        => $is_preview
			) );

			list( $wp_query, $portfolio_temp_query ) = array( $portfolio_temp_query, $wp_query ); // swap the portfolio_temp_query object

			ob_start();

			a3_portfolio_get_template( 'single-portfolio.php' );

			$content = ob_get_clean();

			list( $wp_query, $portfolio_temp_query ) = array( $portfolio_temp_query, $wp_query ); // swap the portfolio_temp_query objects back

		}

		return $content;
	}

	// handles replacing the tags in the pages
	public function portfolio_category_template( $content = '' ) {
		global $wpdb, $wp_query, $portfolio_query, $portfolio_query_vars;

		$output = '';
		if ( preg_match( "/\[portfoliopage\]/", $content ) ) {
			global $more, $is_IE ;
			$more = 0;
			remove_filter( 'the_content', 'wpautop' );

			list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query object

			$display_type = '';
			$number_columns = a3_portfolio_get_col_per_row();

			ob_start();

			remove_action( 'a3rev_loop_after', 'responsi_pagination', 10, 0 );
			if ( isset( $wp_query->query_vars['taxonomy'] ) && 'portfolio_cat' == $wp_query->query_vars['taxonomy'] ) {
				a3_portfolio_get_template( 'taxonomy-portfolio_cat.php', array( 'container_id' => '', 'number_columns' => $number_columns ) ) ;
			} elseif ( isset( $wp_query->query_vars['taxonomy'] ) && 'portfolio_tag' == $wp_query->query_vars['taxonomy'] ) {
				a3_portfolio_get_template( 'taxonomy-portfolio_tag.php', array( 'container_id' => '', 'number_columns' => $number_columns ) ) ;
			} else {
				a3_portfolio_get_template( 'archive-portfolio.php', array( 'container_id' => '', 'number_columns' => $number_columns ) ) ;
			}

			$is_single = false;

			$output .= ob_get_clean();

			list($wp_query, $portfolio_query) = array( $portfolio_query, $wp_query ); // swap the wpsc_query objects back
			if ( $is_single == false ) {
				$GLOBALS['post'] = $wp_query->post;
			}
			$wp_query->current_post = $wp_query->post_count;

			return str_replace( '[portfoliopage]', $output, $content );
		} elseif ( is_archive() && is_viewing_portfolio_taxonomy() ) {
			remove_filter( 'the_content', 'wpautop' );
			return $this->portfolio_category_template('[portfoliopage]');
		} else {
			return $content;
		}
	}

	public function a3_portfolio_filter_template() {
		add_filter( 'the_title', array( $this, 'a3_portfolio_title_filter_content_template' ), 1000 );
	}

	public function a3_portfolio_title_filter_content_template( $title ) {
		$this->a3_portfolio_filter_content_template();

		return $title;
	}

	public function a3_portfolio_filter_content_template() {
		add_filter( 'the_content', array( $this, 'portfolio_category_template' ), 1 );
		add_filter( 'the_content', array( $this, 'single_template' ), 9 );
	}
}
