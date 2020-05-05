<?php
/**
 * DeveloPress.
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set theme version.
define( 'DEVELOPE_VERSION', '2.0.10' );

if ( ! function_exists( 'develope_setup' ) ) {
	add_action( 'after_setup_theme', 'develope_setup' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 0.1
	 */
	function develope_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'developress' );

		// Add theme support for various features.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'custom-logo', array(
			'height' => 70,
			'width' => 350,
			'flex-height' => true,
			'flex-width' => true,
		) );

		// Register primary menu.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'developress' ),
		) );

		/**
		 * Set the content width to something large
		 * We set a more accurate width in develope_smart_content_width()
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 1200; /* pixels */
		}

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( 'css/admin/editor-style.css' );
	}
}

/**
 * Load theme files
 * 
 * @since 2.0.6
 */
$develope_theme_dir = get_template_directory();
require $develope_theme_dir . '/inc/theme-functions.php';
require $develope_theme_dir . '/inc/class-css.php';
require $develope_theme_dir . '/inc/css-output.php';
require $develope_theme_dir . '/inc/customizer.php';
require $develope_theme_dir . '/inc/deprecated.php';
require $develope_theme_dir . '/inc/general.php';
require $develope_theme_dir . '/inc/markup.php';
require $develope_theme_dir . '/inc/typography.php';
require $develope_theme_dir . '/inc/migrate.php';

/**
 *  Load theme defaults options so we make sure everything looks good
 * 
 * @since 2.0.6
 */
require $develope_theme_dir . '/inc/theme-defaults.php';

/**
 * Add Compatibilty with third party plugins
 * WooCommerce
 * bbPress
 * BuddyPress
 * 
 * @since 2.0.6
 * 
 */

require $develope_theme_dir . '/inc/plugin-compat/compat-buddybbpress.php';
require $develope_theme_dir . '/inc/plugin-compat/compat-woocommerce.php';


/**
 * Load DeveloPress theme common elements
 * 
 * Archives
 * Comments
 * Featured Images
 * Footer
 * Header
 * Navigation
 * Post Meta
 * Sidebars
 * 
 * @since 2.0.6
 */
require $develope_theme_dir . '/inc/elements/archives.php';
require $develope_theme_dir . '/inc/elements/comments.php';
require $develope_theme_dir . '/inc/elements/featured-images.php';
require $develope_theme_dir . '/inc/elements/footer.php';
require $develope_theme_dir . '/inc/elements/header.php';
require $develope_theme_dir . '/inc/elements/navigation.php';
require $develope_theme_dir . '/inc/elements/post-meta.php';
require $develope_theme_dir . '/inc/elements/sidebars.php';


/**
 * Load Admin Metaboxes and DeveloPress Dashboard
 * 
 * @since 2.0.6
 */

if ( is_admin() ) {
	require $develope_theme_dir . '/inc/admin/block-editor.php';
	require $develope_theme_dir . '/inc/admin/meta-box.php';
	require $develope_theme_dir . '/inc/admin/dashboard.php';
}