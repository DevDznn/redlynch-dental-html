<?php
/**
 * Featured article: a large card for a blog post (the latest, or a chosen one) with its featured
 * image, category or a custom tag, byline, date, summary and link.
 *
 * @package Redlynch_Dental
 */

namespace RD\Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Featured article widget.
 */
class Post_Feature extends Base {

	/** @return string */
	public function get_name() {
		return 'rd-post-feature';
	}

	/** @return string */
	public function get_title() {
		return __( 'Redlynch Featured Article', 'redlynch-dental' );
	}

	/** @return string */
	public function get_icon() {
		return 'eicon-post';
	}

	/**
	 * Recent posts for the select control.
	 *
	 * @return array<int|string,string>
	 */
	private function post_options() {
		$out = array( '' => __( 'Latest post', 'redlynch-dental' ) );
		foreach ( get_posts(
			array(
				'numberposts'      => 50,
				'post_status'      => 'publish',
				'suppress_filters' => false,
			)
		) as $p ) {
			$out[ $p->ID ] = get_the_title( $p );
		}
		return $out;
	}

	/** Controls. */
	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Article', 'redlynch-dental' ) ) );
		$this->add_control(
			'post',
			array(
				'label'   => __( 'Post', 'redlynch-dental' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->post_options(),
			)
		);
		$this->add_control(
			'tag',
			array(
				'label'       => __( 'Tag', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Leave empty to show the post’s category.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'text',
			array(
				'label'       => __( 'Summary', 'redlynch-dental' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'Leave empty to use the post excerpt.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'date',
			array(
				'label'   => __( 'Show the date', 'redlynch-dental' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'decorative',
			array(
				'label'       => __( 'Image is decorative (empty alternative text)', 'redlynch-dental' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Use when the card sits next to text that already describes it.', 'redlynch-dental' ),
			)
		);
		$this->add_control(
			'more',
			array(
				'label'   => __( 'Link text', 'redlynch-dental' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Read the article', 'redlynch-dental' ),
			)
		);
		$this->end_controls_section();
	}

	/** Render. */
	protected function render() {
		$s  = $this->get_settings_for_display();
		$id = (int) $s['post'];
		if ( ! $id ) {
			$latest = get_posts(
				array(
					'numberposts' => 1,
					'post_status' => 'publish',
					'fields'      => 'ids',
				)
			);
			$id     = $latest ? (int) $latest[0] : 0;
		}
		if ( ! $id || 'publish' !== get_post_status( $id ) ) {
			return;
		}
		$cats   = get_the_category( $id );
		$tag    = '' !== trim( (string) $s['tag'] ) ? $s['tag'] : ( $cats && 'uncategorized' !== $cats[0]->slug ? $cats[0]->name : '' );
		$byline = (string) get_post_meta( $id, '_rd_byline', true );
		$author = '' !== $byline ? trim( strtok( $byline, ',' ) ) : get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $id ) );
		$text   = '' !== trim( (string) $s['text'] ) ? $s['text'] : get_the_excerpt( $id );
		$attrs  = array(
			'loading'  => 'lazy',
			'decoding' => 'async',
		);
		if ( 'yes' === $s['decorative'] ) {
			$attrs['alt'] = '';
		}
		$img = has_post_thumbnail( $id ) ? get_the_post_thumbnail( $id, 'large', $attrs ) : '';
		$this->out(
			'<a class="post-feature" href="' . esc_url( get_permalink( $id ) ) . '" data-reveal>' . $img
			. '<div class="pf-body"><div class="post-meta">' . ( $tag ? '<span class="tag">' . esc_html( $tag ) . '</span>' : '' ) . '<span>' . esc_html( $author ) . '</span>'
			. ( 'yes' === $s['date'] ? '<time datetime="' . esc_attr( get_the_date( 'Y-m-d', $id ) ) . '">' . esc_html( get_the_date( 'j F Y', $id ) ) . '</time>' : '' )
			. '</div><h2>' . esc_html( get_the_title( $id ) ) . '</h2><p>' . esc_html( $text ) . '</p><span class="text-link">' . esc_html( $s['more'] ) . ' ' . rd_icon( 'arrow' ) . '</span></div></a>'
		);
	}
}
