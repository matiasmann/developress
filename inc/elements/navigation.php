<?php
/**
 * Navigation element
 * Builds the navigation section.
 *
 * @package DeveloPress
 * 
 * @since 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_navigation_position' ) ) {
	/**
	 * Build the navigation.
	 *
	 * @since 0.1
	 */
	function develope_navigation_position() {
		?>
		<nav id="site-navigation" <?php develope_do_element_classes( 'navigation' ); ?> <?php develope_do_microdata( 'navigation' ); ?>>
			<div <?php develope_do_element_classes( 'inside_navigation' ); ?>>
				<?php
				/**
				 * develope_inside_navigation hook.
				 *
				 * @since 0.1
				 *
				 * @hooked develope_navigation_search - 10
				 * @hooked develope_mobile_menu_search_icon - 10
				 */
				do_action( 'develope_inside_navigation' );
				?>
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php
					/**
					 * develope_inside_mobile_menu hook.
					 *
					 * @since 0.1
					 */
					do_action( 'develope_inside_mobile_menu' );

					develope_do_svg_icon( 'menu-bars', true );

					$mobile_menu_label = apply_filters( 'develope_mobile_menu_label', __( 'Menu', 'developress' ) );

					if ( $mobile_menu_label ) {
						printf(
							'<span class="mobile-menu">%s</span>',
							$mobile_menu_label
						);
					} else {
						printf(
							'<span class="screen-reader-text">%s</span>',
							__( 'Menu', 'developress' )
						);
					}
					?>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container' => 'div',
						'container_class' => 'main-nav',
						'container_id' => 'primary-menu',
						'menu_class' => '',
						'fallback_cb' => 'develope_menu_fallback',
						'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', develope_get_element_classes( 'menu' ) ) . '">%3$s</ul>',
					)
				);

				/**
				 * develope_after_primary_menu hook.
				 *
				 * @since 2.3
				 */
				do_action( 'develope_after_primary_menu' );
				?>
			</div><!-- .inside-navigation -->
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'develope_menu_fallback' ) ) {
	/**
	 * Menu fallback.
	 *
	 * @since 1.1.4
	 *
	 * @param  array $args
	 * @return string
	 */
	function develope_menu_fallback( $args ) {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);
		?>
		<div id="primary-menu" class="main-nav">
			<ul <?php develope_do_element_classes( 'menu' ); ?>>
				<?php
				$args = array(
					'sort_column' => 'menu_order',
					'title_li' => '',
					'walker' => new Develope_Page_Walker(),
				);

				wp_list_pages( $args );

				if ( 'enable' === $develope_settings['nav_search'] ) {
					printf(
						'<li class="search-item"><a aria-label="%1$s" href="#">%2$s</a></li>',
						esc_attr__( 'Open Search Bar', 'developress' ),
						develope_get_svg_icon( 'search', true )
					);
				}
				?>
			</ul>
		</div><!-- .main-nav -->
		<?php
	}
}

/**
 * Generate the navigation based on settings
 *
 * It would be better to have all of these inside one action, but these
 * are kept this way to maintain backward compatibility for people
 * un-hooking and moving the navigation/changing the priority.
 *
 * @since 0.1
 */

if ( ! function_exists( 'develope_add_navigation_after_header' ) ) {
	add_action( 'develope_after_header', 'develope_add_navigation_after_header', 5 );
	function develope_add_navigation_after_header() {
		if ( 'nav-below-header' == develope_get_navigation_location() ) {
			develope_navigation_position();
		}
	}
}

if ( ! function_exists( 'develope_add_navigation_before_header' ) ) {
	add_action( 'develope_before_header', 'develope_add_navigation_before_header', 5 );
	function develope_add_navigation_before_header() {
		if ( 'nav-above-header' == develope_get_navigation_location() ) {
			develope_navigation_position();
		}
	}
}

if ( ! function_exists( 'develope_add_navigation_float_right' ) ) {
	add_action( 'develope_after_header_content', 'develope_add_navigation_float_right', 5 );
	function develope_add_navigation_float_right() {
		if ( 'nav-float-right' == develope_get_navigation_location() || 'nav-float-left' == develope_get_navigation_location() ) {
			develope_navigation_position();
		}
	}
}

if ( ! function_exists( 'develope_add_navigation_before_right_sidebar' ) ) {
	add_action( 'develope_before_right_sidebar_content', 'develope_add_navigation_before_right_sidebar', 5 );
	function develope_add_navigation_before_right_sidebar() {
		if ( 'nav-right-sidebar' == develope_get_navigation_location() ) {
			echo '<div class="gen-sidebar-nav">';
				develope_navigation_position();
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'develope_add_navigation_before_left_sidebar' ) ) {
	add_action( 'develope_before_left_sidebar_content', 'develope_add_navigation_before_left_sidebar', 5 );
	function develope_add_navigation_before_left_sidebar() {
		if ( 'nav-left-sidebar' == develope_get_navigation_location() ) {
			echo '<div class="gen-sidebar-nav">';
				develope_navigation_position();
			echo '</div>';
		}
	}
}

if ( ! class_exists( 'Develope_Page_Walker' ) && class_exists( 'Walker_Page' ) ) {
	/**
	 * Add current-menu-item to the current item if no theme location is set
	 * This means we don't have to duplicate CSS properties for current_page_item and current-menu-item
	 *
	 * @since 1.3.21
	 */
	class Develope_Page_Walker extends Walker_Page {
		function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
			$css_class = array( 'page_item', 'page-item-' . $page->ID );
			$button = '';

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'menu-item-has-children';
				$icon = develope_get_svg_icon( 'arrow' );
				$button = '<span role="presentation" class="dropdown-menu-toggle">' . $icon . '</span>';
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
					$css_class[] = 'current-menu-ancestor';
				}
				if ( $page->ID == $current_page ) {
					$css_class[] = 'current-menu-item';
				} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
					$css_class[] = 'current-menu-parent';
				}
			} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
				$css_class[] = 'current-menu-parent';
			}

			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$output .= sprintf(
				'<li class="%s"><a href="%s">%s%s%s%s</a>',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after'],
				$button
			);
		}
	}
}

if ( ! function_exists( 'develope_dropdown_icon_to_menu_link' ) ) {
	add_filter( 'nav_menu_item_title', 'develope_dropdown_icon_to_menu_link', 10, 4 );
	/**
	 * Add dropdown icon if menu item has children.
	 *
	 * @since 1.3.42
	 *
	 * @param string $title The menu item title.
	 * @param WP_Post $item All of our menu item data.
	 * @param stdClass $args All of our menu item args.
	 * @param int $dept Depth of menu item.
	 * @return string The menu item.
	 */
	function develope_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
		$role = 'presentation';
		$tabindex = '';

		if ( 'click-arrow' === develope_get_option( 'nav_dropdown_type' ) ) {
			$role = 'button';
			$tabindex = ' tabindex="0"';
		}

		if ( isset( $args->container_class ) && 'main-nav' === $args->container_class ) {
			foreach ( $item->classes as $value ) {
				if ( 'menu-item-has-children' === $value ) {
					$icon = develope_get_svg_icon( 'arrow' );
					$title = $title . '<span role="' . $role . '" class="dropdown-menu-toggle"' . $tabindex . '>' . $icon . '</span>';
				}
			}
		}

		return $title;
	}
}

if ( ! function_exists( 'develope_navigation_search' ) ) {
	add_action( 'develope_inside_navigation', 'develope_navigation_search' );
	/**
	 * Add the search bar to the navigation.
	 *
	 * @since 1.1.4
	 */
	function develope_navigation_search() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		if ( 'enable' !== $develope_settings['nav_search'] ) {
			return;
		}

		echo apply_filters( 'develope_navigation_search_output', sprintf( // phpcs:ignore Standard.Category.SniffName.ErrorCode.
			'<form method="get" class="search-form navigation-search" action="%1$s">
				<input type="search" class="search-field" value="%2$s" name="s" title="%3$s" />
			</form>',
			esc_url( home_url( '/' ) ),
			esc_attr( get_search_query() ),
			esc_attr_x( 'Search', 'label', 'developress' )
		));
	}
}

if ( ! function_exists( 'develope_menu_search_icon' ) ) {
	add_filter( 'wp_nav_menu_items', 'develope_menu_search_icon', 10, 2 );
	/**
	 * Add search icon to primary menu if set
	 *
	 * @since 1.2.9.7
	 *
	 * @param string $nav The HTML list content for the menu items.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 * @return string The search icon menu item.
	 */
	function develope_menu_search_icon( $nav, $args ) {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		// If the search icon isn't enabled, return the regular nav.
		if ( 'enable' !== $develope_settings['nav_search'] ) {
			return $nav;
		}

		// If our primary menu is set, add the search icon.
		if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
			return sprintf(
				'%1$s<li class="search-item"><a aria-label="%2$s" href="#">%3$s</a></li>',
				$nav,
				esc_attr__( 'Open Search Bar', 'developress' ),
				develope_get_svg_icon( 'search', true )
			);
		}

		// Our primary menu isn't set, return the regular nav.
		// In this case, the search icon is added to the develope_menu_fallback() function in navigation.php.
		return $nav;
	}
}

if ( ! function_exists( 'develope_mobile_menu_search_icon' ) ) {
	add_action( 'develope_inside_navigation', 'develope_mobile_menu_search_icon' );
	/**
	 * Add search icon to mobile menu bar
	 *
	 * @since 1.3.12
	 */
	function develope_mobile_menu_search_icon() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		// If the search icon isn't enabled, return the regular nav.
		if ( 'enable' !== $develope_settings['nav_search'] ) {
			return;
		}

		?>
		<div class="mobile-bar-items">
			<?php do_action( 'develope_inside_mobile_menu_bar' ); ?>
			<span class="search-item">
				<a aria-label="<?php _e( 'Open Search Bar', 'developress' ); ?>" href="#">
					<?php develope_do_svg_icon( 'search', true ); ?>
				</a>
			</span>
		</div><!-- .mobile-bar-items -->
		<?php
	}
}

add_action( 'wp_footer', 'develope_clone_sidebar_navigation' );
/**
 * Clone our sidebar navigation and place it below the header.
 * This places our mobile menu in a more user-friendly location.
 *
 * We're not using wp_add_inline_script() as this needs to happens
 * before menu.js is enqueued.
 *
 * @since 2.0
 */
function develope_clone_sidebar_navigation() {
	if ( 'nav-left-sidebar' !== develope_get_navigation_location() && 'nav-right-sidebar' !== develope_get_navigation_location() ) {
		return;
	}
	?>
	<script>
		var target, nav, clone;
		nav = document.getElementById( 'site-navigation' );
		if ( nav ) {
			clone = nav.cloneNode( true );
			clone.className += ' sidebar-nav-mobile';
			clone.setAttribute( 'aria-label', '<?php esc_attr_e( 'Mobile Menu', 'developress' ); ?>' );
			target = document.getElementById( 'masthead' );
			if ( target ) {
				target.insertAdjacentHTML( 'afterend', clone.outerHTML );
			} else {
				document.body.insertAdjacentHTML( 'afterbegin', clone.outerHTML )
			}
		}
	</script>
	<?php
}
