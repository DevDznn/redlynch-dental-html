<?php
/**
 * Callout: highlighted text blocks — "From the Redlynch Dental team" (the practice's own words),
 * lime, sand and warning callouts, and the "At a glance" style cards.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Callout widget.
 */
class Callout extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-callout';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Callout', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'section_callout', array( 'label' => __( 'Callout', 'redlynch-dental' ) ) );
		$this->add_control(
			'style',
			array(
				'label'   => __( 'Style', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'callout',
				'options' => array(
					'from-practice' => __( 'From the practice (lime edge, label)', 'redlynch-dental' ),
					'callout'       => __( 'Lime callout', 'redlynch-dental' ),
					'callout info'  => __( 'Sand callout', 'redlynch-dental' ),
					'callout warn'  => __( 'Warning callout (red edge)', 'redlynch-dental' ),
					'card'          => __( 'Card with heading', 'redlynch-dental' ),
					'card lime'     => __( 'Lime card with heading', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			'label',
			array(
				'label'       => __( 'Label / heading', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'The small label of the "From the practice" style, or the heading of a card.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'content',
			array(
				'label' => __( 'Text', 'redlynch-dental' ),
				'type'  => Controls_Manager::WYSIWYG,
			)
		);
		$this->add_control(
			'spacing',
			array(
				'label'   => __( 'Extra space above', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''     => __( 'Standard', 'redlynch-dental' ),
					'mt-2' => __( 'More', 'redlynch-dental' ),
				),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s     = $this->get_settings_for_display();
		$space = 'mt-2' === $s['spacing'] ? ' mt-2' : '';
		$text  = $this->rich( $s['content'] );
		switch ( $s['style'] ) {
			case 'from-practice':
				$this->out( '<div class="from-practice' . $space . '"><span class="label">' . esc_html( $s['label'] ) . '</span>' . $text . '</div>' );
				return;
			case 'card':
			case 'card lime':
				$style = 'card lime' === $s['style'] ? ' style="background:var(--lime-wash);border:0"' : '';
				$this->out( '<aside class="card' . $space . '" data-reveal' . $style . '>' . ( $s['label'] ? '<h3>' . esc_html( $s['label'] ) . '</h3>' : '' ) . str_replace( '<ul class="ticks">', '<ul class="ticks" style="margin:0">', $text ) . '</aside>' );
				return;
		}
		$cls = in_array( $s['style'], array( 'callout', 'callout info', 'callout warn' ), true ) ? $s['style'] : 'callout';
		$this->out( '<div class="' . esc_attr( $cls . $space ) . '">' . $text . '</div>' );
	}
}
