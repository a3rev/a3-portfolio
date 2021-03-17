<?php

namespace A3Rev\Portfolio\Backend\Permalinks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'A3_Portfolio_Permalink_Settings' ) && ! class_exists( '\A3Rev\Portfolio\Backend\Permalinks\Settings' ) ) :

class Settings {

	private $permalinks = array();

	public function __construct() {

		$this->settings_init();
		$this->settings_save();
	}

	/**
	 * Init our settings.
	 */
	public function settings_init() {
		// Add a section to the permalinks page
		add_settings_section( 'a3-portfolio-permalink', __( 'a3 Portfolio Permalinks', 'a3-portfolio' ), array( $this, 'settings' ), 'permalink' );

		// Add our settings
		add_settings_field(
			'a3_portfolio_category_slug',            				// id
			__( 'a3 Portfolio category base', 'a3-portfolio' ),   	// setting title
			array( $this, 'portfolio_category_slug_input' ),		// display callback
			'permalink',                                    		// settings page
			'optional'                                      		// settings section
		);

		add_settings_field(
			'a3_portfolio_tag_slug',            					// id
			__( 'a3 Portfolio tag base', 'a3-portfolio' ),   		// setting title
			array( $this, 'portfolio_tag_slug_input' ),				// display callback
			'permalink',                                    		// settings page
			'optional'                                      		// settings section
		);

		$this->permalinks = a3_portfolio_get_permalink_structure();
	}

	public function portfolio_category_slug_input() {
		?>
		<input name="a3_portfolio_category_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['category_base'] ); ?>" placeholder="<?php echo esc_attr_x('portfolio-category', 'slug', 'a3-portfolio' ) ?>" />
		<?php
	}

	public function portfolio_tag_slug_input() {
		?>
		<input name="a3_portfolio_tag_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->permalinks['tag_base'] ); ?>" placeholder="<?php echo esc_attr_x('portfolio-tag', 'slug', 'a3-portfolio' ) ?>" />
		<?php
	}

	/**
	 * Show the settings.
	 */
	public function settings() {
		echo wpautop( __( 'These settings control the permalinks used for a3 Portfolios. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'a3-portfolio' ) );

		$portfolio_permalink = $this->permalinks['portfolio_base'];

		// Get main page
		global $portfolio_page_id;
		$base_slug      = urldecode( ( $portfolio_page_id > 0 && get_post( $portfolio_page_id ) ) ? get_page_uri( $portfolio_page_id ) : _x( 'portfolios', 'default-slug', 'a3-portfolio' ) );
		$portfolio_base = _x( 'a3-portfolio', 'default-slug', 'a3-portfolio' );

		$structures = array(
			0 => '',
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%portfolio_cat%' )
		);
		?>
		<table class="form-table a3_portfolio_permalink_structure_table">
			<tbody>
				<tr>
					<th><label><input name="portfolio_permalink" type="radio" value="<?php echo $structures[0]; ?>" class="a3_portfolio_permalink" <?php checked( $structures[0], $portfolio_permalink ); ?> /> <?php _e( 'Default', 'a3-portfolio' ); ?></label></th>
					<td><code class="default-example"><?php echo esc_html( home_url() ); ?>/?a3-portfolio=sample-portfolio</code> <code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $portfolio_base ); ?>/sample-portfolio/</code></td>
				</tr>
				<?php if ( $portfolio_page_id ) : ?>
					<tr>
						<th><label><input name="portfolio_permalink" type="radio" value="<?php echo $structures[1]; ?>" class="a3_portfolio_permalink" <?php checked( $structures[1], $portfolio_permalink ); ?> /> <?php _e( 'Main page base', 'a3-portfolio' ); ?></label></th>
						<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-portfolio/</code></td>
					</tr>
					<tr>
						<th><label><input name="portfolio_permalink" type="radio" value="<?php echo $structures[2]; ?>" class="a3_portfolio_permalink" <?php checked( $structures[2], $portfolio_permalink ); ?> /> <?php _e( 'Main page base with category', 'a3-portfolio' ); ?></label></th>
						<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/portfolio-category/sample-portfolio/</code></td>
					</tr>
				<?php endif; ?>
				<tr>
					<th><label><input name="portfolio_permalink" id="a3_portfolio_custom_selection" type="radio" value="custom" class="tog" <?php checked( in_array( $portfolio_permalink, $structures ), false ); ?> />
						<?php _e( 'Custom Base', 'a3-portfolio' ); ?></label></th>
					<td>
						<input name="portfolio_permalink_structure" id="a3_portfolio_permalink_structure" type="text" value="<?php echo esc_attr( $portfolio_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'a3-portfolio' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery( function() {
				jQuery('input.a3_portfolio_permalink').on('change', function() {
					jQuery('#a3_portfolio_permalink_structure').val( jQuery( this ).val() );
				});
				jQuery('.permalink-structure input').on('change', function() {
					jQuery('.a3_portfolio_permalink_structure_table').find('code.non-default-example, code.default-example').hide();
					if ( jQuery(this).val() ) {
						jQuery('.a3_portfolio_permalink_structure_table code.non-default-example').show();
						jQuery('.a3_portfolio_permalink_structure_table input').prop('disabled',false);
					} else {
						jQuery('.a3_portfolio_permalink_structure_table code.default-example').show();
						jQuery('.a3_portfolio_permalink_structure_table input').eq(0).trigger('click');
						jQuery('.a3_portfolio_permalink_structure_table input').prop('disabled', true);
					}
				});
				jQuery('.permalink-structure input:checked').trigger('change');
				jQuery('#a3_portfolio_permalink_structure').on('focus', function(){
					jQuery('#a3_portfolio_custom_selection').trigger('click');
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {

		if ( ! is_admin() ) {
			return;
		}

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) && isset( $_POST['portfolio_permalink'] ) ) {
			if ( function_exists( 'switch_to_locale' ) ) {
				switch_to_locale( get_locale() );
			}

			// Cat and tag bases
			$a3_portfolio_category_slug = sanitize_text_field( $_POST['a3_portfolio_category_slug'] );
			$a3_portfolio_tag_slug      = sanitize_text_field( $_POST['a3_portfolio_tag_slug'] );

			$permalinks = get_option( 'a3_portfolio_permalinks' );

			if ( ! $permalinks ) {
				$permalinks = array();
			}

			$permalinks['category_base'] = untrailingslashit( $a3_portfolio_category_slug );
			$permalinks['tag_base']      = untrailingslashit( $a3_portfolio_tag_slug );

			// Portfolio base
			$portfolio_permalink = sanitize_text_field( $_POST['portfolio_permalink'] );

			if ( $portfolio_permalink == 'custom' ) {
				// Get permalink without slashes
				$portfolio_permalink = trim( sanitize_text_field( $_POST['portfolio_permalink_structure'] ), '/' );

				// This is an invalid base structure and breaks pages
				if ( '%portfolio_cat%' == $portfolio_permalink ) {
					$portfolio_permalink = _x( 'a3-portfolio', 'default-slug', 'a3-portfolio' ) . '/' . $portfolio_permalink;
				}

				// Prepending slash
				$portfolio_permalink = '/' . $portfolio_permalink;
			} elseif ( empty( $portfolio_permalink ) ) {
				$portfolio_permalink = false;
			}

			$permalinks['portfolio_base'] = untrailingslashit( $portfolio_permalink );

			// Main page base may require verbose page rules if nesting pages
			global $portfolio_page_id;
			$main_permalink = ( $portfolio_page_id > 0 && get_post( $portfolio_page_id ) ) ? get_page_uri( $portfolio_page_id ) : _x( 'portfolios', 'default-slug', 'a3-portfolio' );

			if ( $portfolio_page_id && trim( $permalinks['portfolio_base'], '/' ) === $main_permalink ) {
				$permalinks['use_verbose_page_rules'] = true;
			}

			update_option( 'a3_portfolio_permalinks', $permalinks );

			if ( function_exists( 'restore_current_locale' ) ) {
				restore_current_locale();
			}
		}
	}
}

endif;
