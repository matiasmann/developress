<?php
/**
 * The template for displaying the footer.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * develope_before_footer hook.
 *
 * @since 0.1
 */
do_action( 'develope_before_footer' );
?>

<div <?php develope_do_element_classes( 'footer' ); ?>>
	<?php
	/**
	 * develope_before_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'develope_before_footer_content' );

	/**
	 * develope_footer hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked develope_construct_footer_widgets - 5
	 * @hooked develope_construct_footer - 10
	 */
	do_action( 'develope_footer' );

	/**
	 * develope_after_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'develope_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * develope_after_footer hook.
 *
 * @since 2.1
 */
do_action( 'develope_after_footer' );

wp_footer();
?>

</body>
</html>
