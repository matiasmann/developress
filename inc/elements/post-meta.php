<?php
/**
 * Post meta element.
 * Builds the Post Meta section.
 *
 * @package DeveloPress
 * 
 * @since 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable.
	 *
	 * @since 0.1
	 *
	 * @param string $nav_id The id of our navigation.
	 */
	function develope_content_nav( $nav_id ) {
		if ( ! apply_filters( 'develope_show_post_navigation', true ) ) {
			return;
		}

		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
		?>
		<nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'developress' ); ?></span>

			<?php if ( is_single() ) : // navigation links for single posts.

				$post_navigation_args = apply_filters( 'develope_post_navigation_args', array(
					'previous_format' => '<div class="nav-previous">' . develope_get_svg_icon( 'arrow' ) . '<span class="prev" title="' . esc_attr__( 'Previous', 'developress' ) . '">%link</span></div>',
					'next_format' => '<div class="nav-next">' . develope_get_svg_icon( 'arrow' ) . '<span class="next" title="' . esc_attr__( 'Next', 'developress' ) . '">%link</span></div>',
					'link' => '%title',
					'in_same_term' => apply_filters( 'develope_category_post_navigation', false ),
					'excluded_terms' => '',
					'taxonomy' => 'category',
				) );

				previous_post_link(
					$post_navigation_args['previous_format'],
					$post_navigation_args['link'],
					$post_navigation_args['in_same_term'],
					$post_navigation_args['excluded_terms'],
					$post_navigation_args['taxonomy']
				);

				next_post_link(
					$post_navigation_args['next_format'],
					$post_navigation_args['link'],
					$post_navigation_args['in_same_term'],
					$post_navigation_args['excluded_terms'],
					$post_navigation_args['taxonomy']
				);

			elseif ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages.

				if ( get_next_posts_link() ) : ?>
					<div class="nav-previous">
						<?php develope_do_svg_icon( 'arrow' ); ?>
						<span class="prev" title="<?php esc_attr_e( 'Previous', 'developress' );?>"><?php next_posts_link( __( 'Older posts', 'developress' ) ); ?></span>
					</div>
				<?php endif;

				if ( get_previous_posts_link() ) : ?>
					<div class="nav-next">
						<?php develope_do_svg_icon( 'arrow' ); ?>
						<span class="next" title="<?php esc_attr_e( 'Next', 'developress' );?>"><?php previous_posts_link( __( 'Newer posts', 'developress' ) ); ?></span>
					</div>
				<?php endif;

				if ( function_exists( 'the_posts_pagination' ) ) {
					the_posts_pagination( array(
						'mid_size' => apply_filters( 'develope_pagination_mid_size', 1 ),
						'prev_text' => apply_filters( 'develope_previous_link_text', __( '&larr; Previous', 'developress' ) ),
						'next_text' => apply_filters( 'develope_next_link_text', __( 'Next &rarr;', 'developress' ) ),
					) );
				}

				/**
				 * develope_paging_navigation hook.
				 *
				 * @since 0.1
				 */
				do_action( 'develope_paging_navigation' );

			endif; ?>
		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
		<?php
	}
}

if ( ! function_exists( 'develope_modify_posts_pagination_template' ) ) {
	add_filter( 'navigation_markup_template', 'develope_modify_posts_pagination_template', 10, 2 );
	/**
	 * Remove the container and screen reader text from the_posts_pagination()
	 * We add this in ourselves in develope_content_nav()
	 *
	 * @since 1.3.45
	 *
	 * @param string $template The default template.
	 * @param string $class The class passed by the calling function.
	 * @return string The HTML for the post navigation.
	 */
	function develope_modify_posts_pagination_template( $template, $class ) {
		if ( ! empty( $class ) && false !== strpos( $class, 'pagination' ) ) {
			$template = '<div class="nav-links">%3$s</div>';
		}

		return $template;
	}
}

/**
 * Output requested post meta.
 *
 * @since 2.3
 *
 * @param string $item The post meta item we're requesting
 * @return The requested HTML.
 */
function develope_do_post_meta_item( $item ) {
	if ( 'date' === $item ) {
		$date = apply_filters( 'develope_post_date', true );

		$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>' . $time_string;
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		// If our date is enabled, show it.
		if ( $date ) {
			echo apply_filters( 'develope_post_date_output',
				sprintf( // phpcs:ignore Standard.Category.SniffName.ErrorCode.
					'<span class="posted-on">%1$s<a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></span> ',
						apply_filters( 'develope_inside_post_meta_item_output', '', 'date' ),
						esc_url( get_permalink() ),
						esc_attr( get_the_time() ),
						$time_string
				),
			$time_string );
		}
	}

	if ( 'author' === $item ) {
		$author = apply_filters( 'develope_post_author', true );

		if ( $author ) {
			echo apply_filters( 'develope_post_author_output',
				sprintf( '<span class="byline">%1$s<span class="author vcard" %5$s><a class="url fn n" href="%2$s" title="%3$s" rel="author" itemprop="url"><span class="author-name" itemprop="name">%4$s</span></a></span></span> ',
					apply_filters( 'develope_inside_post_meta_item_output', '', 'author' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					/* translators: 1: Author name */
					esc_attr( sprintf( __( 'View all posts by %s', 'developress' ), get_the_author() ) ),
					esc_html( get_the_author() ),
					develope_get_microdata( 'post-author' )
				)
			);
		}
	}

	if ( 'categories' === $item ) {
		$categories = apply_filters( 'develope_show_categories', true );

		$term_separator = apply_filters( 'develope_term_separator', _x( ', ', 'Used between list items, there is a space after the comma.', 'developress' ), 'categories' );
		$categories_list = get_the_category_list( $term_separator );

		if ( $categories_list && $categories ) {
			echo apply_filters( 'develope_category_list_output',
				sprintf( '<span class="cat-links">%3$s<span class="screen-reader-text">%1$s </span>%2$s</span> ', // phpcs:ignore Standard.Category.SniffName.ErrorCode.
					esc_html_x( 'Categories', 'Used before category names.', 'developress' ),
					$categories_list,
					apply_filters( 'develope_inside_post_meta_item_output', '', 'categories' )
				)
			);
		}
	}

	if ( 'tags' === $item ) {
		$tags = apply_filters( 'develope_show_tags', true );

		$term_separator = apply_filters( 'develope_term_separator', _x( ', ', 'Used between list items, there is a space after the comma.', 'developress' ), 'tags' );
		$tags_list = get_the_tag_list( '', $term_separator );

		if ( $tags_list && $tags ) {
			echo apply_filters( 'develope_tag_list_output',
				sprintf( '<span class="tags-links">%3$s<span class="screen-reader-text">%1$s </span>%2$s</span> ', // phpcs:ignore Standard.Category.SniffName.ErrorCode.
					esc_html_x( 'Tags', 'Used before tag names.', 'developress' ),
					$tags_list,
					apply_filters( 'develope_inside_post_meta_item_output', '', 'tags' )
				)
			);
		}
	}

	if ( 'comments-link' === $item ) {
		$comments = apply_filters( 'develope_show_comments', true );

		if ( $comments && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
				echo apply_filters( 'develope_inside_post_meta_item_output', '', 'comments-link' );
				comments_popup_link( __( 'Leave a comment', 'developress' ), __( '1 Comment', 'developress' ), __( '% Comments', 'developress' ) );
			echo '</span> ';
		}
	}

	/**
	 * develope_post_meta_items hook.
	 *
	 * @since 2.4
	 */
	do_action( 'develope_post_meta_items', $item );
}

add_filter( 'develope_inside_post_meta_item_output', 'develope_do_post_meta_prefix', 10, 2 );
/**
 * Add svg icons or text to our post meta output.
 *
 * @since 2.4
 */
function develope_do_post_meta_prefix( $output, $item ) {
	if ( 'author' === $item ) {
		$output = __( 'by', 'developress' ) . ' ';
	}

	if ( 'categories' === $item ) {
		$output = develope_get_svg_icon( 'categories' );
	}

	if ( 'tags' === $item ) {
		$output = develope_get_svg_icon( 'tags' );
	}

	if ( 'comments-link' === $item ) {
		$output = develope_get_svg_icon( 'comments' );
	}

	return $output;
}

if ( ! function_exists( 'develope_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 0.1
	 */
	function develope_posted_on() {
		$items = apply_filters( 'develope_header_entry_meta_items', array(
			'date',
			'author',
		) );

		foreach ( $items as $item ) {
			develope_do_post_meta_item( $item );
		}
	}
}

if ( ! function_exists( 'develope_entry_meta' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * @since 1.2.5
	 */
	function develope_entry_meta() {
		$items = apply_filters( 'develope_footer_entry_meta_items', array(
			'categories',
			'tags',
			'comments-link',
		) );

		foreach ( $items as $item ) {
			develope_do_post_meta_item( $item );
		}
	}
}

if ( ! function_exists( 'develope_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'develope_excerpt_more' );
	/**
	 * Prints the read more HTML to post excerpts.
	 *
	 * @since 0.1
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link.
	 */
	function develope_excerpt_more( $more ) {
		return apply_filters( 'develope_excerpt_more_output', sprintf( ' ... <a title="%1$s" class="read-more" href="%2$s">%3$s %4$s</a>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) ),
			__( 'Read more', 'developress' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		) );
	}
}

if ( ! function_exists( 'develope_content_more' ) ) {
	add_filter( 'the_content_more_link', 'develope_content_more' );
	/**
	 * Prints the read more HTML to post content using the more tag.
	 *
	 * @since 0.1
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link
	 */
	function develope_content_more( $more ) {
		return apply_filters( 'develope_content_more_link_output', sprintf( '<p class="read-more-container"><a title="%1$s" class="read-more content-read-more" href="%2$s">%3$s%4$s</a></p>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) . apply_filters( 'develope_more_jump','#more-' . get_the_ID() ) ),
			__( 'Read more', 'developress' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		) );
	}
}

if ( ! function_exists( 'develope_post_meta' ) ) {
	add_action( 'develope_after_entry_title', 'develope_post_meta' );
	/**
	 * Build the post meta.
	 *
	 * @since 1.3.29
	 */
	function develope_post_meta() {
		$post_types = apply_filters( 'develope_entry_meta_post_types', array(
			'post',
		) );

		if ( in_array( get_post_type(), $post_types ) ) : ?>
			<div class="entry-meta">
				<?php develope_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif;
	}
}

if ( ! function_exists( 'develope_footer_meta' ) ) {
	add_action( 'develope_after_entry_content', 'develope_footer_meta' );
	/**
	 * Build the footer post meta.
	 *
	 * @since 1.3.30
	 */
	function develope_footer_meta() {
		$post_types = apply_filters( 'develope_footer_meta_post_types', array(
			'post',
		) );

		if ( in_array( get_post_type(), $post_types ) ) : ?>
			<footer class="entry-meta">
				<?php
				develope_entry_meta();

				if ( is_single() ) {
					develope_content_nav( 'nav-below' );
				}
				?>
			</footer><!-- .entry-meta -->
		<?php endif;
	}
}