<?php
/**
 * The template for displaying portfolio attribute table on the expander and Item post.
 *
 * Override this template by copying it to yourtheme/portfolios/global/attribute-table.php
 *
 * @author 		A3 Rev
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
?>

<?php if ( $attributes_value ) : ?>

<div class="a3-portfolio-attributes-container">

	<table class="a3-portfolio-attributes-table">

	<?php foreach ( $attributes_value as $attribute ) : ?>

		<tr>
			<th><span class="attribute-label"><?php echo $attribute['label']; ?></span></th>
			<td width="60%"><div class="attribute-value"><?php echo $attribute['value']; ?></div></td>
		</tr>

	<?php endforeach; ?>

	</table>

</div>

<?php endif; ?>