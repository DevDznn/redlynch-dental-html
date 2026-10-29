<?php
/**
 * Related treatments: small link cards for chosen service pages (image or icon, name and the first
 * sentence of the service summary).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Related treatments widget.
 */
class Related extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-related';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Related Treatments', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-link';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Related treatments', 'redlynch-dental' ) ) );
		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Related treatments', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'services',
			array(
				'label'    => __( 'Service pages', 'redlynch-dental' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => rd_service_options(),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) $s['services'] as $id ) {
			$id = (int) $id;
			if ( ! $id || 'publish' !== get_post_status( $id ) ) {
				continue;
			}
			$r     = rd_service_data( $id );
			$first = strtok( $r['short'], '.' );
			$out  .= '<a class="rel" href="' . esc_url( $r['url'] ) . '">' . rd_service_image( $r, $r['contain'] ? 'medium' : 'thumbnail', array( 'sizes' => '84px' ) ) . '<span><b>' . esc_html( $r['name'] ) . '</b><span>' . esc_html( $first ? $first . '.' : '' ) . '</span></span></a>';
		}
		if ( '' === $out ) {
			return;
		}
		$id = wp_unique_id( 'rel-h-' );
		$this->out( ( $s['heading'] ? '<h2 id="' . esc_attr( $id ) . '" style="font-size:1.7rem">' . esc_html( $s['heading'] ) . '</h2>' : '' ) . '<div class="related" data-reveal-group>' . $out . '</div>' );
	}
}
