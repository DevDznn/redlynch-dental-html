<?php
/**
 * A page that is not built with Elementor: cream hero with breadcrumbs, a text column, the band.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

$rd_trail = array( array( __( 'Home', 'redlynch-dental' ), home_url( '/' ) ) );
foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $rd_parent ) {
	$rd_trail[] = array( get_the_title( $rd_parent ), get_permalink( $rd_parent ) );
}
$rd_trail[] = array( get_the_title(), '' );
?>
<section class="page-hero cream"><div class="wrap narrow">
 <?php echo rd_breadcrumbs( $rd_trail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_breadcrumbs(). ?>
 <h1><?php the_title(); ?></h1>
 <?php if ( has_excerpt() ) : ?>
	<p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
 <?php endif; ?>
</div></section>
<section class="bg-paper"><div class="wrap narrow prose">
 <?php
	the_content();
	wp_link_pages();
	?>
</div></section>
<?php
if ( comments_open() || get_comments_number() ) {
	echo '<section><div class="wrap narrow">';
	comments_template();
	echo '</div></section>';
}
echo rd_cta_band(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_cta_band().
