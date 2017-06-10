<?php
/**
 * The template for displaying the category nav bar on category page
 *
 * Override this template by copying it to yourtheme/portfolios/navbar/category-navbar.php
 *
 * @author 		A3 Rev
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( $menus ) : ?>

<div style="clear:both"></div>

<div class="a3-portfolio-navigation-mobile">
	<i class="fa fa-bars a3-portfolio-navigation-mobile-icon a3-portfolio-icon-list"></i>
	<span><?php echo a3_portfolio_ei_ict_t__( 'Mobile Navigation', __( 'Navigation', 'a3-portfolio' ) ); ?></span>
</div>

<div style="clear:both"></div>

<div class="a3-portfolio-menus-container">

	<div style="clear:both"></div>

	<ul class="filter">

		<li>
			<a data-filter=".a3-portfolio-item" href="#" class="active"><?php echo a3_portfolio_ei_ict_t__( 'All Filter', __( 'All', 'a3-portfolio' ) ); ?></a>
		</li>

		<?php
			foreach ( $menus as $item ):
		?>
		<li class="_<?php echo $item->slug; ?>">
			<a data-filter=".<?php echo $item->slug; ?>" href="#"><?php echo $item->name; ?></a>
		</li>
		<?php
			endforeach;
		?>

	</ul>

	<div style="clear:both"></div>

</div>

<div style="clear:both"></div>

<?php endif; ?>