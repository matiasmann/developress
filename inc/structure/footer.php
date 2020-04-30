<?php
/**
 * Footer elements.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_construct_footer' ) ) {
	add_action( 'develope_footer', 'develope_construct_footer' );
	/**
	 * Build our footer.
	 *
	 * @since 1.3.42
	 */
	function develope_construct_footer() {
		?>
		<footer class="site-info" <?php develope_do_microdata( 'footer' ); ?>>
			<div class="inside-site-info <?php if ( 'full-width' !== develope_get_option( 'footer_inner_width' ) ) : ?>grid-container grid-parent<?php endif; ?>">
				<?php
				/**
				 * develope_before_copyright hook.
				 *
				 * @since 0.1
				 *
				 * @hooked develope_footer_bar - 15
				 */
				do_action( 'develope_before_copyright' );
				?>
				<div class="copyright-bar">
					<?php
					/**
					 * develope_credits hook.
					 *
					 * @since 0.1
					 *
					 * @hooked develope_add_footer_info - 10
					 */
					do_action( 'develope_credits' );
					?>
				</div>
			</div>
		</footer><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'develope_footer_bar' ) ) {
	add_action( 'develope_before_copyright', 'develope_footer_bar', 15 );
	/**
	 * Build our footer bar
	 *
	 * @since 1.3.42
	 */
	function develope_footer_bar() {
		if ( ! is_active_sidebar( 'footer-bar' ) ) {
			return;
		}
		?>
		<div class="footer-bar">
			<?php dynamic_sidebar( 'footer-bar' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'develope_add_footer_info' ) ) {
	add_action( 'develope_credits', 'develope_add_footer_info' );
	/**
	 * Add the copyright to the footer
	 *
	 * @since 0.1
	 */
	function develope_add_footer_info() {
		$copyright = sprintf( '<span class="copyright">&copy; %1$s %2$s</span> &bull; %4$s <a href="%3$s" itemprop="url">%5$s</a>',
			date( 'Y' ),
			get_bloginfo( 'name' ),
			esc_url( 'https://wordpress.org' ),
			_x( 'Powered by', 'WordPress', 'developress' ),
			__( 'WordPress', 'developress' )
		);

		echo apply_filters( 'develope_copyright', $copyright ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Build our individual footer widgets.
 * Displays a sample widget if no widget is found in the area.
 *
 * @since 2.0
 *
 * @param int $widget_width The width class of our widget.
 * @param int $widget The ID of our widget.
 */
function develope_do_footer_widget( $widget_width, $widget ) {
	$widget_width = apply_filters( "develope_footer_widget_{$widget}_width", $widget_width );
	$tablet_widget_width = apply_filters( "develope_footer_widget_{$widget}_tablet_width", '50' );
	?>
	<div class="footer-widget-<?php echo absint( $widget ); ?> grid-parent grid-<?php echo absint( $widget_width ); ?> tablet-grid-<?php echo absint( $tablet_widget_width ); ?> mobile-grid-100">
		<?php dynamic_sidebar( 'footer-' . absint( $widget ) ); ?>
	</div>
	<?php
}

if ( ! function_exists( 'develope_construct_footer_widgets' ) ) {
	add_action( 'develope_footer', 'develope_construct_footer_widgets', 5 );
	/**
	 * Build our footer widgets.
	 *
	 * @since 1.3.42
	 */
	function develope_construct_footer_widgets() {
		// Get how many widgets to show.
		$widgets = develope_get_footer_widgets();

		if ( ! empty( $widgets ) && 0 !== $widgets ) :

			// If no footer widgets exist, we don't need to continue.
			if (
				! is_active_sidebar( 'footer-1' ) &&
				! is_active_sidebar( 'footer-2' ) &&
				! is_active_sidebar( 'footer-3' ) &&
				! is_active_sidebar( 'footer-4' ) &&
				! is_active_sidebar( 'footer-5' ) )
			{
				return;
			}

			// Set up the widget width.
			$widget_width = '';
			if ( $widgets == 1 ) {
				$widget_width = '100';
			}

			if ( $widgets == 2 ) {
				$widget_width = '50';
			}

			if ( $widgets == 3 ) {
				$widget_width = '33';
			}

			if ( $widgets == 4 ) {
				$widget_width = '25';
			}

			if ( $widgets == 5 ) {
				$widget_width = '20';
			}
			?>
			<div id="footer-widgets" class="site footer-widgets">
				<div <?php develope_do_element_classes( 'inside_footer' ); ?>>
					<div class="inside-footer-widgets">
						<?php
						if ( $widgets >= 1 ) {
							develope_do_footer_widget( $widget_width, 1 );
						}

						if ( $widgets >= 2 ) {
							develope_do_footer_widget( $widget_width, 2 );
						}

						if ( $widgets >= 3 ) {
							develope_do_footer_widget( $widget_width, 3 );
						}

						if ( $widgets >= 4 ) {
							develope_do_footer_widget( $widget_width, 4 );
						}

						if ( $widgets >= 5 ) {
							develope_do_footer_widget( $widget_width, 5 );
						}
						?>
					</div>
				</div>
			</div>
		<?php
		endif;

		/**
		 * develope_after_footer_widgets hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_after_footer_widgets' );
	}
}

if ( ! function_exists( 'develope_back_to_top' ) ) {
	add_action( 'develope_after_footer', 'develope_back_to_top' );
	/**
	 * Build the back to top button
	 *
	 * @since 1.3.24
	 */
	function develope_back_to_top() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		if ( 'enable' !== $develope_settings['back_to_top'] ) {
			return;
		}

		echo apply_filters( 'develope_back_to_top_output', sprintf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'<a title="%1$s" rel="nofollow" href="#" class="develope-back-to-top" style="opacity:0;visibility:hidden;" data-scroll-speed="%2$s" data-start-scroll="%3$s">
				<span class="screen-reader-text">%5$s</span>
				%6$s
			</a>',
			esc_attr__( 'Scroll back to top', 'developress' ),
			absint( apply_filters( 'develope_back_to_top_scroll_speed', 400 ) ),
			absint( apply_filters( 'develope_back_to_top_start_scroll', 300 ) ),
			esc_attr( apply_filters( 'develope_back_to_top_icon', 'fa-angle-up' ) ),
			esc_html__( 'Scroll back to top', 'developress' ),
			develope_get_svg_icon( 'arrow' )
		) );
	}
}
