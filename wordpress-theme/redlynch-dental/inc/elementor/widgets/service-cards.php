<?php
/**
 * Service cards: photo cards for chosen services (homepage). Name, icon, summary and photo come
 * from each service page (its "Service listing" box and featured image), so they stay in step
 * with the Services page and menus. The urgent-care service is shown in emergency colours.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Service cards widget.
 */
class Service_Cards extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-service-cards';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Service Cards', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Services', 'redlynch-dental' ) ) );
		$r = new Repeater();
		$r->add_control(
			'service',
			array(
				'label'   => __( 'Service page', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => rd_service_options(),
			)
		);
		$r->add_control(
			'focus',
			array(
				'label'       => __( 'Photo focus point', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '50% 50%',
				'description' => __( 'Which part of the photo stays visible when it is cropped: horizontal % then vertical %, e.g. 62% 30%.', 'redlynch-dental' ),
			)
		);
		$r->add_control(
			'badge',
			array(
				'label' => __( 'Badge on the photo (optional)', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'more',
			array(
				'label'   => __( 'Link text', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Learn more', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'items',
			array(
				'label'       => __( 'Cards', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ more }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s   = $this->get_settings_for_display();
		$out = '';
		foreach ( (array) ( $s['items'] ?? array() ) as $i ) {
			$id = (int) $i['service'];
			if ( ! $id || 'publish' !== get_post_status( $id ) ) {
				continue;
			}
			$svc   = rd_service_data( $id );
			$em    = 'urgent' === $svc['cat'];
			$focus = preg_match( '/^\d{1,3}% \d{1,3}%$/', (string) $i['focus'] ) ? $i['focus'] : '50% 50%';
			$img   = $svc['img'] ? wp_get_attachment_image(
				$svc['img'],
				'large',
				false,
				array(
					'alt'      => '',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'style'    => 'object-position:' . $focus,
				)
			) : '';
			$out  .= '<a class="svc-card' . ( $em ? ' em' : '' ) . '" href="' . esc_url( $svc['url'] ) . '"><span class="svc-img">' . $img
				. ( '' !== trim( (string) $i['badge'] ) ? '<span class="svc-badge">' . esc_html( $i['badge'] ) . '</span>' : '' )
				. '</span><span class="svc-body"><span class="svc-ic">' . rd_icon( $svc['icon'] ) . '</span><h3>' . esc_html( $svc['nav'] ) . '</h3><p>' . esc_html( $svc['short'] ) . '</p><span class="text-link">' . esc_html( $i['more'] ) . ' ' . rd_icon( 'arrow' ) . '</span></span></a>';
		}
		$this->out( '<div class="svc-cards" data-reveal-group>' . $out . '</div>' );
	}
}
