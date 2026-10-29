<?php
/**
 * Page not found (the static site's 404 page).
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

get_header();
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- rd_button() and rd_cta_band() escape their output.
?>
<section class="page-hero" style="min-height:50vh"><div class="wrap">
 <span class="eyebrow"><?php esc_html_e( 'Error 404', 'redlynch-dental' ); ?></span>
 <h1><?php esc_html_e( 'Sorry, we can’t find that page.', 'redlynch-dental' ); ?></h1>
 <p class="lead"><?php /* translators: %s: phone number. */ echo esc_html( sprintf( __( 'The page may have moved. Try one of these instead, or call us on %s.', 'redlynch-dental' ), rd_practice( 'phone' ) ) ); ?></p>
 <div class="actions mt-1">
	<?php
	echo rd_button(
		array(
			'url'   => home_url( '/' ),
			'label' => __( 'Home', 'redlynch-dental' ),
		)
	);
	echo rd_button(
		array(
			'url'   => rd_page_url( 'services' ),
			'label' => __( 'Services', 'redlynch-dental' ),
			'class' => 'ghost-light',
		)
	);
	echo rd_button(
		array(
			'kind'  => 'emergency',
			'label' => __( 'Emergency dental care', 'redlynch-dental' ),
			'class' => 'ember',
			'icon'  => 'none',
		)
	);
	echo rd_button(
		array(
			'url'   => rd_page_url( 'contact' ),
			'label' => __( 'Contact', 'redlynch-dental' ),
			'class' => 'ghost-light',
		)
	);
	?>
 </div>
</div></section>
<?php
echo rd_cta_band();
// phpcs:enable
get_footer();
