<?php
/**
 * Comments element
 * Builds the comments section.
 *
 * @package DeveloPress
 * 
 * @since 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function develope_comment( $comment, $args, $depth ) {
		$args['avatar_size'] = apply_filters( 'develope_comment_avatar_size', 50 );

		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'developress' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'developress' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		<?php else : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body" <?php develope_do_microdata( 'comment-body' ); ?>>
				<footer class="comment-meta">
					<?php
					if ( 0 != $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<div class="comment-author-info">
						<div class="comment-author vcard" <?php develope_do_microdata( 'comment-author' ); ?>>
							<?php printf( '<cite itemprop="name" class="fn">%s</cite>', get_comment_author_link() ); ?>
						</div><!-- .comment-author -->

						<div class="entry-meta comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>" itemprop="datePublished">
									<?php printf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										/* translators: 1: date, 2: time */
										_x( '%1$s at %2$s', '1: date, 2: time', 'developress' ),
										get_comment_date(),
										get_comment_time()
									); ?>
								</time>
							</a>
							<?php edit_comment_link( __( 'Edit', 'developress' ), '<span class="edit-link">| ', '</span>' ); ?>
						</div><!-- .comment-metadata -->
					</div><!-- .comment-author-info -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'developress' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->

				<div class="comment-content" itemprop="text">
					<?php
					/**
					 * develope_before_comment_content hook.
					 *
					 * @since 2.4
					 *
					 */
					do_action( 'develope_before_comment_text', $comment, $args, $depth );

					comment_text();

					/**
					 * develope_after_comment_content hook.
					 *
					 * @since 2.4
					 *
					 */
					do_action( 'develope_after_comment_text', $comment, $args, $depth );
					?>
				</div><!-- .comment-content -->
			</article><!-- .comment-body -->
		<?php
		endif;
	}
}

add_action( 'develope_after_comment_text', 'develope_do_comment_reply_link', 10, 3 );
/**
 * Add our comment reply link after the comment text.
 *
 * @since 2.4
 */
function develope_do_comment_reply_link( $comment, $args, $depth ) {
	comment_reply_link( array_merge( $args, array(
		'add_below' => 'div-comment',
		'depth'     => $depth,
		'max_depth' => $args['max_depth'],
		'before'    => '<span class="reply">',
		'after'     => '</span>',
	) ) );
}

add_filter( 'comment_form_defaults', 'develope_set_comment_form_defaults' );
/**
 * Set the default settings for our comments.
 *
 * @since 2.3
 *
 * @param array $defaults
 * @return array
 */
function develope_set_comment_form_defaults( $defaults ) {
	$defaults['comment_field'] = sprintf(
		'<p class="comment-form-comment"><label for="comment" class="screen-reader-text">%1$s</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		esc_html__( 'Comment', 'developress' )
	);

	$defaults['comment_notes_before']	= null;
	$defaults['comment_notes_after']	= null;
	$defaults['id_form'] 				= 'commentform';
	$defaults['id_submit'] 				= 'submit';
	$defaults['title_reply'] 			= apply_filters( 'develope_leave_comment', __( 'Leave a Comment', 'developress' ) );
	$defaults['label_submit'] 			= apply_filters( 'develope_post_comment', __( 'Post Comment', 'developress' ) );

	return $defaults;
}

add_filter( 'comment_form_default_fields', 'develope_filter_comment_fields' );
/**
 * Customizes the existing comment fields.
 *
 * @since 2.1.2
 * @param array $fields
 * @return array
 */
function develope_filter_comment_fields( $fields ) {
	$commenter = wp_get_current_commenter();

	$fields['author'] = sprintf(
		'<label for="author" class="screen-reader-text">%1$s</label><input placeholder="%1$s *" id="author" name="author" type="text" value="%2$s" size="30" />',
		esc_html__( 'Name', 'developress' ),
		esc_attr( $commenter['comment_author'] )
	);

	$fields['email'] = sprintf(
		'<label for="email" class="screen-reader-text">%1$s</label><input placeholder="%1$s *" id="email" name="email" type="email" value="%2$s" size="30" />',
		esc_html__( 'Email', 'developress' ),
		esc_attr( $commenter['comment_author_email'] )
	);

	$fields['url'] = sprintf(
		'<label for="url" class="screen-reader-text">%1$s</label><input placeholder="%1$s" id="url" name="url" type="url" value="%2$s" size="30" />',
		esc_html__( 'Website', 'developress' ),
		esc_attr( $commenter['comment_author_url'] )
	);

	return $fields;
}
