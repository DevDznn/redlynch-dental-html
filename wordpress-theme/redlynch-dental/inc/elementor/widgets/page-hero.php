<?php
/**
 * Page hero: the top section of inner pages. Dark green or cream; text only, or split with a
 * photo, an illustration on white, a designed type graphic (pages without photography) or a
 * dark checklist card. Breadcrumbs, small heading, H1, introduction, buttons, info chips and,
 * for the team page, jump links to each profile.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Page hero widget.
 */
class Page_Hero extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-page-hero';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Page Hero', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-header';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Text', 'redlynch-dental' ) ) );
		$this->add_control(
			'tone',
			array(
				'label'   => __( 'Background', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'forest',
				'options' => array(
					'forest' => __( 'Dark green', 'redlynch-dental' ),
					'cream'  => __( 'Cream', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			'narrow',
			array(
				'label' => __( 'Narrow text column', 'redlynch-dental' ),
				'type'  => Controls_Manager::SWITCHER,
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
				'label'       => __( 'Page heading (H1)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'description' => __( 'Leave empty to use the page title.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'lead',
			array(
				'label' => __( 'Introduction', 'redlynch-dental' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);
		$this->add_buttons_control( 'buttons', array() );
		$this->add_control(
			'meta',
			array(
				'label'       => __( 'Info chips (one per line)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'description' => __( '{address} is replaced with the street and suburb.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'team_jump',
			array(
				'label'       => __( 'Show jump links to team profiles', 'redlynch-dental' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'For the Meet Our Team page.', 'redlynch-dental' ),
			)
		);
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
		$r = new Repeater();
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
				'condition'   => array( 'crumbs' => 'yes' ),
			)
		);
		$this->add_control(
			'current',
			array(
				'label'       => __( 'This page in the breadcrumbs', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Leave empty to use the page title.', 'redlynch-dental' ),
				'condition'   => array( 'crumbs' => 'yes' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'visual_section', array( 'label' => __( 'Right-hand side', 'redlynch-dental' ) ) );
		$this->add_control(
			'visual',
			array(
				'label'   => __( 'Show', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'    => __( 'Nothing (text only)', 'redlynch-dental' ),
					'image'   => __( 'Photo', 'redlynch-dental' ),
					'contain' => __( 'Illustration on white (shown whole)', 'redlynch-dental' ),
					'art'     => __( 'Type graphic (no photo)', 'redlynch-dental' ),
					'card'    => __( 'Dark checklist card', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			'image',
			array(
				'label'     => __( 'Image', 'redlynch-dental' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'visual' => array( 'image', 'contain' ) ),
			)
		);
		$this->add_control(
			'alt',
			array(
				'label'       => __( 'Alternative text (optional)', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Leave empty to use the Media Library text.', 'redlynch-dental' ),
				'condition'   => array( 'visual' => array( 'image', 'contain' ) ),
			)
		);
		$this->add_control(
			'focus',
			array(
				'label'       => __( 'Photo focus point', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Optional, e.g. 30% 40% (horizontal, vertical).', 'redlynch-dental' ),
				'condition'   => array( 'visual' => 'image' ),
			)
		);
		$this->add_control(
			'art_icon',
			array(
				'label'     => __( 'Icon', 'redlynch-dental' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'tooth',
				'options'   => rd_icon_options(),
				'condition' => array( 'visual' => 'art' ),
			)
		);
		$this->add_control(
			'art_text',
			array(
				'label'       => __( 'Graphic text', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Wrap words in <em></em> to show them in lime italics.', 'redlynch-dental' ),
				'condition'   => array( 'visual' => 'art' ),
			)
		);
		$this->add_control(
			'card_eyebrow',
			array(
				'label'     => __( 'Card small heading', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'visual' => 'card' ),
			)
		);
		$this->add_control(
			'card_title',
			array(
				'label'     => __( 'Card heading', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'visual' => 'card' ),
			)
		);
		$this->add_control(
			'card_ticks',
			array(
				'label'     => __( 'Card checklist (one per line)', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 7,
				'condition' => array( 'visual' => 'card' ),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Breadcrumb trail.
	 *
	 * @param array $s Settings.
	 * @return string
	 */
	protected function crumbs_html( $s ) {
		if ( 'yes' !== $s['crumbs'] ) {
			return '';
		}
		$trail = array( array( __( 'Home', 'redlynch-dental' ), home_url( '/' ) ) );
		foreach ( (array) ( $s['parents'] ?? array() ) as $p ) {
			$trail[] = array( $p['label'], $this->url( $p['link'] ) );
		}
		$trail[] = array( '' !== trim( (string) $s['current'] ) ? $s['current'] : get_the_title(), '' );
		return rd_breadcrumbs( $trail );
	}

	/**
	 * Info chips.
	 *
	 * @param string $text Lines.
	 * @return string
	 */
	protected function meta_html( $text ) {
		$items = $this->lines( str_replace( '{address}', rd_practice( 'street' ) . ', ' . rd_practice( 'suburb' ), (string) $text ) );
		if ( ! $items ) {
			return '';
		}
		$out = '<ul class="hero-meta">';
		foreach ( $items as $m ) {
			$out .= '<li>' . esc_html( $m ) . '</li>';
		}
		return $out . '</ul>';
	}

	/** Render. */
	protected function render() {
		$s       = $this->get_settings_for_display();
		$title   = '' !== trim( (string) $s['title'] ) ? $this->inline( $s['title'] ) : esc_html( get_the_title() );
		$buttons = $this->buttons_html( $s['buttons'] ?? array() );
		$copy    = $this->crumbs_html( $s )
			. ( $s['eyebrow'] ? '<span class="eyebrow">' . esc_html( $s['eyebrow'] ) . '</span>' : '' )
			. '<h1>' . $title . '</h1>'
			. ( '' !== trim( (string) $s['lead'] ) ? '<p class="lead">' . $this->inline( $s['lead'] ) . '</p>' : '' )
			. ( $buttons ? '<div class="actions mt-1">' . $buttons . '</div>' : '' )
			. $this->meta_html( $s['meta'] )
			. ( 'yes' === $s['team_jump'] ? rd_team_jump_html() : '' );

		$visual = '';
		switch ( $s['visual'] ) {
			case 'image':
				$focus  = preg_match( '/^\d{1,3}% \d{1,3}%$/', (string) $s['focus'] ) ? 'object-position:' . $s['focus'] : false;
				$visual = '<div class="ph-img">' . $this->image_html(
					$s['image'],
					'large',
					array(
						'alt'           => '' !== trim( (string) $s['alt'] ) ? $s['alt'] : null,
						'loading'       => false,
						'decoding'      => false,
						'fetchpriority' => 'high',
						'style'         => $focus,
					)
				) . '</div>';
				break;
			case 'contain':
				$visual = '<div class="ph-img" style="background:#fff;aspect-ratio:1/1;max-width:440px;justify-self:center;width:100%">' . $this->image_html(
					$s['image'],
					'large',
					array(
						'alt'           => '' !== trim( (string) $s['alt'] ) ? $s['alt'] : null,
						'loading'       => false,
						'decoding'      => false,
						'fetchpriority' => 'high',
						'style'         => 'object-fit:contain;padding:14px',
					)
				) . '</div>';
				break;
			case 'art':
				$visual = '<div class="type-art" aria-hidden="true"><span class="ta-ic">' . rd_icon( $s['art_icon'] ) . '</span><span class="ta-k">' . esc_html( $s['eyebrow'] ) . '</span><p class="ta-t">' . $this->inline( $s['art_text'] ) . '</p></div>';
				break;
			case 'card':
				$visual = '<div class="card" style="background:var(--ink);color:#C9D4CD;border:0;padding:32px">'
					. ( $s['card_eyebrow'] ? '<span class="eyebrow">' . esc_html( $s['card_eyebrow'] ) . '</span>' : '' )
					. '<h2 style="color:#fff;font-size:1.7rem">' . esc_html( $s['card_title'] ) . '</h2>'
					. $this->ticks( $this->lines( $s['card_ticks'] ), 'margin:0' ) . '</div>';
				break;
		}

		$cls  = 'page-hero' . ( 'cream' === $s['tone'] ? ' cream' : '' ) . ( $visual ? ' split-hero' : '' );
		$wrap = 'wrap' . ( 'yes' === $s['narrow'] ? ' narrow' : '' );
		$this->out(
			'<section class="' . esc_attr( $cls ) . '"><div class="' . esc_attr( $wrap ) . '">'
			. ( $visual ? '<div>' . $copy . '</div>' . $visual : $copy )
			. '</div></section>'
		);
	}
}
