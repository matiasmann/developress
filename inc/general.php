<?php
/**
 * General functions.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'develope_scripts' );
	/**
	 * Enqueue scripts and styles
	 */
	function develope_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$dir_uri = get_template_directory_uri();

		if ( develope_get_option( 'combine_css' ) && $suffix ) {
			wp_enqueue_style( 'develope-style', $dir_uri . "/css/mini.css", array(), DEVELOPE_VERSION, 'all' );
		} else {
			wp_enqueue_style( 'develope-style', $dir_uri . "/css/style.css", array(), DEVELOPE_VERSION, 'all' );
			wp_enqueue_style( 'develope-mobile-style', $dir_uri . "/css/mobile.css", array( 'develope-style' ), DEVELOPE_VERSION, 'all' );
		}

		if ( is_child_theme() ) {
			wp_enqueue_style( 'develope-child', get_stylesheet_uri(), array( 'develope-style' ), filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
		}

		if ( ! apply_filters( 'develope_fontawesome_essentials', false ) ) {
			wp_enqueue_style( 'font-awesome', $dir_uri . "/css/font-awesome{$suffix}.css", false, '4.7', 'all' );
		}

		if ( function_exists( 'wp_script_add_data' ) ) {
			wp_enqueue_script( 'develope-classlist', $dir_uri . "/js/classList{$suffix}.js", array(), DEVELOPE_VERSION, true );
			wp_script_add_data( 'develope-classlist', 'conditional', 'lte IE 11' );
		}

		wp_enqueue_script( 'develope-menu', $dir_uri . "/js/menu{$suffix}.js", array(), DEVELOPE_VERSION, true );
		wp_enqueue_script( 'develope-a11y', $dir_uri . "/js/a11y{$suffix}.js", array(), DEVELOPE_VERSION, true );

		if ( 'click' === develope_get_option( 'nav_dropdown_type' ) || 'click-arrow' === develope_get_option( 'nav_dropdown_type' ) ) {
			wp_enqueue_script( 'develope-dropdown-click', $dir_uri . "/js/dropdown-click{$suffix}.js", array( 'develope-menu' ), DEVELOPE_VERSION, true );
		}

		if ( 'enable' === develope_get_option( 'nav_search' ) ) {
			wp_enqueue_script( 'develope-navigation-search', $dir_uri . "/js/navigation-search{$suffix}.js", array( 'develope-menu' ), DEVELOPE_VERSION, true );

			wp_localize_script(
				'develope-navigation-search',
				'developressNavSearch',
				array(
					'open' => esc_attr__( 'Open Search Bar', 'developress' ),
					'close' => esc_attr__( 'Close Search Bar', 'developress' ),
				)
			);
		}

		if ( 'enable' === develope_get_option( 'back_to_top' ) ) {
			wp_enqueue_script( 'develope-back-to-top', $dir_uri . "/js/back-to-top{$suffix}.js", array(), DEVELOPE_VERSION, true );
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

if ( ! function_exists( 'develope_widgets_init' ) ) {
	add_action( 'widgets_init', 'develope_widgets_init' );
	/**
	 * Register widgetized area and update sidebar with default widgets
	 */
	function develope_widgets_init() {
		$widgets = array(
			'sidebar-1' => __( 'Right Sidebar', 'developress' ),
			'sidebar-2' => __( 'Left Sidebar', 'developress' ),
			'header' => __( 'Header', 'developress' ),
			'footer-1' => __( 'Footer Widget 1', 'developress' ),
			'footer-2' => __( 'Footer Widget 2', 'developress' ),
			'footer-3' => __( 'Footer Widget 3', 'developress' ),
			'footer-4' => __( 'Footer Widget 4', 'developress' ),
			'footer-5' => __( 'Footer Widget 5', 'developress' ),
			'footer-bar' => __( 'Footer Bar','developress' ),
			'top-bar' => __( 'Top Bar','developress' ),
		);

		foreach ( $widgets as $id => $name ) {
			register_sidebar( array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => apply_filters( 'develope_start_widget_title', '<h2 class="widget-title">' ),
				'after_title'   => apply_filters( 'develope_end_widget_title', '</h2>' ),
			) );
		}
	}
}

if ( ! function_exists( 'develope_smart_content_width' ) ) {
	add_action( 'wp', 'develope_smart_content_width' );
	/**
	 * Set the $content_width depending on layout of current page
	 * Hook into "wp" so we have the correct layout setting from develope_get_layout()
	 * Hooking into "after_setup_theme" doesn't get the correct layout setting
	 */
	function develope_smart_content_width() {
		global $content_width;

		$container_width = develope_get_option( 'container_width' );
		$right_sidebar_width = apply_filters( 'develope_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'develope_left_sidebar_width', '25' );
		$layout = develope_get_layout();

		if ( 'left-sidebar' == $layout ) {
			$content_width = $container_width * ( ( 100 - $left_sidebar_width ) / 100 );
		} elseif ( 'right-sidebar' == $layout ) {
			$content_width = $container_width * ( ( 100 - $right_sidebar_width ) / 100 );
		} elseif ( 'no-sidebar' == $layout ) {
			$content_width = $container_width;
		} else {
			$content_width = $container_width * ( ( 100 - ( $left_sidebar_width + $right_sidebar_width ) ) / 100 );
		}
	}
}

if ( ! function_exists( 'develope_page_menu_args' ) ) {
	add_filter( 'wp_page_menu_args', 'develope_page_menu_args' );
	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @since 0.1
	 *
	 * @param array $args The existing menu args.
	 * @return array Menu args.
	 */
	function develope_page_menu_args( $args ) {
		$args['show_home'] = true;

		return $args;
	}
}

if ( ! function_exists( 'develope_disable_title' ) ) {
	add_filter( 'develope_show_title', 'develope_disable_title' );
	/**
	 * Remove our title if set.
	 *
	 * @since 1.3.18
	 *
	 * @param bool $title Whether the title is displayed or not.
	 * @return bool Whether to display the content title.
	 */
	function develope_disable_title( $title ) {
		if ( is_singular() ) {
			$disable_title = get_post_meta( get_the_ID(), '_develope-disable-headline', true );

			if ( $disable_title ) {
				$title = false;
			}
		}

		return $title;
	}
}

if ( ! function_exists( 'develope_resource_hints' ) ) {
	add_filter( 'wp_resource_hints', 'develope_resource_hints', 10, 2 );
	/**
	 * Add resource hints to our Google fonts call.
	 *
	 * @since 1.3.42
	 *
	 * @param array  $urls           URLs to print for resource hints.
	 * @param string $relation_type  The relation type the URLs are printed.
	 * @return array $urls           URLs to print for resource hints.
	 */
	function develope_resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'develope-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
			if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '>=' ) ) {
				$urls[] = array(
					'href' => 'https://fonts.gstatic.com',
					'crossorigin',
				);
			} else {
				$urls[] = 'https://fonts.gstatic.com';
			}
		}

		return $urls;
	}
}

if ( ! function_exists( 'develope_remove_caption_padding' ) ) {
	add_filter( 'img_caption_shortcode_width', 'develope_remove_caption_padding' );
	/**
	 * Remove WordPress's default padding on images with captions
	 *
	 * @param int $width Default WP .wp-caption width (image width + 10px)
	 * @return int Updated width to remove 10px padding
	 */
	function develope_remove_caption_padding( $width ) {
		return $width - 10;
	}
}

if ( ! function_exists( 'develope_enhanced_image_navigation' ) ) {
	add_filter( 'attachment_link', 'develope_enhanced_image_navigation', 10, 2 );
	/**
	 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
	 */
	function develope_enhanced_image_navigation( $url, $id ) {
		if ( ! is_attachment() && ! wp_attachment_is_image( $id ) ) {
			return $url;
		}

		$image = get_post( $id );
		if ( ! empty( $image->post_parent ) && $image->post_parent != $id ) {
			$url .= '#main';
		}

		return $url;
	}
}

if ( ! function_exists( 'develope_categorized_blog' ) ) {
	/**
	 * Determine whether blog/site has more than one category.
	 *
	 * @since 1.2.5
	 *
	 * @return bool True of there is more than one category, false otherwise.
	 */
	function develope_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'develope_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'develope_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so twentyfifteen_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so twentyfifteen_categorized_blog should return false.
			return false;
		}
	}
}

if ( ! function_exists( 'develope_category_transient_flusher' ) ) {
	add_action( 'edit_category', 'develope_category_transient_flusher' );
	add_action( 'save_post',     'develope_category_transient_flusher' );
	/**
	 * Flush out the transients used in {@see develope_categorized_blog()}.
	 *
	 * @since 1.2.5
	 */
	function develope_category_transient_flusher() {
		// Like, beat it. Dig?
		delete_transient( 'develope_categories' );
	}
}

if ( ! function_exists( 'develope_get_default_color_palettes' ) ) {
	/**
	 * Set up our colors for the color picker palettes and filter them so you can change them.
	 *
	 * @since 1.3.42
	 */
	function develope_get_default_color_palettes() {
		$palettes = array(
			'#000000',
			'#FFFFFF',
			'#F1C40F',
			'#E74C3C',
			'#1ABC9C',
			'#1e72bd',
			'#8E44AD',
			'#00CC77',
		);

		return apply_filters( 'develope_default_color_palettes', $palettes );
	}
}

add_filter( 'develope_fontawesome_essentials', 'develope_set_font_awesome_essentials' );
/**
 * Check to see if we should include the full Font Awesome library or not.
 *
 * @since 2.0
 *
 * @param bool $essentials
 * @return bool
 */
function develope_set_font_awesome_essentials( $essentials ) {
	if ( develope_get_option( 'font_awesome_essentials' ) ) {
		return true;
	}

	return $essentials;
}

add_filter( 'develope_dynamic_css_skip_cache', 'develope_skip_dynamic_css_cache' );
/**
 * Skips caching of the dynamic CSS if set to false.
 *
 * @since 2.0
 *
 * @param bool $cache
 * @return bool
 */
function develope_skip_dynamic_css_cache( $cache ) {
	if ( ! develope_get_option( 'dynamic_css_cache' ) ) {
		return true;
	}

	return $cache;
}

add_filter( 'wp_headers', 'develope_set_wp_headers' );
/**
 * Set any necessary headers.
 *
 * @since 2.3
 */
function develope_set_wp_headers( $headers ) {
	$headers['X-UA-Compatible'] = 'IE=edge';

	return $headers;
}
