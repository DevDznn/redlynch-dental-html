<?php
/**
 * Base class for the Redlynch Dental Elementor widgets: shared controls (buttons, links, images)
 * and output helpers. Widgets print the static site's markup, so the site stylesheet styles them.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Shared widget behaviour.
 */
abstract class Base extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'redlynch-dental' );
	}

	/**
	 * No extra .elementor-widget-container wrapper: the widget's own markup is the content.
	 *
	 * @return bool
	 */
	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/**
	 * Search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'redlynch', 'dental' );
	}

	/**
	 * Button style options.
	 *
	 * @return array<string,string>
	 */
	protected function button_styles() {
		return array(
			''            => __( 'Lime (main)', 'redlynch-dental' ),
			'ghost'       => __( 'Outline, dark', 'redlynch-dental' ),
			'ghost-light' => __( 'Outline, light (on dark backgrounds)', 'redlynch-dental' ),
			'dark'        => __( 'Dark', 'redlynch-dental' ),
			'forest'      => __( 'Forest green', 'redlynch-dental' ),
			'ember'       => __( 'Emergency red', 'redlynch-dental' ),
			'white'       => __( 'White', 'redlynch-dental' ),
			'text'        => __( 'Text link with arrow', 'redlynch-dental' ),
		);
	}

	/**
	 * A buttons repeater control.
	 *
	 * @param string $id       Control ID.
	 * @param array  $defaults Default items.
	 * @param string $label    Label.
	 */
	protected function add_buttons_control( $id = 'buttons', $defaults = array(), $label = '' ) {
		$r = new Repeater();
		$r->add_control(
			'kind',
			array(
				'label'   => __( 'Button links to', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => array(
					'book'      => __( 'Online booking (from Customizer)', 'redlynch-dental' ),
					'call'      => __( 'Phone call (from Customizer)', 'redlynch-dental' ),
					'emergency' => __( 'Emergency dental care page', 'redlynch-dental' ),
					'email'     => __( 'Email (from Customizer)', 'redlynch-dental' ),
					'link'      => __( 'Custom link', 'redlynch-dental' ),
				),
			)
		);
		$r->add_control(
			'label',
			array(
				'label'       => __( 'Text', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Leave empty for the standard text. {phone} is replaced with the phone number.', 'redlynch-dental' ),
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
			'style',
			array(
				'label'   => __( 'Style', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->button_styles(),
			)
		);
		$r->add_control(
			'size',
			array(
				'label'   => __( 'Size', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'sm' => __( 'Small', 'redlynch-dental' ),
					''   => __( 'Normal', 'redlynch-dental' ),
					'lg' => __( 'Large', 'redlynch-dental' ),
				),
			)
		);
		$r->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array_merge(
					array(
						''     => __( 'Standard for this link', 'redlynch-dental' ),
						'none' => __( 'No icon', 'redlynch-dental' ),
					),
					rd_icon_options()
				),
			)
		);
		$r->add_control(
			'arrow',
			array(
				'label'   => __( 'Arrow after the text', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''    => __( 'Standard for this link', 'redlynch-dental' ),
					'yes' => __( 'Yes', 'redlynch-dental' ),
					'no'  => __( 'No', 'redlynch-dental' ),
				),
			)
		);
		$this->add_control(
			$id,
			array(
				'label'       => $label ? $label : __( 'Buttons', 'redlynch-dental' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $r->get_controls(),
				'default'     => $defaults,
				'title_field' => '{{{ label || kind }}}',
			)
		);
	}

	/**
	 * Buttons markup from a repeater value.
	 *
	 * @param array $items Repeater items.
	 * @return string
	 */
	protected function buttons_html( $items ) {
		$out = '';
		foreach ( (array) $items as $b ) {
			$kind  = $b['kind'] ?? 'link';
			$style = $b['style'] ?? '';
			$label = (string) ( $b['label'] ?? '' );
			$url   = $b['link']['url'] ?? '';
			if ( 'text' === $style ) {
				$href = 'book' === $kind ? rd_practice( 'booking_url' ) : ( 'call' === $kind ? rd_tel_href() : ( 'emergency' === $kind ? rd_page_url( 'emergency-dental-care' ) : $url ) );
				$out .= '<a class="text-link" href="' . esc_url( $href ) . '">' . esc_html( $label ) . ' ' . rd_icon( 'arrow' ) . '</a>';
				continue;
			}
			$arrow = $b['arrow'] ?? '';
			$out  .= rd_button(
				array(
					'kind'    => $kind,
					'label'   => $label,
					'class'   => trim( $style . ' ' . ( $b['size'] ?? '' ) ),
					'url'     => $url,
					'new_tab' => ! empty( $b['link']['is_external'] ),
					'icon'    => $b['icon'] ?? '',
					'arrow'   => '' === $arrow ? null : 'yes' === $arrow,
				)
			);
		}
		return $out;
	}

	/**
	 * Image markup from a MEDIA control value (attachment when possible, so srcset works).
	 *
	 * @param array  $media Control value.
	 * @param string $size  Image size.
	 * @param array  $attrs Attributes (alt '' = decorative; null alt = use the Media Library alt).
	 * @return string
	 */
	protected function image_html( $media, $size = 'large', $attrs = array() ) {
		$attrs = array_merge(
			array(
				'loading'  => 'lazy',
				'decoding' => 'async',
			),
			$attrs
		);
		if ( ! empty( $media['id'] ) && wp_attachment_is_image( (int) $media['id'] ) ) {
			foreach ( $attrs as $k => $v ) {
				// WordPress reads loading/decoding = false as "omit"; drop other empty attributes.
				if ( ( null === $v || false === $v ) && ! in_array( $k, array( 'loading', 'decoding' ), true ) ) {
					unset( $attrs[ $k ] );
				}
			}
			return wp_get_attachment_image( (int) $media['id'], $size, false, $attrs );
		}
		if ( empty( $media['url'] ) ) {
			return '';
		}
		$html = '<img src="' . esc_url( $media['url'] ) . '"';
		foreach ( $attrs as $k => $v ) {
			if ( null === $v || false === $v ) {
				continue;
			}
			$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
		if ( ! isset( $attrs['alt'] ) ) {
			$html .= ' alt=""';
		}
		return $html . '>';
	}

	/**
	 * Inline text that may contain simple emphasis (headings, labels).
	 *
	 * @param string $text Text.
	 * @return string
	 */
	protected function inline( $text ) {
		return wp_kses(
			(string) $text,
			array(
				'em'     => array(),
				'strong' => array(),
				'b'      => array(),
				'i'      => array(),
				'br'     => array(),
				'wbr'    => array(),
				'span'   => array( 'class' => true ),
				'a'      => array(
					'href'   => true,
					'target' => true,
					'rel'    => true,
					'class'  => true,
				),
			)
		);
	}

	/**
	 * Rich text (WYSIWYG fields): post HTML; adds paragraphs to plain text.
	 *
	 * @param string $html HTML.
	 * @return string
	 */
	protected function rich( $html ) {
		$html = trim( (string) $html );
		if ( '' === $html ) {
			return '';
		}
		if ( '<' !== $html[0] ) {
			$html = wpautop( $html );
		}
		return wp_kses_post( $html );
	}

	/**
	 * Lines of a textarea as an array.
	 *
	 * @param string $text Text.
	 * @return string[]
	 */
	protected function lines( $text ) {
		return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
	}

	/**
	 * A "ticks" list.
	 *
	 * @param string[] $items Items (inline HTML allowed).
	 * @param string   $style Inline style.
	 * @return string
	 */
	protected function ticks( $items, $style = '' ) {
		if ( ! $items ) {
			return '';
		}
		$out = '<ul class="ticks"' . ( $style ? ' style="' . esc_attr( $style ) . '"' : '' ) . '>';
		foreach ( $items as $i ) {
			$out .= '<li>' . $this->inline( $i ) . '</li>';
		}
		return $out . '</ul>';
	}

	/**
	 * URL from a URL control value.
	 *
	 * @param array $link Control value.
	 * @return string
	 */
	protected function url( $link ) {
		return is_array( $link ) ? (string) ( $link['url'] ?? '' ) : (string) $link;
	}

	/**
	 * A heading-tag select control.
	 *
	 * @param string $id      Control ID.
	 * @param string $default Default tag.
	 */
	protected function add_tag_control( $id, $default = 'h2' ) {
		$this->add_control(
			$id,
			array(
				'label'   => __( 'Heading level', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default,
				'options' => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'div' => 'div',
				),
			)
		);
	}

	/**
	 * Validated heading tag.
	 *
	 * @param string $tag Tag.
	 * @return string
	 */
	protected function tag( $tag ) {
		return in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'div', 'span', 'p' ), true ) ? $tag : 'h2';
	}

	/**
	 * Print markup assembled by a widget. All dynamic values are escaped as the markup is built.
	 *
	 * @param string $html HTML.
	 */
	protected function out( $html ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every value is escaped where the markup is assembled.
	}
}
