<?php
/**
 * Top line, header bar, main menu (with the mobile drawer's buttons) and scrim.
 * Elementor Pro users can replace this with a Theme Builder header; see the README.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	return;
}
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- rd_icon() is static SVG; rd_button() and rd_logo_img() escape their output.
?>
<?php if ( get_theme_mod( 'rd_show_topline', true ) ) : ?>
<div class="topline"><div class="wrap">
 <div class="tl-l">
  <span class="tl-item tl-addr"><?php echo rd_icon( 'pin' ); ?><a href="<?php echo esc_url( rd_practice( 'maps_link' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( rd_address() ); ?></a></span>
  <span class="tl-item tl-hours status" data-status><i></i><span><?php echo esc_html( rd_hours_summary() ); ?></span></span>
 </div>
 <div class="tl-r">
  <a class="tl-item tl-em" href="<?php echo esc_url( rd_page_url( 'emergency-dental-care' ) ); ?>"><?php echo rd_icon( 'alert' ); ?> <?php /* translators: %s: phone number. */ echo esc_html( sprintf( __( 'Dental emergency? Call %s', 'redlynch-dental' ), rd_practice( 'phone' ) ) ); ?></a>
 </div>
</div></div>
<?php endif; ?>
<header class="site"><div class="wrap">
 <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo rd_logo_img(); ?></a>
 <nav class="main" id="site-nav" aria-label="<?php esc_attr_e( 'Main', 'redlynch-dental' ); ?>">
  <div class="nav-mobile-extra">
   <?php
	echo rd_button(
		array(
			'kind'  => 'emergency',
			'class' => 'ember',
		)
	);
	echo rd_button( array( 'kind' => 'call' ) );
	echo rd_button(
		array(
			'kind'  => 'book',
			'class' => 'ghost-light',
		)
	);
	?>
  </div>
  <?php rd_primary_menu(); ?>
 </nav>
 <div class="hdr-actions">
  <a class="hdr-phone" href="<?php echo esc_url( rd_tel_href() ); ?>" aria-label="<?php /* translators: 1: practice name, 2: phone number. */ echo esc_attr( sprintf( __( 'Call %1$s on %2$s', 'redlynch-dental' ), rd_practice( 'name' ), rd_practice( 'phone' ) ) ); ?>"><?php echo rd_icon( 'phone' ); ?><span><?php echo esc_html( rd_practice( 'phone' ) ); ?></span></a>
  <?php
	echo rd_button(
		array(
			'kind'  => 'book',
			'class' => 'sm',
		)
	);
	?>
  <button class="burger" type="button" aria-expanded="false" aria-controls="site-nav"><span class="bars" aria-hidden="true"><span></span></span><span class="lbl"><?php esc_html_e( 'Menu', 'redlynch-dental' ); ?></span></button>
 </div>
</div></header>
<div class="nav-scrim" aria-hidden="true"></div>
<?php
// phpcs:enable
