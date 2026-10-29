<?php
/**
 * Footer: introduction and contact, Services and Practice menus, address and hours, legal line,
 * plus the mobile call/book bar. Elementor Pro users can replace it with a Theme Builder footer.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
	return;
}
$rd_fb = rd_practice( 'facebook_url' );
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- rd_logo_img(), rd_hours_mini() and rd_button() escape their output.
?>
<footer class="site"><div class="wrap">
 <div class="cols">
  <div>
   <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo rd_logo_img( true ); ?></a>
   <p><?php echo esc_html( rd_practice( 'footer_blurb' ) ); ?></p>
   <a class="f-tel" href="<?php echo esc_url( rd_tel_href() ); ?>"><?php echo esc_html( rd_practice( 'phone' ) ); ?></a><br>
   <a href="<?php echo esc_url( 'mailto:' . rd_practice( 'email' ) ); ?>"><?php echo esc_html( rd_practice( 'email' ) ); ?></a>
  </div>
  <?php if ( has_nav_menu( 'footer_services' ) ) : ?>
  <div><h2><?php echo esc_html( rd_menu_name( 'footer_services', __( 'Services', 'redlynch-dental' ) ) ); ?></h2><?php rd_footer_menu( 'footer_services' ); ?></div>
  <?php endif; ?>
  <?php if ( has_nav_menu( 'footer_practice' ) ) : ?>
  <div><h2><?php echo esc_html( rd_menu_name( 'footer_practice', __( 'Practice', 'redlynch-dental' ) ) ); ?></h2><?php rd_footer_menu( 'footer_practice' ); ?></div>
  <?php endif; ?>
  <div><h2><?php esc_html_e( 'Visit us', 'redlynch-dental' ); ?></h2>
   <p><a href="<?php echo esc_url( rd_practice( 'maps_link' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( rd_practice( 'street' ) ); ?><br><?php echo esc_html( rd_practice( 'suburb' ) . ' ' . rd_practice( 'state' ) . ' ' . rd_practice( 'postcode' ) ); ?></a><?php if ( rd_practice( 'location_note' ) ) : ?><br><span style="font-size:.86rem"><?php echo esc_html( rd_practice( 'location_note' ) ); ?></span><?php endif; ?></p>
   <?php echo rd_hours_mini( '' ); ?>
   <?php if ( $rd_fb ) : ?>
   <p style="margin-top:14px"><a href="<?php echo esc_url( $rd_fb ); ?>" target="_blank" rel="noopener"><?php /* translators: %s: practice name. */ echo esc_html( sprintf( __( '%s on Facebook', 'redlynch-dental' ), rd_practice( 'name' ) ) ); ?> ↗<span class="sr-only"><?php esc_html_e( ' (opens in a new tab)', 'redlynch-dental' ); ?></span></a></p>
   <?php endif; ?>
  </div>
 </div>
 <div class="f-bottom">
  <span>© <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( rd_practice( 'legal_name' ) ); ?> · <?php echo esc_html( rd_practice( 'name' ) ); ?> · <a href="<?php echo esc_url( rd_privacy_url() ); ?>"><?php esc_html_e( 'Privacy policy', 'redlynch-dental' ); ?></a></span>
  <p><?php echo esc_html( rd_practice( 'footer_risk' ) ); ?></p>
 </div>
</div></footer>
<div class="mcall">
<?php
echo rd_button(
	array(
		'kind'  => 'call',
		'label' => __( 'Call us', 'redlynch-dental' ),
	)
);
echo rd_button(
	array(
		'kind'  => 'book',
		'class' => 'white',
		'label' => __( 'Book online', 'redlynch-dental' ),
	)
);
?>
</div>
<?php
// phpcs:enable
