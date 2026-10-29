<?php
/**
 * Styles, scripts and fonts. Everything is served from the theme: no Google Fonts, no CDN.
 *
 * Load order: Elementor's CSS → wp-base.css (resets Elementor/WordPress defaults) → site.css
 * (the static site's stylesheet, unchanged) → wp.css (WordPress menus and Elementor containers).
 * Loading after Elementor lets the site's rules win where both set the same property.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset version: file modification time in development, theme version otherwise.
 *
 * @param string $rel Path inside the theme.
 * @return string
 */
function rd_asset_ver( $rel ) {
	$file = RD_THEME_DIR . '/' . $rel;
	return ( defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $file ) ) ? (string) filemtime( $file ) : RD_THEME_VERSION;
}

/**
 * Enqueue front-end assets.
 */
function rd_enqueue_assets() {
	// Print after Elementor's stylesheet when Elementor is in use on this page (never force-load it).
	$deps = wp_style_is( 'elementor-frontend', 'enqueued' ) ? array( 'elementor-frontend' ) : array();
	wp_enqueue_style( 'rd-base', RD_THEME_URI . '/assets/css/wp-base.css', $deps, rd_asset_ver( 'assets/css/wp-base.css' ) );
	wp_enqueue_style( 'rd-site', RD_THEME_URI . '/assets/css/site.css', array( 'rd-base' ), rd_asset_ver( 'assets/css/site.css' ) );
	wp_enqueue_style( 'rd-wp', RD_THEME_URI . '/assets/css/wp.css', array( 'rd-site' ), rd_asset_ver( 'assets/css/wp.css' ) );

	wp_enqueue_script(
		'rd-main',
		RD_THEME_URI . '/assets/js/main.js',
		array(),
		rd_asset_ver( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_add_inline_script( 'rd-main', 'window.REDLYNCH=' . wp_json_encode( rd_js_data() ) . ';', 'before' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rd_enqueue_assets', 20 );

/**
 * Print the theme's stylesheets after every Elementor stylesheet on the page (its frontend CSS,
 * per-widget CSS and per-page CSS are queued later than the theme's), so the site's rules win
 * where both set the same property, as the load-order note at the top of this file describes.
 */
function rd_styles_after_elementor() {
	$styles = wp_styles();
	if ( empty( $styles->registered['rd-base'] ) ) {
		return;
	}
	$deps = array();
	foreach ( $styles->queue as $handle ) {
		if ( preg_match( '/^(elementor|widget-|e-|base-)/', $handle ) ) {
			$deps[] = $handle;
		}
	}
	$styles->registered['rd-base']->deps = array_values( array_unique( array_merge( $styles->registered['rd-base']->deps, $deps ) ) );
}
add_action( 'wp_print_styles', 'rd_styles_after_elementor' );

/**
 * Preload the two main font files (Latin subsets).
 */
function rd_preload_fonts() {
	foreach ( array( 'manrope-latin.woff2', 'fraunces-latin.woff2' ) as $f ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( RD_THEME_URI . '/assets/fonts/' . $f ) );
	}
}
add_action( 'wp_head', 'rd_preload_fonts', 2 );

/**
 * Block editor: the brand fonts and colours for posts edited without Elementor.
 */
function rd_editor_assets() {
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/wp-base.css', 'assets/css/editor.css' ) );
}
add_action( 'after_setup_theme', 'rd_editor_assets' );
