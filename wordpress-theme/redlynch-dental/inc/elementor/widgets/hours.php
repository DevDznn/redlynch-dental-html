<?php
/**
 * Opening hours table, from the Customizer (Redlynch Dental › Opening hours). Today's row is
 * highlighted by main.js. Optionally inside a card with a heading and the note under the hours (Customizer).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Opening hours widget.
 */
class Hours extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-hours';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Opening Hours', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-clock-o';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Opening hours', 'redlynch-dental' ) ) );
		$this->add_control(
			'style',
			array(
				'label'       => __( 'Style', 'redlynch-dental' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'table',
				'options'     => array(
					'table' => __( 'Every day', 'redlynch-dental' ),
					'card'  => __( 'Every day, in a card with heading and note', 'redlynch-dental' ),
					'mini'  => __( 'Compact (days grouped)', 'redlynch-dental' ),
				),
				'description' => __( 'The hours themselves are set in Appearance › Customize › Redlynch Dental › Opening hours.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'heading',
			array(
				'label'     => __( 'Heading', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Opening hours', 'redlynch-dental' ),
				'condition' => array( 'style' => 'card' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s = $this->get_settings_for_display();
		if ( 'mini' === $s['style'] ) {
			$this->out( rd_hours_mini() );
			return;
		}
		if ( 'card' === $s['style'] ) {
			$this->out( '<div class="card" data-reveal><h2 style="font-size:1.6rem">' . esc_html( $s['heading'] ) . '</h2>' . rd_hours_table() . rd_hours_note() . '</div>' );
			return;
		}
		$this->out( rd_hours_table() );
	}
}
