<?php
/**
 * The Template for displaying all single posts.
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

			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'single' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || '0' != get_comments_number() ) :
					/**
					 * develope_before_comments_container hook.
					 *
					 * @since 2.1
					 */
					do_action( 'develope_before_comments_container' );
					?>

					<div class="comments-area">
						<?php comments_template(); ?>
					</div>

					<?php
				endif;

			endwhile;

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
