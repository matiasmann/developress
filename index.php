<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				endwhile;

				/**
				 * develope_after_loop hook.
				 *
				 * @since 2.3
				 */
				do_action( 'develope_after_loop' );

				develope_content_nav( 'nav-below' );

			else :

				get_template_part( 'template-parts/no-results', 'index' );

			endif;

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
