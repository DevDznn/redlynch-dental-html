<?php
/**
 * Services: each treatment is an ordinary WordPress page (so its address stays /dental-implants/ etc.)
 * with a "Service listing" box that says how it appears in service cards, the Services page,
 * "Related treatments" and the Services mega-menu: menu name, short summary, icon and category.
 * The page's featured image is its card image. One place to edit; every listing follows.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Service categories (Services page groups), in display order.
 *
 * @return array<string,array{name:string,blurb:string}>
 */
function rd_service_categories() {
	return array(
		'everyday' => array(
			'name'  => __( 'Everyday and family care', 'redlynch-dental' ),
			'blurb' => __( 'The routine care that keeps teeth healthy for life, for every age.', 'redlynch-dental' ),
		),
		'urgent'   => array(
			'name'  => __( 'Urgent care', 'redlynch-dental' ),
			'blurb' => __( 'Appointments are reserved every day for dental emergencies.', 'redlynch-dental' ),
		),
		'restore'  => array(
			'name'  => __( 'Restoring and replacing teeth', 'redlynch-dental' ),
			'blurb' => __( 'Repairing damaged teeth and replacing missing ones, so you can eat, speak and smile comfortably.', 'redlynch-dental' ),
		),
		'surgery'  => array(
			'name'  => __( 'Oral surgery', 'redlynch-dental' ),
			'blurb' => __( 'Gentle tooth removal, including wisdom teeth, with time to talk through your concerns.', 'redlynch-dental' ),
		),
		'cosmetic' => array(
			'name'  => __( 'Cosmetic and smile treatments', 'redlynch-dental' ),
			'blurb' => __( 'Brightening, straightening and reshaping, planned around your face and your goals.', 'redlynch-dental' ),
		),
	);
}

/**
 * Register the service meta (also readable in the REST API for the block editor).
 */
function rd_register_service_meta() {
	$keys = array(
		'_rd_service'         => 'boolean',
		'_rd_service_nav'     => 'string',
		'_rd_service_short'   => 'string',
		'_rd_service_icon'    => 'string',
		'_rd_service_cat'     => 'string',
		'_rd_service_contain' => 'boolean',
	);
	foreach ( $keys as $key => $type ) {
		register_post_meta(
			'page',
			$key,
			array(
				'type'          => $type,
				'single'        => true,
				'show_in_rest'  => false,
				'auth_callback' => function () {
					return current_user_can( 'edit_pages' );
				},
			)
		);
	}
}
add_action( 'init', 'rd_register_service_meta' );

/**
 * All service pages, in menu order (Page Attributes › Order), then title.
 *
 * @return array<int,array{id:int,url:string,name:string,nav:string,short:string,icon:string,cat:string,img:int,contain:bool}>
 */
function rd_get_services() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$q     = new WP_Query(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'meta_key'       => '_rd_service', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- a handful of pages.
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'  => true,
		)
	);
	$cache = array();
	foreach ( $q->posts as $p ) {
		$cache[ $p->ID ] = rd_service_data( $p->ID );
	}
	return $cache;
}

/**
 * Listing data for one service page.
 *
 * @param int $id Page ID.
 * @return array
 */
function rd_service_data( $id ) {
	$name = get_the_title( $id );
	$nav  = (string) get_post_meta( $id, '_rd_service_nav', true );
	return array(
		'id'      => (int) $id,
		'url'     => get_permalink( $id ),
		'name'    => $name,
		'nav'     => '' !== $nav ? $nav : $name,
		'short'   => (string) get_post_meta( $id, '_rd_service_short', true ),
		'icon'    => (string) get_post_meta( $id, '_rd_service_icon', true ),
		'cat'     => (string) get_post_meta( $id, '_rd_service_cat', true ),
		'img'     => (int) get_post_thumbnail_id( $id ),
		'contain' => (bool) get_post_meta( $id, '_rd_service_contain', true ),
	);
}

/**
 * Service pages for Elementor select controls.
 *
 * @return array<int,string>
 */
function rd_service_options() {
	$out = array();
	foreach ( rd_get_services() as $id => $s ) {
		$out[ $id ] = $s['name'];
	}
	return $out;
}

/**
 * Card image (or icon placeholder) for a service, as the static site prints it.
 *
 * @param array  $s     Service data.
 * @param string $size  Image size.
 * @param array  $attrs Extra attributes (style, width/height override).
 * @return string
 */
function rd_service_image( $s, $size = 'medium', $attrs = array() ) {
	if ( $s['img'] ) {
		$attrs = array_merge(
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
			),
			$attrs
		);
		if ( $s['contain'] ) {
			$attrs['style'] = 'object-fit:contain;background:#fff';
		}
		return wp_get_attachment_image( $s['img'], $size, false, $attrs );
	}
	return '<span class="ph">' . rd_icon( $s['icon'] ) . '</span>';
}

/**
 * Meta box.
 */
function rd_service_meta_box() {
	add_meta_box( 'rd-service', __( 'Service listing', 'redlynch-dental' ), 'rd_service_meta_box_html', 'page', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'rd_service_meta_box' );

/**
 * Meta box fields.
 *
 * @param WP_Post $post Page.
 */
function rd_service_meta_box_html( $post ) {
	wp_nonce_field( 'rd_service_save', 'rd_service_nonce' );
	$on      = (bool) get_post_meta( $post->ID, '_rd_service', true );
	$nav     = (string) get_post_meta( $post->ID, '_rd_service_nav', true );
	$short   = (string) get_post_meta( $post->ID, '_rd_service_short', true );
	$icon    = (string) get_post_meta( $post->ID, '_rd_service_icon', true );
	$cat     = (string) get_post_meta( $post->ID, '_rd_service_cat', true );
	$contain = (bool) get_post_meta( $post->ID, '_rd_service_contain', true );
	?>
	<p><label><input type="checkbox" name="rd_service" value="1" <?php checked( $on ); ?>> <?php esc_html_e( 'This page is a service (treatment)', 'redlynch-dental' ); ?></label></p>
	<p><label for="rd_service_nav"><?php esc_html_e( 'Short name (menus and cards)', 'redlynch-dental' ); ?></label><br>
		<input type="text" class="widefat" id="rd_service_nav" name="rd_service_nav" value="<?php echo esc_attr( $nav ); ?>"></p>
	<p><label for="rd_service_short"><?php esc_html_e( 'Summary (one or two sentences)', 'redlynch-dental' ); ?></label><br>
		<textarea class="widefat" rows="4" id="rd_service_short" name="rd_service_short"><?php echo esc_textarea( $short ); ?></textarea></p>
	<p><label for="rd_service_icon"><?php esc_html_e( 'Icon', 'redlynch-dental' ); ?></label><br>
		<select id="rd_service_icon" name="rd_service_icon" class="widefat">
			<?php foreach ( rd_icon_options() as $k => $label ) : ?>
				<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $icon, $k ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select></p>
	<p><label for="rd_service_cat"><?php esc_html_e( 'Category (Services page)', 'redlynch-dental' ); ?></label><br>
		<select id="rd_service_cat" name="rd_service_cat" class="widefat">
			<?php foreach ( rd_service_categories() as $k => $c ) : ?>
				<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $cat, $k ); ?>><?php echo esc_html( $c['name'] ); ?></option>
			<?php endforeach; ?>
		</select></p>
	<p><label><input type="checkbox" name="rd_service_contain" value="1" <?php checked( $contain ); ?>> <?php esc_html_e( 'Show the featured image whole on white (for diagrams)', 'redlynch-dental' ); ?></label></p>
	<p class="description"><?php esc_html_e( 'The featured image is the card image. List order follows Page Attributes › Order.', 'redlynch-dental' ); ?></p>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Page ID.
 */
function rd_service_save( $post_id ) {
	if ( ! isset( $_POST['rd_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rd_service_nonce'] ) ), 'rd_service_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$cats  = rd_service_categories();
	$icons = rd_icon_paths();
	update_post_meta( $post_id, '_rd_service', empty( $_POST['rd_service'] ) ? '' : '1' );
	update_post_meta( $post_id, '_rd_service_contain', empty( $_POST['rd_service_contain'] ) ? '' : '1' );
	update_post_meta( $post_id, '_rd_service_nav', sanitize_text_field( wp_unslash( $_POST['rd_service_nav'] ?? '' ) ) );
	update_post_meta( $post_id, '_rd_service_short', sanitize_textarea_field( wp_unslash( $_POST['rd_service_short'] ?? '' ) ) );
	$icon = sanitize_key( wp_unslash( $_POST['rd_service_icon'] ?? '' ) );
	update_post_meta( $post_id, '_rd_service_icon', isset( $icons[ $icon ] ) ? $icon : 'tooth' );
	$cat = sanitize_key( wp_unslash( $_POST['rd_service_cat'] ?? '' ) );
	update_post_meta( $post_id, '_rd_service_cat', isset( $cats[ $cat ] ) ? $cat : 'everyday' );
}
add_action( 'save_post_page', 'rd_service_save' );
