<?php
/**
 * Emergency panel: the pale-red "In pain?" panel with a tag, heading, text and buttons.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Emergency panel widget.
 */
class Emergency_Panel extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-emergency-panel';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Emergency Panel', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-alert';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Panel', 'redlynch-dental' ) ) );
		$this->add_control(
			'tag',
			array(
				'label'   => __( 'Tag', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Dental emergency', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'title',
			array(
				'label' => __( 'Heading', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_tag_control( 'title_tag', 'h3' );
		$this->add_control(
			'text',
			array(
				'label' => __( 'Text', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_buttons_control();
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$tag = $this->tag( $s['title_tag'] );
		$this->out(
			'<div class="em-panel" data-reveal><div><span class="em-tag">' . rd_icon( 'alert' ) . ' ' . esc_html( $s['tag'] ) . '</span><' . $tag . '>' . $this->inline( $s['title'] ) . '</' . $tag . '>'
			. ( '' !== trim( (string) $s['text'] ) ? '<p>' . $this->inline( $s['text'] ) . '</p>' : '' )
			. '</div><div class="actions">' . $this->buttons_html( $s['buttons'] ) . '</div></div>'
		);
	}
}
