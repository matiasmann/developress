<?php
/**
 * Sets all of the theme defaults.
 * 
 * Color default options. develope_get_color_defaults()
 * Font default options. develope_get_font_defaults()
 * General default options. develope_get_general_defaults()
 * Spacing default options. develope_get_spacing_defaults()
 * System Fonts default options. develope_get_system_font_default()
 * 
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_get_color_defaults' ) ) {
	/**
	 * Set Color default options.
	 *
	 * @since 0.1
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 */
	function develope_get_color_defaults() {
		return apply_filters( 'develope_color_option_defaults',
			array(
				'top_bar_background_color' => '#f7f7f7;',
				'top_bar_text_color' => '#0073aa',
				'top_bar_link_color' => '#0073aa',
				'top_bar_link_color_hover' => '#00a0d2',
				'header_background_color' => '#00a0d2',
				'header_text_color' => '#ffffff',
				'header_link_color' => '#ffffff',
				'header_link_hover_color' => '',
				'site_title_color' => '#ffffff',
				'site_tagline_color' => '#ffffff',
				'navigation_background_color' => '#0073aa',
				'navigation_text_color' => '#ffffff',
				'navigation_background_hover_color' => '#32373c',
				'navigation_text_hover_color' => '#ffffff',
				'navigation_background_current_color' => '#32373c',
				'navigation_text_current_color' => '#ffffff',
				'subnavigation_background_color' => '#191e23',
				'subnavigation_text_color' => '#ffffff',
				'subnavigation_background_hover_color' => '#32373c',
				'subnavigation_text_hover_color' => '#ffffff',
				'subnavigation_background_current_color' => '#32373c',
				'subnavigation_text_current_color' => '#ffffff',
				'navigation_search_background_color' => '',
				'navigation_search_text_color' => '',
				'content_background_color' => '#ffffff',
				'content_text_color' => '',
				'content_link_color' => '',
				'content_link_hover_color' => '',
				'content_title_color' => '',
				'blog_post_title_color' => '',
				'blog_post_title_hover_color' => '',
				'entry_meta_text_color' => '#23282d',
				'entry_meta_link_color' => '#23282d',
				'entry_meta_link_color_hover' => '#32373c',
				'h1_color' => '',
				'h2_color' => '',
				'h3_color' => '',
				'h4_color' => '',
				'h5_color' => '',
				'h6_color' => '',
				'sidebar_widget_background_color' => '#ffffff',
				'sidebar_widget_text_color' => '',
				'sidebar_widget_link_color' => '',
				'sidebar_widget_link_hover_color' => '',
				'sidebar_widget_title_color' => '#000000',
				'footer_widget_background_color' => 'linear-gradient(to right, #fafafa 40%, #fff 40%)',
				'footer_widget_text_color' => '',
				'footer_widget_link_color' => '',
				'footer_widget_link_hover_color' => '',
				'footer_widget_title_color' => '#000000',
				'footer_background_color' => '#f7f7f7;',
				'footer_text_color' => '#0073aa',
				'footer_link_color' => '#0073aa',
				'footer_link_hover_color' => '#00a0d2',
				'form_background_color' => '#fafafa',
				'form_text_color' => '#666666',
				'form_background_color_focus' => '#ffffff',
				'form_text_color_focus' => '#666666',
				'form_border_color' => '#cccccc',
				'form_border_color_focus' => '#bfbfbf',
				'form_button_background_color' => '#666666',
				'form_button_background_color_hover' => '#3f3f3f',
				'form_button_text_color' => '#ffffff',
				'form_button_text_color_hover' => '#ffffff',
				'back_to_top_background_color' => 'rgba( 0,0,0,0.4 )',
				'back_to_top_background_color_hover' => 'rgba( 0,0,0,0.6 )',
				'back_to_top_text_color' => '#ffffff',
				'back_to_top_text_color_hover' => '#ffffff',
			)
		);
	}
}
						 
if ( ! function_exists( 'develope_get_font_defaults' ) ) {
	/**
	 * Set Font default options.
	 *
	 * @since 0.1
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 */
	function develope_get_font_defaults( $filter = true ) {
		$defaults = array(
			'font_body' => 'System Stack',
			'font_body_category' => '',
			'font_body_variants' => '',
			'body_font_weight' => 'normal',
			'body_font_transform' => 'none',
			'body_font_size' => '17',
			'body_line_height' => '1.5', // no unit
			'paragraph_margin' => '1.5', // em
			'font_top_bar' => 'inherit',
			'font_top_bar_category' => '',
			'font_top_bar_variants' => '',
			'top_bar_font_weight' => 'normal',
			'top_bar_font_transform' => 'none',
			'top_bar_font_size' => '13',
			'font_site_title' => 'inherit',
			'font_site_title_category' => '',
			'font_site_title_variants' => '',
			'site_title_font_weight' => 'bold',
			'site_title_font_transform' => 'none',
			'site_title_font_size' => '50',
			'mobile_site_title_font_size' => '30',
			'font_site_tagline' => 'inherit',
			'font_site_tagline_category' => '',
			'font_site_tagline_variants' => '',
			'site_tagline_font_weight' => 'normal',
			'site_tagline_font_transform' => 'none',
			'site_tagline_font_size' => '20',
			'font_navigation' => 'inherit',
			'font_navigation_category' => '',
			'font_navigation_variants' => '',
			'navigation_font_weight' => '600',
			'navigation_font_transform' => 'none',
			'navigation_font_size' => '16',
			'font_widget_title' => 'inherit',
			'font_widget_title_category' => '',
			'font_widget_title_variants' => '',
			'widget_title_font_weight' => 'normal',
			'widget_title_font_transform' => 'none',
			'widget_title_font_size' => '20',
			'widget_title_separator' => '30',
			'widget_content_font_size' => '17',
			'font_buttons' => 'inherit',
			'font_buttons_category' => '',
			'font_buttons_variants' => '',
			'buttons_font_weight' => 'normal',
			'buttons_font_transform' => 'none',
			'buttons_font_size' => '',
			'font_heading_1' => 'inherit',
			'font_heading_1_category' => '',
			'font_heading_1_variants' => '',
			'heading_1_weight' => '300',
			'heading_1_transform' => 'none',
			'heading_1_font_size' => '40',
			'heading_1_line_height' => '1.2', // em
			'heading_1_margin_bottom' => '20',
			'mobile_heading_1_font_size' => '30',
			'font_heading_2' => 'inherit',
			'font_heading_2_category' => '',
			'font_heading_2_variants' => '',
			'heading_2_weight' => '300',
			'heading_2_transform' => 'none',
			'heading_2_font_size' => '30',
			'heading_2_line_height' => '1.2', // em
			'heading_2_margin_bottom' => '20',
			'mobile_heading_2_font_size' => '25',
			'font_heading_3' => 'inherit',
			'font_heading_3_category' => '',
			'font_heading_3_variants' => '',
			'heading_3_weight' => 'normal',
			'heading_3_transform' => 'none',
			'heading_3_font_size' => '20',
			'heading_3_line_height' => '1.2', // em
			'heading_3_margin_bottom' => '20',
			'font_heading_4' => 'inherit',
			'font_heading_4_category' => '',
			'font_heading_4_variants' => '',
			'heading_4_weight' => 'normal',
			'heading_4_transform' => 'none',
			'heading_4_font_size' => '',
			'heading_4_line_height' => '', // em
			'font_heading_5' => 'inherit',
			'font_heading_5_category' => '',
			'font_heading_5_variants' => '',
			'heading_5_weight' => 'normal',
			'heading_5_transform' => 'none',
			'heading_5_font_size' => '',
			'heading_5_line_height' => '', // em
			'font_heading_6' => 'inherit',
			'font_heading_6_category' => '',
			'font_heading_6_variants' => '',
			'heading_6_weight' => 'normal',
			'heading_6_transform' => 'none',
			'heading_6_font_size' => '',
			'heading_6_line_height' => '', // em
			'font_footer' => 'inherit',
			'font_footer_category' => '',
			'font_footer_variants' => '',
			'footer_weight' => 'normal',
			'footer_transform' => 'none',
			'footer_font_size' => '15',
		);

		if ( $filter ) {
			return apply_filters( 'develope_font_option_defaults', $defaults );
		}

		return $defaults;
	}
}

if ( ! function_exists( 'develope_get_general_defaults' ) ) {
	/**
	 * Set General default options
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 * 
	 * @since 0.1
	 */
	function develope_get_general_defaults() {
		return apply_filters( 'develope_option_defaults',
			array(
				'hide_title' => '',
				'hide_tagline' => '',
				'logo' => '',
				'inline_logo_site_branding' => false,
				'retina_logo' => '',
				'logo_width' => '',
				'top_bar_width' => 'full',
				'top_bar_inner_width' => 'contained',
				'top_bar_alignment' => 'right',
				'container_width' => '960',
				'container_alignment' => 'boxes',
				'header_layout_setting' => 'fluid-header',
				'header_inner_width' => 'contained',
				'nav_alignment_setting' => ( is_rtl() ) ? 'right' : 'left',
				'header_alignment_setting' => ( is_rtl() ) ? 'right' : 'left',
				'nav_layout_setting' => 'fluid-nav',
				'nav_inner_width' => 'contained',
				'nav_position_setting' => 'nav-below-header',
				'nav_drop_point' => '',
				'nav_dropdown_type' => 'hover',
				'nav_dropdown_direction' => 'right',
				'nav_search' => 'disable',
				'content_layout_setting' => 'one-container',
				'layout_setting' => 'left-sidebar',
				'blog_layout_setting' => 'left-sidebar',
				'single_layout_setting' => 'left-sidebar',
				'post_content' => 'excerpt',
				'footer_layout_setting' => 'fluid-footer',
				'footer_inner_width' => 'contained',
				'footer_widget_setting' => '3',
				'footer_bar_alignment' => 'right',
				'back_to_top' => '',
				'background_color' => '#efefef',
				'text_color' => '#3a3a3a',
				'link_color' => '#1e73be',
				'link_color_hover' => '#000000',
				'link_color_visited' => '',
				'font_awesome_essentials' => true,
				'icons' => 'font',
				'combine_css' => false,
				'dynamic_css_cache' => true,
			)
		);
	}
}

if ( ! function_exists( 'develope_get_spacing_defaults' ) ) {
	/**
	 * Set Spacing default options
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 * 
	 * @since 0.1
	 */
	function develope_get_spacing_defaults( $filter = true ) {
		$defaults = array(
			'top_bar_top' => '10',
			'top_bar_right' => '10',
			'top_bar_bottom' => '10',
			'top_bar_left' => '10',
			'header_top' => '40',
			'header_right' => '40',
			'header_bottom' => '40',
			'header_left' => '40',
			'menu_item' => '20',
			'menu_item_height' => '60',
			'sub_menu_item_height' => '10',
			'sub_menu_width' => '200',
			'content_top' => '40',
			'content_right' => '40',
			'content_bottom' => '40',
			'content_left' => '40',
			'mobile_content_top' => '30',
			'mobile_content_right' => '30',
			'mobile_content_bottom' => '30',
			'mobile_content_left' => '30',
			'separator' => '20',
			'mobile_separator' => '',
			'left_sidebar_width' => '25',
			'right_sidebar_width' => '25',
			'widget_top' => '40',
			'widget_right' => '40',
			'widget_bottom' => '40',
			'widget_left' => '40',
			'footer_widget_container_top' => '40',
			'footer_widget_container_right' => '40',
			'footer_widget_container_bottom' => '40',
			'footer_widget_container_left' => '40',
			'footer_widget_separator' => '40',
			'footer_top' => '20',
			'footer_right' => '20',
			'footer_bottom' => '20',
			'footer_left' => '20',
		);

		if ( $filter ) {
			return apply_filters( 'develope_spacing_option_defaults', $defaults );
		}

		return $defaults;
	}
}

if ( ! function_exists( 'develope_get_system_font_default' ) ) {
	/**
	 * Set System Fonts default options
	 *
	 * @param bool $filter Whether to return the filtered values or original values.
	 * @return array Option defaults.
	 * 
	 * @since 1.3.4
	 */
	function develope_get_system_font_default() {
		$fonts = array(
			'inherit',
			'System Stack',
			'Arial, Helvetica, sans-serif',
			'Century Gothic',
			'Comic Sans MS',
			'Courier New',
			'Georgia, Times New Roman, Times, serif',
			'Helvetica',
			'Impact',
			'Lucida Console',
			'Lucida Sans Unicode',
			'Palatino Linotype',
			'Segoe UI, Helvetica Neue, Helvetica, sans-serif',
			'Tahoma, Geneva, sans-serif',
			'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
		);

		return apply_filters( 'develope_get_system_font_default', $fonts );
	}
}