<?php
/**
 * Elementor integration (works with Elementor free; nothing here needs Elementor Pro):
 * the "Redlynch Dental" widget category and widgets, Theme Builder locations for Pro users,
 * the self-hosted brand fonts in Elementor's font lists, no Google Fonts from Elementor, and
 * editor-preview tweaks.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget files => classes (load order matters: Emergency_Hero extends Page_Hero).
 *
 * @return array<string,string>
 */
function rd_elementor_widgets() {
	return array(
		'buttons'           => 'Buttons',
		'page-hero'         => 'Page_Hero',
		'emergency-hero'    => 'Emergency_Hero',
		'visit-card'        => 'Visit_Card',
		'link-strip'        => 'Link_Strip',
		'path-cards'        => 'Path_Cards',
		'emergency-panel'   => 'Emergency_Panel',
		'icon-cards'        => 'Icon_Cards',
		'service-cards'     => 'Service_Cards',
		'service-directory' => 'Service_Directory',
		'service-sidebar'   => 'Service_Sidebar',
		'related'           => 'Related',
		'team'              => 'Team',
		'faq'               => 'Faq',
		'anchor-nav'        => 'Anchor_Nav',
		'steps'             => 'Steps',
		'callout'           => 'Callout',
		'offer'             => 'Offer',
		'contact-cards'     => 'Contact_Cards',
		'hours'             => 'Hours',
		'map'               => 'Map',
		'contact-form'      => 'Contact_Form',
		'post-feature'      => 'Post_Feature',
		'guide-cards'       => 'Guide_Cards',
		'cta'               => 'Cta',
	);
}

/**
 * Widget category.
 *
 * @param \Elementor\Elements_Manager $manager Elements manager.
 */
function rd_elementor_category( $manager ) {
	$manager->add_category(
		'redlynch-dental',
		array(
			'title' => __( 'Redlynch Dental', 'redlynch-dental' ),
			'icon'  => 'eicon-site-identity',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'rd_elementor_category' );

/**
 * Register the widgets.
 *
 * @param \Elementor\Widgets_Manager $manager Widgets manager.
 */
function rd_elementor_register_widgets( $manager ) {
	require_once __DIR__ . '/base.php';
	foreach ( rd_elementor_widgets() as $file => $class ) {
		require_once __DIR__ . '/widgets/' . $file . '.php';
		$fqcn = '\\RD\\Elementor\\' . $class;
		$manager->register( new $fqcn() );
	}
}
add_action( 'elementor/widgets/register', 'rd_elementor_register_widgets' );

/**
 * Elementor Pro Theme Builder locations (header, footer, single, archive). With Elementor free the
 * theme's own header and footer are used; they are edited in Appearance › Menus and Customize.
 *
 * @param object $manager Elementor Pro locations manager.
 */
function rd_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'rd_elementor_locations' );

/**
 * List the self-hosted brand fonts in Elementor's typography controls (served by the theme, not Google).
 *
 * @param array $fonts Fonts.
 * @return array
 */
function rd_elementor_fonts( $fonts ) {
	$fonts['Fraunces'] = 'system';
	$fonts['Manrope']  = 'system';
	return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'rd_elementor_fonts' );

// Elementor must not load Google Fonts: the theme bundles its fonts.
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/**
 * Editor preview: content that scrolls into view is shown straight away while editing.
 */
function rd_elementor_preview_styles() {
	wp_add_inline_style( 'rd-wp', '.elementor-editor-active [data-reveal],.elementor-editor-active .rd-reveal,.elementor-editor-active .rd-reveal-group>*{opacity:1!important;transform:none!important}' );
}
add_action( 'elementor/preview/enqueue_styles', 'rd_elementor_preview_styles' );
