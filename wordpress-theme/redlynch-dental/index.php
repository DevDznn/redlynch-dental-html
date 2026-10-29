<?php
/**
 * Fallback for post lists: archives, search results and the posts page (if one is set in
 * Settings › Reading; the imported Blog page is an Elementor page instead).
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_search() ) {
	/* translators: %s: search terms. */
	$rd_title = sprintf( __( 'Search results for “%s”', 'redlynch-dental' ), get_search_query() );
} elseif ( is_archive() ) {
	$rd_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$rd_title = is_home() && get_option( 'page_for_posts' ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'Blog', 'redlynch-dental' );
}
?>
<section class="page-hero cream"><div class="wrap">
 <?php
	echo rd_breadcrumbs( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_breadcrumbs().
		array(
			array( __( 'Home', 'redlynch-dental' ), home_url( '/' ) ),
			array( $rd_title, '' ),
		)
	);
	?>
 <h1><?php echo esc_html( $rd_title ); ?></h1>
</div></section>
<section class="bg-paper"><div class="wrap">
<?php if ( have_posts() ) : ?>
 <div class="guide-grid">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content/post-card' );
	endwhile;
	?>
 </div>
	<?php
	the_posts_pagination(
		array(
			'prev_text' => __( 'Newer', 'redlynch-dental' ),
			'next_text' => __( 'Older', 'redlynch-dental' ),
		)
	);
	?>
<?php else : ?>
 <p class="lead"><?php esc_html_e( 'Nothing was found. Try another search, or call us and we will help.', 'redlynch-dental' ); ?></p>
 <?php get_search_form(); ?>
<?php endif; ?>
</div></section>
<?php
echo rd_cta_band(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_cta_band().
get_footer();
