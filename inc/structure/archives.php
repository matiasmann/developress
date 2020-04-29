<?php
/**
 * Archive elements.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_archive_title' ) ) {
	add_action( 'develope_archive_title', 'develope_archive_title' );
	/**
	 * Build the archive title
	 *
	 * @since 1.3.24
	 */
	function develope_archive_title() {
		if ( ! function_exists( 'the_archive_title' ) ) {
			return;
		}

		$clearfix = is_author() ? ' clearfix' : '';
		?>
		<header class="page-header<?php echo $clearfix; // phpcs:ignore Standard.Category.SniffName.ErrorCode. ?>">
			<?php
			/**
			 * develope_before_archive_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'develope_before_archive_title' );
			?>

			<h1 class="page-title">
				<?php the_archive_title(); ?>
			</h1>

			<?php
			/**
			 * develope_after_archive_title hook.
			 *
			 * @since 0.1
			 *
			 * @hooked develope_do_archive_description - 10
			 */
			do_action( 'develope_after_archive_title' );
			?>
		</header><!-- .page-header -->
		<?php
	}
}

if ( ! function_exists( 'develope_filter_the_archive_title' ) ) {
	add_filter( 'get_the_archive_title', 'develope_filter_the_archive_title' );
	/**
	 * Alter the_archive_title() function to match our original archive title function
	 *
	 * @since 1.3.45
	 *
	 * @param string $title The archive title
	 * @return string The altered archive title
	 */
	function develope_filter_the_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			/*
			 * Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			 */
			the_post();
			$title = sprintf( '%1$s<span class="vcard">%2$s</span>',
				get_avatar( get_the_author_meta( 'ID' ), 75 ),
				get_the_author()
			);
			/*
			 * Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
		}

		return $title;

	}
}

add_action( 'develope_after_archive_title', 'develope_do_archive_description' );
/**
 * Output the archive description.
 *
 * @since 2.3
 */
function develope_do_archive_description() {
	$term_description = term_description();

	if ( ! empty( $term_description ) ) {
		printf( '<div class="taxonomy-description">%s</div>', $term_description ); // phpcs:ignore Standard.Category.SniffName.ErrorCode.
	}

	if ( get_the_author_meta( 'description' ) && is_author() ) {
		echo '<div class="author-info">' . get_the_author_meta( 'description' ) . '</div>'; // phpcs:ignore Standard.Category.SniffName.ErrorCode.
	}

	/**
	 * develope_after_archive_description hook.
	 *
	 * @since 0.1
	 */
	do_action( 'develope_after_archive_description' );
}
