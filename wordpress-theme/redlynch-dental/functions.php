<?php
/**
 * Redlynch Dental theme bootstrap.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

define( 'RD_THEME_VERSION', '1.0.0' );
define( 'RD_THEME_DIR', get_template_directory() );
define( 'RD_THEME_URI', get_template_directory_uri() );

require_once RD_THEME_DIR . '/inc/icon-paths.php';
require_once RD_THEME_DIR . '/inc/practice.php';
require_once RD_THEME_DIR . '/inc/template-tags.php';
require_once RD_THEME_DIR . '/inc/setup.php';
require_once RD_THEME_DIR . '/inc/assets.php';
require_once RD_THEME_DIR . '/inc/services.php';
require_once RD_THEME_DIR . '/inc/team.php';
require_once RD_THEME_DIR . '/inc/posts.php';
require_once RD_THEME_DIR . '/inc/navigation.php';
require_once RD_THEME_DIR . '/inc/seo.php';
require_once RD_THEME_DIR . '/inc/redirects.php';
require_once RD_THEME_DIR . '/inc/elementor/loader.php';

if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once RD_THEME_DIR . '/inc/import/importer.php';
}
