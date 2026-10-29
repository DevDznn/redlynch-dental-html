<?php
/**
 * Service directory: every service page, grouped by category, with the sticky category index
 * (the Services page). New service pages appear automatically once their "Service listing" box
 * is ticked; order follows Page Attributes › Order.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Service directory widget.
 */
class Service_Directory extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-service-directory';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Service Directory', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Categories', 'redlynch-dental' ) ) );
		foreach ( rd_service_categories() as $id => $c ) {
			$this->add_control(
				'name_' . $id,
				array(
					/* translators: %s: category. */
					'label'     => sprintf( __( '%s: name', 'redlynch-dental' ), $c['name'] ),
					'type'      => Controls_Manager::TEXT,
					'default'   => $c['name'],
					'separator' => 'before',
				)
			);
			$this->add_control(
				'blurb_' . $id,
				array(
					/* translators: %s: category. */
					'label'   => sprintf( __( '%s: description', 'redlynch-dental' ), $c['name'] ),
					'type'    => Controls_Manager::TEXTAREA,
					'default' => $c['blurb'],
				)
			);
		}
		$this->add_control(
			'note',
			array(
				'label'     => __( 'Note under the list', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s      = $this->get_settings_for_display();
		$byc    = array();
		foreach ( rd_get_services() as $svc ) {
			$byc[ $svc['cat'] ][] = $svc;
		}
		$nav    = '';
		$groups = '';
		foreach ( rd_service_categories() as $id => $c ) {
			if ( empty( $byc[ $id ] ) ) {
				continue;
			}
			$list  = $byc[ $id ];
			$name  = '' !== trim( (string) ( $s[ 'name_' . $id ] ?? '' ) ) ? $s[ 'name_' . $id ] : $c['name'];
			$nav  .= '<li><a href="#' . esc_attr( $id ) . '">' . esc_html( $name ) . '</a></li>';
			$items = '';
			foreach ( $list as $svc ) {
				$items .= '<li><a class="svc-row' . ( 'urgent' === $svc['cat'] ? ' em' : '' ) . '" href="' . esc_url( $svc['url'] ) . '">' . rd_service_image( $svc, 'medium', array( 'sizes' => '150px' ) ) . '<div><h3>' . esc_html( $svc['name'] ) . '</h3><p>' . esc_html( $svc['short'] ) . '</p></div><span class="go">' . rd_icon( 'arrow' ) . '</span></a></li>';
			}
			/* translators: %d: number of services. */
			$count   = sprintf( _n( '%d service', '%d services', count( $list ), 'redlynch-dental' ), count( $list ) );
			$groups .= '<div class="svc-group" id="' . esc_attr( $id ) . '"><h2>' . esc_html( $name ) . ' <small>' . esc_html( $count ) . '</small></h2><p>' . esc_html( $s[ 'blurb_' . $id ] ?? $c['blurb'] ) . '</p>
 <ul class="svc-list" data-reveal-group>' . $items . '</ul></div>';
		}
		$note = '' !== trim( (string) $s['note'] ) ? '<p class="fine">' . esc_html( $s['note'] ) . '</p>' : '';
		$this->out( '<div class="svc-index"><nav aria-label="' . esc_attr__( 'Service categories', 'redlynch-dental' ) . '"><ul class="svc-jump" data-spy>' . $nav . '</ul></nav><div>' . $groups . $note . '</div></div>' );
	}
}
