<?php
/**
 * Offer card: the new patient offer with its graphic, inclusions, the two prices, terms and buttons.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Offer widget.
 */
class Offer extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-offer';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Offer', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Offer', 'redlynch-dental' ) ) );
		$this->add_control(
			'image',
			array(
				'label'       => __( 'Offer graphic', 'redlynch-dental' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'Its alternative text (Media Library) should repeat the text in the graphic.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'lazy',
			array(
				'label'   => __( 'Load the graphic lazily (below the fold)', 'redlynch-dental' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'eyebrow',
			array(
				'label' => __( 'Small heading', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'title',
			array(
				'label'       => __( 'Heading', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$this->add_tag_control( 'title_tag', 'h2' );
		$this->add_control(
			'intro',
			array(
				'label' => __( 'Introduction', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_control(
			'ticks',
			array(
				'label'       => __( 'Included (one per line)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
			)
		);
		$r = new Repeater();
		$r->add_control(
			'big',
			array(
				'label' => __( 'Price', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$r->add_control(
			'small',
			array(
				'label' => __( 'Condition', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'prices',
			array(
				'label'       => __( 'Prices', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'title_field' => '{{{ big }}}',
			)
		);
		$this->add_control(
			'fine',
			array(
				'label' => __( 'Terms (small print)', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_buttons_control();
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s      = $this->get_settings_for_display();
		$tag    = $this->tag( $s['title_tag'] );
		$prices = '';
		foreach ( (array) ( $s['prices'] ?? array() ) as $p ) {
			$prices .= '<div><b>' . esc_html( $p['big'] ) . '</b><span>' . esc_html( $p['small'] ) . '</span></div>';
		}
		$img = $this->image_html(
			$s['image'],
			'large',
			array(
				'alt'      => null,
				'loading'  => 'yes' === $s['lazy'] ? 'lazy' : false,
				'decoding' => 'yes' === $s['lazy'] ? 'async' : false,
			)
		);
		$this->out(
			'<div class="offer" data-reveal>'
			. ( $img ? '<div class="o-img">' . $img . '</div>' : '' )
			. '<div class="o-body">'
			. ( $s['eyebrow'] ? '<span class="eyebrow">' . esc_html( $s['eyebrow'] ) . '</span>' : '' )
			. '<' . $tag . '>' . $this->inline( $s['title'] ) . '</' . $tag . '>'
			. ( '' !== trim( (string) $s['intro'] ) ? '<p>' . $this->inline( $s['intro'] ) . '</p>' : '' )
			. $this->ticks( $this->lines( $s['ticks'] ) )
			. ( $prices ? '<div class="price-strip">' . $prices . '</div>' : '' )
			. ( '' !== trim( (string) $s['fine'] ) ? '<p class="fine">' . esc_html( $s['fine'] ) . '</p>' : '' )
			. '<div class="actions mt-1">' . $this->buttons_html( $s['buttons'] ) . '</div>'
			. '</div></div>'
		);
	}
}
