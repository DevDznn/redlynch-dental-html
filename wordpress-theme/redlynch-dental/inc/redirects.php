<?php
/**
 * Old addresses → WordPress pages (301), only when the request would otherwise be a 404.
 *
 * Covers the static site's file names (/about.html → /about/) and the practice's previous
 * website's addresses that changed (/about-us → /about/, /whitening → /teeth-whitening/ …).
 * Addresses that are the same on the old site and here (/dental-implants, /contact …) need
 * nothing. Redirects added in ThinkRank (or the server) take priority because they run first.
 * Turn this off with: add_filter( 'rd_legacy_redirects', '__return_false' );
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path (no slashes, no .html) => new page path or 'post:slug' / 'home'.
 *
 * @return array<string,string>
 */
function rd_legacy_map() {
	return array(
		'index'                             => 'home',
		'about-us'                          => 'about',
		'meet-our-team'                     => 'our-team',
		'crowns-and-bridge-work'            => 'crowns-and-bridges',
		'whitening'                         => 'teeth-whitening',
		'blog-why-choose-dental-implants'   => 'post:why-choose-dental-implants',
		'blog/why-choose-dental-implants'   => 'post:why-choose-dental-implants',
	);
}

/**
 * Redirect old addresses on 404.
 */
function rd_legacy_redirect() {
	if ( ! is_404() || ! apply_filters( 'rd_legacy_redirects', true ) ) {
		return;
	}
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$base = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( '' !== $base && '/' !== $base && 0 === strpos( $path, $base ) ) {
		$path = substr( $path, strlen( $base ) );
	}
	$key = strtolower( trim( preg_replace( '/\.html?$/i', '', trim( $path, '/' ) ), '/' ) );
	if ( '' === $key ) {
		return;
	}
	$map    = rd_legacy_map();
	$target = $map[ $key ] ?? ( preg_match( '/\.html?$/i', $path ) ? $key : '' );
	if ( '' === $target ) {
		return;
	}
	$url = '';
	if ( 'home' === $target ) {
		$url = home_url( '/' );
	} elseif ( 0 === strpos( $target, 'post:' ) ) {
		$post = get_page_by_path( substr( $target, 5 ), OBJECT, 'post' );
		$url  = $post ? get_permalink( $post ) : '';
	} else {
		$page = get_page_by_path( $target );
		$url  = $page ? get_permalink( $page ) : '';
	}
	if ( $url ) {
		wp_safe_redirect( $url, 301, 'Redlynch Dental theme' );
		exit;
	}
}
add_action( 'template_redirect', 'rd_legacy_redirect', 1 );
