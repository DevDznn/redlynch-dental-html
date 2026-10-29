<?php
/**
 * Blog posts: an optional byline (e.g. "Dr Nadia Mullins, Associate Dentist") shown on the
 * article and on the Featured Article widget instead of the WordPress author name.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the byline meta.
 */
function rd_register_post_meta() {
	register_post_meta(
		'post',
		'_rd_byline',
		array(
			'type'          => 'string',
			'single'        => true,
			'show_in_rest'  => true,
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'rd_register_post_meta' );

/**
 * Meta box.
 */
function rd_byline_meta_box() {
	add_meta_box( 'rd-byline', __( 'Byline', 'redlynch-dental' ), 'rd_byline_meta_box_html', 'post', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'rd_byline_meta_box' );

/**
 * Meta box field.
 *
 * @param WP_Post $post Post.
 */
function rd_byline_meta_box_html( $post ) {
	wp_nonce_field( 'rd_byline_save', 'rd_byline_nonce' );
	printf(
		'<p><label for="rd_byline">%s</label><input type="text" class="widefat" id="rd_byline" name="rd_byline" value="%s"></p><p class="description">%s</p>',
		esc_html__( 'Written by', 'redlynch-dental' ),
		esc_attr( (string) get_post_meta( $post->ID, '_rd_byline', true ) ),
		esc_html__( 'For example "Dr Nadia Mullins, Associate Dentist". Leave empty to show the author’s name.', 'redlynch-dental' )
	);
}

/**
 * Save the byline.
 *
 * @param int $post_id Post ID.
 */
function rd_byline_save( $post_id ) {
	if ( ! isset( $_POST['rd_byline_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rd_byline_nonce'] ) ), 'rd_byline_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_rd_byline', sanitize_text_field( wp_unslash( $_POST['rd_byline'] ?? '' ) ) );
}
add_action( 'save_post_post', 'rd_byline_save' );
