<?php
/**
 * The template for displaying search forms in DeveloPress
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo apply_filters( 'develope_search_label', _x( 'Search for:', 'label', 'developress' ) ); // WPCS: XSS ok, sanitization ok. ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr( apply_filters( 'develope_search_placeholder', _x( 'Search &hellip;', 'placeholder', 'developress' ) ) ); // WPCS: XSS ok, sanitization ok. ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr( apply_filters( 'develope_search_label', _x( 'Search for:', 'label', 'developress' ) ) ); // WPCS: XSS ok, sanitization ok. ?>">
	</label>
	<input type="submit" class="search-submit" value="<?php echo apply_filters( 'develope_search_button', _x( 'Search', 'submit button', 'developress' ) ); // WPCS: XSS ok, sanitization ok. ?>">
</form>
