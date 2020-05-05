<?php
/**
 * Third Party Plugins Compatibility
 * bbPress
 * BuddyPress
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * bbPress Compatibility CSS
 *
 * @since 2.0.6
 */

if ( ! function_exists( 'develope_buddypress_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'develope_buddypress_css', 100 );
	/**
	 * Add BuddyPress CSS
	 *
	 * @since 1.3.45
	 */
	function develope_buddypress_css() {
		if ( ! class_exists( 'BuddyPress' ) ) {
			return;
		}

		$css = '#buddypress form#whats-new-form #whats-new-options[style] {
			min-height: 6rem;
			overflow: visible;
		}';

		$css = str_replace( array( "\r", "\n", "\t" ), '', $css );
		wp_add_inline_style( 'bp-legacy-css', $css );
	}
}

/**
 * bbPress Compatibility CSS
 *
 * @since 2.0.6
 */

if ( ! function_exists( 'develope_bbpress_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'develope_bbpress_css', 100 );
	/**
	 * Add bbPress CSS
	 *
	 * @since 1.3.45
	 */
	function develope_bbpress_css() {
		if ( ! class_exists( 'bbPress' ) ) {
			return;
		}

		$css = '#bbpress-forums ul.bbp-lead-topic,
		#bbpress-forums ul.bbp-topics,
		#bbpress-forums ul.bbp-forums,
		#bbpress-forums ul.bbp-replies,
		#bbpress-forums ul.bbp-search-results,
		#bbpress-forums,
		div.bbp-breadcrumb,
		div.bbp-topic-tags {
			font-size: inherit;
		}

		.single-forum #subscription-toggle {
			display: block;
			margin: 1em 0;
			clear: left;
		}

		#bbpress-forums .bbp-search-form {
			margin-bottom: 10px;
		}

		.bbp-login-form fieldset {
			border: 0;
			padding: 0;
		}';

		$css = str_replace( array( "\r", "\n", "\t" ), '', $css );
		wp_add_inline_style( 'bbp-default', $css );
	}
}