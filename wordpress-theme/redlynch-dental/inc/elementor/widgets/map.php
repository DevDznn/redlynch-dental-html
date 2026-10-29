<?php
/**
 * Map: the single embedded Google map with its loading indicator, "couldn't load" fallback and
 * one "Reload map" button that refreshes the same map in place (main.js). The embed address and
 * the directions link are set in the Customizer.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Map widget.
 */
class Map extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-map';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Map', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Map', 'redlynch-dental' ) ) );
		$this->add_control(
			'note',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'The map address and the directions link are set in Appearance › Customize › Redlynch Dental › Practice details.', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$this->out( rd_map_box() );
	}
}
