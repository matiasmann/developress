<?php
/**
 * Header element
 * Builds the header section.
 *
 * @package DeveloPress
 * 
 * @since 2.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_construct_header' ) ) {
	add_action( 'develope_header', 'develope_construct_header' );
	/**
	 * Build the header.
	 *
	 * @since 1.3.42
	 */
	function develope_construct_header() {
		?>
		<header id="masthead" <?php develope_do_element_classes( 'header' ); ?> <?php develope_do_microdata( 'header' ); ?>>
			<div <?php develope_do_element_classes( 'inside_header' ); ?>>
				<?php
				/**
				 * develope_before_header_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'develope_before_header_content' );

				// Add our main header items.
				develope_header_items();

				/**
				 * develope_after_header_content hook.
				 *
				 * @since 0.1
				 *
				 * @hooked develope_add_navigation_float_right - 5
				 */
				do_action( 'develope_after_header_content' );
				?>
			</div><!-- .inside-header -->
		</header><!-- #masthead -->
		<?php
	}
}

if ( ! function_exists( 'develope_header_items' ) ) {
	/**
	 * Build the header contents.
	 * Wrapping this into a function allows us to customize the order.
	 *
	 * @since 1.2.9.7
	 */
	function develope_header_items() {
		$order = apply_filters( 'develope_header_items_order',
			array(
				'header-widget',
				'site-branding',
				'logo',
			)
		);

		foreach ( $order as $item ) {
			if ( 'header-widget' === $item ) {
				develope_construct_header_widget();
			}

			if ( 'site-branding' === $item ) {
				develope_construct_site_title();
			}

			if ( 'logo' === $item ) {
				develope_construct_logo();
			}
		}
	}
}

if ( ! function_exists( 'develope_construct_logo' ) ) {
	/**
	 * Build the logo
	 *
	 * @since 1.3.28
	 */
	function develope_construct_logo() {
		$logo_url = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
		$logo_url = ( $logo_url ) ? $logo_url[0] : develope_get_option( 'logo' );

		$logo_url = esc_url( apply_filters( 'develope_logo', $logo_url ) );
		$retina_logo_url = esc_url( apply_filters( 'develope_retina_logo', develope_get_option( 'retina_logo' ) ) );

		// If we don't have a logo, bail.
		if ( empty( $logo_url ) ) {
			return;
		}

		/**
		 * develope_before_logo hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_before_logo' );

		$attr = apply_filters( 'develope_logo_attributes', array(
			'class' => 'header-image',
			'alt'	=> esc_attr( apply_filters( 'develope_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			'src'	=> $logo_url,
			'title'	=> esc_attr( apply_filters( 'develope_logo_title', get_bloginfo( 'name', 'display' ) ) ),
		) );

		if ( '' !== $retina_logo_url ) {
			$attr['srcset'] = $logo_url . ' 1x, ' . $retina_logo_url . ' 2x';

			// Add dimensions to image if retina is set. This fixes a container width bug in Firefox.
			if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) {
				$data = wp_get_attachment_metadata( get_theme_mod( 'custom_logo' ) );

				if ( ! empty( $data ) ) {
					$attr['width'] = $data['width'];
					$attr['height'] = $data['height'];
				}
			}
		}

		$attr = array_map( 'esc_attr', $attr );

		$html_attr = '';
		foreach ( $attr as $name => $value ) {
			$html_attr .= " $name=" . '"' . $value . '"';
		}

		// Print our HTML.
		echo apply_filters( 'develope_logo_output', sprintf( // phpcs:ignore Standard.Category.SniffName.ErrorCode.
			'<div class="site-logo">
				<a href="%1$s" title="%2$s" rel="home">
					<img %3$s />
				</a>
			</div>',
			esc_url( apply_filters( 'develope_logo_href' , home_url( '/' ) ) ),
			esc_attr( apply_filters( 'develope_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			$html_attr
		), $logo_url, $html_attr );

		/**
		 * develope_after_logo hook.
		 *
		 * @since 0.1
		 */
		do_action( 'develope_after_logo' );
	}
}

if ( ! function_exists( 'develope_construct_site_title' ) ) {
	/**
	 * Build the site title and tagline.
	 *
	 * @since 1.3.28
	 */
	function develope_construct_site_title() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		// Get the title and tagline.
		$title = get_bloginfo( 'title' );
		$tagline = get_bloginfo( 'description' );

		// If the disable title checkbox is checked, or the title field is empty, return true.
		$disable_title = ( '1' == $develope_settings['hide_title'] || '' == $title ) ? true : false;

		// If the disable tagline checkbox is checked, or the tagline field is empty, return true.
		$disable_tagline = ( '1' == $develope_settings['hide_tagline'] || '' == $tagline ) ? true : false;

		// Build our site title.
		$site_title = apply_filters( 'develope_site_title_output', sprintf(
			'<%1$s class="main-title" itemprop="headline">
				<a href="%2$s" rel="home">
					%3$s
				</a>
			</%1$s>',
			( is_front_page() && is_home() ) ? 'h1' : 'p',
			esc_url( apply_filters( 'develope_site_title_href', home_url( '/' ) ) ),
			get_bloginfo( 'name' )
		) );

		// Build our tagline.
		$site_tagline = apply_filters( 'develope_site_description_output', sprintf(
			'<p class="site-description" itemprop="description">
				%1$s
			</p>',
			html_entity_decode( get_bloginfo( 'description', 'display' ) )
		) );

		// Site title and tagline.
		if ( false == $disable_title || false == $disable_tagline ) {
			if ( develope_get_option( 'inline_logo_site_branding' ) && develope_has_logo_site_branding() ) {
				echo '<div class="site-branding-container">';
				develope_construct_logo();
			}

			echo apply_filters( 'develope_site_branding_output', sprintf( // phpcs:ignore Standard.Category.SniffName.ErrorCode.
				'<div class="site-branding">
					%1$s
					%2$s
				</div>',
				( ! $disable_title ) ? $site_title : '',
				( ! $disable_tagline ) ? $site_tagline : ''
			) );

			if ( develope_get_option( 'inline_logo_site_branding' ) && develope_has_logo_site_branding() ) {
				echo '</div><!-- .site-branding-container -->';
			}
		}
	}
}

add_filter( 'develope_header_items_order', 'develope_reorder_inline_site_branding' );
/**
 * Remove the logo from it's usual position.
 *
 * @since 2.3
 */
function develope_reorder_inline_site_branding( $order ) {
	if ( ! develope_get_option( 'inline_logo_site_branding' ) || ! develope_has_logo_site_branding() ) {
		return $order;
	}

	return array(
		'header-widget',
		'site-branding',
	);
}

if ( ! function_exists( 'develope_construct_header_widget' ) ) {
	/**
	 * Build the header widget.
	 *
	 * @since 1.3.28
	 */
	function develope_construct_header_widget() {
		if ( is_active_sidebar( 'header' ) ) : ?>
			<div class="header-widget">
				<?php dynamic_sidebar( 'header' ); ?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'develope_top_bar' ) ) {
	add_action( 'develope_before_header', 'develope_top_bar', 5 );
	/**
	 * Build our top bar.
	 *
	 * @since 1.3.45
	 */
	function develope_top_bar() {
		if ( ! is_active_sidebar( 'top-bar' ) ) {
			return;
		}
		?>
		<div <?php develope_do_element_classes( 'top_bar' ); ?>>
			<div class="inside-top-bar<?php if ( 'contained' == develope_get_option( 'top_bar_inner_width' ) ) echo ' grid-container grid-parent'; ?>">
				<?php dynamic_sidebar( 'top-bar' ); ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'develope_pingback_header' ) ) {
	add_action( 'wp_head', 'develope_pingback_header' );
	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 *
	 * @since 1.3.42
	 */
	function develope_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

if ( ! function_exists( 'develope_add_viewport' ) ) {
	add_action( 'wp_head', 'develope_add_viewport' );
	/**
	 * Add viewport to wp_head.
	 *
	 * @since 1.1.0
	 */
	function develope_add_viewport() {
		echo apply_filters( 'develope_meta_viewport', '<meta name="viewport" content="width=device-width, initial-scale=1">' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'develope_before_header', 'develope_do_skip_to_content_link', 2 );
/**
 * Add skip to content link before the header.
 *
 * @since 2.0
 */
function develope_do_skip_to_content_link() {
	printf( '<a class="screen-reader-text skip-link" href="#content" title="%1$s">%2$s</a>',
		esc_attr__( 'Skip to content', 'developress' ),
		esc_html__( 'Skip to content', 'developress' )
	);
}
