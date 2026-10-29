<?php
/**
 * A post in a list: the guide-card style from the Blog page.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

$rd_cats = get_the_category();
?>
<a <?php post_class( 'guide' ); ?> href="<?php the_permalink(); ?>">
	<span class="k"><?php echo esc_html( $rd_cats && 'uncategorized' !== $rd_cats[0]->slug ? $rd_cats[0]->name : get_the_date( 'j F Y' ) ); ?></span>
	<h3><?php the_title(); ?></h3>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
	<span class="text-link" style="margin-top:auto"><?php esc_html_e( 'Read the article', 'redlynch-dental' ); ?> <?php echo rd_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
</a>
