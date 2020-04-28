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
<div id="right-sidebar" <?php develope_do_element_classes( 'right_sidebar' ); ?> <?php develope_do_microdata( 'sidebar' ); ?>>
	<div class="inside-right-sidebar">
		<?php
		/**
		 * develope_before_right_sidebar_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_before_right_sidebar_content' );

		if ( ! dynamic_sidebar( 'sidebar-1' ) ) {
			develope_do_default_sidebar_widgets( 'right-sidebar' );
		}

		/**
		 * develope_after_right_sidebar_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_after_right_sidebar_content' );
		?>
	</div><!-- .inside-right-sidebar -->
</div><!-- #secondary -->
