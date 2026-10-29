<?php
/**
 * Emergency hero: the Emergency Dental Care page's own top section — dark hero with the
 * emergency tag, the white tap-to-call card with opening status, and the yellow "Call 000" bar.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Emergency hero widget.
 */
class Emergency_Hero extends Page_Hero {

	/** @return string */
	public function get_name() {
		return 'rd-emergency-hero';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Emergency Hero', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-alert';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Text', 'redlynch-dental' ) ) );
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
				'label' => __( 'Page heading (H1)', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'lead',
			array(
				'label' => __( 'Introduction', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_buttons_control();
		$this->end_controls_section();

		$this->start_controls_section( 'crumbs_section', array( 'label' => __( 'Breadcrumbs', 'redlynch-dental' ) ) );
		$this->add_control(
			'crumbs',
			array(
				'label'   => __( 'Show breadcrumbs', 'redlynch-dental' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$r = new \Elementor\Repeater();
		$r->add_control(
			'label',
			array(
				'label' => __( 'Text', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'link',
			array(
				'label' => __( 'Link', 'redlynch-dental' ),
				'type'  => Controls_Manager::URL,
			)
		);
		$this->add_control(
			'parents',
			array(
				'label'       => __( 'Parent pages (after Home)', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ label }}}',
			)
		);
		$this->add_control(
			'current',
			array(
				'label' => __( 'This page in the breadcrumbs', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'call_section', array( 'label' => __( 'Call card and 000 bar', 'redlynch-dental' ) ) );
		$this->add_control(
			'call_label',
			array(
				'label'   => __( 'Card label', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Call us immediately', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'call_button',
			array(
				'label'   => __( 'Card button', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Tap to call', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'call_note',
			array(
				'label'       => __( 'Card note', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( '{address} is replaced with the street and suburb.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'bar',
			array(
				'label'       => __( '"Call 000" bar', 'redlynch-dental' ),
				'type'        => Controls_Manager::WYSIWYG,
				'description' => __( 'Leave empty to hide the bar.', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s    = $this->get_settings_for_display();
		$note = str_replace( '{address}', rd_practice( 'street' ) . ', ' . rd_practice( 'suburb' ), (string) $s['call_note'] );
		$bar  = trim( (string) $s['bar'] );
		$bar  = '' !== $bar ? preg_replace( '#^<p>(.*)</p>$#s', '$1', trim( wpautop( $bar ) ) ) : '';
		$this->out(
			'<section class="em-hero"><div class="wrap">
 <div>' . $this->crumbs_html( $s ) . '<span class="em-tag">' . rd_icon( 'alert' ) . ' ' . esc_html( $s['tag'] ) . '</span><h1>' . esc_html( $s['title'] ? $s['title'] : get_the_title() ) . '</h1>'
			. ( '' !== trim( (string) $s['lead'] ) ? '<p class="lead" style="color:#C8D4CC">' . $this->inline( $s['lead'] ) . '</p>' : '' )
			. '<div class="actions mt-1">' . $this->buttons_html( $s['buttons'] ) . '</div></div>
 <div class="em-call" role="region" aria-label="' . esc_attr__( 'Call the practice', 'redlynch-dental' ) . '"><span class="label">' . esc_html( $s['call_label'] ) . '</span><a class="num" href="' . esc_url( rd_tel_href() ) . '">' . esc_html( rd_practice( 'phone' ) ) . '</a>' . rd_status()
			. rd_button(
				array(
					'kind'  => 'call',
					'label' => (string) $s['call_button'],
					'class' => 'ember',
				)
			) . ( '' !== trim( $note ) ? '<p class="small">' . esc_html( $note ) . '</p>' : '' ) . '</div>
</div></section>'
			. ( '' !== $bar ? '<div class="call-000" role="note"><div class="wrap">' . rd_icon( 'alert' ) . '<p>' . wp_kses_post( $bar ) . '</p></div></div>' : '' )
		);
	}
}
