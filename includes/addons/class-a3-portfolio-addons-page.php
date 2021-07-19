<?php

namespace A3Rev\Portfolio;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Addons
{
	public function __construct() {

		if ( is_admin() ) {
			add_filter( $GLOBALS[A3_PORTFOLIO_PREFIX.'admin_init']->plugin_name . '_plugin_extension_boxes', array( $this, 'plugin_extension_box' ) );
		}
	}

	public function plugin_extension_box( $boxes = array() ) {
		$addons = get_transient( 'a3_portfolio_addons_data' );

		if ( !$addons ) {
			$addons_json = wp_remote_get( 'http://d3dzuqj2pabxt6.cloudfront.net/portfolios-addons.json', array( 'user-agent' => 'a3 Portfolios Addons Page' ) );
			if ( ! is_wp_error( $addons_json ) ) {
				$addons = json_decode( wp_remote_retrieve_body( $addons_json ), true );
				if ( $addons ) {
					set_transient( 'a3_portfolio_addons_data', $addons, 60*60*24 ); // 1 day
				}
			} else {
				$addons_json = wp_remote_get( 'https://s3.amazonaws.com/a3portfolios/portfolios-addons.json', array( 'user-agent' => 'a3 Portfolios Addons Page' ) );
				if ( ! is_wp_error( $addons_json ) ) {
					$addons = json_decode( wp_remote_retrieve_body( $addons_json ), true );
					if ( $addons ) {
						set_transient( 'a3_portfolio_addons_data', $addons, 60*60*24 ); // 1 day
					}
				}
			}
		}

		/**
		 * Example about addon data
		 *
		 * $addon = array(
		 *		'url'             => 'http://a3rev.com/shop/a3-portfolios-dynamic-styling/',
		 * 		'title'           => __( 'Portfolio Dynamic Styling', 'a3-portfolio' ),
		 * 		'header_bg'		  => '#9378d9',
		 * 		'title_color'	  => '#fff',
		 * 		'title_bg'		  => '#000',
		 * 		'image'           => 'https://s3.amazonaws.com/a3_plugins/a3+Portfolios+Dynamic+Styling/plugin.png',
		 * 		'desc'            => __( 'Support for change the styling from Admin Panel and apply for Portfolio front end.', 'a3-portfolio' ),
		 * 		'php_class_check' => 'A3_Portfolio_Dynamic_Styling',
		 * 		'folder_name'     => 'a3-portfolio-isotope-addon',
		 * 		'is_free'         => true
		 * );
		*/

		$third_party_addons = apply_filters( 'a3_portfolio_third_party_addons', array() );

		$all_addons = array_merge( $addons, $third_party_addons );
		$all_addons = array_merge( $all_addons, $addons );

		if ( is_array( $all_addons ) && count( $all_addons ) > 0 ) :

			foreach ( $all_addons as $id => $addon ) :
				$had_plugin = false;
				$is_installed = false;
				$addon = (object) $addon;
				if ( class_exists( $addon->php_class_check ) ) {
					$is_installed = true;
				} else {
					$activate_plugin_able = get_plugins('/' . $addon->folder_name );

					if ( ! empty( $activate_plugin_able ) && count( $activate_plugin_able ) == 1 ) {
						$had_plugin = true;
						$key = array_keys( $activate_plugin_able );
						$key = array_shift( $key ); //Use the first plugin regardless of the name, Could have issues for multiple-plugins in one directory if they share different version numbers
						$plugin_slug = $addon->folder_name.'/'.$key;
						$activate_url = add_query_arg( array(
									'action' 		=> 'activate',
									'plugin'		=> $plugin_slug,
						), self_admin_url( 'plugins.php' ) );
					}
				}
				$header_style = '';
				if ( ! empty( $addon->image ) ) :
					$header_style .= 'background-image: url( ' . esc_url( $addon->image ) . ');';
				endif;
				if ( ! empty( $addon->header_bg ) ) :
					$header_style .= 'background-color: ' . $addon->header_bg . ';';
				endif;

				$title_style = '';
				if ( ! empty( $addon->title_color ) ) :
					$title_style .= 'color: ' . $addon->title_color . ';';
				endif;
				if ( ! empty( $addon->title_bg ) ) :
					$title_style .= 'background-color: ' . $addon->title_bg . ';';
				endif;

				ob_start();
			?>

				<div class="extension-card <?php echo esc_attr( $id ); ?>">
					<a class="extension-card-header" target="_blank" href="<?php echo esc_url( $addon->url ); ?>">
						<h3 style="<?php echo esc_attr( $header_style ); ?>"><span class="extension-title" style="<?php echo esc_attr( $title_style ); ?>"><?php echo esc_html( $addon->title ); ?></span></h3>
					</a>

					<p><?php echo esc_html( $addon->desc ); ?></p>

					<span class="extension-control">
						<?php if ( $is_installed ) { ?>
							<button class="button-primary installed"><?php echo __( 'Activated', 'a3-portfolio' ); ?></button>
						<?php } elseif ( $had_plugin ) { ?>
							<a href="<?php echo esc_url( wp_nonce_url( $activate_url, 'activate-plugin_' . $plugin_slug ) ); ?>" class="button-primary"><?php echo __( 'Activate', 'a3-portfolio' ); ?></a>
						<?php } else { ?>
							<a target="_blank" href="<?php echo esc_url( $addon->url ); ?>" class="button-primary">
								<?php echo __( 'Get this extension', 'a3-portfolio' ); ?>
							</a>
						<?php } ?>
					</span>

					<?php if ( $addon->is_free ) { ?>
					<span class="free-extension"><?php echo __( 'Free', 'a3-portfolio' ); ?></span>
					<?php } ?>
				</div>

				<?php
				$box_content = ob_get_clean();
				$boxes[] = array(
					'id'      => $id,
					'class'   => 'a3-portfolio-addons-tab-wrap',
					'content' => $box_content,
					'css'     => 'border-color: #ff6f00; padding:0; background: #f5f5f5;'
				);
				?>

			<?php endforeach; ?>

		<?php endif; ?>

		<?php

		return $boxes;
	}
}
