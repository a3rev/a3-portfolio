<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div data-attribute-id="<?php echo esc_attr( $attribute_id ); ?>" data-taxonomy="<?php echo esc_attr( $taxonomy ); ?>" class="portfolio_attribute a3-metabox-item closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo $position; ?>">
	<h3>
		<a href="#" class="remove_row delete"><?php _e( 'Remove', 'a3-portfolio' ); ?></a>
		<div class="a3-metabox-icon handlediv" title="<?php _e( 'Click to toggle', 'a3-portfolio' ); ?>"></div>
		<strong class="attribute_name"><?php echo esc_html( $attribute_label ); ?></strong>
	</h3>
	<div class="portfolio_attribute_data a3-metabox-item-content">
		<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td class="attribute_name">
						<label><?php _e( 'Name', 'a3-portfolio' ); ?>:</label>
						<strong><?php echo esc_html( $attribute_label ); ?></strong>
						<input type="hidden" name="attribute_names[<?php echo $attribute_id; ?>]" value="<?php echo esc_attr( $taxonomy ); ?>" />
					</td>
					<td rowspan="2">

						<?php if ( 'select' === $attribute_taxonomy->attribute_type ) : ?>
							<label><?php _e( 'Value(s)', 'a3-portfolio' ); ?>:</label>

							<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'a3-portfolio' ); ?>" class="multiselect attribute_values a3-portfolio-enhanced-select a3rev-ui-multiselect chzn-select" name="attribute_values[<?php echo $attribute_id; ?>][]" style="width: 100%;">
								<?php
								$orderby = $attribute_taxonomy->attribute_orderby;
								$all_terms = get_terms( $taxonomy, 'orderby='.$orderby.'&hide_empty=0' );
								if ( $all_terms ) {
									foreach ( $all_terms as $term ) {
										echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $thepostid ), true, false ) . '>' . $term->name . '</option>';
									}
								}
								?>
							</select>
							<button class="button plus select_all_attributes"><?php _e( 'Select all', 'a3-portfolio' ); ?></button>
							<button class="button minus select_no_attributes"><?php _e( 'Select none', 'a3-portfolio' ); ?></button>
							<button class="button fr plus add_new_attribute"><?php _e( 'Add new', 'a3_portfolios	' ); ?></button>

						<?php else : ?>
							<label><?php _e( 'Value', 'a3-portfolio' ); ?>:</label>

							<input type="text" name="attribute_values[<?php echo $attribute_id; ?>]" value="<?php
								echo esc_attr( $attribute['value'] );
							?>" placeholder="<?php echo esc_attr( __( 'Enter the value here', 'a3-portfolio' ) ); ?>" />

						<?php endif; ?>

						<?php do_action( 'a3_portfolio_option_terms', $attribute_taxonomy, $attribute_id ); ?>

					</td>
				</tr>
				<tr>
					<td>
						<div><label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_visible_expander'], 1 ); ?> name="attribute_visibility[<?php echo $attribute_id; ?>][expander]" value="1" /> <?php _e( 'Visible on Item expander', 'a3-portfolio' ); ?></label></div>
						<div><label><input type="checkbox" class="checkbox" <?php checked( $attribute['is_visible_post'], 1 ); ?> name="attribute_visibility[<?php echo $attribute_id; ?>][post]" value="1" /> <?php _e( 'Visible on Item Post', 'a3-portfolio' ); ?></label></div>
					</td>
				</tr>
				<?php do_action( 'a3_portfolio_metabox_portfolio_attribute_item', $attribute_id ); ?>
			</tbody>
		</table>
	</div>
</div>
