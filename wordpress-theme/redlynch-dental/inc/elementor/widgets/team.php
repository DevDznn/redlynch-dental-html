<?php
/**
 * Team: shows the profiles from Team in the admin menu (name, role, qualifications, biography,
 * photo). Edit a profile once and every layout follows. Layouts: homepage cards, the About
 * page's dark row, full profiles (Meet Our Team), the support team row or cards, and the jump links.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Team widget.
 */
class Team extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-team';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Team', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-person';
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Team', 'redlynch-dental' ) ) );
		$this->add_control(
			'layout',
			array(
				'label'       => __( 'Layout', 'redlynch-dental' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cards',
				'options'     => array(
					'cards'        => __( 'Dentists: cards', 'redlynch-dental' ),
					'row'          => __( 'Dentists: on dark green', 'redlynch-dental' ),
					'profiles'     => __( 'Dentists: full profiles', 'redlynch-dental' ),
					'support-row'  => __( 'Support team: compact row', 'redlynch-dental' ),
					'support-grid' => __( 'Support team: cards', 'redlynch-dental' ),
					'jump'         => __( 'Jump links to profiles', 'redlynch-dental' ),
				),
				'description' => __( 'Profiles are edited under Team in the WordPress admin menu.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'link',
			array(
				'label'       => __( 'Team page (cards link to each profile on it)', 'redlynch-dental' ),
				'type'        => Controls_Manager::URL,
				'condition'   => array( 'layout' => array( 'cards', 'row' ) ),
				'description' => __( 'Leave empty to use the Meet Our Team page.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'support_label',
			array(
				'label'     => __( 'Support team link text', 'redlynch-dental' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Support team', 'redlynch-dental' ),
				'condition' => array( 'layout' => 'jump' ),
			)
		);
		$this->add_control(
			'book',
			array(
				'label'     => __( 'Book online button under each profile', 'redlynch-dental' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'layout' => 'profiles' ),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * A team photo.
	 *
	 * @param array  $m     Member.
	 * @param string $alt   Alt text.
	 * @param array  $attrs Extra attributes.
	 * @param string $size  Image size.
	 * @return string
	 */
	private function photo( $m, $alt, $attrs = array(), $size = 'rd-portrait' ) {
		if ( ! $m['img'] ) {
			return '';
		}
		return wp_get_attachment_image(
			$m['img'],
			$size,
			false,
			array_merge(
				array(
					'alt'      => $alt,
					'loading'  => 'lazy',
					'decoding' => 'async',
				),
				$attrs
			)
		);
	}

	/** Render. */
	protected function render() {
		$s        = $this->get_settings_for_display();
		$layout   = $s['layout'];
		$practice = rd_practice( 'name' );
		$base     = $this->url( $s['link'] ?? '' );
		$base     = '' !== $base ? $base : rd_page_url( 'our-team' );
		$out      = '';

		if ( 'support-row' === $layout || 'support-grid' === $layout ) {
			foreach ( rd_get_team( 'support' ) as $m ) {
				if ( 'support-row' === $layout ) {
					/* translators: 1: name, 2: role, 3: practice name. */
					$alt  = sprintf( __( '%1$s, %2$s at %3$s', 'redlynch-dental' ), $m['name'], $m['role'], $practice );
					$out .= '<div class="support">' . $this->photo( $m, $alt, array( 'sizes' => '92px' ), 'medium' ) . '<div><h3>' . esc_html( $m['name'] ) . '</h3><p>' . esc_html( $m['role'] . ( $m['quals'] ? ' · ' . $m['quals'] : '' ) ) . '</p></div></div>';
				} else {
					/* translators: 1: name, 2: role, 3: practice name. */
					$alt  = sprintf( __( 'Portrait of %1$s, %2$s at %3$s', 'redlynch-dental' ), $m['name'], $m['role'], $practice );
					$out .= '<div class="support-card">' . $this->photo( $m, $alt ) . '<div><span class="eyebrow">' . esc_html( $m['role'] ) . '</span><h3 style="font-size:1.8rem">' . esc_html( $m['name'] ) . '</h3>' . ( $m['quals'] ? '<p>' . esc_html( $m['quals'] ) . '</p>' : '' ) . '</div></div>';
				}
			}
			$this->out( '<div class="' . esc_attr( $layout ) . '" data-reveal-group>' . $out . '</div>' );
			return;
		}

		$team = rd_get_team( 'dentist' );

		if ( 'jump' === $layout ) {
			$this->out( rd_team_jump_html( (string) $s['support_label'] ) );
			return;
		}

		if ( 'profiles' === $layout ) {
			foreach ( $team as $i => $m ) {
				/* translators: 1: name, 2: role, 3: practice name. */
				$alt   = sprintf( __( 'Portrait of %1$s, %2$s at %3$s', 'redlynch-dental' ), $m['name'], $m['role'], $practice );
				$focus = $m['focus'] ? '<div class="interests"><h3>' . esc_html( $m['focus_heading'] ? $m['focus_heading'] : __( 'Known for', 'redlynch-dental' ) ) . '</h3>' . $this->ticks( $m['focus'] ) . '</div>' : '';
				$book  = 'yes' === $s['book'] ? '<div class="actions mt-1">' . rd_button(
					array(
						'kind'  => 'book',
						'class' => 'sm',
					)
				) . '</div>' : '';
				$out  .= '<article class="profile' . ( $i % 2 ? ' flip' : '' ) . '" id="' . esc_attr( $m['anchor'] ) . '" aria-labelledby="' . esc_attr( $m['anchor'] ) . '-h">
  <div class="p-photo" data-reveal><div class="arch">' . $this->photo( $m, $alt ) . '</div></div>
  <div data-reveal>
   <span class="role">' . esc_html( $m['role'] ) . '</span>
   <h2 id="' . esc_attr( $m['anchor'] ) . '-h">' . esc_html( $m['name'] ) . '</h2>
   ' . ( $m['quals_long'] ? '<span class="quals">' . esc_html( $m['quals_long'] ) . '</span>' : '' ) . '
   ' . wp_kses_post( wpautop( $m['bio'] ) ) . $focus . $book . '
  </div>
 </article>';
			}
			$this->out( $out );
			return;
		}

		foreach ( $team as $m ) {
			$href = $base . '#' . $m['anchor'];
			if ( 'row' === $layout ) {
				/* translators: 1: name, 2: role. */
				$alt  = sprintf( __( '%1$s, %2$s', 'redlynch-dental' ), $m['name'], $m['role'] );
				$out .= '<a class="member" href="' . esc_url( $href ) . '" style="color:#fff"><div class="arch">' . $this->photo( $m, $alt ) . '</div><h3>' . esc_html( $m['name'] ) . '</h3><span class="role" style="color:var(--lime)">' . esc_html( $m['role'] ) . '</span><span class="qual" style="color:#C3CEC6">' . esc_html( $m['quals'] ) . '</span></a>';
			} else {
				/* translators: 1: name, 2: role, 3: practice name. */
				$alt  = sprintf( __( '%1$s, %2$s at %3$s', 'redlynch-dental' ), $m['name'], $m['role'], $practice );
				$out .= '<a class="member" href="' . esc_url( $href ) . '"><div class="arch">' . $this->photo( $m, $alt ) . '</div><div class="m-body"><h3>' . esc_html( $m['name'] ) . '</h3><span class="role">' . esc_html( $m['role'] ) . '</span><span class="qual">' . esc_html( $m['quals'] ) . '</span></div></a>';
			}
		}
		$this->out( '<div class="team-row' . ( 'cards' === $layout ? ' cards' : '' ) . '" data-reveal-group>' . $out . '</div>' );
	}
}
