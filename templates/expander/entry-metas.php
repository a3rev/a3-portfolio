<?php
/**
 * The template for displaying portfolio entry metas in the expander.
 *
 * Override this template by copying it to yourtheme/portfolios/expander/entry-metas.php
 *
 * @author 		A3 Rev
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $a3_portfolio_global_item_expander_settings;

$portfolio_data = get_post( $portfolio_id );
setup_postdata( $portfolio_data );
?>
<div class="portfolio-entry-meta entry-meta">

	<?php if ( $a3_portfolio_global_item_expander_settings['enable_expander_author'] ) : ?>

	<span class="fa fa-user a3-portfolio-icon-user author vcard">

		<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?>" rel="author"><?php the_author(); ?></a>

	</span>

	<?php endif; ?>

	<?php if ( $a3_portfolio_global_item_expander_settings['enable_expander_date'] ) : ?>

	<span class="fa fa-calendar a3-portfolio-icon-calendar">

		<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo get_the_date(); ?></time>

	</span>

	<?php endif; ?>

	<?php if ( comments_open() ) : ?>

	<span class="fa fa-comments a3-portfolio-icon-talk-chat post-comments comments">

		<a href="<?php echo get_comments_link(); ?>"><?php comments_number( ); ?></a>

	</span>

	<?php endif; ?>

</div>
<?php wp_reset_postdata(); ?>