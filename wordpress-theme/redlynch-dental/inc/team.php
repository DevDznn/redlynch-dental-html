<?php
/**
 * Team members (Team in the admin menu): name = title, biography = content, photo = featured image,
 * plus role, qualifications, "areas of expertise" and whether they are a dentist or support team.
 * The Redlynch Team Elementor widget shows them on the homepage, About and Meet Our Team pages;
 * edit a profile once and every layout updates. Photos are cropped by the layouts with CSS
 * (fixed aspect ratios, focus near the top of the image), so any portrait photo works.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post type.
 */
function rd_register_team() {
	register_post_type(
		'rd_team',
		array(
			'labels'          => array(
				'name'               => __( 'Team', 'redlynch-dental' ),
				'singular_name'      => __( 'Team member', 'redlynch-dental' ),
				'add_new_item'       => __( 'Add team member', 'redlynch-dental' ),
				'edit_item'          => __( 'Edit team member', 'redlynch-dental' ),
				'new_item'           => __( 'New team member', 'redlynch-dental' ),
				'search_items'       => __( 'Search team', 'redlynch-dental' ),
				'not_found'          => __( 'No team members found.', 'redlynch-dental' ),
				'featured_image'     => __( 'Photo', 'redlynch-dental' ),
				'set_featured_image' => __( 'Set photo', 'redlynch-dental' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-groups',
			'menu_position'   => 21,
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'capability_type' => 'page',
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'rd_register_team' );

/**
 * Team member fields.
 *
 * @return array<string,string> meta key => label
 */
function rd_team_fields() {
	return array(
		'_rd_role'          => __( 'Role (e.g. Principal Dentist)', 'redlynch-dental' ),
		'_rd_quals'         => __( 'Qualifications, short (e.g. BDS)', 'redlynch-dental' ),
		'_rd_quals_long'    => __( 'Qualifications, in full (profile page)', 'redlynch-dental' ),
		'_rd_focus_heading' => __( 'List heading (e.g. Areas of expertise)', 'redlynch-dental' ),
		'_rd_focus'         => __( 'List items, one per line', 'redlynch-dental' ),
		'_rd_anchor'        => __( 'Link anchor on the team page (e.g. dr-alex-bornsztejn)', 'redlynch-dental' ),
	);
}

/**
 * Team members of a group, in order.
 *
 * @param string $group 'dentist' or 'support'.
 * @return array<int,array>
 */
function rd_get_team( $group = 'dentist' ) {
	$q   = new WP_Query(
		array(
			'post_type'      => 'rd_team',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'meta_key'       => '_rd_group', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- a handful of posts.
			'meta_value'     => $group, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'  => true,
		)
	);
	$out = array();
	foreach ( $q->posts as $p ) {
		$anchor = (string) get_post_meta( $p->ID, '_rd_anchor', true );
		$focus  = array_filter( array_map( 'trim', explode( "\n", (string) get_post_meta( $p->ID, '_rd_focus', true ) ) ) );
		$out[]  = array(
			'id'            => $p->ID,
			'name'          => get_the_title( $p ),
			'role'          => (string) get_post_meta( $p->ID, '_rd_role', true ),
			'quals'         => (string) get_post_meta( $p->ID, '_rd_quals', true ),
			'quals_long'    => (string) get_post_meta( $p->ID, '_rd_quals_long', true ),
			'focus_heading' => (string) get_post_meta( $p->ID, '_rd_focus_heading', true ),
			'focus'         => array_values( $focus ),
			'anchor'        => '' !== $anchor ? sanitize_title( $anchor ) : sanitize_title( get_the_title( $p ) ),
			'img'           => (int) get_post_thumbnail_id( $p ),
			'bio'           => $p->post_content,
		);
	}
	return $out;
}

/**
 * Jump links to the profiles on the team page (small round photos), plus the support team.
 *
 * @param string $support_label Text of the support team link.
 * @return string
 */
function rd_team_jump_html( $support_label = '' ) {
	$thumb = function ( $id ) {
		return $id ? wp_get_attachment_image(
			$id,
			'medium',
			false,
			array(
				'alt'     => '',
				'sizes'   => '34px',
				'loading' => false,
			)
		) : '';
	};
	$out = '';
	foreach ( rd_get_team( 'dentist' ) as $m ) {
		$out .= '<li><a href="#' . esc_attr( $m['anchor'] ) . '">' . $thumb( $m['img'] ) . esc_html( $m['name'] ) . '</a></li>';
	}
	$support = rd_get_team( 'support' );
	if ( $support ) {
		$out .= '<li><a href="#support-team">' . $thumb( $support[0]['img'] ) . esc_html( '' !== $support_label ? $support_label : __( 'Support team', 'redlynch-dental' ) ) . '</a></li>';
	}
	return $out ? '<ul class="team-jump">' . $out . '</ul>' : '';
}

/**
 * Meta box.
 */
function rd_team_meta_box() {
	add_meta_box( 'rd-team', __( 'Profile details', 'redlynch-dental' ), 'rd_team_meta_box_html', 'rd_team', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'rd_team_meta_box' );

/**
 * Meta box fields.
 *
 * @param WP_Post $post Team member.
 */
function rd_team_meta_box_html( $post ) {
	wp_nonce_field( 'rd_team_save', 'rd_team_nonce' );
	$group = (string) get_post_meta( $post->ID, '_rd_group', true );
	?>
	<p><label for="rd_group"><strong><?php esc_html_e( 'Group', 'redlynch-dental' ); ?></strong></label><br>
		<select id="rd_group" name="rd_group">
			<option value="dentist" <?php selected( $group, 'dentist' ); ?>><?php esc_html_e( 'Dentist (full profile)', 'redlynch-dental' ); ?></option>
			<option value="support" <?php selected( $group, 'support' ); ?>><?php esc_html_e( 'Support team', 'redlynch-dental' ); ?></option>
		</select></p>
	<?php foreach ( rd_team_fields() as $key => $label ) : ?>
		<?php $val = (string) get_post_meta( $post->ID, $key, true ); ?>
		<p><label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<?php if ( '_rd_focus' === $key ) : ?>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>"><?php echo esc_textarea( $val ); ?></textarea>
		<?php else : ?>
			<input type="text" class="widefat" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>">
		<?php endif; ?>
		</p>
	<?php endforeach; ?>
	<p class="description"><?php esc_html_e( 'The biography is the main text above. The photo is the featured image: a portrait at least 720 px wide works best; layouts crop it to fixed shapes, keeping the top of the photo.', 'redlynch-dental' ); ?></p>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function rd_team_save( $post_id ) {
	if ( ! isset( $_POST['rd_team_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rd_team_nonce'] ) ), 'rd_team_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$group = sanitize_key( wp_unslash( $_POST['rd_group'] ?? 'dentist' ) );
	update_post_meta( $post_id, '_rd_group', 'support' === $group ? 'support' : 'dentist' );
	foreach ( array_keys( rd_team_fields() ) as $key ) {
		$raw = wp_unslash( $_POST[ $key ] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
		update_post_meta( $post_id, $key, '_rd_focus' === $key ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw ) );
	}
}
add_action( 'save_post_rd_team', 'rd_team_save' );

/**
 * Admin list: show the role and group.
 *
 * @param array $cols Columns.
 * @return array
 */
function rd_team_columns( $cols ) {
	$cols['rd_role']  = __( 'Role', 'redlynch-dental' );
	$cols['rd_group'] = __( 'Group', 'redlynch-dental' );
	return $cols;
}
add_filter( 'manage_rd_team_posts_columns', 'rd_team_columns' );

/**
 * Admin list column values.
 *
 * @param string $col     Column.
 * @param int    $post_id Post ID.
 */
function rd_team_column_values( $col, $post_id ) {
	if ( 'rd_role' === $col ) {
		echo esc_html( (string) get_post_meta( $post_id, '_rd_role', true ) );
	} elseif ( 'rd_group' === $col ) {
		echo 'support' === get_post_meta( $post_id, '_rd_group', true ) ? esc_html__( 'Support team', 'redlynch-dental' ) : esc_html__( 'Dentist', 'redlynch-dental' );
	}
}
add_action( 'manage_rd_team_posts_custom_column', 'rd_team_column_values', 10, 2 );
