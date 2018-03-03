<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

				<h1 class="page-title"><?php the_title(); ?></h1>

				<?php echo do_shortcode( '[a3_portfolio_category ids="'.get_queried_object_id().'" ]' );?>
				
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>