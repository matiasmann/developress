<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php develope_do_element_classes( 'content' ); ?>>
		<main id="main" <?php develope_do_element_classes( 'main' ); ?>>
			<?php
			/**
			 * develope_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'develope_before_main_content' );
			?>

			<div class="inside-article">

				<?php
				/**
				 * develope_before_content hook.
				 *
				 * @since 0.1
				 *
				 * @hooked develope_featured_page_header_inside_single - 10
				 */
				do_action( 'develope_before_content' );
				?>

				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline"><?php echo apply_filters( 'develope_404_title', __( 'Oops! That page can&rsquo;t be found.', 'developress' ) ); // WPCS: XSS OK. ?></h1>
				</header><!-- .entry-header -->

				<?php
				/**
				 * develope_after_entry_header hook.
				 *
				 * @since 0.1
				 *
				 * @hooked develope_post_image - 10
				 */
				do_action( 'develope_after_entry_header' );
				?>

				<div class="entry-content" itemprop="text">
					<?php
					echo '<p>' . apply_filters( 'develope_404_text', __( 'It looks like nothing was found at this location. Maybe try searching?', 'developress' ) ) . '</p>'; // WPCS: XSS OK.

					get_search_form();
					?>
				</div><!-- .entry-content -->

				<?php
				/**
				 * develope_after_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'develope_after_content' );
				?>

			</div><!-- .inside-article -->

			<?php
			/**
			 * develope_after_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'develope_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	/**
	 * develope_after_primary_content_area hook.
	 *
	 * @since 2.0
	 */
	do_action( 'develope_after_primary_content_area' );

	develope_construct_sidebars();

get_footer();
