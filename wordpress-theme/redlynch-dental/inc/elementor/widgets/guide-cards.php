<?php
/**
 * Guide cards: link cards with a small category label, title, text and "Read guide" link
 * (the Blog page's treatment guides). Pick a service page, or enter a custom link and title.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Guide cards widget.
 */
class Guide_Cards extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-guide-cards';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Guide Cards', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Cards', 'redlynch-dental' ) ) );
		$r = new Repeater();
		$r->add_control(
			'service',
			array(
				'label'   => __( 'Service page', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => array( '' => __( '— Custom link —', 'redlynch-dental' ) ) + rd_service_options(),
			)
		);
		$r->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Leave empty to use the service name.', 'redlynch-dental' ),
			)
		);
		$r->add_control(
			'link',
			array(
				'label'     => __( 'Custom link', 'redlynch-dental' ),
				'type'      => Controls_Manager::URL,
				'condition' => array( 'service' => '' ),
			)
		);
		$r->add_control(
			'kicker',
			array(
				'label' => __( 'Small label', 'redlynch-dental' ),
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
				'label'       => __( 'Cards', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ kicker }}}',
			)
		);
		$this->add_control(
			'more',
			array(
				'label'   => __( 'Link text', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Read guide', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$id    = (int) $i['service'];
			$url   = $id ? get_permalink( $id ) : $this->url( $i['link'] );
			$title = '' !== trim( (string) $i['title'] ) ? $i['title'] : ( $id ? get_the_title( $id ) : '' );
			if ( ! $url ) {
				continue;
			}
			$out .= '<a class="guide" href="' . esc_url( $url ) . '"><span class="k">' . esc_html( $i['kicker'] ) . '</span><h3>' . esc_html( $title ) . '</h3><p>' . esc_html( $i['text'] ) . '</p><span class="text-link" style="margin-top:auto">' . esc_html( $s['more'] ) . ' ' . rd_icon( 'arrow' ) . '</span></a>';
		}
		$this->out( '<div class="guide-grid" data-reveal-group>' . $out . '</div>' );
	}
}
