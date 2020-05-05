<?php
/**
 * Third Party Plugins Compatibility
 * WooCommerce
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'after_setup_theme', 'develope_setup_woocommerce' );
/**
 * Set up WooCommerce
 *
 * @since 2.0.6
 */
function develope_setup_woocommerce() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Add support for WC features.
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Remove default WooCommerce wrappers.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_sidebar', 'develope_construct_sidebars' );
}

if ( ! function_exists( 'develope_woocommerce_start' ) ) {
	add_action( 'woocommerce_before_main_content', 'develope_woocommerce_start', 10 );
	/**
	 * Add WooCommerce starting wrappers
	 *
	 * @since 2.0.6
	 */
	function develope_woocommerce_start() {
		?>
		<div id="primary" <?php develope_do_element_classes( 'content' );?>>
			<main id="main" <?php develope_do_element_classes( 'main' ); ?>>
				<?php
				/**
				 * develope_before_main_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'develope_before_main_content' );
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
						<div class="entry-content" itemprop="text">
	<?php
	}
}

if ( ! function_exists( 'develope_woocommerce_end' ) ) {
	add_action( 'woocommerce_after_main_content', 'develope_woocommerce_end', 10 );
	/**
	 * Add WooCommerce ending wrappers
	 *
	 * @since 1.3.22
	 */
	function develope_woocommerce_end() {
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
				</article><!-- #post-## -->
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
	}
}

if ( ! function_exists( 'develope_woocommerce_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'develope_woocommerce_css', 100 );
	/**
	 * Add WooCommerce CSS
	 *
	 * @since 2.0.6
	 */
	function develope_woocommerce_css() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$mobile = develope_get_media_query( 'mobile' );

		$css = '.woocommerce .page-header-image-single {
			display: none;
		}

		.woocommerce .entry-content,
		.woocommerce .product .entry-summary {
			margin-top: 0;
		}

		.related.products {
			clear: both;
		}

		.checkout-subscribe-prompt.clear {
			visibility: visible;
			height: initial;
			width: initial;
		}

		@media ' . esc_attr( $mobile ) . ' {
			.woocommerce .woocommerce-ordering,
			.woocommerce-page .woocommerce-ordering {
				float: none;
			}

			.woocommerce .woocommerce-ordering select {
				max-width: 100%;
			}

			.woocommerce ul.products li.product,
			.woocommerce-page ul.products li.product,
			.woocommerce-page[class*=columns-] ul.products li.product,
			.woocommerce[class*=columns-] ul.products li.product {
				width: 100%;
				float: none;
			}
		}';

		$css = str_replace( array( "\r", "\n", "\t" ), '', $css );
		wp_add_inline_style( 'woocommerce-general', $css );
	}
}
