<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div id="left-sidebar" <?php develope_do_element_classes( 'left_sidebar' ); ?> <?php develope_do_microdata( 'sidebar' ); ?>>
	<div class="inside-left-sidebar">
		<?php
		/**
		 * develope_before_left_sidebar_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_before_left_sidebar_content' );

		if ( ! dynamic_sidebar( 'sidebar-2' ) ) {
			develope_do_default_sidebar_widgets( 'left-sidebar' );
		}

		/**
		 * develope_after_left_sidebar_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_after_left_sidebar_content' );
		?>
	</div><!-- .inside-left-sidebar -->
</div><!-- #secondary -->
