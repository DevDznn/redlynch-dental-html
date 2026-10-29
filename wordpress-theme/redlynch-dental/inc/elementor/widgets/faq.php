<?php
/**
 * FAQ accordion: questions and answers as native <details> (they work without JavaScript; main.js
 * animates them). An optional group heading and anchor make the categories on the FAQ page.
 *
 * The theme adds no FAQ schema: structured data is ThinkRank's job (see the README).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * FAQ widget.
 */
class Faq extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-faq';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch FAQ', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-accordion';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Questions', 'redlynch-dental' ) ) );
		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Group heading (optional)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Used on the FAQ page to title each category.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'anchor',
			array(
				'label'       => __( 'Group anchor (optional)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Lets a jump link point here, e.g. "children" for #children.', 'redlynch-dental' ),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'question',
			array(
				'label'       => __( 'Question', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$r->add_control(
			'answer',
			array(
				'label' => __( 'Answer', 'redlynch-dental' ),
				'type'  => Controls_Manager::WYSIWYG,
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Questions', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ question }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$out .= '<details><summary>' . esc_html( $i['question'] ) . '</summary><div class="ans">' . $this->rich( $i['answer'] ) . '</div></details>';
		}
		$faq = '<div class="faq" data-reveal-group>' . $out . '</div>';
		if ( '' !== trim( (string) $s['heading'] ) ) {
			$anchor = sanitize_title( (string) $s['anchor'] );
			$faq    = '<div class="faq-group"' . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . '><h2>' . esc_html( $s['heading'] ) . '</h2>' . $faq . '</div>';
		}
		$this->out( $faq );
	}
}
