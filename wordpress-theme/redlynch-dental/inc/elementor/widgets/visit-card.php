<?php
/**
 * Visit card: the dark booking card that overlaps the homepage hero photo. The opening status
 * and call button come from the Customizer.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Visit card widget.
 */
class Visit_Card extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-visit-card';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Visit Card', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-info-box';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Card', 'redlynch-dental' ) ) );
		$this->add_control(
			'title',
			array(
				'label'   => __( 'Heading', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Book your visit', 'redlynch-dental' ),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'label',
			array(
				'label' => __( 'Label', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'value',
			array(
				'label' => __( 'Value', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ label }}}',
			)
		);
		$this->add_control(
			'button',
			array(
				'label'       => __( 'Call button text', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Call {phone}',
				'description' => __( '{phone} is replaced with the phone number.', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s    = $this->get_settings_for_display();
		$rows = '';
		foreach ( (array) ( $s['rows'] ?? array() ) as $r ) {
			$rows .= '<div><dt>' . esc_html( $r['label'] ) . '</dt><dd>' . esc_html( $r['value'] ) . '</dd></div>';
		}
		$this->out(
			'<div class="visit-card">' . rd_status() . '<h2 class="vc-title">' . esc_html( $s['title'] ) . '</h2>'
			. ( $rows ? '<dl>' . $rows . '</dl>' : '' )
			. rd_button(
				array(
					'kind'  => 'call',
					'label' => (string) $s['button'],
					'class' => 'white',
				)
			) . '</div>'
		);
	}
}
