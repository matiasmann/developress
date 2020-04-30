<?php
/**
 * Migrates old options on update.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'admin_init', 'develope_do_admin_db_updates' );
/**
 * Process database updates if necessary.
 * There's nothing in here yet, but we're setting the version to use later.
 *
 * @since 2.1
 */
function develope_do_admin_db_updates() {
	// Get the current version.
	$current_version = get_option( 'develope_db_version', false );

	// Process future database updates here.

	// Set the new database version.
	if ( version_compare( $current_version, DEVELOPE_VERSION, '<' ) ) {
		update_option( 'develope_db_version', DEVELOPE_VERSION, false );
	}
}

add_action( 'init', 'develope_do_db_updates', 5 );
/**
 * Process important database updates when someone visits the front or backend.
 *
 * @since 2.3
 */
function develope_do_db_updates() {
	$flags = get_option( 'develope_migration_settings', array() );

	if ( ! isset( $flags['combine_css'] ) || 'done' !== $flags['combine_css'] ) {
		if ( ! get_option( 'fresh_site' ) ) {
			$settings = get_option( 'develope_settings', array() );

			$settings['combine_css'] = false;
			update_option( 'develope_settings', $settings );
		}

		$flags['combine_css'] = 'done';
		update_option( 'develope_migration_settings', $flags );
	}
}

if ( ! function_exists( 'develope_update_logo_setting' ) ) {
	add_action( 'admin_init', 'develope_update_logo_setting' );
	/**
	 * Migrate the old logo database entry to the new custom_logo theme mod (WordPress 4.5)
	 *
	 * @since 1.3.29
	 */
	function develope_update_logo_setting() {
		// If we're not running WordPress 4.5, bail.
		if ( ! function_exists( 'the_custom_logo' ) ) {
			return;
		}

		// If we already have a custom logo, bail.
		if ( get_theme_mod( 'custom_logo' ) ) {
			return;
		}

		// Get our settings.
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		// Get the old logo value.
		$old_value = $develope_settings['logo'];

		// If there's no old value, bail.
		if ( empty( $old_value ) ) {
			return;
		}

		// We made it this far, that means we have an old logo, and no new logo.

		// Let's get the ID from our old value.
		$logo = attachment_url_to_postid( $old_value );

		// Now let's update the new logo setting with our ID.
		if ( is_int( $logo ) ) {
			set_theme_mod( 'custom_logo', $logo );
		}

		// Got our custom logo? Time to delete the old value
		if ( get_theme_mod( 'custom_logo' ) ) {
			$new_settings['logo'] = '';
			$update_settings = wp_parse_args( $new_settings, $develope_settings );
			update_option( 'develope_settings', $update_settings );
		}
	}
}

if ( ! function_exists( 'develope_typography_convert_values' ) ) {
	add_action( 'admin_init', 'develope_typography_convert_values' );
	/**
	 * Take the old body font value and strip it of variants
	 * This should only run once
	 * @since 1.3.0
	 */
	function develope_typography_convert_values() {
		// Don't run this if Typography add-on is activated
		if ( function_exists( 'develope_fonts_customize_register' ) ) {
			return;
		}

		// If we've done this before, bail
		if ( 'true' == get_option( 'develope_update_core_typography' ) || 'true' == get_option( 'develope_update_premium_typography' ) ) {
			return;
		}

		// Get all settings
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_font_defaults()
		);

		// Get our body font family setting
		$value = $develope_settings['font_body'];

		// Create a new, empty array
		$new_settings = array();

		// If our value has : in it, and isn't empty
		if ( strpos( $value, ':' ) !== false && ! empty( $value ) ) {

			// Remove the : and anything past it
			$value = current( explode( ':', $value ) );

			// Populate our new array with our new, clean value
			$new_settings['font_body'] = $value;

		}

		// Update our options if our new array isn't empty
		if ( ! empty( $new_settings ) ) {
			$develope_new_typography_settings = wp_parse_args( $new_settings, $develope_settings );
			update_option( 'develope_settings', $develope_new_typography_settings );
		}

		// All done, set an option so we don't go through this again
		update_option( 'develope_update_core_typography','true' );
	}
}

if ( ! function_exists( 'develope_typography_set_font_data' ) ) {
	add_action( 'admin_init', 'develope_typography_set_font_data' );
	/**
	 * This function will check to see if your category and variants are saved
	 * If not, it will set them for you
	 * Generally, set_theme_mod isn't best practice, but this is here for migration purposes for a set amount of time only
	 * Any time a user saves a font in the Customizer from now on, the category and variants are saved as theme_mods, so this function won't be necessary.
	 *
	 * @since 1.3.40
	 */
	function develope_typography_set_font_data() {
		// Get our defaults
		$defaults = develope_get_font_defaults();

		// Get our settings
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			$defaults
		);

		// We don't need to do this if we're using the default font, as these values have defaults already
		if ( $defaults['font_body'] == $develope_settings['font_body'] ) {
			return;
		}

		// Don't need to continue if we're using a system font or our default font
		if ( in_array( $develope_settings['font_body'], develope_get_typography_fonts_default() ) ) {
			return;
		}

		// Don't continue if our category and variants are already set
		if ( get_theme_mod( 'font_body_category' ) && get_theme_mod( 'font_body_variants' ) ) {
			return;
		}

		// Get all of our fonts
		$fonts = develope_get_all_google_fonts();

		// Get the ID from our font
		$id = strtolower( str_replace( ' ', '_', $develope_settings['font_body'] ) );

		// If the ID doesn't exist within our fonts, we can bail
		if ( ! array_key_exists( $id, $fonts ) ) {
			return;
		}

		// Let's grab our category to go with our font
		$category = ! empty( $fonts[$id]['category'] ) ? $fonts[$id]['category'] : '';

		// Grab all of the variants associated with our font
		$variants = $fonts[$id]['variants'];

		// Loop through our variants and put them into an array, then turn them into a comma separated list
		$output = array();
		if ( $variants ) {
			foreach ( $variants as $variant ) {
				$output[] = $variant;
			}
			$variants = implode(',', $output);
		}

		// Set our theme mods with our new settings
		if ( '' !== $category ) {
			set_theme_mod( 'font_body_category', $category );
		}

		if ( '' !== $variants ) {
			set_theme_mod( 'font_body_variants', $variants );
		}
	}
}

add_action( 'admin_init', 'develope_migrate_existing_settings', 1 );
/**
 * Execute functions after existing sites update.
 *
 * We check to see if options already exist. If they do, we can assume the user has
 * updated the theme, and not installed it from scratch.
 *
 * We run this right away in the Dashboard to avoid other migration functions from
 * setting options and causing these functions to run on fresh installs.
 *
 * @since 2.0
 */
function develope_migrate_existing_settings() {
	// Existing settings with no defaults.
	$existing_settings = get_option( 'develope_settings' );

	if ( get_theme_mod( 'font_body_variants', '' ) ) {
		$existing_settings['font_body_variants'] = get_theme_mod( 'font_body_variants' );
	}

	if ( get_theme_mod( 'font_body_category', '' ) ) {
		$existing_settings['font_body_category'] = get_theme_mod( 'font_body_category' );
	}

	// Existing settings with defaults.
	$settings = wp_parse_args(
		get_option( 'develope_settings', array() ),
		develope_get_general_defaults()
	);

	// Empty arrays to add data to.
	$migrated_flags = array();
	$new_settings = array();

	// An option to see what we've migrated.
	$migration_settings = get_option( 'develope_migration_settings', array() );

	// We have settings, so this isn't a fresh install.
	if ( ! empty( $existing_settings ) ) {

		// Turn on the full Font Awesome library for existing websites.
		if ( ! isset( $migration_settings['font_awesome_essentials_updated'] ) || 'true' !== $migration_settings['font_awesome_essentials_updated'] ) {
			$new_settings['font_awesome_essentials'] = false;
		}

		// Turn off dynamic CSS caching for existing websites.
		if ( ! isset( $migration_settings['skip_dynamic_css_cache'] ) || 'true' !== $migration_settings['skip_dynamic_css_cache'] ) {
			$new_settings['dynamic_css_cache'] = false;
		}

		// Set our font family to Open Sans if we never saved a different font.
		if ( ! isset( $migration_settings['default_font_updated'] ) || 'true' !== $migration_settings['default_font_updated'] ) {
			$develope_settings = wp_parse_args(
				get_option( 'develope_settings', array() ),
				array(
					'font_body' => 'Open Sans',
				)
			);

			$category = get_theme_mod( 'font_body_category', 'sans-serif' );
			$variants = get_theme_mod( 'font_body_variants', '300,300italic,regular,italic,600,600italic,700,700italic,800,800italic' );

			if ( 'Open Sans' == $develope_settings['font_body'] ) {
				$new_settings['font_body'] = 'Open Sans';
				set_theme_mod( 'font_body_category', $category );
				set_theme_mod( 'font_body_variants', $variants );
			}
		}

		// Set blog post content to full content if it hasn't been set otherwise.
		if ( ! isset( $migration_settings['blog_post_content_preview'] ) || 'true' !== $migration_settings['blog_post_content_preview'] ) {
			$develope_settings = wp_parse_args(
				get_option( 'develope_settings', array() ),
				array(
					'post_content' => 'full',
				)
			);

			if ( 'full' === $develope_settings['post_content'] ) {
				$new_settings['post_content'] = 'full';
			}
		}

	}

	// Set our flags.
	if ( ! isset( $migration_settings['font_awesome_essentials_updated'] ) || 'true' !== $migration_settings['font_awesome_essentials_updated'] ) {
		$migrated_flags['font_awesome_essentials_updated'] = 'true';
	}

	if ( ! isset( $migration_settings['skip_dynamic_css_cache'] ) || 'true' !== $migration_settings['skip_dynamic_css_cache'] ) {
		$migrated_flags['skip_dynamic_css_cache'] = 'true';
	}

	if ( ! isset( $migration_settings['default_font_updated'] ) || 'true' !== $migration_settings['default_font_updated'] ) {
		$migrated_flags['default_font_updated'] = 'true';
	}

	if ( ! isset( $migration_settings['blog_post_content_preview'] ) || 'true' !== $migration_settings['blog_post_content_preview'] ) {
		$migrated_flags['blog_post_content_preview'] = 'true';
	}

	// Merge our new settings with our existing settings.
	if ( ! empty( $new_settings ) ) {
		$update_settings = wp_parse_args( $new_settings, $settings );
		update_option( 'develope_settings', $update_settings );
	}

	// Set our migrated setting flags.
	if ( ! empty( $migrated_flags ) ) {
		$update_migration_flags = wp_parse_args( $migrated_flags, $migration_settings );
		update_option( 'develope_migration_settings', $update_migration_flags );
	}
}
