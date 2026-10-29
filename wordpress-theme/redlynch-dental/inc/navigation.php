<?php
/**
 * Menus: the header menu (dropdowns, the Services mega-menu and the mobile drawer) and the footer
 * menus, all managed in Appearance › Menus. The markup matches the static site's, so its CSS and
 * main.js (hover, click and keyboard dropdowns; the drawer with focus trap) work unchanged.
 *
 * Header menu rules:
 * - A top-level item with sub-items becomes a dropdown button (its own link is not used,
 *   except by the mega-menu's "View all" link).
 * - Add the CSS class "mega" to a top-level item (Screen Options › CSS Classes) to show its
 *   sub-items as the mega-menu with the emergency card. Its Description (Screen Options ›
 *   Description) is the mega-menu heading, e.g. "Our services".
 * - Sub-items that link to a service page show the service's icon; the urgent-care service is
 *   shown in the emergency colour. Any item can set an icon with a class such as "icon-tooth".
 * - Items set to open in a new tab get "↗" and a screen-reader note.
 *
 * @package Redlynch_Dental
 */

defined( 'ABSPATH' ) || exit;

/**
 * Header menu walker.
 */
class RD_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Current top-level item (for the sub-menu wrapper).
	 *
	 * @var WP_Post|null
	 */
	private $top = null;

	/**
	 * Is an item a mega-menu parent?
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	private function is_mega( $item ) {
		return in_array( 'mega', (array) $item->classes, true );
	}

	/**
	 * Sub-menu id for a top-level item.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function sub_id( $item ) {
		return 'dd-' . sanitize_title( $item->title ) . '-' . (int) $item->ID;
	}

	/**
	 * Is the item the current page or an ancestor of it?
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	private function in_section( $item ) {
		return (bool) array_intersect( array( 'current-menu-item', 'current-menu-ancestor', 'current-menu-parent', 'current_page_parent', 'current_page_ancestor', 'rd-current-section' ), (array) $item->classes );
	}

	/**
	 * Start a sub-menu.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth || ! $this->top ) {
			$output .= '<ul class="dd">';
			return;
		}
		$top = $this->top;
		if ( $this->is_mega( $top ) ) {
			$head    = '' !== trim( (string) $top->description ) ? $top->description : __( 'Our services', 'redlynch-dental' );
			$current = $this->is_current( $top ) ? ' aria-current="page"' : '';
			$output .= '<div class="mega" id="' . esc_attr( $this->sub_id( $top ) ) . '"><div><div class="mega-head"><strong>' . esc_html( $head ) . '</strong><a href="' . esc_url( $top->url ) . '"' . $current . '>' . esc_html__( 'View all services →', 'redlynch-dental' ) . '</a></div><ul class="mega-cols"><li class="mobile-only"><a href="' . esc_url( $top->url ) . '"' . $current . '>' . rd_icon( 'search' ) . '<span>' . esc_html__( 'All services', 'redlynch-dental' ) . '</span></a></li>';
		} else {
			$output .= '<ul class="dd" id="' . esc_attr( $this->sub_id( $top ) ) . '">';
		}
	}

	/**
	 * End a sub-menu.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth && $this->top && $this->is_mega( $this->top ) ) {
			$output .= '</ul></div><div class="mega-feature"><span class="em-tag" style="background:rgba(0,0,0,.18);margin:0;align-self:flex-start">' . rd_icon( 'alert' ) . ' ' . esc_html__( 'Emergency', 'redlynch-dental' ) . '</span><strong>' . esc_html( rd_practice( 'mega_title' ) ) . '</strong><p>' . esc_html( rd_practice( 'mega_text' ) ) . '</p><a class="btn white sm" href="' . esc_url( rd_tel_href() ) . '">' . rd_icon( 'phone' ) . ' ' . esc_html( rd_practice( 'phone' ) ) . '</a><a href="' . esc_url( rd_page_url( 'emergency-dental-care' ) ) . '" style="color:#fff;font-weight:700;font-size:.9rem">' . esc_html__( 'Emergency dental care →', 'redlynch-dental' ) . '</a></div></div>';
			return;
		}
		$output .= '</ul>';
	}

	/**
	 * Is the item the current page?
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	private function is_current( $item ) {
		return in_array( 'current-menu-item', (array) $item->classes, true );
	}

	/**
	 * Start an item.
	 *
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
		if ( 0 === $depth ) {
			$this->top = $item;
			$cls       = array();
			if ( $this->has_children ) {
				$cls[] = 'has-dd';
				if ( $this->is_mega( $item ) ) {
					$cls[] = 'has-mega';
				}
			}
			if ( $this->in_section( $item ) ) {
				$cls[] = 'is-active';
			}
			$output .= '<li' . ( $cls ? ' class="' . esc_attr( implode( ' ', $cls ) ) . '"' : '' ) . '>';
			if ( $this->has_children ) {
				// The mega menu's button carries position:relative, as in the static site's header.
				$style   = $this->is_mega( $item ) ? ' style="position:relative"' : '';
				$output .= '<button class="dd-btn" type="button" aria-expanded="false" aria-controls="' . esc_attr( $this->sub_id( $item ) ) . '"' . $style . '>' . esc_html( $title ) . rd_icon( 'chev' ) . '</button>';
			} else {
				$output .= $this->link( $item, esc_html( $title ) );
			}
			return;
		}
		$inner = esc_html( $title );
		$extra = '';
		if ( $this->top && $this->is_mega( $this->top ) ) {
			$icon = $this->item_icon( $item );
			if ( 'page' === $item->object && 'urgent' === get_post_meta( (int) $item->object_id, '_rd_service_cat', true ) ) {
				$extra = ' class="is-em"';
			}
			$inner = rd_icon( $icon ) . '<span>' . $inner . '</span>';
		}
		$output .= '<li>' . $this->link( $item, $inner, $extra );
	}

	/**
	 * End an item.
	 *
	 * @param string   $output Output.
	 * @param WP_Post  $item   Item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}

	/**
	 * Icon for a mega-menu item: an "icon-NAME" class, else the linked service's icon.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function item_icon( $item ) {
		foreach ( (array) $item->classes as $c ) {
			if ( 0 === strpos( (string) $c, 'icon-' ) ) {
				return substr( $c, 5 );
			}
		}
		if ( 'page' === $item->object ) {
			$icon = (string) get_post_meta( (int) $item->object_id, '_rd_service_icon', true );
			if ( '' !== $icon ) {
				return $icon;
			}
		}
		return 'tooth';
	}

	/**
	 * Link markup for an item.
	 *
	 * @param WP_Post $item  Item.
	 * @param string  $inner Escaped inner HTML.
	 * @param string  $extra Extra attributes.
	 * @return string
	 */
	private function link( $item, $inner, $extra = '' ) {
		return rd_menu_link( $item, $inner, $extra );
	}
}

/**
 * A menu link: aria-current on the current page; "↗" and a screen-reader note for new-tab links.
 *
 * @param WP_Post $item  Menu item.
 * @param string  $inner Escaped inner HTML.
 * @param string  $extra Extra attributes (already escaped).
 * @return string
 */
function rd_menu_link( $item, $inner, $extra = '' ) {
	$blank = '_blank' === $item->target;
	$attrs = ' href="' . esc_url( $item->url ) . '"';
	if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
		$attrs .= ' aria-current="page"';
	}
	if ( $blank ) {
		$attrs .= ' target="_blank" rel="noopener"';
		$inner .= ' ↗<span class="sr-only">' . esc_html__( ' (opens in a new tab)', 'redlynch-dental' ) . '</span>';
	}
	return '<a' . $attrs . $extra . '>' . $inner . '</a>';
}

/**
 * Footer menu walker: a flat list of links.
 */
class RD_Footer_Walker extends Walker_Nav_Menu {

	/**
	 * Start an item.
	 *
	 * @param string   $output Output.
	 * @param WP_Post  $item   Item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title   = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
		$output .= '<li>' . rd_menu_link( $item, esc_html( $title ) );
	}

	/**
	 * Sub-levels are flattened.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Sub-levels are flattened.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/**
 * Mark the Blog menu item as the current section on blog posts.
 *
 * @param string[] $classes Classes.
 * @param WP_Post  $item    Item.
 * @return string[]
 */
function rd_menu_section_class( $classes, $item ) {
	if ( is_singular( 'post' ) && 'page' === $item->object && (int) $item->object_id === (int) rd_blog_page_id() ) {
		$classes[] = 'rd-current-section';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'rd_menu_section_class', 10, 2 );

/**
 * The Blog page: Settings › Reading "Posts page" if set, else the page with the slug "blog".
 *
 * @return int
 */
function rd_blog_page_id() {
	$id = (int) get_option( 'page_for_posts' );
	if ( ! $id ) {
		$page = get_page_by_path( 'blog' );
		$id   = $page ? (int) $page->ID : 0;
	}
	return $id;
}

/**
 * The header menu.
 */
function rd_primary_menu() {
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '<ul>%3$s</ul>',
			'walker'         => new RD_Nav_Walker(),
			'depth'          => 2,
			'fallback_cb'    => 'rd_menu_fallback',
		)
	);
}

/**
 * Header menu before one is assigned: Home plus the imported pages that exist.
 */
function rd_menu_fallback() {
	$links = array( array( home_url( '/' ), __( 'Home', 'redlynch-dental' ) ) );
	foreach ( array( 'about', 'services', 'faq', 'blog', 'contact' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$links[] = array( get_permalink( $page ), get_the_title( $page ) );
		}
	}
	echo '<ul>';
	foreach ( $links as $l ) {
		echo '<li><a href="' . esc_url( $l[0] ) . '">' . esc_html( $l[1] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * A footer menu.
 *
 * @param string $location Menu location.
 */
function rd_footer_menu( $location ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '<ul>%3$s</ul>',
			'walker'         => new RD_Footer_Walker(),
			'depth'          => 1,
		)
	);
}

/**
 * Footer menu heading: the menu's name in Appearance › Menus (e.g. "Services", "Practice").
 *
 * @param string $location Menu location.
 * @param string $fallback Fallback heading.
 * @return string
 */
function rd_menu_name( $location, $fallback ) {
	$locations = get_nav_menu_locations();
	$menu      = isset( $locations[ $location ] ) ? wp_get_nav_menu_object( $locations[ $location ] ) : null;
	return $menu ? $menu->name : $fallback;
}
