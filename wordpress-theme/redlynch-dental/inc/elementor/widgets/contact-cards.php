<?php
/**
 * Contact cards: address, phone, email, online booking and opening hours, filled from the
 * Customizer (Redlynch Dental › Practice details / Opening hours) so they always match the header
 * and footer. The opening-hours card is the one with the homepage's mobile layout (c-hours).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Contact cards widget.
 */
class Contact_Cards extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-contact-cards';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Contact Cards', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-envelope';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Cards', 'redlynch-dental' ) ) );
		$r = new Repeater();
		$r->add_control(
			'type',
			array(
				'label'   => __( 'Card', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'phone',
				'options' => array(
					'address' => __( 'Address (linked to Google Maps)', 'redlynch-dental' ),
					'visit'   => __( 'Address with location note and directions link', 'redlynch-dental' ),
					'phone'   => __( 'Phone', 'redlynch-dental' ),
					'email'   => __( 'Email', 'redlynch-dental' ),
					'book'    => __( 'Online booking', 'redlynch-dental' ),
					'hours'   => __( 'Opening hours', 'redlynch-dental' ),
				),
			)
		);
		$r->add_control(
			'title',
			array(
				'label' => __( 'Heading', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'text',
			array(
				'label'     => __( 'Link text', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'type' => 'book' ),
			)
		);
		$r->add_control(
			'wbr',
			array(
				'label'       => __( 'Allow the email to wrap after the @', 'redlynch-dental' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array( 'type' => 'email' ),
				'description' => __( 'Keeps a long address inside narrow cards on phones.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$h    = '<h3>' . esc_html( $i['title'] ) . '</h3>';
			$city = esc_html( rd_practice( 'suburb' ) . ' ' . rd_practice( 'state' ) . ' ' . rd_practice( 'postcode' ) );
			switch ( $i['type'] ) {
				case 'address':
					$out .= '<div class="c-card"><span class="ic">' . rd_icon( 'pin' ) . '</span><div>' . $h . '<p><a href="' . esc_url( rd_practice( 'maps_link' ) ) . '" target="_blank" rel="noopener">' . esc_html( rd_practice( 'street' ) ) . '<br>' . $city . '<span class="sr-only">' . esc_html__( ' (opens Google Maps in a new tab)', 'redlynch-dental' ) . '</span></a></p></div></div>';
					break;
				case 'visit':
					$note = rd_practice( 'location_note' );
					$out .= '<div class="c-card"><span class="ic">' . rd_icon( 'pin' ) . '</span><div>' . $h . '<p>' . esc_html( rd_practice( 'street' ) ) . '<br>' . $city . '</p><p style="font-weight:500;color:var(--muted);font-size:.92rem;margin-top:6px">' . ( $note ? esc_html( $note ) . ' · ' : '' ) . '<a href="' . esc_url( rd_practice( 'maps_link' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'Get directions', 'redlynch-dental' ) . ' ↗</a></p></div></div>';
					break;
				case 'email':
					$email = rd_practice( 'email' );
					$text  = 'yes' === $i['wbr'] ? str_replace( '@', '@<wbr>', esc_html( $email ) ) : esc_html( $email );
					$out  .= '<div class="c-card"><span class="ic">' . rd_icon( 'mail' ) . '</span><div>' . $h . '<p><a href="' . esc_url( 'mailto:' . $email ) . '">' . $text . '</a></p></div></div>';
					break;
				case 'book':
					$out .= '<div class="c-card"><span class="ic">' . rd_icon( 'calendar' ) . '</span><div>' . $h . '<p><a href="' . esc_url( rd_practice( 'booking_url' ) ) . '" target="_blank" rel="noopener">' . esc_html( $i['text'] ? $i['text'] : __( 'Choose an appointment time', 'redlynch-dental' ) ) . ' ↗<span class="sr-only">' . esc_html__( ' (opens in a new tab)', 'redlynch-dental' ) . '</span></a></p></div></div>';
					break;
				case 'hours':
					$out .= '<div class="c-card c-hours"><span class="ic">' . rd_icon( 'clock' ) . '</span><div style="flex:1">' . $h . rd_hours_table() . '</div></div>';
					break;
				default:
					$out .= '<div class="c-card"><span class="ic">' . rd_icon( 'phone' ) . '</span><div>' . $h . '<p><a href="' . esc_url( rd_tel_href() ) . '">' . esc_html( rd_practice( 'phone' ) ) . '</a></p></div></div>';
			}
		}
		$this->out( '<div class="c-cards" data-reveal-group>' . $out . '</div>' );
	}
}
