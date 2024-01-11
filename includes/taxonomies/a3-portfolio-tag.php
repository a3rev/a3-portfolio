<?php

namespace A3Rev\Portfolio\Taxonomy;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tag
{

	public function __construct() {
		add_action( 'portfolio_tag_pre_add_form', array( $this, 'portfolio_tag_description' ) );

		// add color picker fields for portfolio tag taxonomy
		add_action( 'portfolio_tag_add_form_fields', array( $this, 'add_tag_color_fields' ), 11 );

		// add color picker fields to edit portfolio tag taxonomy
		add_action( 'portfolio_tag_edit_form_fields', array( $this, 'edit_tag_color_fields' ), 11 );
		
		add_action( 'edited_portfolio_tag', array( $this, 'save_taxonomy_custom_meta' ), 11 );
		add_action( 'create_portfolio_tag', array( $this, 'save_taxonomy_custom_meta' ), 11 );
		add_action( 'delete_portfolio_tag', array( $this, 'delete_taxonomy_custom_meta' ), 11 );

		// enqueue color picker scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_color_picker' ) );

	}

	public function enqueue_color_picker() {
		if ( ! in_array( basename( $_SERVER['PHP_SELF'] ), array( 'edit-tags.php', 'term.php' ) ) ) return;
		if ( ! isset( $_REQUEST['taxonomy'] ) || ! in_array( $_REQUEST['taxonomy'], array( 'portfolio_tag' ) ) ) return;

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}

	public function portfolio_tag_description() {
		echo wpautop( sprintf( __( 'Use the a3 Portfolios Tag Cloud <a href="%s">widget</a> for navigation.', 'a3-portfolio' ), 'widgets.php' ) );
	}

	public function add_tag_color_fields() {
		?>
		<div class="form-field term-color-wrap">
			<label for="text-color"><?php _e( 'Text Color', 'a3-portfolio' ); ?></label>
			<input name="text-color" id="text-color" class="tag-color" type="text" value="#000000" />
			<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
		</div>
		<div class="form-field term-color-wrap">
			<label for="bg-color"><?php _e( 'Background Color', 'a3-portfolio' ); ?></label>
			<input name="bg-color" id="bg-color" class="tag-color" type="text" value="#ffffff" />
			<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
		</div>
		<div class="form-field term-color-wrap">
			<label for="border-color"><?php _e( 'Border Color', 'a3-portfolio' ); ?></label>
			<input name="border-color" id="border-color" class="tag-color" type="text" value="#000000" />
			<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('.tag-color').wpColorPicker();
			});
		</script>
		<?php
	}

	public function save_taxonomy_custom_meta( $term_id ) {
		if ( ! isset( $_POST['text-color'] ) || ! isset( $_POST['bg-color'] ) || ! isset( $_POST['border-color'] ) ) return;

		$text_color = sanitize_hex_color( $_POST['text-color'] );
		$bg_color = sanitize_hex_color( $_POST['bg-color'] );
		$border_color = sanitize_hex_color( $_POST['border-color'] );

		update_term_meta( $term_id, 'text-color', $text_color );
		update_term_meta( $term_id, 'bg-color', $bg_color );
		update_term_meta( $term_id, 'border-color', $border_color );
	}

	public function delete_taxonomy_custom_meta( $term_id ) {
		delete_term_meta( $term_id, 'text-color' );
		delete_term_meta( $term_id, 'bg-color' );
		delete_term_meta( $term_id, 'border-color' );
	}

	public function edit_tag_color_fields( $term ) {
		$text_color = get_term_meta( $term->term_id, 'text-color', true );
		$bg_color = get_term_meta( $term->term_id, 'bg-color', true );
		$border_color = get_term_meta( $term->term_id, 'border-color', true );
	    ?>
		<tr class="form-field term-color-wrap">
	    	<th scope="row" valign="top"><label for="text-color"><?php _e( 'Text Color', 'a3-portfolio' ); ?></label></th>
	        <td>
				<input name="text-color" id="text-color" class="tag-color" type="text" value="<?php echo esc_attr( $text_color ); ?>" />
	        	<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
	        </td>
		</tr>
		<tr class="form-field term-color-wrap">
	    	<th scope="row" valign="top"><label for="bg-color"><?php _e( 'Background Color', 'a3-portfolio' ); ?></label></th>
	        <td>
				<input name="bg-color" id="bg-color" class="tag-color" type="text" value="<?php echo esc_attr( $bg_color ); ?>" />
	        	<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
	        </td>
	    </tr>
		<tr class="form-field term-color-wrap">
	    	<th scope="row" valign="top"><label for="border-color"><?php _e( 'Border Color', 'a3-portfolio' ); ?></label></th>
	        <td>
				<input name="border-color" id="border-color" class="tag-color" type="text" value="<?php echo esc_attr( $border_color ); ?>" />
	        	<p class="description"><?php _e( 'Choose a colour.', 'a3-portfolio' ); ?></p>
	        	<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('.tag-color').wpColorPicker();
					});
				</script>
	        </td>
	    </tr>
	    <?php
	}

}
