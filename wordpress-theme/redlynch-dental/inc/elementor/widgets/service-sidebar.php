<?php
/**
 * Service sidebar: the sticky booking box, compact opening hours and "Find us" box beside a
 * service article. Phone, booking link, hours and address come from the Customizer.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Service sidebar widget.
 */
class Service_Sidebar extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-service-sidebar';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Booking Sidebar', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-sidebar';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Sidebar', 'redlynch-dental' ) ) );
		$this->add_control(
			'em',
			array(
				'label'       => __( 'Emergency version (red, call only)', 'redlynch-dental' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Phone, booking link, hours and address come from the Customizer.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'book_title',
			array(
				'label'   => __( 'First box heading', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Book an appointment', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'book_text',
			array(
				'label'   => __( 'First box text', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Call our Redlynch team, or book online at a time that suits you.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'contact_link',
			array(
				'label' => __( '"Map and directions" link', 'redlynch-dental' ),
				'type'  => Controls_Manager::URL,
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s       = $this->get_settings_for_display();
		$em      = 'yes' === $s['em'];
		$contact = $this->url( $s['contact_link'] );
		$contact = '' !== $contact ? $contact : rd_page_url( 'contact' );
		$this->out(
			'<aside class="side" aria-label="' . esc_attr__( 'Book and contact', 'redlynch-dental' ) . '">
 <div class="side-box' . ( $em ? ' em' : '' ) . '"><h3>' . esc_html( $s['book_title'] ) . '</h3><p>' . esc_html( $s['book_text'] ) . '</p>'
			. rd_button(
				array(
					'kind'  => 'call',
					'label' => rd_practice( 'phone' ),
					'class' => $em ? 'white' : '',
				)
			)
			. ( $em ? '' : rd_button(
				array(
					'kind'  => 'book',
					'class' => 'ghost-light',
				)
			) ) . '</div>
 <div class="side-box light"><h3>' . esc_html__( 'Opening hours', 'redlynch-dental' ) . '</h3>' . rd_hours_mini() . '<p class="fine" style="margin-top:12px">' . esc_html( rd_practice( 'hours_note' ) ) . '</p></div>
 <div class="side-box light"><h3>' . esc_html__( 'Find us', 'redlynch-dental' ) . '</h3><p style="margin-bottom:8px">' . esc_html( rd_practice( 'street' ) ) . '<br>' . esc_html( rd_practice( 'suburb' ) . ' ' . rd_practice( 'state' ) . ' ' . rd_practice( 'postcode' ) ) . '</p><a class="text-link" href="' . esc_url( $contact ) . '">' . esc_html__( 'Map and directions', 'redlynch-dental' ) . ' ' . rd_icon( 'arrow' ) . '</a></div>
</aside>'
		);
	}
}
