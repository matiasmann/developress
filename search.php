<?php
/**
 * The template for displaying Search Results pages.
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

			if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						printf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							/* translators: 1: Search query name */
							__( 'Search Results for: %s', 'developress' ),
							'<span>' . get_search_query() . '</span>'
						);
						?>
					</h1>
				</header><!-- .page-header -->

				<?php while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'search' );

				endwhile;

				/**
				 * develope_after_loop hook.
				 *
				 * @since 2.3
				 */
				do_action( 'develope_after_loop' );

				develope_content_nav( 'nav-below' );

			else :

				get_template_part( 'template-parts/no-results', 'search' );

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
