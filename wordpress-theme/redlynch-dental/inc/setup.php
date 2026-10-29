<?php
/**
 * Theme setup: supports, menus, image sizes, body classes, favicons.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports and menu locations.
 */
function rd_setup() {
	load_theme_textdomain( 'redlynch-dental', RD_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 215,
			'width'       => 900,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Team portraits keep their proportions; the layouts crop them with CSS (object-fit) at fixed aspect ratios.
	add_image_size( 'rd-portrait', 720, 900, false );

	register_nav_menus(
		array(
			'primary'         => __( 'Main menu (header and mobile drawer)', 'redlynch-dental' ),
			'footer_services' => __( 'Footer: Services', 'redlynch-dental' ),
			'footer_practice' => __( 'Footer: Practice', 'redlynch-dental' ),
		)
	);
}
add_action( 'after_setup_theme', 'rd_setup' );

/**
 * Content width.
 */
function rd_content_width() {
	$GLOBALS['content_width'] = 1140; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- core convention.
}
add_action( 'after_setup_theme', 'rd_content_width', 0 );

/**
 * Body classes: the static site's page-<name> classes (the homepage is page-index; its mobile
 * contact-card rules in site.css are scoped to it), plus rd-elementor on Elementor pages.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function rd_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-index';
	} elseif ( is_page() ) {
		$classes[] = 'page-' . sanitize_html_class( get_post_field( 'post_name', get_queried_object_id() ) );
	} elseif ( is_404() ) {
		$classes[] = 'page-404';
	}
	if ( is_singular() && rd_is_elementor_page( get_queried_object_id() ) ) {
		$classes[] = 'rd-elementor';
	}
	return $classes;
}
add_filter( 'body_class', 'rd_body_class' );

/**
 * Add html.js before anything renders, so scroll reveals never hide content without JavaScript.
 */
function rd_js_class() {
	wp_print_inline_script_tag( "document.documentElement.classList.add('js')" );
}
add_action( 'wp_head', 'rd_js_class', 0 );

/**
 * Bundled favicons and theme colour, unless a Site Icon is set in the Customizer.
 */
function rd_favicons() {
	echo '<meta name="theme-color" content="#111915">' . "\n";
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" sizes="32x32" type="image/png">' . "\n", esc_url( rd_theme_image( 'branding/favicon-32.png' ) ) );
	printf( '<link rel="icon" href="%s" sizes="192x192" type="image/png">' . "\n", esc_url( rd_theme_image( 'branding/favicon-192.png' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( rd_theme_image( 'branding/apple-touch-icon.png' ) ) );
}
add_action( 'wp_head', 'rd_favicons', 5 );

/**
 * Excerpts on pages (used as a fallback meta description when no SEO plugin is active).
 */
function rd_page_excerpts() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'rd_page_excerpts' );
