<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class A3_Portfolio_Duplicate
{

	public function __construct() {
		if ( is_admin() ) {
			/* START : Duplicate a Portfolio */
			add_action( 'admin_action_duplicate_a3-portfolio', array( $this, 'duplicate_action' ) );

			//Duplicate a Portfolio link on Portfolio list
			add_filter( 'post_row_actions', array( $this, 'duplicate_link_row' ), 10, 2 );
			add_filter( 'page_row_actions', array( $this, 'duplicate_link_row' ), 10, 2 );

			// Duplicate a Portfolio link on edit screen
			add_action( 'post_submitbox_start', array( $this, 'duplicate_post_button' ) );

			/* END : Duplicate a Portfolio */
		}
	}

	public function duplicate_action() {
		$this->duplicate_item();
	}

	public function duplicate_link_row( $actions, $post ) {

		if ( function_exists( 'duplicate_post_plugin_activation' ) ) return $actions;

		if ( $post->post_type != 'a3-portfolio' ) return $actions;

		$actions['duplicate'] = apply_filters( 'a3_portfolio_manager_duplicate_link', '<a href="' . wp_nonce_url( admin_url( 'admin.php?action=duplicate_a3-portfolio&amp;post=' . $post->ID ), 'a3-duplicate-portfolio_' . $post->ID ) . '" title="' . __( "Make a duplicate from this Portfolio", 'a3_portfolios' )
			. '" rel="permalink">' .  __( "Duplicate", 'a3_portfolios' ) . '</a>', $post, $actions );

		return $actions;
	}

	public function duplicate_post_button() {
		global $post;

		if ( function_exists( 'duplicate_post_plugin_activation' ) ) return;

		if( !is_object( $post ) ) return;

		if ( $post->post_type != 'a3-portfolio' ) return;

		if ( isset( $_GET['post'] ) ) :
			$notifyUrl = wp_nonce_url( admin_url( "admin.php?action=duplicate_a3-portfolio&post=" . $_GET['post'] ), 'a3-duplicate-portfolio_' . $_GET['post'] );
			$duplicate_link = apply_filters( 'a3_portfolio_edit_post_duplicate_link', '<div id="duplicate-action"><a class="submitduplicate duplication" href="'.esc_url( $notifyUrl ).'">'.__( 'Duplicate', 'a3_portfolios' ).'</a></div>', $post );
			echo $duplicate_link;
		endif;
	}

	public function duplicate_item() {
		if ( ! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'duplicate_post_save_as_new_page' == $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No Portfolio to duplicate has been supplied!', 'a3_portfolios' ) );
		}

		// Get the original page
		$id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		check_admin_referer( 'a3-duplicate-portfolio_' . $id );
		$post = $this->get_item_to_duplicate( $id );

		// Copy the page and insert it
		if ( isset( $post ) && $post != null ) {
			$new_id = $this->create_duplicate_from_item( $post );

			// If you have written a plugin which uses non-WP database tables to save
			// information about a page you can hook this action to dupe that data.
			do_action( 'duplicate_item', $new_id, $post );

			// Redirect to the edit screen for the new draft page
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
			exit;
		} else {
			wp_die( __( 'Portfolio creation failed, could not find original Portfolio:', 'a3_portfolios' ) . ' ' . $id);
		}
	}

	/**
	 * Get a Item from the database
	 */
	public function get_item_to_duplicate( $id ) {
		global $wpdb;
		$post = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id");
		if ( isset( $post->post_type ) && $post->post_type == "revision" ){
			$id = $post->post_parent;
			$post = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id");
		}
		return $post[0];
	}

	/**
	 * Function to create the duplicate
	 */
	public function create_duplicate_from_item( $post, $parent = 0, $post_status = '' ) {
		global $wpdb;

		$new_post_author 	= wp_get_current_user();
		$new_post_date 		= current_time('mysql');
		$new_post_date_gmt 	= get_gmt_from_date($new_post_date);

		if ( $parent > 0 ) {
			$post_parent		= $parent;
			$post_status 		= $post_status ? $post_status : 'publish';
			$suffix 			= '';
		} else {
			$post_parent		= $post->post_parent;
			$post_status 		= $post_status ? $post_status : 'publish';
			$suffix 			= ' ' . __("(Copy)", 'a3_portfolios');
		}

		$new_post_type 			= $post->post_type;
		$post_content    		= str_replace("'", "''", $post->post_content);
		$post_content_filtered 	= str_replace("'", "''", $post->post_content_filtered);
		$post_excerpt    		= str_replace("'", "''", $post->post_excerpt);
		$post_title      		= str_replace("'", "''", $post->post_title).$suffix;
		$post_name       		= str_replace("'", "''", $post->post_name);
		$comment_status  		= str_replace("'", "''", $post->comment_status);
		$ping_status     		= str_replace("'", "''", $post->ping_status);

		// Insert the new template in the post table
		$wpdb->query(
				"INSERT INTO $wpdb->posts
				(post_author, post_date, post_date_gmt, post_content, post_content_filtered, post_title, post_excerpt,  post_status, post_type, comment_status, ping_status, post_password, to_ping, pinged, post_modified, post_modified_gmt, post_parent, menu_order, post_mime_type)
				VALUES
				('$new_post_author->ID', '$new_post_date', '$new_post_date_gmt', '$post_content', '$post_content_filtered', '$post_title', '$post_excerpt', '$post_status', '$new_post_type', '$comment_status', '$ping_status', '$post->post_password', '$post->to_ping', '$post->pinged', '$new_post_date', '$new_post_date_gmt', '$post_parent', '$post->menu_order', '$post->post_mime_type')");

		$new_post_id = $wpdb->insert_id;

		wp_update_post( array(
			'ID'			=> $new_post_id,
			'post_title'	=> $post_title,
		) );

		// Copy the taxonomies
		$this->duplicate_post_taxonomies( $post->ID, $new_post_id, $post->post_type );

		// Copy the meta information
		$this->duplicate_post_meta( $post->ID, $new_post_id );


		return $new_post_id;
	}

	/**
	 * Copy the taxonomies of a post to another post
	 */
	public function duplicate_post_taxonomies( $id, $new_id, $post_type ) {
		global $wpdb;
		$taxonomies = get_object_taxonomies( $post_type ); //array("category", "post_tag");
		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms($id, $taxonomy);
			$post_terms_count = sizeof( $post_terms );
			for ( $i=0; $i < $post_terms_count; $i++ ) {
				wp_set_object_terms( $new_id, $post_terms[$i]->slug, $taxonomy, true);
			}
		}
	}

	/**
	 * Copy the meta information of a post to another post
	 */
	public function duplicate_post_meta( $id, $new_id ) {
		$all_custom_keys = get_post_custom_keys( $id );
		foreach ( $all_custom_keys as $key ) {
			$value = get_post_meta( $id, $key, true );
			add_post_meta( $new_id, $key, $value );
		}
	}

}