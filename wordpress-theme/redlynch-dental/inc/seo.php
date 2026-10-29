<?php
/**
 * SEO ownership: ThinkRank (the site's SEO plugin) owns titles, meta descriptions, canonical URLs,
 * robots directives, Open Graph/X tags, XML sitemaps, robots.txt and all schema, including the
 * practice's Dentist/LocalBusiness entity (ThinkRank › Local SEO).
 *
 * The theme prints none of those. The <title> comes from WordPress core (title-tag support),
 * which ThinkRank replaces. The theme never prints a robots meta tag and never writes robots.txt:
 * a staging site is hidden with Settings › Reading › "Discourage search engines", which
 * ThinkRank respects, and production visibility is set in ThinkRank. Nothing from the static
 * site's staging build (noindex everywhere, robots.txt "Disallow: /") is carried over.
 *
 * Only while NO SEO plugin is active does the theme add a basic meta description from the
 * page excerpt, so a fresh install isn't left without one. It never adds JSON-LD.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is an SEO plugin handling metadata?
 *
 * @return bool
 */
function rd_seo_plugin_active() {
	$active = defined( 'THINKRANK_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) || defined( 'SLIM_SEO_VER' );
	return (bool) apply_filters( 'rd_seo_plugin_active', $active );
}

/**
 * Fallback meta description (only without an SEO plugin).
 */
function rd_fallback_meta_description() {
	if ( rd_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$desc = '';
	if ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
	}
	if ( '' === trim( $desc ) && is_front_page() ) {
		$desc = get_bloginfo( 'description' );
	}
	$desc = trim( wp_strip_all_tags( $desc ) );
	if ( '' !== $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'rd_fallback_meta_description', 1 );

/**
 * Admin notice pointing to ThinkRank while no SEO plugin is active.
 */
function rd_seo_notice() {
	if ( rd_seo_plugin_active() || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'appearance_page_rd-setup' ), true ) ) {
		return;
	}
	echo '<div class="notice notice-info"><p>' . esc_html__( 'Redlynch Dental theme: install and activate the ThinkRank plugin to manage SEO titles, descriptions, canonical URLs, sitemaps, robots and structured data (Local SEO). The theme deliberately outputs none of these.', 'redlynch-dental' ) . '</p></div>';
}
add_action( 'admin_notices', 'rd_seo_notice' );
