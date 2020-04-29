<?php
/**
 * The template for displaying the header.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php develope_do_microdata( 'body' ); ?>>
	<?php
	/**
	 * wp_body_open hook.
	 *
	 * @since 2.3
	 * https://make.wordpress.org/themes/2019/03/29/addition-of-new-wp_body_open-hook/#comment-43714
	 */
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}

	/**
	 * develope_before_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked develope_do_skip_to_content_link - 2
	 * @hooked develope_top_bar - 5
	 * @hooked develope_add_navigation_before_header - 5
	 */
	do_action( 'develope_before_header' );

	/**
	 * develope_header hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked develope_construct_header - 10
	 */
	do_action( 'develope_header' );

	/**
	 * develope_after_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked develope_featured_page_header - 10
	 */
	do_action( 'develope_after_header' );
	?>

	<div id="page" class="hfeed site grid-container container grid-parent">
		<?php
		/**
		 * develope_inside_site_container hook.
		 *
		 * @since 2.4
		 */
		do_action( 'develope_inside_site_container' );
		?>
		<div id="content" class="site-content">
			<?php
			/**
			 * develope_inside_container hook.
			 *
			 * @since 0.1
			 */
			do_action( 'develope_inside_container' );
