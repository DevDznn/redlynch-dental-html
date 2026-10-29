<?php
/**
 * Call-to-action band: the lime band with a heading, text, the call button and Book online.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Call-to-action widget.
 */
class Cta extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-cta';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Call to Action', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Band', 'redlynch-dental' ) ) );
		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Leave empty for the default from the Customizer.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( '{phone} is replaced with the phone number.', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s = $this->get_settings_for_display();
		$this->out( rd_cta_band( (string) $s['heading'], str_replace( '{phone}', rd_practice( 'phone' ), (string) $s['text'] ) ) );
	}
}
