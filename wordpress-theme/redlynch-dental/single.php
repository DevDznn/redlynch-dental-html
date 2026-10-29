<?php
/**
 * Blog post: cream hero with breadcrumbs, category, byline and date; the article with its
 * featured image; then the call-to-action band. Same layout as the static site's article page.
 *
 * The byline uses the post's "Byline" field (Post › Byline box) when set, e.g.
 * "Dr Alex Bornsztejn, Principal Dentist", otherwise the author's display name.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$rd_blog   = rd_blog_page_id();
	$rd_trail  = array( array( __( 'Home', 'redlynch-dental' ), home_url( '/' ) ) );
	$rd_trail[] = $rd_blog ? array( get_the_title( $rd_blog ), get_permalink( $rd_blog ) ) : array( __( 'Blog', 'redlynch-dental' ), home_url( '/' ) );
	$rd_trail[] = array( get_the_title(), '' );
	$rd_cats   = get_the_category();
	$rd_byline = (string) get_post_meta( get_the_ID(), '_rd_byline', true );
	$rd_byline = '' !== $rd_byline ? $rd_byline : get_the_author();
	?>
<section class="page-hero cream"><div class="wrap narrow">
 <?php echo rd_breadcrumbs( $rd_trail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_breadcrumbs(). ?>
 <div class="post-meta">
	<?php if ( $rd_cats && 'uncategorized' !== $rd_cats[0]->slug ) : ?>
	<span class="tag"><?php echo esc_html( $rd_cats[0]->name ); ?></span>
	<?php endif; ?>
	<?php /* translators: %s: author name and role. */ ?>
	<span><?php echo esc_html( sprintf( __( 'By %s', 'redlynch-dental' ), $rd_byline ) ); ?></span>
	<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
 </div>
 <h1><?php the_title(); ?></h1>
</div></section>
<section class="bg-paper"><div class="wrap">
 <article <?php post_class( 'article prose' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<figure style="margin-top:0"><?php the_post_thumbnail( 'large', array( 'decoding' => 'async' ) ); ?></figure>
	<?php endif; ?>
	<?php
	the_content();
	wp_link_pages();
	?>
	<p class="mt-2"><a class="text-link" href="<?php echo esc_url( $rd_blog ? get_permalink( $rd_blog ) : home_url( '/' ) ); ?>"><?php echo rd_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?> <?php esc_html_e( 'Back to all articles', 'redlynch-dental' ); ?></a></p>
 </article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="article">';
		comments_template();
		echo '</div>';
	}
	?>
</div></section>
	<?php
endwhile;

echo rd_cta_band(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rd_cta_band().

get_footer();
