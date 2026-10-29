<?php
/**
 * Icon cards: the site's card grids in one widget. Layouts: cards (2, 3 or 4 columns), the
 * forest-band comfort cards, the icon feature list, the emergency triage cards (with "Right now"
 * advice and an optional call card) and the "Call the dentist / Call 000" panels.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Icon cards widget.
 */
class Icon_Cards extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-icon-cards';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Icon Cards', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Cards', 'redlynch-dental' ) ) );
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cards',
				'options' => array(
					'cards'     => __( 'Cards grid', 'redlynch-dental' ),
					'comfort'   => __( 'Cards on dark green (two columns)', 'redlynch-dental' ),
					'features'  => __( 'Icon feature list (two columns)', 'redlynch-dental' ),
					'emergency' => __( 'Emergency cards with "Right now" advice', 'redlynch-dental' ),
					'when'      => __( 'Two checklist panels (green / red)', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			'columns',
			array(
				'label'     => __( 'Columns', 'redlynch-dental' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'g2',
				'options'   => array(
					'g2' => '2',
					'g3' => '3',
					'g4' => '4',
				),
				'condition' => array( 'layout' => 'cards' ),
			)
		);
		$r = new Repeater();
		$r->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tooth',
				'options' => rd_icon_options( true ),
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
				'label'       => __( 'Text', 'redlynch-dental' ),
				'type'        => Controls_Manager::WYSIWYG,
				'description' => __( 'For the checklist panels, use a bulleted list.', 'redlynch-dental' ),
			)
		);
		$r->add_control(
			'extra',
			array(
				'label'       => __( '"Right now" advice (emergency cards)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'Only shown in the emergency cards layout.', 'redlynch-dental' ),
			)
		);
		$r->add_control(
			'tone',
			array(
				'label'       => __( 'Panel colour (checklist panels)', 'redlynch-dental' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dent',
				'options'     => array(
					'dent' => __( 'Green', 'redlynch-dental' ),
					'000'  => __( 'Red', 'redlynch-dental' ),
				),
				'description' => __( 'Only used by the checklist panels layout.', 'redlynch-dental' ),
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
		$this->add_control(
			'call_title',
			array(
				'label'     => __( 'Final call card: heading (leave empty for none)', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'layout' => 'emergency' ),
			)
		);
		$this->add_control(
			'call_text',
			array(
				'label'     => __( 'Final call card: text', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array( 'layout' => 'emergency' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s      = $this->get_settings_for_display();
		$items  = (array) ( $s['items'] ?? array() );
		$layout = $s['layout'];
		$out    = '';

		if ( 'features' === $layout ) {
			foreach ( $items as $i ) {
				$out .= '<li>' . rd_icon( $i['icon'] ? $i['icon'] : 'check' ) . '<span><b>' . esc_html( $i['title'] ) . '</b>' . $this->inline( wp_strip_all_tags( (string) $i['text'], true ) ) . '</span></li>';
			}
			$this->out( '<ul class="feature-list">' . $out . '</ul>' );
			return;
		}

		if ( 'when' === $layout ) {
			foreach ( $items as $i ) {
				$cls  = '000' === $i['tone'] ? 'w-000' : 'w-dent';
				$text = str_replace( '<ul>', '<ul class="ticks" style="margin:0">', $this->rich( $i['text'] ) );
				$out .= '<div class="' . $cls . '"><h3>' . ( $i['icon'] ? rd_icon( $i['icon'] ) . ' ' : '' ) . esc_html( $i['title'] ) . '</h3>' . $text . '</div>';
			}
			$this->out( '<div class="when">' . $out . '</div>' );
			return;
		}

		if ( 'comfort' === $layout ) {
			foreach ( $items as $i ) {
				$out .= '<div><span>' . rd_icon( $i['icon'] ? $i['icon'] : 'check' ) . '</span><div><h3>' . esc_html( $i['title'] ) . '</h3>' . $this->rich( $i['text'] ) . '</div></div>';
			}
			$this->out( '<div class="comfort" data-reveal-group>' . $out . '</div>' );
			return;
		}

		if ( 'emergency' === $layout ) {
			foreach ( $items as $i ) {
				$out .= '<div class="em-card">' . ( $i['icon'] ? '<span class="ic">' . rd_icon( $i['icon'] ) . '</span>' : '' ) . '<h3>' . esc_html( $i['title'] ) . '</h3>' . $this->rich( $i['text'] )
					. ( '' !== trim( (string) $i['extra'] ) ? '<div class="do"><b>' . esc_html__( 'Right now', 'redlynch-dental' ) . '</b>' . esc_html( $i['extra'] ) . '</div>' : '' ) . '</div>';
			}
			if ( '' !== trim( (string) $s['call_title'] ) ) {
				$out .= '<div class="em-card call-card"><h3>' . esc_html( $s['call_title'] ) . '</h3><p>' . esc_html( $s['call_text'] ) . '</p>' . rd_button(
					array(
						'kind'  => 'call',
						'label' => rd_practice( 'phone' ),
						'class' => 'white',
					)
				) . '</div>';
			}
			$this->out( '<div class="em-grid" data-reveal-group>' . $out . '</div>' );
			return;
		}

		foreach ( $items as $i ) {
			$out .= '<div class="card">' . ( $i['icon'] ? '<div class="ic">' . rd_icon( $i['icon'] ) . '</div>' : '' ) . '<h3>' . esc_html( $i['title'] ) . '</h3>' . $this->rich( $i['text'] ) . '</div>';
		}
		$cols = in_array( $s['columns'], array( 'g2', 'g3', 'g4' ), true ) ? $s['columns'] : 'g2';
		$this->out( '<div class="grid ' . $cols . '" data-reveal-group>' . $out . '</div>' );
	}
}
