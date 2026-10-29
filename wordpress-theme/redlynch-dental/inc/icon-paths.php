<?php
/**
 * Icon set (24×24 stroke icons), the same set the static site uses.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon paths keyed by name.
 *
 * @return array<string,string>
 */
function rd_icon_paths() {
	return array(
		'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.9.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
		'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'pin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'ext' => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
		'chev' => '<path d="m6 9 6 6 6-6"/>',
		'alert' => '<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/>',
		'tooth' => '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/>',
		'smile' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
		'child' => '<circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M10 7h.01M14 7h.01"/>',
		'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
		'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
		'crown' => '<path d="m3 8 4 4 5-7 5 7 4-4-2 11H5z"/>',
		'implant' => '<path d="M8 3h8l-1 5H9z"/><path d="M10 8v3l-1 1 2 1-2 1 2 1-2 1 3 4 3-4-2-1 2-1-2-1 2-1-1-1V8"/>',
		'denture' => '<path d="M3 10c0-3 4-5 9-5s9 2 9 5"/><path d="M3 10c0 5 4 9 9 9s9-4 9-9"/><path d="M7 10v3M10 10v4M14 10v4M17 10v3"/>',
		'extract' => '<path d="M9 3 5 14l3 7M15 3l4 11-3 7"/><path d="M7 10h10"/>',
		'filling' => '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><circle cx="12" cy="10" r="2.2"/>',
		'aligner' => '<path d="M4 9c0-2 3.5-4 8-4s8 2 8 4v3c0 4-3.5 7-8 7s-8-3-8-7z"/><path d="M8 9.5h8"/>',
		'guard' => '<path d="M3 9c3-3 15-3 18 0l-1.5 6c-2 3-13 3-15 0z"/><path d="M8 11.5h8"/>',
		'root' => '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><path d="M10 11l-.5 6M14 11l.5 6"/>',
		'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'wisdom' => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="M7 9h2M11 9h2M15 9h2M7 15h2M11 15h2M15 15h2" stroke-width="2.4"/>',
		'face' => '<path d="M12 2C7 2 5 6 5 10c0 5 3 10 7 10s7-5 7-10c0-4-2-8-7-8z"/><path d="M9 10h.01M15 10h.01M10 15c1 .7 3 .7 4 0"/>',
		'veneer' => '<path d="M5 6c2-1 4-1 7-1s5 0 7 1l-1 9c-.5 3-2 4-6 4s-5.5-1-6-4z"/><path d="M9 6v12M15 6v12"/>',
		'bridge' => '<path d="M2 9h20"/><path d="M4 9v6c0 2 1 4 2 4s1.5-3 2-3M20 9v6c0 2-1 4-2 4s-1.5-3-2-3M9 9v5c0 1.5 1.3 2 3 2s3-.5 3-2V9"/>',
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'wallet' => '<rect x="2" y="6" width="20" height="14" rx="2"/><path d="M2 10h20M16 15h2"/>',
		'heart' => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/>',
		'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
		'film' => '<rect x="2" y="3" width="20" height="18" rx="2"/><path d="M7 3v18M17 3v18M2 8h5M2 16h5M17 8h5M17 16h5M2 12h20"/>',
		'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
		'tag' => '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/><circle cx="7" cy="7" r="1.5"/>',
		'cpu' => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>',
		'moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
		'check' => '<path d="M20 6 9 17l-5-5"/>',
		'info' => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
		'bolt' => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
		'swelling' => '<circle cx="12" cy="12" r="9"/><path d="M8 13c1.5 2 6.5 2 8 0M9 9h.01M15 9h.01"/><path d="M18.5 7.5c1.5 1 2 3 1.5 4.5"/>',
		'broken' => '<path d="M12 5.5c-1.5-1.5-4-2-5.5-.5S4 9 5 12c1 3 1.5 8 3 8s1.5-4 4-4 2.5 4 4 4 2-5 3-8 1-5.5-.5-7-4-1-5.5.5z"/><path d="m12 5.5-1.5 3 2.5 1.5-2 3"/>',
		'hand' => '<path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v6M10 10.5V6a2 2 0 0 0-4 0v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.9-6-2.4l-3.6-3.6a2 2 0 0 1 2.8-2.8L7 15"/>',
		'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/><path d="M4 19.5V21h16"/>',
		'leaf' => '<path d="M11 20A7 7 0 0 1 4 13c0-6 6-9 16-10-1 10-4 16-9 17z"/><path d="M4 21c4-4 7-7 10-11"/>',
		'search' => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'x' => '<path d="M18 6 6 18M6 6l12 12"/>',
		'refresh' => '<path d="M21 12a9 9 0 1 1-2.6-6.4L21 8"/><path d="M21 3v5h-5"/>',
	);
}
