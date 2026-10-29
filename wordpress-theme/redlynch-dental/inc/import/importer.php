<?php
/**
 * Appearance › Redlynch setup: imports the website from inc/import/content.json (generated from the
 * static site by wordpress-theme/tools/build-content.js): 28 pages as Elementor layouts, the blog
 * article, the team profiles, the images (into the Media Library, with their alternative text),
 * the three menus, the logo, the homepage setting and, if ThinkRank is active, each page's SEO
 * title and description. Also available as a WP-CLI command: wp redlynch import.
 *
 * Safe to run more than once: pages, posts, team members and images are matched by slug or source
 * file and reused; existing pages are left alone unless "replace" is ticked; nothing is deleted.
 * Team members who have left the practice (listed in the content file) are moved to Drafts.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin page.
 */
function rd_setup_menu() {
	add_theme_page( __( 'Redlynch setup', 'redlynch-dental' ), __( 'Redlynch setup', 'redlynch-dental' ), 'manage_options', 'rd-setup', 'rd_setup_page' );
}
add_action( 'admin_menu', 'rd_setup_menu' );

/**
 * After activating the theme, point to the setup page.
 */
function rd_setup_activation_notice() {
	global $pagenow;
	if ( 'themes.php' !== $pagenow || ! isset( $_GET['activated'] ) || ! current_user_can( 'manage_options' ) || get_option( 'rd_imported' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
		return;
	}
	printf(
		'<div class="notice notice-success"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'Redlynch Dental theme activated.', 'redlynch-dental' ),
		esc_url( admin_url( 'themes.php?page=rd-setup' ) ),
		esc_html__( 'Import the website’s pages, menus, team and images', 'redlynch-dental' )
	);
}
add_action( 'admin_notices', 'rd_setup_activation_notice' );

/**
 * Setup page.
 */
function rd_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'redlynch-dental' ) );
	}
	$log = array();
	if ( isset( $_POST['rd_import'] ) ) {
		check_admin_referer( 'rd_import' );
		$log = rd_run_import(
			array(
				'replace'    => ! empty( $_POST['rd_replace'] ),
				'menus'      => ! empty( $_POST['rd_menus'] ),
				'front'      => ! empty( $_POST['rd_front'] ),
				'permalinks' => ! empty( $_POST['rd_permalinks'] ),
				'elementor'  => ! empty( $_POST['rd_elementor'] ),
			)
		);
	}
	$elementor = did_action( 'elementor/loaded' );
	$thinkrank = defined( 'THINKRANK_VERSION' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Redlynch Dental setup', 'redlynch-dental' ); ?></h1>
		<?php if ( $log ) : ?>
			<div class="notice notice-info"><ul style="list-style:disc;padding-left:1.5em">
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul></div>
		<?php endif; ?>
		<h2><?php esc_html_e( 'Plugins', 'redlynch-dental' ); ?></h2>
		<ul>
			<li><?php echo $elementor ? '&#10003; ' : '&#10007; '; ?><strong>Elementor</strong> — <?php $elementor ? esc_html_e( 'active. Pages are edited with “Edit with Elementor”.', 'redlynch-dental' ) : esc_html_e( 'not active. Install and activate Elementor (free) to display and edit the imported pages. You can import first; the pages appear once Elementor is active.', 'redlynch-dental' ); ?></li>
			<li><?php echo $thinkrank ? '&#10003; ' : '&#10007; '; ?><strong>ThinkRank</strong> — <?php $thinkrank ? esc_html_e( 'active. Each page’s SEO title and description from the static site are copied into its ThinkRank fields (only where empty), and the privacy page is set to noindex.', 'redlynch-dental' ) : esc_html_e( 'not active. Activate it before importing so each page’s SEO title and description are filled in.', 'redlynch-dental' ); ?></li>
		</ul>
		<h2><?php esc_html_e( 'Import the website', 'redlynch-dental' ); ?></h2>
		<p><?php esc_html_e( 'Creates the homepage, About, Meet Our Team, New Patients, Services, the 15 treatment pages, FAQs, Blog, Contact, the New Patient Offer, Payments and Fees, the privacy policy and two campaign landing pages as Elementor pages; the blog article; the six team profiles (profiles of people who have left are moved to Drafts); the images in the Media Library; the main and footer menus; and the logo.', 'redlynch-dental' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'rd_import' ); ?>
			<p><label><input type="checkbox" name="rd_menus" value="1" checked> <?php esc_html_e( 'Create the main and footer menus and assign them', 'redlynch-dental' ); ?></label></p>
			<p><label><input type="checkbox" name="rd_front" value="1" checked> <?php esc_html_e( 'Use the imported Home page as the homepage (Settings › Reading)', 'redlynch-dental' ); ?></label></p>
			<p><label><input type="checkbox" name="rd_permalinks" value="1" checked> <?php esc_html_e( 'Use page-name addresses, e.g. /dental-implants/ and /blog/why-choose-dental-implants/ (only if permalinks are still “Plain”)', 'redlynch-dental' ); ?></label></p>
			<p><label><input type="checkbox" name="rd_elementor" value="1" checked> <?php esc_html_e( 'Stop Elementor adding its default colours and fonts (the theme supplies the brand styles)', 'redlynch-dental' ); ?></label></p>
			<p><label><input type="checkbox" name="rd_replace" value="1"> <?php esc_html_e( 'Replace the content of pages that already exist with these addresses (normally left alone)', 'redlynch-dental' ); ?></label></p>
			<?php submit_button( __( 'Import website content', 'redlynch-dental' ), 'primary', 'rd_import', false ); ?>
		</form>
	</div>
	<?php
}

/**
 * Run the import.
 *
 * @param array $opts Options: replace, menus, front, permalinks, elementor.
 * @return string[] Log.
 */
function rd_run_import( $opts ) {
	$opts = wp_parse_args(
		$opts,
		array(
			'replace'    => false,
			'menus'      => true,
			'front'      => true,
			'permalinks' => true,
			'elementor'  => true,
		)
	);
	$file = RD_THEME_DIR . '/inc/import/content.json';
	$site = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
	if ( ! $site ) {
		return array( __( 'The content file is missing or unreadable.', 'redlynch-dental' ) );
	}
	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- one-off import of ~30 images.
	}
	$imp          = new RD_Importer( $site['media'] ?? array() );
	$imp->replace = (bool) $opts['replace'];
	$log          = array();

	if ( $opts['permalinks'] && '' === get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/blog/%postname%/' );
		$log[] = __( 'Permalinks set: pages at /page-name/, blog posts at /blog/post-name/.', 'redlynch-dental' );
	}

	// 1. Pages first (empty), so every link and service picker can be resolved.
	$created = 0;
	foreach ( $site['pages'] as $page ) {
		$res = $imp->ensure_page( $page['slug'], $page['title'], (int) $page['menu_order'] );
		$imp->page_ids[ $page['slug'] ] = $res['id'];
		$imp->fresh[ $page['slug'] ]    = $res['new'];
		$created                       += $res['new'] ? 1 : 0;
	}
	foreach ( $site['posts'] as $post ) {
		$imp->post_ids[ $post['slug'] ] = $imp->ensure_post( $post );
	}
	/* translators: 1: pages created, 2: pages in total. */
	$log[] = sprintf( __( 'Pages: %1$d created, %2$d already existed.', 'redlynch-dental' ), $created, count( $site['pages'] ) - $created );

	// 2. Page content, service details and SEO.
	$filled  = 0;
	$skipped = array();
	foreach ( $site['pages'] as $page ) {
		$id = $imp->page_ids[ $page['slug'] ];
		if ( ! $id ) {
			continue;
		}
		if ( $imp->fresh[ $page['slug'] ] || $opts['replace'] || get_post_meta( $id, '_rd_imported', true ) ) {
			$imp->fill_page( $id, $page );
			++$filled;
		} else {
			$skipped[] = $page['slug'];
		}
		$imp->seo( $id, $page['seo'] );
	}
	/* translators: %d: number of pages. */
	$log[] = sprintf( __( 'Page layouts written: %d.', 'redlynch-dental' ), $filled );
	if ( $skipped ) {
		/* translators: %s: page addresses. */
		$log[] = sprintf( __( 'Left unchanged (they existed before the import; tick “Replace” to overwrite): %s', 'redlynch-dental' ), implode( ', ', $skipped ) );
	}

	// 3. Team: current members, then former members out of view (Drafts, not deleted).
	foreach ( $site['team'] as $m ) {
		$imp->ensure_team_member( $m );
	}
	/* translators: %d: number of profiles. */
	$log[]   = sprintf( __( 'Team profiles: %d.', 'redlynch-dental' ), count( $site['team'] ) );
	$retired = $imp->retire_team_members( $site['retired_team'] ?? array() );
	if ( $retired ) {
		/* translators: %s: names. */
		$log[] = sprintf( __( 'Moved to Drafts (no longer at the practice): %s.', 'redlynch-dental' ), implode( ', ', $retired ) );
	}

	// 4. Menus.
	if ( $opts['menus'] ) {
		foreach ( $site['menus'] as $location => $menu ) {
			$imp->ensure_menu( $location, $menu );
		}
		$log[] = __( 'Menus created and assigned: Main menu, Footer: Services, Footer: Practice.', 'redlynch-dental' );
	}

	// 5. Homepage, privacy page, logo.
	if ( $opts['front'] && ! empty( $imp->page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $imp->page_ids['home'] );
		$log[] = __( 'Homepage set to the imported Home page.', 'redlynch-dental' );
	}
	if ( ! empty( $imp->page_ids['privacy-policy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', $imp->page_ids['privacy-policy'] );
	}
	if ( ! get_theme_mod( 'custom_logo' ) ) {
		$logo = $imp->media( 'branding/redlynch-dental-logo-light.png' );
		if ( $logo ) {
			update_post_meta( $logo, '_wp_attachment_image_alt', __( 'Redlynch Dental', 'redlynch-dental' ) );
			set_theme_mod( 'custom_logo', $logo );
			$log[] = __( 'Logo set (Appearance › Customize › Site Identity).', 'redlynch-dental' );
		}
	}

	// 6. Elementor: no default colours/fonts, no Google Fonts; rebuild its CSS.
	if ( $opts['elementor'] ) {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		update_option( 'elementor_google_font', '0' );
		$log[] = __( 'Elementor default colours, fonts and Google Fonts turned off.', 'redlynch-dental' );
	}
	if ( did_action( 'elementor/loaded' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	flush_rewrite_rules( false );
	update_option( 'rd_imported', RD_THEME_VERSION );
	/* translators: %d: number of images. */
	$log[] = sprintf( __( 'Images in the Media Library: %d.', 'redlynch-dental' ), count( $imp->media_ids ) );
	if ( ! did_action( 'elementor/loaded' ) ) {
		$log[] = __( 'Elementor is not active: the pages are saved and will display once Elementor is activated.', 'redlynch-dental' );
	}
	return $log;
}

/**
 * Import helpers.
 */
class RD_Importer {

	/**
	 * Page IDs by slug.
	 *
	 * @var array<string,int>
	 */
	public $page_ids = array();

	/**
	 * Whether each page was created by this run.
	 *
	 * @var array<string,bool>
	 */
	public $fresh = array();

	/**
	 * Post IDs by slug.
	 *
	 * @var array<string,int>
	 */
	public $post_ids = array();

	/**
	 * Whether existing imported content is refreshed ("replace" ticked).
	 *
	 * @var bool
	 */
	public $replace = false;

	/**
	 * Attachment IDs by source path.
	 *
	 * @var array<string,int>
	 */
	public $media_ids = array();

	/**
	 * Alternative text by source path.
	 *
	 * @var array<string,string>
	 */
	private $alts;

	/**
	 * Constructor.
	 *
	 * @param array $alts Alternative text by path.
	 */
	public function __construct( $alts ) {
		$this->alts = (array) $alts;
	}

	/**
	 * Find or create a page.
	 *
	 * @param string $slug  Slug.
	 * @param string $title Title.
	 * @param int    $order Menu order.
	 * @return array{id:int,new:bool}
	 */
	public function ensure_page( $slug, $title, $order ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page ) {
			// An unpublished page nobody has written yet (e.g. WordPress's draft Privacy Policy) is filled like a new one.
			return array(
				'id'  => (int) $page->ID,
				'new' => 'publish' !== $page->post_status && ! get_post_meta( $page->ID, '_rd_imported', true ),
			);
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'menu_order'   => $order,
				'post_content' => '',
			),
			true
		);
		return array(
			'id'  => is_wp_error( $id ) ? 0 : (int) $id,
			'new' => ! is_wp_error( $id ),
		);
	}

	/**
	 * Write a page's layout and details.
	 *
	 * @param int   $id   Page ID.
	 * @param array $page Page data.
	 */
	public function fill_page( $id, $page ) {
		$data = $this->resolve( $page['elementor'] );
		wp_update_post(
			array(
				'ID'           => $id,
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_excerpt' => $page['excerpt'],
				'menu_order'   => (int) $page['menu_order'],
				'post_content' => '',
			)
		);
		// Campaign landing pages use the landing template (body.lp and landing.css); others the default.
		$template = ( $page['template'] ?? '' ) === 'page-templates/landing.php' ? 'page-templates/landing.php' : 'default';
		update_post_meta( $id, '_wp_page_template', $template );
		update_post_meta( $id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
		update_post_meta( $id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		delete_post_meta( $id, '_elementor_css' );
		update_post_meta( $id, '_rd_imported', RD_THEME_VERSION );

		if ( ! empty( $page['service'] ) ) {
			$s = $page['service'];
			update_post_meta( $id, '_rd_service', '1' );
			update_post_meta( $id, '_rd_service_nav', $s['nav'] );
			update_post_meta( $id, '_rd_service_short', $s['short'] );
			update_post_meta( $id, '_rd_service_icon', $s['icon'] );
			update_post_meta( $id, '_rd_service_cat', $s['cat'] );
			update_post_meta( $id, '_rd_service_contain', $s['contain'] ? '1' : '' );
			if ( $s['img'] ) {
				$img = $this->media( $s['img'] );
				if ( $img ) {
					set_post_thumbnail( $id, $img );
				}
			}
		}
	}

	/**
	 * SEO title, description and robots into ThinkRank's fields (only where empty).
	 *
	 * @param int   $id  Post ID.
	 * @param array $seo Title, description, noindex.
	 */
	public function seo( $id, $seo ) {
		// The values written last time, so an untouched title or description can be updated while
		// one edited in ThinkRank is kept.
		$prev = json_decode( (string) get_post_meta( $id, '_rd_seo_imported', true ), true );
		$prev = is_array( $prev ) ? $prev : array();
		foreach ( array(
			'title'       => '_thinkrank_seo_title',
			'description' => '_thinkrank_meta_description',
		) as $field => $key ) {
			if ( empty( $seo[ $field ] ) ) {
				continue;
			}
			$current = (string) get_post_meta( $id, $key, true );
			if ( '' === $current || ( isset( $prev[ $field ] ) && $prev[ $field ] === $current ) ) {
				update_post_meta( $id, $key, wp_slash( $seo[ $field ] ) );
				$prev[ $field ] = $seo[ $field ];
			}
		}
		update_post_meta( $id, '_rd_seo_imported', wp_slash( wp_json_encode( $prev ) ) );
		if ( ! empty( $seo['noindex'] ) && '' === (string) get_post_meta( $id, '_thinkrank_robots_meta', true ) ) {
			update_post_meta( $id, '_thinkrank_robots_meta_enabled', 1 );
			update_post_meta(
				$id,
				'_thinkrank_robots_meta',
				wp_slash(
					wp_json_encode(
						array(
							'index'    => false,
							'noindex'  => true,
							'nofollow' => true,
						)
					)
				)
			);
		}
	}

	/**
	 * Find or create the blog post.
	 *
	 * @param array $post Post data.
	 * @return int
	 */
	public function ensure_post( $post ) {
		$existing = get_page_by_path( $post['slug'], OBJECT, 'post' );
		if ( $existing ) {
			$id = (int) $existing->ID;
			if ( $this->replace && get_post_meta( $id, '_rd_imported', true ) ) {
				update_post_meta( $id, '_rd_byline', $post['byline'] );
			}
		} else {
			$cat = term_exists( $post['category'], 'category' );
			if ( ! $cat ) {
				$cat = wp_insert_term( $post['category'], 'category' );
			}
			$cat_id = is_array( $cat ) ? (int) $cat['term_id'] : 0;
			$id     = wp_insert_post(
				array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'post_title'     => $post['title'],
					'post_name'      => $post['slug'],
					'post_date'      => $post['date'],
					'post_excerpt'   => $post['excerpt'],
					'post_content'   => '',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'post_category'  => $cat_id ? array( $cat_id ) : array(),
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				return 0;
			}
			$this->post_ids[ $post['slug'] ] = $id;
			wp_update_post(
				array(
					'ID'           => $id,
					'post_content' => $this->resolve( $post['content'] ),
				)
			);
			update_post_meta( $id, '_rd_byline', $post['byline'] );
			update_post_meta( $id, '_rd_imported', RD_THEME_VERSION );
		}
		$img = $this->media( $post['featured'] );
		if ( $img && ! has_post_thumbnail( $id ) ) {
			set_post_thumbnail( $id, $img );
		}
		$this->seo( $id, $post['seo'] );
		return $id;
	}

	/**
	 * Find or create a team member.
	 *
	 * @param array $m Member data.
	 */
	public function ensure_team_member( $m ) {
		$found = get_posts(
			array(
				'post_type'   => 'rd_team',
				'post_status' => 'any',
				'numberposts' => 1,
				'meta_key'    => '_rd_anchor', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-off import.
				'meta_value'  => $m['anchor'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			// "Replace" refreshes the imported details; the photo is only added if there is none.
			if ( $this->replace ) {
				$this->fill_team_member( (int) $found[0], $m );
			}
			return;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'rd_team',
				'post_status'  => 'publish',
				'post_title'   => $m['name'],
				'post_content' => implode( "\n\n", $m['bio'] ),
				'menu_order'   => (int) $m['order'],
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			return;
		}
		$this->fill_team_member( $id, $m );
	}

	/**
	 * Write a team member's details and photo.
	 *
	 * @param int   $id Team member.
	 * @param array $m  Member data.
	 */
	private function fill_team_member( $id, $m ) {
		wp_update_post(
			array(
				'ID'           => $id,
				'post_status'  => 'publish',
				'post_title'   => $m['name'],
				'post_content' => implode( "\n\n", $m['bio'] ),
				'menu_order'   => (int) $m['order'],
			)
		);
		update_post_meta( $id, '_rd_group', $m['group'] );
		update_post_meta( $id, '_rd_role', $m['role'] );
		update_post_meta( $id, '_rd_quals', $m['quals'] );
		update_post_meta( $id, '_rd_quals_long', $m['quals_long'] );
		update_post_meta( $id, '_rd_focus_heading', $m['focus_heading'] );
		update_post_meta( $id, '_rd_focus', implode( "\n", $m['focus'] ) );
		update_post_meta( $id, '_rd_anchor', $m['anchor'] );
		$img = $this->media( $m['img'] );
		if ( $img && ! has_post_thumbnail( $id ) ) {
			set_post_thumbnail( $id, $img );
		}
	}

	/**
	 * Move the profiles of people who have left the practice to Drafts (they are not deleted).
	 *
	 * @param string[] $anchors Their link anchors.
	 * @return string[] Names moved.
	 */
	public function retire_team_members( $anchors ) {
		$moved = array();
		foreach ( (array) $anchors as $anchor ) {
			$ids = get_posts(
				array(
					'post_type'   => 'rd_team',
					'post_status' => 'publish',
					'numberposts' => -1,
					'meta_key'    => '_rd_anchor', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-off import.
					'meta_value'  => $anchor, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					'fields'      => 'ids',
				)
			);
			foreach ( $ids as $id ) {
				wp_update_post(
					array(
						'ID'          => $id,
						'post_status' => 'draft',
					)
				);
				$moved[] = get_the_title( $id );
			}
		}
		return $moved;
	}

	/**
	 * Create (or rebuild) a menu and assign it to its location.
	 *
	 * @param string $location Theme location.
	 * @param array  $menu     Name and items.
	 */
	public function ensure_menu( $location, $menu ) {
		$obj = wp_get_nav_menu_object( $menu['name'] );
		if ( $obj ) {
			foreach ( (array) wp_get_nav_menu_items( $obj->term_id, array( 'post_status' => 'any' ) ) as $item ) {
				wp_delete_post( $item->ID, true );
			}
			$menu_id = (int) $obj->term_id;
		} else {
			$menu_id = (int) wp_create_nav_menu( $menu['name'] );
		}
		if ( ! $menu_id ) {
			return;
		}
		$this->menu_items( $menu_id, $menu['items'], 0 );
		$locations              = get_theme_mod( 'nav_menu_locations', array() );
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Add menu items (recursive).
	 *
	 * @param int   $menu_id Menu.
	 * @param array $items   Items.
	 * @param int   $parent  Parent item ID.
	 */
	private function menu_items( $menu_id, $items, $parent ) {
		foreach ( $items as $pos => $it ) {
			$args = array(
				'menu-item-title'       => $it['label'],
				'menu-item-status'      => 'publish',
				'menu-item-parent-id'   => $parent,
				'menu-item-position'    => $pos + 1,
				'menu-item-classes'     => $it['classes'] ?? '',
				'menu-item-description' => $it['description'] ?? '',
				'menu-item-target'      => $it['target'] ?? '',
			);
			if ( ! empty( $it['page'] ) && ! empty( $this->page_ids[ $it['page'] ] ) ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $this->page_ids[ $it['page'] ];
			} else {
				$args['menu-item-type'] = 'custom';
				$args['menu-item-url']  = 'rd-booking' === ( $it['url'] ?? '' ) ? rd_practice( 'booking_url' ) : ( $it['url'] ?? '#' );
			}
			$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( ! is_wp_error( $item_id ) && ! empty( $it['children'] ) ) {
				$this->menu_items( $menu_id, $it['children'], (int) $item_id );
			}
		}
	}

	/**
	 * Import a bundled image into the Media Library (once) and return its attachment ID.
	 *
	 * @param string $rel Path under assets/images/.
	 * @return int
	 */
	public function media( $rel ) {
		$rel = ltrim( (string) $rel, '/' );
		if ( '' === $rel ) {
			return 0;
		}
		if ( isset( $this->media_ids[ $rel ] ) ) {
			return $this->media_ids[ $rel ];
		}
		$found = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'numberposts' => 1,
				'meta_key'    => '_rd_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-off import.
				'meta_value'  => $rel, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			$this->media_ids[ $rel ] = (int) $found[0];
			return (int) $found[0];
		}
		$src = RD_THEME_DIR . '/assets/images/' . $rel;
		if ( ! is_readable( $src ) || false !== strpos( $rel, '..' ) ) {
			return 0;
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = wp_tempnam( basename( $rel ) );
		if ( ! $tmp || ! copy( $src, $tmp ) ) {
			return 0;
		}
		$alt = $this->alts[ $rel ] ?? '';
		$id  = media_handle_sideload(
			array(
				'name'     => basename( $rel ),
				'tmp_name' => $tmp,
			),
			0
		);
		if ( is_wp_error( $id ) ) {
			wp_delete_file( $tmp );
			return 0;
		}
		update_post_meta( $id, '_rd_source', $rel );
		if ( '' !== $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		}
		$this->media_ids[ $rel ] = (int) $id;
		return (int) $id;
	}

	/**
	 * Replace the content placeholders (pages, posts, images) in a value, recursively.
	 *
	 * @param mixed $value Value.
	 * @return mixed
	 */
	public function resolve( $value ) {
		if ( is_array( $value ) ) {
			if ( isset( $value['rd_media'] ) && 1 === count( $value ) ) {
				$id = $this->media( $value['rd_media'] );
				return array(
					'url'    => $id ? (string) wp_get_attachment_url( $id ) : '',
					'id'     => $id,
					'alt'    => '',
					'source' => 'library',
				);
			}
			foreach ( $value as $k => $v ) {
				$value[ $k ] = $this->resolve( $v );
			}
			return $value;
		}
		if ( ! is_string( $value ) || false === strpos( $value, 'rd-' ) ) {
			return $value;
		}
		if ( preg_match( '/^rd-id:([a-z0-9-]+)$/', $value, $m ) ) {
			return isset( $this->page_ids[ $m[1] ] ) ? (string) $this->page_ids[ $m[1] ] : '';
		}
		if ( preg_match( '/^rd-post-id:([a-z0-9-]+)$/', $value, $m ) ) {
			return isset( $this->post_ids[ $m[1] ] ) ? (string) $this->post_ids[ $m[1] ] : '';
		}
		$value = str_replace( 'rd-home', home_url( '/' ), $value );
		$value = preg_replace_callback(
			'/rd-page:([a-z0-9-]+)/',
			function ( $m ) {
				return isset( $this->page_ids[ $m[1] ] ) ? get_permalink( $this->page_ids[ $m[1] ] ) : home_url( '/' . $m[1] . '/' );
			},
			$value
		);
		$value = preg_replace_callback(
			'/rd-post:([a-z0-9-]+)/',
			function ( $m ) {
				return isset( $this->post_ids[ $m[1] ] ) ? get_permalink( $this->post_ids[ $m[1] ] ) : home_url( '/' );
			},
			$value
		);
		return preg_replace_callback(
			'/rd-media:([a-z0-9\/._-]+)/i',
			function ( $m ) {
				$id = $this->media( $m[1] );
				return $id ? (string) wp_get_attachment_url( $id ) : RD_THEME_URI . '/assets/images/' . $m[1];
			},
			$value
		);
	}
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * WP-CLI: wp redlynch import [--replace] [--no-menus] [--no-front] [--no-permalinks] [--no-elementor]
	 *
	 * @param array $args       Positional.
	 * @param array $assoc_args Flags.
	 */
	function rd_cli_import( $args, $assoc_args ) {
		$log = rd_run_import(
			array(
				'replace'    => ! empty( $assoc_args['replace'] ),
				'menus'      => \WP_CLI\Utils\get_flag_value( $assoc_args, 'menus', true ),
				'front'      => \WP_CLI\Utils\get_flag_value( $assoc_args, 'front', true ),
				'permalinks' => \WP_CLI\Utils\get_flag_value( $assoc_args, 'permalinks', true ),
				'elementor'  => \WP_CLI\Utils\get_flag_value( $assoc_args, 'elementor', true ),
			)
		);
		foreach ( $log as $line ) {
			WP_CLI::log( $line );
		}
		WP_CLI::success( 'Redlynch Dental content imported.' );
	}
	WP_CLI::add_command( 'redlynch import', 'rd_cli_import' );
}
