<?php
/**
 * Output all of our dynamic CSS.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_base_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 0.1
	 */
	function develope_base_css() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_general_defaults()
		);

		$css = new DeveloPress_CSS;

		$css->set_selector( 'body' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['background_color'] ) );
		$css->add_property( 'color', esc_attr( $develope_settings['text_color'] ) );

		$css->set_selector( 'a, a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['link_color'] ) );

		$css->set_selector( 'a:visited' )->add_property( 'color', esc_attr( $develope_settings['link_color_visited'] ) );

		$css->set_selector( 'a:hover, a:focus, a:active' );
		$css->add_property( 'color', esc_attr( $develope_settings['link_color_hover'] ) );

		$css->set_selector( 'body .grid-container' )->add_property( 'max-width', absint( $develope_settings['container_width'] ), false, 'px' );

		if ( apply_filters( 'develope_do_group_inner_container_style', true ) ) {
			$css->set_selector( '.wp-block-group__inner-container' );
			$css->add_property( 'max-width', absint( $develope_settings['container_width'] ), false, 'px' );
			$css->add_property( 'margin-left', 'auto' );
			$css->add_property( 'margin-right', 'auto' );
		}

		$nav_drop_point = develope_get_option( 'nav_drop_point' );
		$nav_location = develope_get_navigation_location();

		if ( ( 'nav-float-right' === $nav_location || 'nav-float-left' === $nav_location ) && $nav_drop_point ) {
			$media_query = sprintf(
				'(max-width: %1$s) and %2$s',
				absint( $nav_drop_point ) . 'px',
				apply_filters( 'develope_not_mobile_menu_media_query', '(min-width: 769px)' )
			);

			$css->start_media_query( $media_query );
				$css->set_selector( '.inside-header' );
				$css->add_property( 'display', '-ms-flexbox' );
				$css->add_property( 'display', 'flex' );

				$css->add_property( '-ms-flex-direction', 'column' );
				$css->add_property( 'flex-direction', 'column' );

				$css->add_property( '-ms-flex-align', 'center' );
				$css->add_property( 'align-items', 'center' );

				$css->set_selector( '.site-logo, .site-branding' );
				$css->add_property( 'margin-bottom', '1.5em' );

				$css->set_selector( '#site-navigation' );
				$css->add_property( 'margin', '0 auto' );

				$css->set_selector( '.header-widget' );
				$css->add_property( 'margin-top', '1.5em' );

				if ( 'nav-float-left' === develope_get_option( 'nav_position_setting' ) ) {
					$css->set_selector( '.nav-float-left .site-logo,.nav-float-left .site-branding,.nav-float-left .header-widget' );
					$css->add_property( '-webkit-box-ordinal-group', 'initial' );
					$css->add_property( '-ms-flex-order', 'initial' );
					$css->add_property( 'order', 'initial' );
				}
			$css->stop_media_query();
		}

		if ( develope_get_option( 'logo_width' ) ) {
			$css->set_selector( '.site-header .header-image' );
			$css->add_property( 'width', absint( develope_get_option( 'logo_width' ) ), false, 'px' );
		}

		do_action( 'develope_base_css', $css );

		return apply_filters( 'develope_base_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'develope_advanced_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 0.1
	 */
	function develope_advanced_css() {
		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_color_defaults()
		);

		$css = new DeveloPress_CSS;

		$css->set_selector( '.top-bar' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['top_bar_background_color'] ) );
		$css->add_property( 'color', esc_attr( $develope_settings['top_bar_text_color'] ) );

		$css->set_selector( '.top-bar a,.top-bar a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['top_bar_link_color'] ) );

		$css->set_selector( '.top-bar a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['top_bar_link_color_hover'] ) );

		$css->set_selector( '.site-header' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['header_background_color'] ) );
		$css->add_property( 'color', esc_attr( $develope_settings['header_text_color'] ) );

		$css->set_selector( '.site-header a,.site-header a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['header_link_color'] ) );

		$css->set_selector( '.site-header a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['header_link_hover_color'] ) );

		$css->set_selector( '.main-title a,.main-title a:hover,.main-title a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['site_title_color'] ) );

		$css->set_selector( '.site-description' );
		$css->add_property( 'color', esc_attr( $develope_settings['site_tagline_color'] ) );

		$css->set_selector( '.main-navigation,.main-navigation ul ul' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['navigation_background_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul li a,.menu-toggle' );
		$css->add_property( 'color', esc_attr( $develope_settings['navigation_text_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul li:hover > a,.main-navigation .main-nav ul li:focus > a, .main-navigation .main-nav ul li.sfHover > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['navigation_text_hover_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['navigation_background_hover_color'] ) );

		$css->set_selector( 'button.menu-toggle:hover,button.menu-toggle:focus,.main-navigation .mobile-bar-items a,.main-navigation .mobile-bar-items a:hover,.main-navigation .mobile-bar-items a:focus' );
		$css->add_property( 'color', esc_attr( $develope_settings['navigation_text_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul li[class*="current-menu-"] > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['navigation_text_current_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['navigation_background_current_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul li[class*="current-menu-"] > a:hover,.main-navigation .main-nav ul li[class*="current-menu-"].sfHover > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['navigation_text_current_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['navigation_background_current_color'] ) );

		$navigation_search_background = $develope_settings['navigation_background_hover_color'];
		$navigation_search_text = $develope_settings['navigation_text_hover_color'];

		if ( '' !== $develope_settings['navigation_search_background_color'] ) {
			$navigation_search_background = $develope_settings['navigation_search_background_color'];
		}

		if ( '' !== $develope_settings['navigation_search_text_color'] ) {
			$navigation_search_text = $develope_settings['navigation_search_text_color'];
		}

		$css->set_selector( '.navigation-search input[type="search"],.navigation-search input[type="search"]:active, .navigation-search input[type="search"]:focus, .main-navigation .main-nav ul li.search-item.active > a' );
		$css->add_property( 'color', esc_attr( $navigation_search_text ) );
		$css->add_property( 'background-color', esc_attr( $navigation_search_background ) );

		if ( '' !== $develope_settings['navigation_search_background_color'] ) {
			$css->add_property( 'opacity', '1' );
		}

		$css->set_selector( '.main-navigation ul ul' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['subnavigation_background_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul ul li a' );
		$css->add_property( 'color', esc_attr( $develope_settings['subnavigation_text_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul ul li:hover > a,.main-navigation .main-nav ul ul li:focus > a,.main-navigation .main-nav ul ul li.sfHover > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['subnavigation_text_hover_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['subnavigation_background_hover_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul ul li[class*="current-menu-"] > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['subnavigation_text_current_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['subnavigation_background_current_color'] ) );

		$css->set_selector( '.main-navigation .main-nav ul ul li[class*="current-menu-"] > a:hover,.main-navigation .main-nav ul ul li[class*="current-menu-"].sfHover > a' );
		$css->add_property( 'color', esc_attr( $develope_settings['subnavigation_text_current_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['subnavigation_background_current_color'] ) );

		$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .one-container .container, .separate-containers .paging-navigation, .inside-page-header' );
		$css->add_property( 'color', esc_attr( $develope_settings['content_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['content_background_color'] ) );

		$css->set_selector( '.inside-article a,.inside-article a:visited,.paging-navigation a,.paging-navigation a:visited,.comments-area a,.comments-area a:visited,.page-header a,.page-header a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['content_link_color'] ) );

		$css->set_selector( '.inside-article a:hover,.paging-navigation a:hover,.comments-area a:hover,.page-header a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['content_link_hover_color'] ) );

		$css->set_selector( '.entry-header h1,.page-header h1' );
		$css->add_property( 'color', esc_attr( $develope_settings['content_title_color'] ) );

		$css->set_selector( '.entry-title a,.entry-title a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['blog_post_title_color'] ) );

		$css->set_selector( '.entry-title a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['blog_post_title_hover_color'] ) );

		$css->set_selector( '.entry-meta' );
		$css->add_property( 'color', esc_attr( $develope_settings['entry_meta_text_color'] ) );

		$css->set_selector( '.entry-meta a,.entry-meta a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['entry_meta_link_color'] ) );

		$css->set_selector( '.entry-meta a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['entry_meta_link_color_hover'] ) );

		$css->set_selector( 'h1' );
		$css->add_property( 'color', esc_attr( $develope_settings['h1_color'] ) );

		$css->set_selector( 'h2' );
		$css->add_property( 'color', esc_attr( $develope_settings['h2_color'] ) );

		$css->set_selector( 'h3' );
		$css->add_property( 'color', esc_attr( $develope_settings['h3_color'] ) );

		$css->set_selector( 'h4' );
		$css->add_property( 'color', esc_attr( $develope_settings['h4_color'] ) );

		$css->set_selector( 'h5' );
		$css->add_property( 'color', esc_attr( $develope_settings['h5_color'] ) );

		$css->set_selector( 'h6' );
		$css->add_property( 'color', esc_attr( $develope_settings['h6_color'] ) );

		$css->set_selector( '.sidebar .widget' );
		$css->add_property( 'color', esc_attr( $develope_settings['sidebar_widget_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['sidebar_widget_background_color'] ) );

		$css->set_selector( '.sidebar .widget a,.sidebar .widget a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['sidebar_widget_link_color'] ) );

		$css->set_selector( '.sidebar .widget a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['sidebar_widget_link_hover_color'] ) );

		$css->set_selector( '.sidebar .widget .widget-title' );
		$css->add_property( 'color', esc_attr( $develope_settings['sidebar_widget_title_color'] ) );

		$css->set_selector( '.footer-widgets' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_widget_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['footer_widget_background_color'] ) );

		$css->set_selector( '.footer-widgets a,.footer-widgets a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_widget_link_color'] ) );

		$css->set_selector( '.footer-widgets a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_widget_link_hover_color'] ) );

		$css->set_selector( '.footer-widgets .widget-title' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_widget_title_color'] ) );

		$css->set_selector( '.site-info' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['footer_background_color'] ) );

		$css->set_selector( '.site-info a,.site-info a:visited' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_link_color'] ) );

		$css->set_selector( '.site-info a:hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_link_hover_color'] ) );

		$css->set_selector( '.footer-bar .widget_nav_menu .current-menu-item a' );
		$css->add_property( 'color', esc_attr( $develope_settings['footer_link_hover_color'] ) );

		$css->set_selector( 'input[type="text"],input[type="email"],input[type="url"],input[type="password"],input[type="search"],input[type="tel"],input[type="number"],textarea,select' );
		$css->add_property( 'color', esc_attr( $develope_settings['form_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['form_background_color'] ) );
		$css->add_property( 'border-color', esc_attr( $develope_settings['form_border_color'] ) );

		$css->set_selector( 'input[type="text"]:focus,input[type="email"]:focus,input[type="url"]:focus,input[type="password"]:focus,input[type="search"]:focus,input[type="tel"]:focus,input[type="number"]:focus,textarea:focus,select:focus' );
		$css->add_property( 'color', esc_attr( $develope_settings['form_text_color_focus'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['form_background_color_focus'] ) );
		$css->add_property( 'border-color', esc_attr( $develope_settings['form_border_color_focus'] ) );

		$css->set_selector( 'button,html input[type="button"],input[type="reset"],input[type="submit"],a.button,a.button:visited,a.wp-block-button__link:not(.has-background)' );
		$css->add_property( 'color', esc_attr( $develope_settings['form_button_text_color'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['form_button_background_color'] ) );

		$css->set_selector( 'button:hover,html input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,a.button:hover,button:focus,html input[type="button"]:focus,input[type="reset"]:focus,input[type="submit"]:focus,a.button:focus,a.wp-block-button__link:not(.has-background):active,a.wp-block-button__link:not(.has-background):focus,a.wp-block-button__link:not(.has-background):hover' );
		$css->add_property( 'color', esc_attr( $develope_settings['form_button_text_color_hover'] ) );
		$css->add_property( 'background-color', esc_attr( $develope_settings['form_button_background_color_hover'] ) );

		$css->set_selector( '.develope-back-to-top,.develope-back-to-top:visited' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['back_to_top_background_color'] ) );
		$css->add_property( 'color', esc_attr( $develope_settings['back_to_top_text_color'] ) );

		$css->set_selector( '.develope-back-to-top:hover,.develope-back-to-top:focus' );
		$css->add_property( 'background-color', esc_attr( $develope_settings['back_to_top_background_color_hover'] ) );
		$css->add_property( 'color', esc_attr( $develope_settings['back_to_top_text_color_hover'] ) );

		do_action( 'develope_colors_css', $css );

		return apply_filters( 'develope_colors_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'develope_font_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 0.1
	 */
	function develope_font_css() {

		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_font_defaults()
		);

		$og_defaults = develope_get_font_defaults( false );

		$css = new DeveloPress_CSS;

		$subnav_font_size = $develope_settings['navigation_font_size'] >= 17 ? $develope_settings['navigation_font_size'] - 3 : $develope_settings['navigation_font_size'] - 1;

		$body_family = develope_get_font_family_css( 'font_body', 'develope_settings', develope_get_font_defaults() );
		$top_bar_family = develope_get_font_family_css( 'font_top_bar', 'develope_settings', develope_get_font_defaults() );
		$site_title_family = develope_get_font_family_css( 'font_site_title', 'develope_settings', develope_get_font_defaults() );
		$site_tagline_family = develope_get_font_family_css( 'font_site_tagline', 'develope_settings', develope_get_font_defaults() );
		$navigation_family = develope_get_font_family_css( 'font_navigation', 'develope_settings', develope_get_font_defaults() );
		$widget_family = develope_get_font_family_css( 'font_widget_title', 'develope_settings', develope_get_font_defaults() );
		$h1_family = develope_get_font_family_css( 'font_heading_1', 'develope_settings', develope_get_font_defaults() );
		$h2_family = develope_get_font_family_css( 'font_heading_2', 'develope_settings', develope_get_font_defaults() );
		$h3_family = develope_get_font_family_css( 'font_heading_3', 'develope_settings', develope_get_font_defaults() );
		$h4_family = develope_get_font_family_css( 'font_heading_4', 'develope_settings', develope_get_font_defaults() );
		$h5_family = develope_get_font_family_css( 'font_heading_5', 'develope_settings', develope_get_font_defaults() );
		$h6_family = develope_get_font_family_css( 'font_heading_6', 'develope_settings', develope_get_font_defaults() );
		$footer_family = develope_get_font_family_css( 'font_footer', 'develope_settings', develope_get_font_defaults() );
		$buttons_family = develope_get_font_family_css( 'font_buttons', 'develope_settings', develope_get_font_defaults() );

		$css->set_selector( 'body, button, input, select, textarea' );
		$css->add_property( 'font-family', $body_family );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['body_font_weight'] ), $og_defaults['body_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['body_font_transform'] ), $og_defaults['body_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['body_font_size'] ), $og_defaults['body_font_size'], 'px' );

		$css->set_selector( 'body' );
		$css->add_property( 'line-height', floatval( $develope_settings['body_line_height'] ), $og_defaults['body_line_height'] );

		$css->set_selector( 'p' );
		$css->add_property( 'margin-bottom', floatval( $develope_settings['paragraph_margin'] ), $og_defaults['paragraph_margin'], 'em' );

		$css->set_selector( '.entry-content > [class*="wp-block-"]:not(:last-child)' );
		$css->add_property( 'margin-bottom', floatval( $develope_settings['paragraph_margin'] ), false, 'em' );

		$css->set_selector( '.top-bar' );
		$css->add_property( 'font-family', $og_defaults['font_top_bar'] !== $develope_settings['font_top_bar'] ? $top_bar_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['top_bar_font_weight'] ), $og_defaults['top_bar_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['top_bar_font_transform'] ), $og_defaults['top_bar_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['top_bar_font_size'] ), absint( $og_defaults['top_bar_font_size'] ), 'px' );

		$css->set_selector( '.main-title' );
		$css->add_property( 'font-family', $og_defaults['font_site_title'] !== $develope_settings['font_site_title'] ? $site_title_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['site_title_font_weight'] ), $og_defaults['site_title_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['site_title_font_transform'] ), $og_defaults['site_title_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['site_title_font_size'] ), $og_defaults['site_title_font_size'], 'px' );

		$css->set_selector( '.site-description' );
		$css->add_property( 'font-family', $og_defaults['font_site_tagline'] !== $develope_settings['font_site_tagline'] ? $site_tagline_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['site_tagline_font_weight'] ), $og_defaults['site_tagline_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['site_tagline_font_transform'] ), $og_defaults['site_tagline_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['site_tagline_font_size'] ), $og_defaults['site_tagline_font_size'], 'px' );

		$css->set_selector( '.main-navigation a, .menu-toggle' );
		$css->add_property( 'font-family', $og_defaults['font_navigation'] !== $develope_settings['font_navigation'] ? $navigation_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['navigation_font_weight'] ), $og_defaults['navigation_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['navigation_font_transform'] ), $og_defaults['navigation_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['navigation_font_size'] ), $og_defaults['navigation_font_size'], 'px' );

		$css->set_selector( '.main-navigation .main-nav ul ul li a' );
		$css->add_property( 'font-size', absint( $subnav_font_size ), false, 'px' );

		$css->set_selector( '.widget-title' );
		$css->add_property( 'font-family', $og_defaults['font_widget_title'] !== $develope_settings['font_widget_title'] ? $widget_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['widget_title_font_weight'] ), $og_defaults['widget_title_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['widget_title_font_transform'] ), $og_defaults['widget_title_font_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['widget_title_font_size'] ), $og_defaults['widget_title_font_size'], 'px' );
		$css->add_property( 'margin-bottom', absint( $develope_settings['widget_title_separator'] ), absint( $og_defaults['widget_title_separator'] ), 'px' );

		$css->set_selector( '.sidebar .widget, .footer-widgets .widget' );
		$css->add_property( 'font-size', absint( $develope_settings['widget_content_font_size'] ), $og_defaults['widget_content_font_size'], 'px' );

		$css->set_selector( 'button:not(.menu-toggle),html input[type="button"],input[type="reset"],input[type="submit"],.button,.button:visited,.wp-block-button .wp-block-button__link' );
		$css->add_property( 'font-family', $og_defaults['font_buttons'] !== $develope_settings['font_buttons'] ? $buttons_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['buttons_font_weight'] ), $og_defaults['buttons_font_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['buttons_font_transform'] ), $og_defaults['buttons_font_transform'] );

		if ( '' !== $develope_settings['buttons_font_size'] ) {
			$css->add_property( 'font-size', absint( $develope_settings['buttons_font_size'] ), $og_defaults['buttons_font_size'], 'px' );
		}

		$css->set_selector( 'h1' );
		$css->add_property( 'font-family', $og_defaults['font_heading_1'] !== $develope_settings['font_heading_1'] ? $h1_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_1_weight'] ), $og_defaults['heading_1_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_1_transform'] ), $og_defaults['heading_1_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['heading_1_font_size'] ), $og_defaults['heading_1_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $develope_settings['heading_1_line_height'] ), $og_defaults['heading_1_line_height'], 'em' );
		$css->add_property( 'margin-bottom', floatval( $develope_settings['heading_1_margin_bottom'] ), $og_defaults['heading_1_margin_bottom'], 'px' );

		$css->set_selector( 'h2' );
		$css->add_property( 'font-family', $og_defaults['font_heading_2'] !== $develope_settings['font_heading_2'] ? $h2_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_2_weight'] ), $og_defaults['heading_2_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_2_transform'] ), $og_defaults['heading_2_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['heading_2_font_size'] ), $og_defaults['heading_2_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $develope_settings['heading_2_line_height'] ), $og_defaults['heading_2_line_height'], 'em' );
		$css->add_property( 'margin-bottom', floatval( $develope_settings['heading_2_margin_bottom'] ), $og_defaults['heading_2_margin_bottom'], 'px' );

		$css->set_selector( 'h3' );
		$css->add_property( 'font-family', $og_defaults['font_heading_3'] !== $develope_settings['font_heading_3'] ? $h3_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_3_weight'] ), $og_defaults['heading_3_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_3_transform'] ), $og_defaults['heading_3_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['heading_3_font_size'] ), $og_defaults['heading_3_font_size'], 'px' );
		$css->add_property( 'line-height', floatval( $develope_settings['heading_3_line_height'] ), $og_defaults['heading_3_line_height'], 'em' );
		$css->add_property( 'margin-bottom', floatval( $develope_settings['heading_3_margin_bottom'] ), $og_defaults['heading_3_margin_bottom'], 'px' );

		$css->set_selector( 'h4' );
		$css->add_property( 'font-family', $og_defaults['font_heading_4'] !== $develope_settings['font_heading_4'] ? $h4_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_4_weight'] ), $og_defaults['heading_4_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_4_transform'] ), $og_defaults['heading_4_transform'] );

		if ( '' !== $develope_settings['heading_4_font_size'] ) {
			$css->add_property( 'font-size', absint( $develope_settings['heading_4_font_size'] ), $og_defaults['heading_4_font_size'], 'px' );
		}

		if ( '' !== $develope_settings['heading_4_line_height'] ) {
			$css->add_property( 'line-height', floatval( $develope_settings['heading_4_line_height'] ), $og_defaults['heading_4_line_height'], 'em' );
		}

		$css->set_selector( 'h5' );
		$css->add_property( 'font-family', $og_defaults['font_heading_5'] !== $develope_settings['font_heading_5'] ? $h5_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_5_weight'] ), $og_defaults['heading_5_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_5_transform'] ), $og_defaults['heading_5_transform'] );

		if ( '' !== $develope_settings['heading_5_font_size'] ) {
			$css->add_property( 'font-size', absint( $develope_settings['heading_5_font_size'] ), $og_defaults['heading_5_font_size'], 'px' );
		}

		if ( '' !== $develope_settings['heading_5_line_height'] ) {
			$css->add_property( 'line-height', floatval( $develope_settings['heading_5_line_height'] ), $og_defaults['heading_5_line_height'], 'em' );
		}

		$css->set_selector( 'h6' );
		$css->add_property( 'font-family', $og_defaults['font_heading_6'] !== $develope_settings['font_heading_6'] ? $h6_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['heading_6_weight'] ), $og_defaults['heading_6_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['heading_6_transform'] ), $og_defaults['heading_6_transform'] );

		if ( '' !== $develope_settings['heading_6_font_size'] ) {
			$css->add_property( 'font-size', absint( $develope_settings['heading_6_font_size'] ), $og_defaults['heading_6_font_size'], 'px' );
		}

		if ( '' !== $develope_settings['heading_6_line_height'] ) {
			$css->add_property( 'line-height', floatval( $develope_settings['heading_6_line_height'] ), $og_defaults['heading_6_line_height'], 'em' );
		}

		$css->set_selector( '.site-info' );
		$css->add_property( 'font-family', $og_defaults['font_footer'] !== $develope_settings['font_footer'] ? $footer_family : null );
		$css->add_property( 'font-weight', esc_attr( $develope_settings['footer_weight'] ), $og_defaults['footer_weight'] );
		$css->add_property( 'text-transform', esc_attr( $develope_settings['footer_transform'] ), $og_defaults['footer_transform'] );
		$css->add_property( 'font-size', absint( $develope_settings['footer_font_size'] ), $og_defaults['footer_font_size'], 'px' );

		$css->start_media_query( develope_get_media_query( 'mobile' ) );
			$mobile_site_title = ( isset( $develope_settings['mobile_site_title_font_size'] ) ) ? $develope_settings['mobile_site_title_font_size'] : '30';
			$css->set_selector( '.main-title' );
			$css->add_property( 'font-size', absint( $mobile_site_title ), false, 'px' );

			$mobile_h1 = ( isset( $develope_settings['mobile_heading_1_font_size'] ) ) ? $develope_settings['mobile_heading_1_font_size'] : '30';
			$css->set_selector( 'h1' );
			$css->add_property( 'font-size', absint( $mobile_h1 ), false, 'px' );

			$mobile_h2 = ( isset( $develope_settings['mobile_heading_2_font_size'] ) ) ? $develope_settings['mobile_heading_2_font_size'] : '25';
			$css->set_selector( 'h2' );
			$css->add_property( 'font-size', absint( $mobile_h2 ), false, 'px' );
		$css->stop_media_query();

		do_action( 'develope_typography_css', $css );

		return apply_filters( 'develope_typography_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'develope_spacing_css' ) ) {
	/**
	 * Write our dynamic CSS.
	 *
	 * @since 0.1
	 */
	function develope_spacing_css() {
		$spacing_settings = wp_parse_args(
			get_option( 'develope_spacing_settings', array() ),
			develope_get_spacing_defaults()
		);

		$og_defaults = develope_get_spacing_defaults( false );
		$sidebar_layout = develope_get_layout();

		$css = new DeveloPress_CSS;

		$css->set_selector( '.inside-top-bar' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['top_bar_top'], $spacing_settings['top_bar_right'], $spacing_settings['top_bar_bottom'], $spacing_settings['top_bar_left'] ), develope_padding_css( $og_defaults['top_bar_top'], $og_defaults['top_bar_right'], $og_defaults['top_bar_bottom'], $og_defaults['top_bar_left'] ) );

		$css->set_selector( '.inside-header' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['header_top'], $spacing_settings['header_right'], $spacing_settings['header_bottom'], $spacing_settings['header_left'] ), develope_padding_css( $og_defaults['header_top'], $og_defaults['header_right'], $og_defaults['header_bottom'], $og_defaults['header_left'] ) );

		$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .separate-containers .paging-navigation, .one-container .site-content, .inside-page-header, .wp-block-group__inner-container' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['content_top'], $spacing_settings['content_right'], $spacing_settings['content_bottom'], $spacing_settings['content_left'] ), develope_padding_css( $og_defaults['content_top'], $og_defaults['content_right'], $og_defaults['content_bottom'], $og_defaults['content_left'] ) );

		
		$content_padding = absint( $spacing_settings['content_right'] ) + absint( $spacing_settings['content_left'] );
		$css->set_selector( '.entry-content .alignwide, body:not(.no-sidebar) .entry-content .alignfull' );
		$css->add_property( 'margin-left', '-' . absint( $spacing_settings['content_left'] ) . 'px' );
		$css->add_property( 'width', 'calc(100% + ' . absint( $content_padding ) . 'px)' );
		$css->add_property( 'max-width', 'calc(100% + ' . absint( $content_padding ) . 'px)' );
		

		if ( 'text' === develope_get_option( 'container_alignment' ) ) {
			$css->set_selector( '.container.grid-container' );
			$css->add_property( 'max-width', develope_get_option( 'container_width' ) + $content_padding, false, 'px' );
		}

		$css->set_selector( '.one-container.right-sidebar .site-main,.one-container.both-right .site-main' );
		$css->add_property( 'margin-right', absint( $spacing_settings['content_right'] ), absint( $og_defaults['content_right'] ), 'px' );

		$css->set_selector( '.one-container.left-sidebar .site-main,.one-container.both-left .site-main' );
		$css->add_property( 'margin-left', absint( $spacing_settings['content_left'] ), absint( $og_defaults['content_left'] ), 'px' );

		$css->set_selector( '.one-container.both-sidebars .site-main' );
		$css->add_property( 'margin', develope_padding_css( '0', $spacing_settings['content_right'], '0', $spacing_settings['content_left'] ), develope_padding_css( '0', $og_defaults['content_right'], '0', $og_defaults['content_left'] ) );

		$css->set_selector( '.separate-containers .widget, .separate-containers .site-main > *, .separate-containers .page-header, .widget-area .main-navigation' );
		$css->add_property( 'margin-bottom', absint( $spacing_settings['separator'] ), absint( $og_defaults['separator'] ), 'px' );

		$css->set_selector( '.separate-containers .site-main' );
		$css->add_property( 'margin', absint( $spacing_settings['separator'] ), $og_defaults['separator'], 'px' );

		$css->set_selector( '.both-right.separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-right', absint( $spacing_settings['separator'] / 2 ), absint( $og_defaults['separator'] / 2 ), 'px' );

		$css->set_selector( '.both-right.separate-containers .inside-right-sidebar' );
		$css->add_property( 'margin-left', absint( $spacing_settings['separator'] / 2 ), absint( $og_defaults['separator'] / 2 ), 'px' );

		$css->set_selector( '.both-left.separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-right', absint( $spacing_settings['separator'] / 2 ), absint( $og_defaults['separator'] / 2 ), 'px' );

		$css->set_selector( '.both-left.separate-containers .inside-right-sidebar' );
		$css->add_property( 'margin-left', absint( $spacing_settings['separator'] / 2 ), absint( $og_defaults['separator'] / 2 ), 'px' );

		$css->set_selector( '.separate-containers .page-header-image, .separate-containers .page-header-contained, .separate-containers .page-header-image-single, .separate-containers .page-header-content-single' );
		$css->add_property( 'margin-top', absint( $spacing_settings['separator'] ), absint( $og_defaults['separator'] ), 'px' );

		$css->set_selector( '.separate-containers .inside-right-sidebar, .separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-top', absint( $spacing_settings['separator'] ), absint( $og_defaults['separator'] ), 'px' );
		$css->add_property( 'margin-bottom', absint( $spacing_settings['separator'] ), absint( $og_defaults['separator'] ), 'px' );

		$css->set_selector( '.main-navigation .main-nav ul li a,.menu-toggle,.main-navigation .mobile-bar-items a' );
		$css->add_property( 'padding-left', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );
		$css->add_property( 'padding-right', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );
		$css->add_property( 'line-height', absint( $spacing_settings['menu_item_height'] ), absint( $og_defaults['menu_item_height'] ), 'px' );

		$css->set_selector( '.main-navigation .main-nav ul ul li a' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['sub_menu_item_height'], $spacing_settings['menu_item'], $spacing_settings['sub_menu_item_height'], $spacing_settings['menu_item'] ), develope_padding_css( $og_defaults['sub_menu_item_height'], $og_defaults['menu_item'], $og_defaults['sub_menu_item_height'], $og_defaults['menu_item'] ) );

		$css->set_selector( '.main-navigation ul ul' );
		$css->add_property( 'width', absint( $spacing_settings['sub_menu_width'] ), absint( $og_defaults['sub_menu_width'] ), 'px' );

		$css->set_selector( '.navigation-search input' );
		$css->add_property( 'height', absint( $spacing_settings['menu_item_height'] ), absint( $og_defaults['menu_item_height'] ), 'px' );

		$css->set_selector( '.rtl .menu-item-has-children .dropdown-menu-toggle' );
		$css->add_property( 'padding-left', absint( $spacing_settings['menu_item'] ), false, 'px' );

		$css->set_selector( '.menu-item-has-children .dropdown-menu-toggle' );
		$css->add_property( 'padding-right', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );

		$css->set_selector( '.menu-item-has-children ul .dropdown-menu-toggle' );
		$css->add_property( 'padding-top', absint( $spacing_settings['sub_menu_item_height'] ), absint( $og_defaults['sub_menu_item_height'] ), 'px' );
		$css->add_property( 'padding-bottom', absint( $spacing_settings['sub_menu_item_height'] ), absint( $og_defaults['sub_menu_item_height'] ), 'px' );
		$css->add_property( 'margin-top', '-' . absint( $spacing_settings['sub_menu_item_height'] ), '-' . absint( $og_defaults['sub_menu_item_height'] ), 'px' );

		$css->set_selector( '.rtl .main-navigation .main-nav ul li.menu-item-has-children > a' );
		$css->add_property( 'padding-right', absint( $spacing_settings['menu_item'] ), false, 'px' );

		$css->set_selector( '.widget-area .widget' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['widget_top'], $spacing_settings['widget_right'], $spacing_settings['widget_bottom'], $spacing_settings['widget_left'] ), develope_padding_css( $og_defaults['widget_top'], $og_defaults['widget_right'], $og_defaults['widget_bottom'], $og_defaults['widget_left'] ) );

		$css->set_selector( '.footer-widgets' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['footer_widget_container_top'], $spacing_settings['footer_widget_container_right'], $spacing_settings['footer_widget_container_bottom'], $spacing_settings['footer_widget_container_left'] ), develope_padding_css( $og_defaults['footer_widget_container_top'], $og_defaults['footer_widget_container_right'], $og_defaults['footer_widget_container_bottom'], $og_defaults['footer_widget_container_left'] ) );

		$css->set_selector( '.site-footer .footer-widgets-container .inner-padding' );
		$css->add_property( 'padding', develope_padding_css( '0', '0', '0', $spacing_settings['footer_widget_separator'] ), develope_padding_css( '0', '0', '0', $og_defaults['footer_widget_separator'] ) );

		$css->set_selector( '.site-footer .footer-widgets-container .inside-footer-widgets' );
		$css->add_property( 'margin-left', '-' . absint( $spacing_settings['footer_widget_separator'] ), '-' . absint( $og_defaults['footer_widget_separator'] ), 'px' );

		$css->set_selector( '.site-info' );
		$css->add_property( 'padding', develope_padding_css( $spacing_settings['footer_top'], $spacing_settings['footer_right'], $spacing_settings['footer_bottom'], $spacing_settings['footer_left'] ), develope_padding_css( $og_defaults['footer_top'], $og_defaults['footer_right'], $og_defaults['footer_bottom'], $og_defaults['footer_left'] ) );

		$css->start_media_query( develope_get_media_query( 'mobile' ) );
			$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .separate-containers .paging-navigation, .one-container .site-content, .inside-page-header, .wp-block-group__inner-container' );
			$css->add_property( 'padding', develope_padding_css( $spacing_settings['mobile_content_top'], $spacing_settings['mobile_content_right'], $spacing_settings['mobile_content_bottom'], $spacing_settings['mobile_content_left'] ) );

			/* Hide this right now for testing purposes with Flexbox
			$mobile_content_padding = absint( $spacing_settings['mobile_content_right'] ) + absint( $spacing_settings['mobile_content_left'] );
			$css->set_selector( '.entry-content .alignwide, body:not(.no-sidebar) .entry-content .alignfull' );
			$css->add_property( 'margin-left', '-' . absint( $spacing_settings['mobile_content_left'] ) . 'px' );
			$css->add_property( 'width', 'calc(100% + ' . absint( $mobile_content_padding ) . 'px)' );
			$css->add_property( 'max-width', 'calc(100% + ' . absint( $mobile_content_padding ) . 'px)' );
			*/

			if ( '' !== $spacing_settings['mobile_separator'] ) {
				$css->set_selector( '.separate-containers .widget, .separate-containers .site-main > *, .separate-containers .page-header' );
				$css->add_property( 'margin-bottom', absint( $spacing_settings['mobile_separator'] ), false, 'px' );

				$css->set_selector( '.separate-containers .site-main' );
				$css->add_property( 'margin', absint( $spacing_settings['mobile_separator'] ), false, 'px' );

				$css->set_selector( '.separate-containers .page-header-image, .separate-containers .page-header-image-single' );
				$css->add_property( 'margin-top', absint( $spacing_settings['mobile_separator'] ), false, 'px' );

				$css->set_selector( '.separate-containers .inside-right-sidebar, .separate-containers .inside-left-sidebar' );
				$css->add_property( 'margin-top', absint( $spacing_settings['mobile_separator'] ), false, 'px' );
				$css->add_property( 'margin-bottom', absint( $spacing_settings['mobile_separator'] ), false, 'px' );
			}
		$css->stop_media_query();

		// Add spacing back where dropdown arrow should be.
		// Old versions of WP don't get nice things.
		if ( version_compare( $GLOBALS['wp_version'], '4.4', '<' ) ) {
			$css->set_selector( '.main-navigation .main-nav ul li.menu-item-has-children>a, .secondary-navigation .main-nav ul li.menu-item-has-children>a' );
			$css->add_property( 'padding-right', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );
		}

		$output = '';

		$develope_settings = wp_parse_args(
			get_option( 'develope_settings', array() ),
			develope_get_color_defaults()
		);

		// Find out if the content background color and sidebar widget background color is the same.
		$sidebar = strtoupper( $develope_settings['sidebar_widget_background_color'] );
		$content = strtoupper( $develope_settings['content_background_color'] );
		$colors_match = ( ( $sidebar == $content ) || '' == $sidebar ) ? true : false;

		// If they're all 40 (default), remove the padding when one container is set.
		// This way, the user can still adjust the padding and it will work (unless they want 40px padding).
		// We'll also remove the padding if there's no color difference between the widgets and content background color.
		if ( ( '40' == $spacing_settings['widget_top'] && '40' == $spacing_settings['widget_right'] && '40' == $spacing_settings['widget_bottom'] && '40' == $spacing_settings['widget_left'] ) && $colors_match ) {
			$output .= '.one-container .sidebar .widget{padding:0px;}';
		}

		do_action( 'develope_spacing_css', $css );

		return apply_filters( 'develope_spacing_css_output', $css->css_output() . $output );
	}
}

/**
 * Generates any CSS that can't be cached (can change from page to page).
 *
 * @since 2.0
 */
function develope_no_cache_dynamic_css() {
	$css = new DeveloPress_CSS;

	if ( ! develope_show_title() ) {
		$css->set_selector( '.page .entry-content' )->add_property( 'margin-top', '0px' );

		if ( is_single() ) {
			if ( ! apply_filters( 'develope_post_author', true ) && ! apply_filters( 'develope_post_date', true ) ) {
				$css->set_selector( '.single .entry-content' )->add_property( 'margin-top', '0px' );
			}
		}
	}

	$css->start_media_query( develope_get_media_query( 'mobile-menu' ) );
		$css->set_selector( '.main-navigation .menu-toggle,.main-navigation .mobile-bar-items,.sidebar-nav-mobile:not(#sticky-placeholder)' );
		$css->add_property( 'display', 'block' );

		$css->set_selector( '.main-navigation ul,.gen-sidebar-nav' );
		$css->add_property( 'display', 'none' );

		$css->set_selector( '[class*="nav-float-"] .site-header .inside-header > *' );
		$css->add_property( 'float', 'none' );
		$css->add_property( 'clear', 'both' );
	$css->stop_media_query();

	return $css->css_output();
}

add_action( 'wp_enqueue_scripts', 'develope_enqueue_dynamic_css', 50 );
/**
 * Enqueue our dynamic CSS.
 *
 * @since 2.0
 */
function develope_enqueue_dynamic_css() {
	if ( ! get_option( 'develope_dynamic_css_output', false ) || is_customize_preview() || apply_filters( 'develope_dynamic_css_skip_cache', false ) ) {
		$css = develope_base_css() . develope_font_css() . develope_advanced_css() . develope_spacing_css();
	} else {
		$css = get_option( 'develope_dynamic_css_output' ) . '/* End cached CSS */';
	}

	$css = $css . develope_no_cache_dynamic_css() . develope_do_icon_css();

	wp_add_inline_style( 'develope-style', $css );
}

add_action( 'init', 'develope_set_dynamic_css_cache' );
/**
 * Sets our dynamic CSS cache if it doesn't exist.
 *
 * If the theme version changed, bust the cache.
 *
 * @since 2.0
 */
function develope_set_dynamic_css_cache() {
	if ( apply_filters( 'develope_dynamic_css_skip_cache', false ) ) {
		return;
	}

	$cached_css = get_option( 'develope_dynamic_css_output', false );
	$cached_version = get_option( 'develope_dynamic_css_cached_version', '' );

	if ( ! $cached_css || $cached_version !== DEVELOPE_VERSION ) {
		$css = develope_base_css() . develope_font_css() . develope_advanced_css() . develope_spacing_css();

		update_option( 'develope_dynamic_css_output', $css );
		update_option( 'develope_dynamic_css_cached_version', DEVELOPE_VERSION );
	}
}

add_action( 'customize_save_after', 'develope_update_dynamic_css_cache' );
/**
 * Update our CSS cache when done saving Customizer options.
 *
 * @since 2.0
 */
function develope_update_dynamic_css_cache() {
	if ( apply_filters( 'develope_dynamic_css_skip_cache', false ) ) {
		return;
	}

	$css = develope_base_css() . develope_font_css() . develope_advanced_css() . develope_spacing_css();
	update_option( 'develope_dynamic_css_output', $css );
}

/**
 * Output CSS for the icon fonts.
 *
 * @since 2.3
 */
function develope_do_icon_css() {
	$output = false;

	if ( 'font' === develope_get_option( 'icons' ) ) {
		$url = trailingslashit( get_template_directory_uri() );

		$output = '@font-face {
			font-family: "DeveloPress";
			src:  url("' . $url . 'assets/fonts/developress.eot");
			src:  url("' . $url . 'assets/fonts/developress.eot#iefix") format("embedded-opentype"),
				  url("' . $url . 'assets/fonts/developress.woff2") format("woff2"),
				  url("' . $url . 'assets/fonts/developress.woff") format("woff"),
				  url("' . $url . 'assets/fonts/developress.ttf") format("truetype"),
				  url("' . $url . 'assets/fonts/developress.svg#DeveloPress") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

		if ( defined( 'DEVELOPE_MENU_PLUS_VERSION' ) ) {
			$output .= '.main-navigation .slideout-toggle a:before,
			.slide-opened .slideout-overlay .slideout-exit:before {
				font-family: DeveloPress;
			}

			.slideout-navigation .dropdown-menu-toggle:before {
				content: "\f107" !important;
			}

			.slideout-navigation .sfHover > a .dropdown-menu-toggle:before {
				content: "\f106" !important;
			}';
		}
	}

	if ( 'svg' === develope_get_option( 'icons' ) ) {
		$output = 'button.menu-toggle:before,
		.search-item a:before,
		.dropdown-menu-toggle:before,
		.cat-links:before,
		.tags-links:before,
		.comments-link:before,
		.nav-previous .prev:before,
		.nav-next .next:before,
		.develope-back-to-top:before {
			display: none;
		}';
	}

	if ( $output ) {
		return str_replace( array( "\r", "\n", "\t" ), '', $output );
	}
}
