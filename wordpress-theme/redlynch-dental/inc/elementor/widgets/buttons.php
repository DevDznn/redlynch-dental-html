<?php
/**
 * Buttons: a row of brand buttons. "Book online" and "Call" buttons always use the booking link
 * and phone number from the Customizer.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Buttons widget.
 */
class Buttons extends Base {

	/**
	 * Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rd-buttons';
	}

	/**
	 * Title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Redlynch Buttons', 'redlynch-dental' );
	}

	/**
	 * Icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Controls.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Buttons', 'redlynch-dental' ) ) );
		$this->add_buttons_control(
			'buttons',
			array(
				array(
					'kind'  => 'book',
					'label' => '',
					'style' => '',
				),
				array(
					'kind'  => 'call',
					'label' => '',
					'style' => 'ghost',
				),
			)
		);
		$this->add_control(
			'spacing',
			array(
				'label'   => __( 'Space above', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''     => __( 'None', 'redlynch-dental' ),
					'mt-1' => __( 'Small', 'redlynch-dental' ),
					'mt-2' => __( 'Medium', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			'wrap',
			array(
				'label'   => __( 'Layout', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'actions',
				'options' => array(
					'actions' => __( 'Button row', 'redlynch-dental' ),
					'p'       => __( 'Paragraph (for a single text link)', 'redlynch-dental' ),
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$s     = $this->get_settings_for_display();
		$inner = $this->buttons_html( $s['buttons'] ?? array() );
		if ( '' === $inner ) {
			return;
		}
		$space = in_array( $s['spacing'] ?? '', array( 'mt-1', 'mt-2' ), true ) ? $s['spacing'] : '';
		if ( 'p' === ( $s['wrap'] ?? '' ) ) {
			$this->out( '<p' . ( $space ? ' class="' . esc_attr( $space ) . '"' : '' ) . '>' . $inner . '</p>' );
			return;
		}
		$this->out( '<div class="' . esc_attr( trim( 'actions ' . $space ) ) . '">' . $inner . '</div>' );
	}
}
