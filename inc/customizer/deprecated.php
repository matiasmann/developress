<?php
/**
 * Where old Customizer functions retire.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_sanitize_typography' ) ) {
	/**
	 * Sanitize typography dropdown.
	 *
	 * @since 1.1.10
	 * @deprecated 1.3.45
	 */
	function develope_sanitize_typography( $input ) {
		// Grab all of our fonts
		$fonts = develope_get_all_google_fonts();

		// Loop through all of them and grab their names
		$font_names = array();
		foreach ( $fonts as $k => $fam ) {
			$font_names[] = $fam['name'];
		}

		// Get all non-Google font names
		$not_google = develope_get_system_font_default();

		// Merge them both into one array
		$valid = array_merge( $font_names, $not_google );

		// Sanitize
		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return 'Open Sans';
		}
	}
}

if ( ! function_exists( 'develope_sanitize_font_weight' ) ) {
	/**
	 * Sanitize font weight.
	 *
	 * @since 1.1.10
	 * @deprecated 1.3.40
	 */
	function develope_sanitize_font_weight( $input ) {

		$valid = array(
			'normal',
			'bold',
			'100',
			'200',
			'300',
			'400',
			'500',
			'600',
			'700',
			'800',
			'900',
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return 'normal';
		}
	}
}

if ( ! function_exists( 'develope_sanitize_text_transform' ) ) {
	/**
	 * Sanitize text transform.
	 *
	 * @since 1.1.10
	 * @deprecated 1.3.40
	 */
	function develope_sanitize_text_transform( $input ) {

		$valid = array(
			'none',
			'capitalize',
			'uppercase',
			'lowercase',
	    );

		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return 'none';
		}
	}
}

if ( ! function_exists( 'develope_typography_customize_preview_css' ) ) {
	/**
	 * Hide the hidden input control
	 * @since 1.3.40
	 */
	function develope_typography_customize_preview_css() {
		?>
		<style>
			.customize-control-dp-hidden-input {display:none !important;}
		</style>
		<?php
	}
}
