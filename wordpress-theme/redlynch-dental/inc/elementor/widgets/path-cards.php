<?php
/**
 * Path cards: "How can we help you today?" — round-icon link cards, optionally in emergency red.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Path cards widget.
 */
class Path_Cards extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-path-cards';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Path Cards', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Cards', 'redlynch-dental' ) ) );
		$this->add_control(
			'label',
			array(
				'label'   => __( 'Navigation label (screen readers)', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Where to start', 'redlynch-dental' ),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'smile',
				'options' => rd_icon_options(),
			)
		);
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
				'label' => __( 'Subtitle', 'redlynch-dental' ),
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
		$r->add_control(
			'em',
			array(
				'label' => __( 'Emergency style (red)', 'redlynch-dental' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ title }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$out .= '<a class="path' . ( 'yes' === $i['em'] ? ' em' : '' ) . '" href="' . esc_url( $this->url( $i['link'] ) ) . '"><span class="pi">' . rd_icon( $i['icon'] ) . '</span><b>' . esc_html( $i['title'] ) . '</b><span>' . esc_html( $i['text'] ) . '</span></a>';
		}
		$this->out( '<nav class="paths" aria-label="' . esc_attr( $s['label'] ) . '" data-reveal-group>' . $out . '</nav>' );
	}
}
