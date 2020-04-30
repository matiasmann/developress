<?php
/**
 * The template for displaying single posts.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php develope_do_microdata( 'article' ); ?>>
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
			<?php
			/**
			 * develope_before_entry_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'develope_before_entry_title' );

			if ( develope_show_title() ) {
				the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
			}

			/**
			 * develope_after_entry_title hook.
			 *
			 * @since 0.1
			 *
			 * @hooked develope_post_meta - 10
			 */
			do_action( 'develope_after_entry_title' );
			?>
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
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'developress' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

		<?php
		/**
		 * develope_after_entry_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked develope_footer_meta - 10
		 */
		do_action( 'develope_after_entry_content' );

		/**
		 * develope_after_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
