<?php
/**
 * Link strip: the dark three-link bar under the homepage hero (book, call, find us).
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Link strip widget.
 */
class Link_Strip extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-link-strip';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Link Strip', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Links', 'redlynch-dental' ) ) );
		$r = new Repeater();
		$r->add_control(
			'kind',
			array(
				'label'   => __( 'Links to', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => array(
					'book' => __( 'Online booking', 'redlynch-dental' ),
					'call' => __( 'Phone call', 'redlynch-dental' ),
					'maps' => __( 'Google Maps directions', 'redlynch-dental' ),
					'link' => __( 'Custom link', 'redlynch-dental' ),
				),
			)
		);
		$r->add_control(
			'link',
			array(
				'label'     => __( 'Link', 'redlynch-dental' ),
				'type'      => Controls_Manager::URL,
				'condition' => array( 'kind' => 'link' ),
			)
		);
		$r->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'calendar',
				'options' => rd_icon_options(),
			)
		);
		$r->add_control(
			'title',
			array(
				'label' => __( 'Bold line', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'text',
			array(
				'label'       => __( 'Second line', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( '{phone} and {address} are replaced from the Customizer.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Links', 'redlynch-dental' ),
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
			$text  = str_replace( array( '{phone}', '{address}' ), array( rd_practice( 'phone' ), rd_practice( 'street' ) . ', ' . rd_practice( 'suburb' ) ), (string) $i['text'] );
			$attrs = '';
			$sr    = '';
			switch ( $i['kind'] ) {
				case 'book':
					$url   = rd_practice( 'booking_url' );
					$attrs = ' target="_blank" rel="noopener"';
					$sr    = __( ' (opens the practice’s online booking system in a new tab)', 'redlynch-dental' );
					break;
				case 'call':
					$url = rd_tel_href();
					break;
				case 'maps':
					$url   = rd_practice( 'maps_link' );
					$attrs = ' target="_blank" rel="noopener"';
					$sr    = __( ' (opens Google Maps in a new tab)', 'redlynch-dental' );
					break;
				default:
					$url = $this->url( $i['link'] );
					if ( ! empty( $i['link']['is_external'] ) ) {
						$attrs = ' target="_blank" rel="noopener"';
						$sr    = __( ' (opens in a new tab)', 'redlynch-dental' );
					}
			}
			$out .= '<a href="' . esc_url( $url ) . '"' . $attrs . '>' . rd_icon( $i['icon'] ) . '<span><b>' . esc_html( $i['title'] ) . '</b>' . esc_html( $text ) . ( $sr ? '<span class="sr-only">' . esc_html( $sr ) . '</span>' : '' ) . '</span></a>';
		}
		$this->out( '<div class="hero-strip"><div class="wrap">' . $out . '</div></div>' );
	}
}
