<?php
/**
 * Jump links: the sticky category list beside the FAQ groups (highlights the section in view).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Jump links widget.
 */
class Anchor_Nav extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-anchor-nav';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Jump Links', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-anchor';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Links', 'redlynch-dental' ) ) );
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Navigation label (screen readers)', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'FAQ categories', 'redlynch-dental' ),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'text',
			array(
				'label' => __( 'Text', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'anchor',
			array(
				'label'       => __( 'Anchor', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'The anchor of the section, without #.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Links', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ text }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$out .= '<li><a href="#' . esc_attr( sanitize_title( $i['anchor'] ) ) . '">' . esc_html( $i['text'] ) . '</a></li>';
		}
		$this->out( '<nav aria-label="' . esc_attr( $s['label'] ) . '"><ul class="faq-nav" data-spy>' . $out . '</ul></nav>' );
	}
}
