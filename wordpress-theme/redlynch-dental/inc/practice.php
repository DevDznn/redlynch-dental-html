<?php
/**
 * Practice details and opening hours (Appearance › Customize › Redlynch Dental).
 *
 * The defaults are the facts verified on the official site (redlynchdental.com.au, 26 October 2026),
 * with the opening hours confirmed by the practice on 29 October 2026: the same values the static site's _build/lib.js uses. Header, footer, contact cards, the hours
 * tables, the "Open now" indicator, booking and call buttons and the map all read from here, so a
 * change in the Customizer updates every page.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default practice details.
 *
 * @return array<string,string>
 */
function rd_practice_defaults() {
	return array(
		'name'          => 'Redlynch Dental',
		'legal_name'    => 'Chelsea Lane Dental Pty Ltd',
		'phone'         => '(07) 4039 4720',
		'phone_intl'    => '+61740394720',
		'email'         => 'reception@redlynchdental.com.au',
		'street'        => '4/2 Chelsea Lane',
		'suburb'        => 'Redlynch',
		'state'         => 'QLD',
		'postcode'      => '4870',
		'location_note' => 'Within the Redlynch Commercial Hub',
		'booking_url'   => 'https://www.centaurportal.com/d4w/org-2836/search?practiceID=3243',
		'facebook_url'  => 'https://www.facebook.com/Redlynch-Dental-102113211727578',
		'maps_link'     => 'https://www.google.com/maps/search/?api=1&query=Redlynch+Dental+4%2F2+Chelsea+Lane+Redlynch+QLD+4870',
		'map_embed'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7635.351081263452!2d145.69347367458835!3d-16.891937453509275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6978656ebcfc004d%3A0x465a2fade70d802a!2sRedlynch%20Dental!5e0!3m2!1sen!2sau!4v1611892217850!5m2!1sen!2sau',
		'timezone'      => 'Australia/Brisbane',
		'hours_note'    => '',
		'hours_summary' => '',
		'footer_blurb'  => 'Gentle and affordable dentistry for families in Redlynch, Brinsmead, Edge Hill, Smithfield and surrounding Cairns suburbs.',
		'footer_risk'   => 'Any surgical or invasive procedure carries risks. Before proceeding, you should seek a second opinion from an appropriately qualified health practitioner.',
		'mega_title'    => 'In pain right now?',
		'mega_text'     => 'We reserve appointments every day for dental emergencies. Call us straight away.',
		'cta_heading'   => 'Ready when you are.',
		'cta_text'      => 'New patients of all ages are welcome. Call our friendly Redlynch team or book online, and we will find a time that suits you.',
	);
}

/**
 * Days in display order (Monday first). Day numbers follow JavaScript: 0 = Sunday.
 *
 * @return array<int,string> day number => name
 */
function rd_days() {
	return array(
		1 => 'Monday',
		2 => 'Tuesday',
		3 => 'Wednesday',
		4 => 'Thursday',
		5 => 'Friday',
		6 => 'Saturday',
		0 => 'Sunday',
	);
}

/**
 * Default opening hours ('' = closed).
 *
 * @return array<int,array{0:string,1:string}>
 */
function rd_hours_defaults() {
	return array(
		1 => array( '08:00', '17:30' ),
		2 => array( '08:00', '17:30' ),
		3 => array( '08:00', '18:30' ),
		4 => array( '08:00', '18:30' ),
		5 => array( '08:00', '16:30' ),
		6 => array( '', '' ),
		0 => array( '', '' ),
	);
}

/**
 * A practice detail from the Customizer (or its verified default).
 *
 * @param string $key Detail key.
 * @return string
 */
function rd_practice( $key ) {
	$defaults = rd_practice_defaults();
	$value    = get_theme_mod( 'rd_' . $key, $defaults[ $key ] ?? '' );
	return is_string( $value ) ? $value : (string) ( $defaults[ $key ] ?? '' );
}

/**
 * The tel: link for the practice phone number.
 *
 * @return string
 */
function rd_tel_href() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', rd_practice( 'phone_intl' ) );
}

/**
 * Full one-line address.
 *
 * @return string
 */
function rd_address() {
	return sprintf( '%s, %s %s %s', rd_practice( 'street' ), rd_practice( 'suburb' ), rd_practice( 'state' ), rd_practice( 'postcode' ) );
}

/**
 * Format an HH:MM time as 8:00am (long) or 8am / 4:30pm (short).
 *
 * @param string $hhmm  Time.
 * @param bool   $short Drop ":00".
 * @return string
 */
function rd_format_time( $hhmm, $short = false ) {
	if ( ! preg_match( '/^(\d{1,2}):(\d{2})$/', $hhmm, $m ) ) {
		return '';
	}
	$h   = (int) $m[1];
	$min = $m[2];
	$ap  = $h >= 12 ? 'pm' : 'am';
	$h12 = $h % 12 ? $h % 12 : 12;
	return ( $short && '00' === $min ) ? $h12 . $ap : $h12 . ':' . $min . $ap;
}

/**
 * Opening hours, Monday first.
 *
 * @return array<int,array{day:string,num:int,open:string,close:string,label:string,short:string}>
 */
function rd_hours() {
	$defaults = rd_hours_defaults();
	$out      = array();
	foreach ( rd_days() as $num => $day ) {
		$open  = (string) get_theme_mod( 'rd_hours_' . $num . '_open', $defaults[ $num ][0] );
		$close = (string) get_theme_mod( 'rd_hours_' . $num . '_close', $defaults[ $num ][1] );
		$is    = '' !== rd_format_time( $open ) && '' !== rd_format_time( $close );
		$out[] = array(
			'day'   => $day,
			'num'   => $num,
			'open'  => $is ? $open : '',
			'close' => $is ? $close : '',
			/* translators: 1: opening time, 2: closing time. */
			'label' => $is ? sprintf( __( '%1$s – %2$s', 'redlynch-dental' ), rd_format_time( $open ), rd_format_time( $close ) ) : __( 'Closed', 'redlynch-dental' ),
			/* translators: 1: opening time, 2: closing time. */
			'short' => $is ? sprintf( __( '%1$s – %2$s', 'redlynch-dental' ), rd_format_time( $open, true ), rd_format_time( $close, true ) ) : __( 'Closed', 'redlynch-dental' ),
		);
	}
	return $out;
}

/**
 * Consecutive days with the same hours, for the compact tables: "Mon – Tue", "Friday", "Sat – Sun".
 *
 * @return array<int,array{label:string,compact:string,short:string,open:bool}>
 */
function rd_hours_groups() {
	$groups = array();
	foreach ( rd_hours() as $d ) {
		$last = count( $groups ) - 1;
		if ( $last >= 0 && $groups[ $last ]['short'] === $d['short'] ) {
			$groups[ $last ]['days'][] = $d['day'];
		} else {
			$groups[] = array(
				'days'  => array( $d['day'] ),
				'short' => $d['short'],
				'open'  => '' !== $d['open'],
			);
		}
	}
	foreach ( $groups as &$g ) {
		$first        = $g['days'][0];
		$end          = end( $g['days'] );
		$g['label']   = 1 === count( $g['days'] ) ? $first : substr( $first, 0, 3 ) . ' – ' . substr( $end, 0, 3 );
		$g['compact'] = 1 === count( $g['days'] ) ? substr( $first, 0, 3 ) : substr( $first, 0, 3 ) . '–' . substr( $end, 0, 3 );
		unset( $g['days'] );
	}
	unset( $g );
	return $groups;
}

/**
 * One-line summary of the open days: "Mon–Tue 8am–5:30pm · Wed–Thu 8am–6:30pm · Fri 8am–4:30pm".
 *
 * @return string
 */
function rd_hours_summary() {
	$custom = trim( rd_practice( 'hours_summary' ) );
	if ( '' !== $custom ) {
		return $custom;
	}
	$parts = array();
	foreach ( rd_hours_groups() as $g ) {
		if ( $g['open'] ) {
			$parts[] = $g['compact'] . ' ' . str_replace( ' – ', '–', $g['short'] );
		}
	}
	return implode( ' · ', $parts );
}

/**
 * Practice data for main.js (window.REDLYNCH): the "Open now" status, today's row and the form.
 *
 * @return array
 */
function rd_js_data() {
	$hours = array();
	foreach ( rd_hours() as $d ) {
		$hours[ $d['num'] ] = $d['open'] ? array( rd_time_decimal( $d['open'] ), rd_time_decimal( $d['close'] ) ) : null;
	}
	return array(
		'name'         => rd_practice( 'name' ),
		'phoneDisplay' => rd_practice( 'phone' ),
		'phoneHref'    => rd_tel_href(),
		'email'        => rd_practice( 'email' ),
		'address'      => rd_address(),
		'mapsLink'     => rd_practice( 'maps_link' ),
		'bookingUrl'   => rd_practice( 'booking_url' ),
		'timeZone'     => rd_practice( 'timezone' ),
		'hours'        => (object) $hours,
	);
}

/**
 * HH:MM as decimal hours (16:30 → 16.5).
 *
 * @param string $hhmm Time.
 * @return float
 */
function rd_time_decimal( $hhmm ) {
	list( $h, $m ) = array_map( 'intval', explode( ':', $hhmm ) );
	return $h + $m / 60;
}

/**
 * Permalink of a page by its slug, falling back to /slug/ if it doesn't exist yet.
 *
 * @param string $slug Page path.
 * @return string
 */
function rd_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

/**
 * Sanitize an HH:MM time ('' allowed for closed).
 *
 * @param string $value Input.
 * @return string
 */
function rd_sanitize_time( $value ) {
	$value = trim( (string) $value );
	return preg_match( '/^([01]?\d|2[0-3]):[0-5]\d$/', $value ) ? $value : '';
}

/**
 * Sanitize the international phone number (digits and a leading +).
 *
 * @param string $value Input.
 * @return string
 */
function rd_sanitize_phone_intl( $value ) {
	return preg_replace( '/[^0-9+]/', '', (string) $value );
}

/**
 * Sanitize a map embed URL: only https Google Maps embeds.
 *
 * @param string $value Input.
 * @return string
 */
function rd_sanitize_map_embed( $value ) {
	$url  = esc_url_raw( trim( (string) $value ), array( 'https' ) );
	$host = (string) wp_parse_url( $url, PHP_URL_HOST );
	return ( $url && preg_match( '/(^|\.)google\.[a-z.]+$/', $host ) ) ? $url : '';
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Input.
 * @return bool
 */
function rd_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Customizer panel.
 *
 * @param WP_Customize_Manager $wpc Customizer.
 */
function rd_customize_register( $wpc ) {
	$d = rd_practice_defaults();

	$wpc->add_panel(
		'rd_panel',
		array(
			'title'    => __( 'Redlynch Dental', 'redlynch-dental' ),
			'priority' => 25,
		)
	);

	$sections = array(
		'rd_details' => __( 'Practice details', 'redlynch-dental' ),
		'rd_hours'   => __( 'Opening hours', 'redlynch-dental' ),
		'rd_header'  => __( 'Header and menu', 'redlynch-dental' ),
		'rd_footer'  => __( 'Footer and call to action', 'redlynch-dental' ),
	);
	foreach ( $sections as $id => $title ) {
		$wpc->add_section(
			$id,
			array(
				'title' => $title,
				'panel' => 'rd_panel',
			)
		);
	}

	$fields = array(
		// key, section, label, type, sanitize, description.
		array( 'name', 'rd_details', __( 'Practice name', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'legal_name', 'rd_details', __( 'Legal entity name (footer)', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'phone', 'rd_details', __( 'Phone (as displayed)', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'phone_intl', 'rd_details', __( 'Phone for tap-to-call links', 'redlynch-dental' ), 'text', 'rd_sanitize_phone_intl', __( 'International format, e.g. +61740394720.', 'redlynch-dental' ) ),
		array( 'email', 'rd_details', __( 'Email', 'redlynch-dental' ), 'email', 'sanitize_email', '' ),
		array( 'street', 'rd_details', __( 'Street address', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'suburb', 'rd_details', __( 'Suburb', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'state', 'rd_details', __( 'State', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'postcode', 'rd_details', __( 'Postcode', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'location_note', 'rd_details', __( 'Location note', 'redlynch-dental' ), 'text', 'sanitize_text_field', __( 'Shown under the address in the footer and on the Contact page.', 'redlynch-dental' ) ),
		array( 'booking_url', 'rd_details', __( 'Online booking link', 'redlynch-dental' ), 'url', 'esc_url_raw', __( 'Every "Book online" button opens this address in a new tab.', 'redlynch-dental' ) ),
		array( 'facebook_url', 'rd_details', __( 'Facebook page', 'redlynch-dental' ), 'url', 'esc_url_raw', '' ),
		array( 'maps_link', 'rd_details', __( 'Google Maps directions link', 'redlynch-dental' ), 'url', 'esc_url_raw', '' ),
		array( 'map_embed', 'rd_details', __( 'Google Maps embed address', 'redlynch-dental' ), 'url', 'rd_sanitize_map_embed', __( 'From Google Maps: Share › Embed a map › copy the address inside src="…". Only https://www.google.com/maps/embed addresses are accepted.', 'redlynch-dental' ) ),
		array( 'hours_note', 'rd_hours', __( 'Note under the hours', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'hours_summary', 'rd_hours', __( 'One-line summary (optional)', 'redlynch-dental' ), 'text', 'sanitize_text_field', __( 'Leave empty to build it from the hours above, e.g. "Mon–Tue 8am–5:30pm · Wed–Thu 8am–6:30pm · Fri 8am–4:30pm".', 'redlynch-dental' ) ),
		array( 'mega_title', 'rd_header', __( 'Services menu: emergency card heading', 'redlynch-dental' ), 'text', 'sanitize_text_field', '' ),
		array( 'mega_text', 'rd_header', __( 'Services menu: emergency card text', 'redlynch-dental' ), 'textarea', 'sanitize_textarea_field', '' ),
		array( 'footer_blurb', 'rd_footer', __( 'Footer introduction', 'redlynch-dental' ), 'textarea', 'sanitize_textarea_field', '' ),
		array( 'footer_risk', 'rd_footer', __( 'Footer health statement', 'redlynch-dental' ), 'textarea', 'sanitize_textarea_field', __( 'The Ahpra risk statement shown at the bottom of every page.', 'redlynch-dental' ) ),
		array( 'cta_heading', 'rd_footer', __( 'Default call-to-action heading', 'redlynch-dental' ), 'text', 'sanitize_text_field', __( 'Used on blog posts and the 404 page. Elementor pages have their own call-to-action widget.', 'redlynch-dental' ) ),
		array( 'cta_text', 'rd_footer', __( 'Default call-to-action text', 'redlynch-dental' ), 'textarea', 'sanitize_textarea_field', '' ),
	);

	foreach ( $fields as $f ) {
		list( $key, $section, $label, $type, $sanitize, $desc ) = $f;
		$wpc->add_setting(
			'rd_' . $key,
			array(
				'default'           => $d[ $key ],
				'sanitize_callback' => $sanitize,
			)
		);
		$wpc->add_control(
			'rd_' . $key,
			array(
				'label'       => $label,
				'section'     => $section,
				'type'        => $type,
				'description' => $desc,
			)
		);
	}

	$hd = rd_hours_defaults();
	foreach ( rd_days() as $num => $day ) {
		foreach ( array( 'open', 'close' ) as $i => $part ) {
			$wpc->add_setting(
				'rd_hours_' . $num . '_' . $part,
				array(
					'default'           => $hd[ $num ][ $i ],
					'sanitize_callback' => 'rd_sanitize_time',
				)
			);
			$wpc->add_control(
				'rd_hours_' . $num . '_' . $part,
				array(
					/* translators: %s: day name. */
					'label'       => sprintf( 'open' === $part ? __( '%s opens', 'redlynch-dental' ) : __( '%s closes', 'redlynch-dental' ), $day ),
					'description' => 'open' === $part && 1 === $num ? __( '24-hour time, e.g. 08:00 and 16:30. Leave both empty for a closed day.', 'redlynch-dental' ) : '',
					'section'     => 'rd_hours',
					'type'        => 'text',
					'input_attrs' => array( 'placeholder' => 'open' === $part ? '08:00' : '17:00' ),
				)
			);
		}
	}

	$wpc->add_setting(
		'rd_show_topline',
		array(
			'default'           => true,
			'sanitize_callback' => 'rd_sanitize_checkbox',
		)
	);
	$wpc->add_control(
		'rd_show_topline',
		array(
			'label'   => __( 'Show the top bar (address, opening status, emergency line)', 'redlynch-dental' ),
			'section' => 'rd_header',
			'type'    => 'checkbox',
		)
	);

	$wpc->add_setting(
		'rd_footer_logo',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wpc->add_control(
		new WP_Customize_Media_Control(
			$wpc,
			'rd_footer_logo',
			array(
				'label'       => __( 'Footer logo', 'redlynch-dental' ),
				'description' => __( 'Optional. The footer is dark, so use a logo with light text. Without one, the header logo is used.', 'redlynch-dental' ),
				'section'     => 'rd_footer',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'rd_customize_register' );
