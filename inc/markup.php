<?php
/**
 * Adds HTML markup.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Display HTML classes for an element.
 *
 * @since 2.2
 *
 * @param string $context The element we're targeting.
 * @param string|array $class One or more classes to add to the class list.
 */
function develope_do_element_classes( $context, $class = '' ) {
	echo 'class="' . join( ' ', develope_get_element_classes( $context, $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
}

/**
 * Retrieve HTML classes for an element.
 *
 * @since 2.2
 *
 * @param string $context The element we're targeting.
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function develope_get_element_classes( $context, $class = '' ) {
	$classes = array();

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( "develope_{$context}_class", $classes, $class );
}

/**
 * Get any necessary microdata.
 *
 * @since 2.2
 *
 * @param string $context The element to target.
 * @return string Our final attribute to add to the element.
 */
function develope_get_microdata( $context ) {
	$data = false;

	if ( 'microdata' !== apply_filters( 'develope_schema_type', 'microdata' ) ) {
		return false;
	}

	if ( 'body' === $context ) {
		$type = 'WebPage';

		if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
			$type = 'Blog';
		}

		if ( is_search() ) {
			$type = 'SearchResultsPage';
		}

		$type = apply_filters( 'develope_body_itemtype', $type );

		$data = sprintf(
			'itemtype="https://schema.org/%s" itemscope',
			esc_html( $type )
		);
	}

	if ( 'header' === $context ) {
		$data = 'itemtype="https://schema.org/WPHeader" itemscope';
	}

	if ( 'navigation' === $context ) {
		$data = 'itemtype="https://schema.org/SiteNavigationElement" itemscope';
	}

	if ( 'article' === $context ) {
		$type = apply_filters( 'develope_article_itemtype', 'CreativeWork' );

		$data = sprintf(
			'itemtype="https://schema.org/%s" itemscope',
			esc_html( $type )
		);
	}

	if ( 'post-author' === $context ) {
		$data = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
	}

	if ( 'comment-body' === $context ) {
		$data = 'itemtype="https://schema.org/Comment" itemscope';
	}

	if ( 'comment-author' === $context ) {
		$data = 'itemprop="author" itemtype="https://schema.org/Person" itemscope';
	}

	if ( 'sidebar' === $context ) {
		$data = 'itemtype="https://schema.org/WPSideBar" itemscope';
	}

	if ( 'footer' === $context ) {
		$data = 'itemtype="https://schema.org/WPFooter" itemscope';
	}

	if ( $data ) {
		return apply_filters( "develope_{$context}_microdata", $data );
	}
}

/**
 * Output our microdata for an element.
 *
 * @since 2.2
 *
 * @param $context The element to target.
 * @return string The microdata.
 */
function develope_do_microdata( $context ) {
	echo develope_get_microdata( $context ); // WPCS: XSS ok, sanitization ok.
}

if ( ! function_exists( 'develope_body_classes' ) ) {
	add_filter( 'body_class', 'develope_body_classes' );
	/**
	 * Adds custom classes to the array of body classes.
	 * @since 0.1
	 */
	function develope_body_classes( $classes ) {
		$sidebar_layout 		= develope_get_layout();
		$navigation_location 	= develope_get_navigation_location();
		$navigation_alignment	= develope_get_option( 'nav_alignment_setting' );
		$navigation_dropdown	= develope_get_option( 'nav_dropdown_type' );
		$header_layout 			= develope_get_option( 'header_layout_setting' );
		$header_alignment		= develope_get_option( 'header_alignment_setting' );
		$content_layout 		= develope_get_option( 'content_layout_setting' );
		$footer_widgets 		= develope_get_footer_widgets();

		// These values all have defaults, but we like to be extra careful.
		$classes[] = ( $sidebar_layout ) ? $sidebar_layout : 'right-sidebar';
		$classes[] = ( $navigation_location ) ? $navigation_location : 'nav-below-header';
		$classes[] = ( $header_layout ) ? $header_layout : 'fluid-header';
		$classes[] = ( $content_layout ) ? $content_layout : 'separate-containers';
		$classes[] = ( '' !== $footer_widgets ) ? 'active-footer-widgets-' . absint( $footer_widgets ) : 'active-footer-widgets-3';

		if ( 'enable' === develope_get_option( 'nav_search' ) ) {
			$classes[] = 'nav-search-enabled';
		}

		// Only necessary for nav before or after header.
		if ( 'nav-below-header' === $navigation_location || 'nav-above-header' === $navigation_location ) {
			if ( 'center' === $navigation_alignment ) {
				$classes[] = 'nav-aligned-center';
			} elseif ( 'right' === $navigation_alignment ) {
				$classes[] = 'nav-aligned-right';
			} elseif ( 'left' === $navigation_alignment ) {
				$classes[] = 'nav-aligned-left';
			}
		}

		if ( 'center' === $header_alignment ) {
			$classes[] = 'header-aligned-center';
		} elseif ( 'right' === $header_alignment ) {
			$classes[] = 'header-aligned-right';
		} elseif ( 'left' === $header_alignment ) {
			$classes[] = 'header-aligned-left';
		}

		if ( 'click' === $navigation_dropdown ) {
			$classes[] = 'dropdown-click';
			$classes[] = 'dropdown-click-menu-item';
		} elseif ( 'click-arrow' === $navigation_dropdown ) {
			$classes[] = 'dropdown-click-arrow';
			$classes[] = 'dropdown-click';
		} else {
			$classes[] = 'dropdown-hover';
		}

		if ( is_singular() ) {
			// Page builder container metabox option.
			// Used to be a single checkbox, hence the name/true value. Now it's a radio choice between full width and contained.
			$content_container = get_post_meta( get_the_ID(), '_develope-full-width-content', true );

			if ( $content_container ) {
				if ( 'true' === $content_container ) {
					$classes[] = 'full-width-content';
				}

				if ( 'contained' === $content_container ) {
					$classes[] = 'contained-content';
				}
			}

			if ( has_post_thumbnail() ) {
				$classes[] = 'featured-image-active';
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_top_bar_classes' ) ) {
	add_filter( 'develope_top_bar_class', 'develope_top_bar_classes' );
	/**
	 * Adds custom classes to the header.
	 *
	 * @since 0.1
	 */
	function develope_top_bar_classes( $classes ) {
		$classes[] = 'top-bar';

		if ( 'contained' === develope_get_option( 'top_bar_width' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		$classes[] = 'top-bar-align-' . esc_attr( develope_get_option( 'top_bar_alignment' ) );

		return $classes;
	}
}

if ( ! function_exists( 'develope_right_sidebar_classes' ) ) {
	add_filter( 'develope_right_sidebar_class', 'develope_right_sidebar_classes' );
	/**
	 * Adds custom classes to the right sidebar.
	 *
	 * @since 0.1
	 */
	function develope_right_sidebar_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'develope_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'develope_left_sidebar_width', '25' );

		$right_sidebar_tablet_width = apply_filters( 'develope_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'develope_left_sidebar_tablet_width', $left_sidebar_width );

		$classes[] = 'widget-area';
		$classes[] = 'grid-' . $right_sidebar_width;
		$classes[] = 'tablet-grid-' . $right_sidebar_tablet_width;
		$classes[] = 'grid-parent';
		$classes[] = 'sidebar';

		// Get the layout
		$layout = develope_get_layout();

		if ( '' !== $layout ) {
			switch ( $layout ) {
				case 'both-left' :
					$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;
					$classes[] = 'pull-' . ( 100 - $total_sidebar_width );

					$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;
					$classes[] = 'tablet-pull-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_left_sidebar_classes' ) ) {
	add_filter( 'develope_left_sidebar_class', 'develope_left_sidebar_classes' );
	/**
	 * Adds custom classes to the left sidebar.
	 *
	 * @since 0.1
	 */
	function develope_left_sidebar_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'develope_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'develope_left_sidebar_width', '25' );
		$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;

		$right_sidebar_tablet_width = apply_filters( 'develope_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'develope_left_sidebar_tablet_width', $left_sidebar_width );
		$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;

		$classes[] = 'widget-area';
		$classes[] = 'grid-' . $left_sidebar_width;
		$classes[] = 'tablet-grid-' . $left_sidebar_tablet_width;
		$classes[] = 'mobile-grid-100';
		$classes[] = 'grid-parent';
		$classes[] = 'sidebar';

		// Get the layout
		$layout = develope_get_layout();

		if ( '' !== $layout ) {
			switch ( $layout ) {
				case 'left-sidebar' :
					$classes[] = 'pull-' . ( 100 - $left_sidebar_width );
					$classes[] = 'tablet-pull-' . ( 100 - $left_sidebar_tablet_width );
				break;

				case 'both-sidebars' :
				case 'both-left' :
					$classes[] = 'pull-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-pull-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_content_classes' ) ) {
	add_filter( 'develope_content_class', 'develope_content_classes' );
	/**
	 * Adds custom classes to the content container.
	 *
	 * @since 0.1
	 */
	function develope_content_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'develope_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'develope_left_sidebar_width', '25' );
		$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;

		$right_sidebar_tablet_width = apply_filters( 'develope_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'develope_left_sidebar_tablet_width', $left_sidebar_width );
		$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;

		$classes[] = 'content-area';
		$classes[] = 'grid-parent';
		$classes[] = 'mobile-grid-100';

		// Get the layout
		$layout = develope_get_layout();

		if ( '' !== $layout ) {
			switch ( $layout ) {

				case 'right-sidebar' :
					$classes[] = 'grid-' . ( 100 - $right_sidebar_width );
					$classes[] = 'tablet-grid-' . ( 100 - $right_sidebar_tablet_width );
				break;

				case 'left-sidebar' :
					$classes[] = 'push-' . $left_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $left_sidebar_width );
					$classes[] = 'tablet-push-' . $left_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $left_sidebar_tablet_width );
				break;

				case 'no-sidebar' :
					$classes[] = 'grid-100';
					$classes[] = 'tablet-grid-100';
				break;

				case 'both-sidebars' :
					$classes[] = 'push-' . $left_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-push-' . $left_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;

				case 'both-right' :
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;

				case 'both-left' :
					$classes[] = 'push-' . $total_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-push-' . $total_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_header_classes' ) ) {
	add_filter( 'develope_header_class', 'develope_header_classes' );
	/**
	 * Adds custom classes to the header.
	 *
	 * @since 0.1
	 */
	function develope_header_classes( $classes ) {
		$classes[] = 'site-header';

		if ( 'contained-header' === develope_get_option( 'header_layout_setting' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_inside_header_classes' ) ) {
	add_filter( 'develope_inside_header_class', 'develope_inside_header_classes' );
	/**
	 * Adds custom classes to inside the header.
	 *
	 * @since 0.1
	 */
	function develope_inside_header_classes( $classes ) {
		$classes[] = 'inside-header';

		if ( 'full-width' !== develope_get_option( 'header_inner_width' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_navigation_classes' ) ) {
	add_filter( 'develope_navigation_class', 'develope_navigation_classes' );
	/**
	 * Adds custom classes to the navigation.
	 *
	 * @since 0.1
	 */
	function develope_navigation_classes( $classes ) {
		$classes[] = 'main-navigation';

		if ( 'contained-nav' === develope_get_option( 'nav_layout_setting' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		if ( 'left' === develope_get_option( 'nav_dropdown_direction' ) ) {
			$nav_layout = develope_get_option( 'nav_position_setting' );

			switch ( $nav_layout ) {
				case 'nav-below-header':
				case 'nav-above-header':
				case 'nav-float-right':
				case 'nav-float-left':
					$classes[] = 'sub-menu-left';
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_inside_navigation_classes' ) ) {
	add_filter( 'develope_inside_navigation_class', 'develope_inside_navigation_classes' );
	/**
	 * Adds custom classes to the inner navigation.
	 *
	 * @since 1.3.41
	 */
	function develope_inside_navigation_classes( $classes ) {
		$classes[] = 'inside-navigation';

		if ( 'full-width' !== develope_get_option( 'nav_inner_width' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_menu_classes' ) ) {
	add_filter( 'develope_menu_class', 'develope_menu_classes' );
	/**
	 * Adds custom classes to the menu.
	 *
	 * @since 0.1
	 */
	function develope_menu_classes( $classes ) {
		$classes[] = 'menu';
		$classes[] = 'sf-menu';

		return $classes;
	}
}

if ( ! function_exists( 'develope_footer_classes' ) ) {
	add_filter( 'develope_footer_class', 'develope_footer_classes' );
	/**
	 * Adds custom classes to the footer.
	 *
	 * @since 0.1
	 */
	function develope_footer_classes( $classes ) {
		$classes[] = 'site-footer';

		if ( 'contained-footer' === develope_get_option( 'footer_layout_setting' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		if ( is_active_sidebar( 'footer-bar' ) ) {
			$classes[] = 'footer-bar-active';
			$classes[] = 'footer-bar-align-' . esc_attr( develope_get_option( 'footer_bar_alignment' ) );
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_inside_footer_classes' ) ) {
	add_filter( 'develope_inside_footer_class', 'develope_inside_footer_classes' );
	/**
	 * Adds custom classes to the footer.
	 *
	 * @since 0.1
	 */
	function develope_inside_footer_classes( $classes ) {
		$classes[] = 'footer-widgets-container';

		if ( 'full-width' !== develope_get_option( 'footer_inner_width' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'develope_main_classes' ) ) {
	add_filter( 'develope_main_class', 'develope_main_classes' );
	/**
	 * Adds custom classes to the <main> element
	 * @since 1.1.0
	 */
	function develope_main_classes( $classes ) {
		$classes[] = 'site-main';

		return $classes;
	}
}

if ( ! function_exists( 'develope_post_classes' ) ) {
	add_filter( 'post_class', 'develope_post_classes' );
	/**
	 * Adds custom classes to the <article> element.
	 * Remove .hentry class from pages to comply with structural data guidelines.
	 *
	 * @since 1.3.39
	 */
	function develope_post_classes( $classes ) {
		if ( 'page' == get_post_type() ) {
			$classes = array_diff( $classes, array( 'hentry' ) );
		}

		return $classes;
	}
}
