<?php
/**
 * Template tags: icons, buttons, breadcrumbs, hours tables, the map and the call-to-action band.
 * Each prints the same markup as the static site's _build/lib.js, so the site stylesheet applies unchanged.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon markup (decorative: aria-hidden).
 *
 * @param string $name  Icon name.
 * @param string $class Optional class.
 * @return string
 */
function rd_icon( $name, $class = '' ) {
	$paths = rd_icon_paths();
	$path  = $paths[ $name ] ?? $paths['tooth'];
	return '<svg' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $path . '</svg>';
}

/**
 * Allowed tags for icon SVG output passed through wp_kses.
 *
 * @return array
 */
function rd_svg_kses() {
	$shape = array(
		'd'            => true,
		'cx'           => true,
		'cy'           => true,
		'r'            => true,
		'x'            => true,
		'y'            => true,
		'width'        => true,
		'height'       => true,
		'rx'           => true,
		'stroke-width' => true,
	);
	return array(
		'svg'    => array(
			'class'           => true,
			'viewbox'         => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'aria-hidden'     => true,
			'focusable'       => true,
		),
		'path'   => $shape,
		'circle' => $shape,
		'rect'   => $shape,
	);
}

/**
 * Icon names for select controls.
 *
 * @param bool $with_none Include "No icon".
 * @return array<string,string>
 */
function rd_icon_options( $with_none = false ) {
	$out = $with_none ? array( '' => __( 'No icon', 'redlynch-dental' ) ) : array();
	foreach ( array_keys( rd_icon_paths() ) as $k ) {
		$out[ $k ] = ucfirst( $k );
	}
	return $out;
}

/**
 * Button markup.
 *
 * Kinds: 'book' (online booking, new tab), 'call' (tel: link), 'emergency' (emergency page),
 * 'email' (mailto:) and 'link' (custom URL).
 *
 * @param array $b {
 *     @type string $kind    Button kind.
 *     @type string $label   Text ('' = default for the kind).
 *     @type string $class   Style classes, e.g. 'ghost lg'.
 *     @type string $url     For 'link'.
 *     @type bool   $new_tab For 'link'.
 *     @type string $icon    Leading icon name ('' = default for the kind).
 *     @type bool   $arrow   Trailing arrow (default: true for book/link).
 * }
 * @return string
 */
function rd_button( $b ) {
	$b     = wp_parse_args(
		$b,
		array(
			'kind'    => 'link',
			'label'   => '',
			'class'   => '',
			'url'     => '',
			'new_tab' => false,
			'icon'    => '',
			'arrow'   => null,
		)
	);
	$kind  = $b['kind'];
	$sr    = '';
	$attrs = '';
	switch ( $kind ) {
		case 'book':
			$url   = rd_practice( 'booking_url' );
			$label = '' !== $b['label'] ? $b['label'] : __( 'Book online', 'redlynch-dental' );
			$sr    = __( ' (opens the practice’s online booking system in a new tab)', 'redlynch-dental' );
			$attrs = ' target="_blank" rel="noopener"';
			$arrow = null === $b['arrow'] ? true : (bool) $b['arrow'];
			break;
		case 'call':
			$url = rd_tel_href();
			/* translators: %s: phone number. */
			$label     = '' !== $b['label'] ? str_replace( '{phone}', rd_practice( 'phone' ), $b['label'] ) : sprintf( __( 'Call %s', 'redlynch-dental' ), rd_practice( 'phone' ) );
			$b['icon'] = '' !== $b['icon'] ? $b['icon'] : 'phone';
			$arrow     = (bool) $b['arrow'];
			break;
		case 'emergency':
			$url       = rd_page_url( 'emergency-dental-care' );
			$label     = '' !== $b['label'] ? $b['label'] : __( 'Dental emergency?', 'redlynch-dental' );
			$b['icon'] = '' !== $b['icon'] ? $b['icon'] : 'alert';
			$arrow     = (bool) $b['arrow'];
			break;
		case 'email':
			$url       = 'mailto:' . rd_practice( 'email' );
			$label     = '' !== $b['label'] ? $b['label'] : rd_practice( 'email' );
			$b['icon'] = '' !== $b['icon'] ? $b['icon'] : 'mail';
			$arrow     = (bool) $b['arrow'];
			break;
		default:
			$url   = $b['url'];
			$label = $b['label'];
			$arrow = null === $b['arrow'] ? false : (bool) $b['arrow'];
			if ( $b['new_tab'] ) {
				$attrs = ' target="_blank" rel="noopener"';
				$sr    = __( ' (opens in a new tab)', 'redlynch-dental' );
			}
	}
	$icon = ( $b['icon'] && 'none' !== $b['icon'] ) ? rd_icon( $b['icon'] ) . ' ' : '';
	return sprintf(
		'<a class="btn%1$s" href="%2$s"%3$s>%4$s%5$s%6$s%7$s</a>',
		$b['class'] ? ' ' . esc_attr( $b['class'] ) : '',
		esc_url( $url, array( 'http', 'https', 'tel', 'mailto' ) ),
		$attrs,
		$icon,
		esc_html( $label ),
		$arrow ? ' ' . rd_icon( 'arrow', 'arr' ) : '',
		$sr ? '<span class="sr-only">' . esc_html( $sr ) . '</span>' : ''
	);
}

/**
 * Breadcrumb trail markup: Home / [parents] / current.
 *
 * @param array $trail List of [label, url]; the last item is the current page (url ignored).
 * @return string
 */
function rd_breadcrumbs( $trail ) {
	$out  = '<nav class="crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'redlynch-dental' ) . '">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $item ) {
		if ( $i === $last ) {
			$out .= '<span aria-current="page">' . esc_html( $item[0] ) . '</span>';
		} else {
			$out .= '<a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a><span aria-hidden="true">/</span>';
		}
	}
	return $out . '</nav>';
}

/**
 * Full opening hours table (rows carry data-day so main.js can highlight today).
 *
 * @param string $class Table class.
 * @return string
 */
function rd_hours_table( $class = 'hours' ) {
	$rows = '';
	foreach ( rd_hours() as $d ) {
		$rows .= sprintf( '<tr data-day="%d"><th scope="row">%s</th><td>%s</td></tr>', (int) $d['num'], esc_html( $d['day'] ), esc_html( $d['label'] ) );
	}
	return '<table class="' . esc_attr( $class ) . '"><caption class="sr-only">' . esc_html__( 'Opening hours', 'redlynch-dental' ) . '</caption><tbody>' . $rows . '</tbody></table>';
}

/**
 * Compact opening hours (grouped days), as in the service sidebar and footer.
 *
 * @param string $class Table class ('' for none).
 * @return string
 */
function rd_hours_mini( $class = 'hours-mini' ) {
	$rows = '';
	foreach ( rd_hours_groups() as $g ) {
		$rows .= '<tr><td>' . esc_html( $g['label'] ) . '</td><td>' . esc_html( $g['short'] ) . '</td></tr>';
	}
	return '<table' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '><tbody>' . $rows . '</tbody></table>';
}

/**
 * Opening status pill (main.js replaces the text with "Open now · until 5pm" etc.).
 *
 * @param string $class Extra classes.
 * @return string
 */
function rd_status( $class = '' ) {
	return '<div class="status' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" data-status><i></i><span>' . esc_html( rd_hours_summary() ) . '</span></div>';
}

/**
 * The embedded map: one iframe, a loading/failed state and a "Reload map" button (wired in main.js).
 *
 * @return string
 */
function rd_map_box() {
	$embed = rd_practice( 'map_embed' );
	$frame = $embed
		? sprintf(
			'<iframe src="%1$s" title="%2$s" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>',
			esc_url( $embed ),
			/* translators: 1: practice name, 2: address. */
			esc_attr( sprintf( __( 'Map showing %1$s, %2$s', 'redlynch-dental' ), rd_practice( 'name' ), rd_address() ) )
		)
		: '';
	return '<figure class="map" data-map>
 <div class="map-frame">' . $frame . '
  <div class="map-state" role="status" aria-live="polite"><span class="map-spin" aria-hidden="true"></span><span class="map-msg">' . esc_html__( 'Loading map…', 'redlynch-dental' ) . '</span></div>
 </div>
 <figcaption class="map-bar"><span class="map-addr">' . rd_icon( 'pin' ) . '<span><b>' . esc_html( rd_practice( 'name' ) ) . '</b> ' . esc_html( rd_practice( 'street' ) . ', ' . rd_practice( 'suburb' ) ) . '</span></span><button class="map-reload" type="button">' . rd_icon( 'refresh' ) . '<span>' . esc_html__( 'Reload map', 'redlynch-dental' ) . '</span></button></figcaption>
</figure>';
}

/**
 * Call-to-action band.
 *
 * @param string $heading Heading ('' = Customizer default).
 * @param string $text    Text ('' = Customizer default).
 * @return string
 */
function rd_cta_band( $heading = '', $text = '' ) {
	$heading = '' !== $heading ? $heading : rd_practice( 'cta_heading' );
	$text    = '' !== $text ? $text : rd_practice( 'cta_text' );
	$id      = wp_unique_id( 'cta-h-' );
	return '<section class="cta" aria-labelledby="' . esc_attr( $id ) . '"><div class="wrap">
 <div data-reveal><h2 id="' . esc_attr( $id ) . '">' . esc_html( $heading ) . '</h2><p>' . esc_html( $text ) . '</p></div>
 <div class="actions" data-reveal>' . rd_button(
		array(
			'kind'  => 'call',
			'label' => rd_practice( 'phone' ),
			'class' => 'dark lg',
		)
	) . rd_button(
		array(
			'kind'  => 'book',
			'class' => 'ghost lg',
		)
	) . '</div>
</div></section>';
}

/**
 * Privacy policy page URL (Settings › Privacy), falling back to /privacy-policy/.
 *
 * @return string
 */
function rd_privacy_url() {
	$url = get_privacy_policy_url();
	return $url ? $url : rd_page_url( 'privacy-policy' );
}

/**
 * Header logo: the Customizer's Site Identity logo, or the bundled light logo for the dark header.
 *
 * @param bool $lazy Lazy-load (footer).
 * @return string
 */
function rd_logo_img( $lazy = false ) {
	/* translators: %s: practice name. */
	$alt = sprintf( __( '%s home', 'redlynch-dental' ), rd_practice( 'name' ) );
	$id  = (int) get_theme_mod( 'custom_logo' );
	if ( $lazy && (int) get_theme_mod( 'rd_footer_logo' ) ) {
		$id = (int) get_theme_mod( 'rd_footer_logo' );
	}
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image(
			$id,
			'full',
			false,
			array(
				'alt'     => $alt,
				'loading' => $lazy ? 'lazy' : false,
				'class'   => '',
			)
		);
	}
	return sprintf(
		'<img src="%s" alt="%s" width="900" height="215"%s>',
		esc_url( RD_THEME_URI . '/assets/images/branding/redlynch-dental-logo-light.png' ),
		esc_attr( $alt ),
		$lazy ? ' loading="lazy"' : ''
	);
}

/**
 * URL of a bundled theme image.
 *
 * @param string $rel Path inside assets/images/.
 * @return string
 */
function rd_theme_image( $rel ) {
	return RD_THEME_URI . '/assets/images/' . ltrim( $rel, '/' );
}

/**
 * Should the page show the default hero? Elementor pages and the importer's pages draw their own.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function rd_is_elementor_page( $post_id ) {
	return 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true ) && did_action( 'elementor/loaded' );
}
