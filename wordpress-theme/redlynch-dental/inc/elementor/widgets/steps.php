<?php
/**
 * Steps: a process as a dashed timeline with numbered circles, or as a numbered editorial list.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Steps widget.
 */
class Steps extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-steps';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Steps', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-time-line';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Steps', 'redlynch-dental' ) ) );
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'timeline',
				'options' => array(
					'timeline' => __( 'Timeline (side by side)', 'redlynch-dental' ),
					'numbered' => __( 'Numbered list (01, 02 …)', 'redlynch-dental' ),
					'lp'       => __( 'Landing page step cards', 'redlynch-dental' ),
				),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'title',
			array(
				'label' => __( 'Title', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'text',
			array(
				'label' => __( 'Text', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Steps', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = (array) ( $s['items'] ?? array() );
		$out   = '';
		if ( 'numbered' === $s['layout'] ) {
			foreach ( $items as $i ) {
				$out .= '<li><div><h3>' . esc_html( $i['title'] ) . '</h3><p>' . $this->inline( $i['text'] ) . '</p></div></li>';
			}
			$this->out( '<ol class="num-list">' . $out . '</ol>' );
			return;
		}
		if ( 'lp' === $s['layout'] ) {
			foreach ( $items as $i ) {
				$out .= '<li><h3>' . esc_html( $i['title'] ) . '</h3><p>' . $this->inline( $i['text'] ) . '</p></li>';
			}
			// Four steps sit in one row of four; other counts in rows of three.
			$this->out( '<ol class="lp-steps' . ( 4 === count( $items ) ? ' s4' : '' ) . '" data-reveal-group>' . $out . '</ol>' );
			return;
		}
		foreach ( $items as $i ) {
			$out .= '<li><h3>' . esc_html( $i['title'] ) . '</h3><p>' . $this->inline( $i['text'] ) . '</p></li>';
		}
		$this->out( '<ol class="timeline" style="--n:' . max( 1, count( $items ) ) . '" data-reveal-group>' . $out . '</ol>' );
	}
}
