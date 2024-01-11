<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Types
{
	public function __construct() {

		if ( is_admin() ) {
			/* START : Update the columns for Portfolio post type */

			// Add sortable for custom column
			add_action( 'restrict_manage_posts', array( $this, 'cats_restrict_manage_posts' ) );
			add_filter( 'parse_query', array( $this, 'a3_portfolio_filters_query' ) );

			// Add custom column for Portfolio post type
			add_filter( 'manage_edit-a3-portfolio_columns', array( $this, 'edit_columns' ) );
			add_filter( 'manage_a3-portfolio_posts_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_a3-portfolio_posts_custom_column', array( $this, 'custom_columns' ) );

			/* END : Update the columns for Portfolio post type */
		}
	}

	public function register_post_type() {

		$permalinks = a3_portfolio_get_permalink_structure();

		// Register custom taxonomy
		register_taxonomy( 'portfolio_cat',
			array( 'a3-portfolio' ),
			apply_filters( 'a3_portfolio_register_cat_agrs', array(
				'hierarchical' 			=> true,
				'update_count_callback' => '_update_post_term_count',
				'label' 				=> __( 'Categories', 'a3-portfolio' ),
				'labels' => array(
						'name' 				=> __( 'Portfolio Categories', 'a3-portfolio' ),
						'singular_name' 	=> __( 'Portfolio Categories', 'a3-portfolio' ),
						'search_items' 		=> __( 'Search Portfolio Categories', 'a3-portfolio' ),
						'menu_name'			=> __( 'Categories', 'a3-portfolio' ),
						'popular_items'		=> NULL,
						'parent_item'		=> __( 'Parent Portfolio Categories', 'a3-portfolio' ),
						'parent_item_colon'	=> __( 'Parent Portfolio Categories:', 'a3-portfolio' ),
						'all_items' 		=> __( 'All Portfolio Categories', 'a3-portfolio' ),
						'edit_item' 		=> __( 'Edit Portfolio Categories', 'a3-portfolio' ),
						'update_item' 		=> __( 'Update Portfolio Categories', 'a3-portfolio' ),
						'add_new_item' 		=> __( 'Add New Portfolio Category', 'a3-portfolio' ),
						'new_item_name' 	=> __( 'New Portfolio Categories Name', 'a3-portfolio' )
				),
				'show_ui' 				=> true,
				'query_var' 			=> true,
				'rewrite' 				=> array(
						'slug'         => $permalinks['category_rewrite_slug'],
						'with_front'   => false,
						'hierarchical' => false
					),
				'show_in_rest' => true
			) )
		);

		// Register custom taxonomy
		register_taxonomy( 'portfolio_tag',
	        array( 'a3-portfolio' ),
			apply_filters( 'a3_portfolio_register_tag_agrs', array(
	            'hierarchical' 			=> false,
	            'update_count_callback' => '_update_post_term_count',
	            'label' 				=> __( 'Tags', 'a3-portfolio' ),
	            'labels' => array(
	                    'name' 				=> __( 'Portfolio Tags', 'a3-portfolio' ),
	                    'singular_name' 	=> __( 'Portfolio Tag', 'a3-portfolio' ),
						'menu_name'			=> _x( 'Tags', 'Admin menu name', 'a3-portfolio' ),
	                    'search_items' 		=> __( 'Search Portfolio Tags', 'a3-portfolio' ),
	                    'all_items' 		=> __( 'All Portfolio Tags', 'a3-portfolio' ),
	                    'parent_item' 		=> __( 'Parent Portfolio Tag', 'a3-portfolio' ),
	                    'parent_item_colon' => __( 'Parent TPortfolio ag:', 'a3-portfolio' ),
	                    'edit_item' 		=> __( 'Edit Portfolio Tag', 'a3-portfolio' ),
	                    'update_item' 		=> __( 'Update Portfolio Tag', 'a3-portfolio' ),
	                    'add_new_item' 		=> __( 'Add New Portfolio Tag', 'a3-portfolio' ),
	                    'new_item_name' 	=> __( 'New Portfolio Tag Name', 'a3-portfolio' )
	            ),

				'show_ui' 				=> true,
				'query_var' 			=> true,
				'rewrite' 				=> array(
						'slug'         => $permalinks['tag_rewrite_slug'],
						'with_front'   => false,
						'hierarchical' => false
					),
				'show_in_rest' => true
	        ) )
	    );

		if ( function_exists( 'a3_portfolio_register_attribute_taxonomies' ) ) {
			a3_portfolio_register_attribute_taxonomies();
		}

		// Register custom post type
		$labels_array = apply_filters( 'a3_portfolio_post_type_labels', array(
							'name'               => __( 'Portfolio Items', 'a3-portfolio' ),
							'singular_name'      => __( 'Portfolio Item', 'a3-portfolio' ),
							'menu_name'          => __( 'Portfolio', 'a3-portfolio' ),
							'all_items'          => __( 'Portfolio Items', 'a3-portfolio' ),
							'add_new'            => __( 'Add Item', 'a3-portfolio' ),
							'add_new_item'       => __( 'Add New Portfolio', 'a3-portfolio' ),
							'edit'               => __( 'Edit', 'a3-portfolio' ),
							'edit_item'          => __( 'Edit Item', 'a3-portfolio' ),
							'new_item'           => __( 'New Portfolio', 'a3-portfolio' ),
							'view'               => __( 'View', 'a3-portfolio' ),
							'view_item'          => __( 'View Item', 'a3-portfolio' ),
							'search_items'       => __( 'Search Portfolios', 'a3-portfolio' ),
							'not_found'          => __( 'No Portfolio Found', 'a3-portfolio' ),
							'not_found_in_trash' => __( 'No Portfolio found in Trash', 'a3-portfolio' ),
							'parent'             => __( 'Parent', 'a3-portfolio' )
							) );

		$supports_array = apply_filters( 'a3_portfolio_post_type_supports', array(
								'title',
								'editor',
								/*'excerpt',*/
								/*'trackbacks',*/
								/*'custom-fields',*/
								/*'comments',*/
								/*'revisions',*/
								'thumbnail',
								/*'author',*/
								'page-attributes'
							   ) );

		register_post_type( 'a3-portfolio',
							apply_filters( 'a3_portfolio_post_type_register', array(
								'description'     => __( 'Portfolios Custom Post Type', 'a3-portfolio' ),
								'public'          => true,
								'show_ui'         => true,
								'show_in_menu'    => true,
								'capability_type' => 'post',
								'hierarchical'    => false,
								'rewrite'         => $permalinks['portfolio_rewrite_slug'] ? array( 'slug' => $permalinks['portfolio_rewrite_slug'], 'with_front' => false, 'feeds' => true ) : false,
								'query_var'       => true,
								'has_archive'     => false,
								'_builtin'        => false,
								'supports'        => $supports_array,
								'labels'          => $labels_array,
								'show_in_rest'    => true,
								'supports'        => array('title', 'editor', 'thumbnail', 'author', 'comments', 'date')
								) ) );

		if ( 'yes' === get_option( 'a3_portfolio_just_installed', 'no' ) ) {
			flush_rewrite_rules();
		}
	}

	public function register_image_sizes() {
		global $a3_portfolio_global_settings;

		$item_card_image_width = 400;
		$item_card_image_height = 400;
		$item_card_image_crop = false;

		if ( (int) trim( $a3_portfolio_global_settings['item_card_image_width'] ) > 0 ) {
			$item_card_image_width = (int) $a3_portfolio_global_settings['item_card_image_width'];
		}
		if ( (int) trim( $a3_portfolio_global_settings['item_card_image_height'] ) > 0 ) {
			$item_card_image_height = (int) $a3_portfolio_global_settings['item_card_image_height'];
		}
		if ( 'yes' == trim( $a3_portfolio_global_settings['item_card_image_crop'] ) ) {
			$item_card_image_crop = true;
		}

		$gallery_image_width = 800;
		$gallery_image_height = 600;
		$gallery_image_crop = false;

		if ( (int) trim( $a3_portfolio_global_settings['gallery_image_width'] ) > 0 ) {
			$gallery_image_width = (int) $a3_portfolio_global_settings['gallery_image_width'];
		}
		if ( (int) trim( $a3_portfolio_global_settings['gallery_image_height'] ) > 0 ) {
			$gallery_image_height = (int) $a3_portfolio_global_settings['gallery_image_height'];
		}
		if ( 'yes' == trim( $a3_portfolio_global_settings['gallery_image_crop'] ) ) {
			$gallery_image_crop = true;
		}

		$gallery_thumbnail_width = 75;
		$gallery_thumbnail_height = 75;
		$gallery_thumbnail_crop = false;

		if ( (int) trim( $a3_portfolio_global_settings['gallery_thumbnail_width'] ) > 0 ) {
			$gallery_thumbnail_width = (int) $a3_portfolio_global_settings['gallery_thumbnail_width'];
		}
		if ( (int) trim( $a3_portfolio_global_settings['gallery_thumbnail_height'] ) > 0 ) {
			$gallery_thumbnail_height = (int) $a3_portfolio_global_settings['gallery_thumbnail_height'];
		}
		if ( 'yes' == trim( $a3_portfolio_global_settings['gallery_thumbnail_crop'] ) ) {
			$gallery_thumbnail_crop = true;
		}

		add_image_size( 'portfolio-item-card-image', $item_card_image_width, $item_card_image_height, $item_card_image_crop );
		add_image_size( 'portfolio-gallery-image', $gallery_image_width, $gallery_image_height, $gallery_image_crop );
		add_image_size( 'portfolio-gallery-thumbnail-image', $gallery_thumbnail_width, $gallery_thumbnail_height, $gallery_thumbnail_crop );
	}

	/* Custom column for Portfolio post type */
	public function cats_restrict_manage_posts_print_terms( $taxonomy, $parent = 0, $level = 0 ){
		$prefix = str_repeat( '&nbsp;&nbsp;&nbsp;' , $level );
		$terms = get_terms( $taxonomy, array( 'parent' => $parent, 'hide_empty' => false ) );
		if ( !( $terms instanceof \WP_Error ) && !empty( $terms ) ) {
			foreach ( $terms as $term ){
				echo '<option value="'. $term->slug . '"', ( isset($_GET[$term->taxonomy]) && $_GET[$term->taxonomy] == $term->slug) ? ' selected="selected"' : '','>' . $prefix . $term->name .' (' . $term->count . ')</option>';
				$this->cats_restrict_manage_posts_print_terms( $taxonomy, $term->term_id, $level+1 );
			}
		}
	}

	public function cats_restrict_manage_posts() {
		global $typenow;
		if ( 'a3-portfolio' == $typenow ) {
			$filters = array( 'portfolio_cat' );

			foreach ( $filters as $tax_slug ) {
				// output html for taxonomy dropdown filter
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>" . __( 'Show all categories', 'a3-portfolio' ) . "</option>";
					$this->cats_restrict_manage_posts_print_terms( $tax_slug );

				$the_query = new \WP_Query( array(
					'posts_per_page'	=> 1,
					'post_type'			=> 'a3-portfolio',
					'post_status'		=> array( 'publish', 'pending', 'draft' ),
					'tax_query'		=> array(
						array(
							'taxonomy' => 'portfolio_cat',
	        				'field' => 'id',
							'terms' => get_terms( 'portfolio_cat', array( 'fields' => 'ids' ) ),
							'operator' => 'NOT IN'
						) ),
				) );
				wp_reset_postdata();
				if ( isset( $_GET['portfolio_cat'] ) && $_GET['portfolio_cat'] == '0' ) {
					echo "<option value='0' selected='selected'>" . __( 'Uncategorized', 'a3-portfolio' ) . " (".$the_query->found_posts.")</option>";
				} else {
					echo "<option value='0'>" . __( 'Uncategorized', 'a3-portfolio' ) . " (".$the_query->found_posts.")</option>";
				}
				echo "</select>";
			}


		}
	}

	public function a3_portfolio_filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( 'a3-portfolio' == $typenow ) {

			// Categories
	        if ( isset( $_GET['portfolio_cat'] ) && $_GET['portfolio_cat'] == '0' ) {
	        	$query->query_vars['tax_query'][] = array(
	        		'taxonomy' => 'portfolio_cat',
	        		'field' => 'id',
					'terms' => get_terms( 'portfolio_cat', array( 'fields' => 'ids' ) ),
					'operator' => 'NOT IN'
	        	);
	        }
		}
		return $query;
	}

	public function edit_columns( $columns ) {
		$columns = array();

		$columns['cb'] 				= '<input type="checkbox" />';
		$columns['image'] 			= __( 'Thumbnail', 'a3-portfolio' );
		$columns['title'] 			= __( 'Name', 'a3-portfolio' );
		$columns['cats'] 			= __( 'Categories', 'a3-portfolio' );
		$columns['date'] 			= __( 'Date', 'a3-portfolio' );

		return $columns;
	}

	public function custom_columns( $column ) {
		global $post;
		global $wp_version;

		switch ( $column ) {
			case 'image':
				echo '<a style="display:inline-block;" href="' . get_edit_post_link( $post->ID ) . '">' . a3_portfolio_get_thumbnail_image( $post->ID, array( 40, 40 ) ) . '</a>';
                break;
			case "cats" :
				$terms = get_the_terms( $post->ID, 'portfolio_cat' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$portfolio_categoriess = array();

					if ( version_compare( $wp_version, '4.5', '<' ) ) {
                		$term_edit_url = 'edit-tags.php?action=edit&taxonomy=portfolio_cat&post_type=a3-portfolio';
                	} else {
                		$term_edit_url = 'term.php?taxonomy=portfolio_cat&post_type=a3-portfolio';
                	}
					foreach ( $terms as $term ) {
						$term_edit_url .= '&tag_ID='.$term->term_id;
						$portfolio_categoriess[] = "<a href='".$term_edit_url."'> " . esc_html( $term->name ) . "</a>";
					}
					echo join( ', ', $portfolio_categoriess );
				} else {
					echo '–';
				}
				break;
		}
	}
}
