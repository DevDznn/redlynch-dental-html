<?php
/**
 * Pages. Pages built with Elementor (all the imported pages) draw their own sections, including
 * the hero and call-to-action band; other pages get the standard hero, text column and band.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	if ( rd_is_elementor_page( get_the_ID() ) ) {
		the_content();
		continue;
	}
	get_template_part( 'template-parts/content/page' );
endwhile;

get_footer();
