<?php
/**
 * Builds our Customizer controls.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'customize_register', 'develope_set_customizer_helpers', 1 );
/**
 * Set up helpers early so they're always available.
 * Other modules might need access to them at some point.
 *
 * @since 2.0
 */
function develope_set_customizer_helpers() {
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}

if ( ! function_exists( 'develope_customize_register' ) ) {
	add_action( 'customize_register', 'develope_customize_register' );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function develope_customize_register( $wp_customize ) {
		$defaults = develope_get_general_defaults();

		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';

		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$wp_customize->get_control( 'blogdescription' )->priority = 3;
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'blogname' ) ) {
			$wp_customize->get_control( 'blogname' )->priority = 1;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'custom_logo' ) ) {
			$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
		}

		if ( method_exists( $wp_customize, 'register_control_type' ) ) {
			$wp_customize->register_control_type( 'Develope_Customize_Misc_Control' );
			$wp_customize->register_control_type( 'Develope_Range_Slider_Control' );
		}

		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'Develope_Upsell_Section' );
		}

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.main-title a',
				'render_callback' => 'develope_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'render_callback' => 'develope_customize_partial_blogdescription',
			) );
		}

		if ( ! defined( 'DP_PREMIUM_VERSION' ) ) {
			$wp_customize->add_section(
				new Develope_Upsell_Section( $wp_customize, 'develope_upsell_section',
					array(
						'pro_text' => __( 'Premium Modules Available', 'developress' ),
						'pro_url' => develope_get_premium_url( 'https://developress.org/premium' ),
						'capability' => 'edit_theme_options',
						'priority' => 0,
						'type' => 'dp-upsell-section',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'develope_settings[hide_title]',
			array(
				'default' => $defaults['hide_title'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'develope_settings[hide_title]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site title', 'developress' ),
				'section' => 'title_tagline',
				'priority' => 2,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[hide_tagline]',
			array(
				'default' => $defaults['hide_tagline'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'develope_settings[hide_tagline]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site tagline', 'developress' ),
				'section' => 'title_tagline',
				'priority' => 4,
			)
		);

		if ( ! function_exists( 'the_custom_logo' ) ) {
			$wp_customize->add_setting(
				'develope_settings[logo]',
				array(
					'default' => $defaults['logo'],
					'type' => 'option',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'develope_settings[logo]',
					array(
						'label' => __( 'Logo', 'developress' ),
						'section' => 'title_tagline',
						'settings' => 'develope_settings[logo]',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'develope_settings[retina_logo]',
			array(
				'default' => $defaults['retina_logo'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'develope_settings[retina_logo]',
				array(
					'label' => __( 'Retina Logo', 'developress' ),
					'section' => 'title_tagline',
					'settings' => 'develope_settings[retina_logo]',
					'active_callback' => 'develope_has_custom_logo_callback',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[logo_width]',
			array(
				'default' => $defaults['logo_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_empty_absint',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Develope_Range_Slider_Control(
				$wp_customize,
				'develope_settings[logo_width]',
				array(
					'label' => __( 'Logo Width', 'developress' ),
					'section' => 'title_tagline',
					'settings' => array(
						'desktop' => 'develope_settings[logo_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 30,
							'max' => 800,
							'step' => 10,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'active_callback' => 'develope_has_custom_logo_callback',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[inline_logo_site_branding]',
			array(
				'default' => $defaults['inline_logo_site_branding'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'develope_settings[inline_logo_site_branding]',
			array(
				'type' => 'checkbox',
				'label' => esc_html__( 'Place logo next to title', 'developress' ),
				'section' => 'title_tagline',
				'active_callback' => 'develope_has_logo_site_branding',
			)
		);

		$wp_customize->add_section(
			'body_section',
			array(
				'title' => $wp_customize->get_panel( 'develope_colors_panel' ) ? __( 'Body', 'developress' ) : __( 'Colors', 'developress' ),
				'capability' => 'edit_theme_options',
				'priority' => 30,
				'panel' => $wp_customize->get_panel( 'develope_colors_panel' ) ? 'develope_colors_panel' : false,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[background_color]', array(
				'default' => $defaults['background_color'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'develope_settings[background_color]',
				array(
					'label' => __( 'Background Color', 'developress' ),
					'section' => 'body_section',
					'settings' => 'develope_settings[background_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[text_color]', array(
				'default' => $defaults['text_color'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'develope_settings[text_color]',
				array(
					'label' => __( 'Text Color', 'developress' ),
					'section' => 'body_section',
					'settings' => 'develope_settings[text_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[link_color]', array(
				'default' => $defaults['link_color'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'develope_settings[link_color]',
				array(
					'label' => __( 'Link Color', 'developress' ),
					'section' => 'body_section',
					'settings' => 'develope_settings[link_color]',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[link_color_hover]', array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_hex_color',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'develope_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'developress' ),
					'section' => 'body_section',
					'settings' => 'develope_settings[link_color_hover]',
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[link_color_visited]', array(
				'default' => $defaults['link_color_visited'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_hex_color',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'develope_settings[link_color_visited]',
				array(
					'label' => __( 'Link Color Visited', 'developress' ),
					'section' => 'body_section',
					'settings' => 'develope_settings[link_color_visited]',
				)
			)
		);

		$color_defaults = develope_get_color_defaults();

		if ( ! $wp_customize->get_setting( 'develope_settings[blog_post_title_color]' ) ) {
			$wp_customize->add_setting(
				'develope_settings[blog_post_title_color]', array(
					'default' => $color_defaults['blog_post_title_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'blog_post_title_color',
					array(
						'label' => __( 'Blog Post Title', 'developress' ),
						'section' => $wp_customize->get_section( 'content_color_section' ) ? 'content_color_section' : 'body_section',
						'settings' => 'develope_settings[blog_post_title_color]',
					)
				)
			);

			$wp_customize->add_setting(
				'develope_settings[blog_post_title_hover_color]', array(
					'default' => $color_defaults['blog_post_title_hover_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'blog_post_title_hover_color',
					array(
						'label' => __( 'Blog Post Title Hover', 'developress' ),
						'section' => $wp_customize->get_section( 'content_color_section' ) ? 'content_color_section' : 'body_section',
						'settings' => 'develope_settings[blog_post_title_hover_color]',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'nav_color_presets',
			array(
				'default' => 'current',
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_preset_layout',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'nav_color_presets',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Color Presets', 'developress' ),
				'section' => $wp_customize->get_section( 'navigation_color_section' ) ? 'navigation_color_section' : 'body_section',
				'priority' => $wp_customize->get_section( 'navigation_color_section' ) ? 0 : null,
				'choices' => array(
					'current' => __( 'Current', 'developress' ),
					'default' => __( 'Default', 'developress' ),
					'white' => __( 'White', 'developress' ),
					'grey' => __( 'Grey', 'developress' ),
					'red' => __( 'Red', 'developress' ),
					'green' => __( 'Green', 'developress' ),
					'blue' => __( 'Blue', 'developress' ),
				),
				'settings' => 'nav_color_presets',
			)
		);

		if ( ! $wp_customize->get_setting( 'develope_settings[navigation_background_color]' ) ) {
			$wp_customize->add_setting(
				'develope_settings[navigation_background_color]', array(
					'default' => $color_defaults['navigation_background_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[navigation_text_color]', array(
					'default' => $color_defaults['navigation_text_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[navigation_background_hover_color]',
				array(
					'default'     => $color_defaults['navigation_background_hover_color'],
					'type'        => 'option',
					'transport'   => 'postMessage',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[navigation_text_hover_color]',
				array(
					'default'     => $color_defaults['navigation_text_hover_color'],
					'type'        => 'option',
					'transport'   => 'postMessage',
					'sanitize_callback' => 'develope_sanitize_hex_color',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[navigation_background_current_color]',
				array(
					'default' => $color_defaults['navigation_background_current_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[navigation_text_current_color]',
				array(
					'default' => $color_defaults['navigation_text_current_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_background_color]',
				array(
					'default'     => $color_defaults['subnavigation_background_color'],
					'type'        => 'option',
					'transport'   => 'postMessage',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_text_color]', array(
					'default' => $color_defaults['subnavigation_text_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_background_hover_color]',
				array(
					'default'     => $color_defaults['subnavigation_background_hover_color'],
					'type'        => 'option',
					'transport'   => 'postMessage',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_text_hover_color]', array(
					'default' => $color_defaults['subnavigation_text_hover_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_background_current_color]',
				array(
					'default'     => $color_defaults['subnavigation_background_current_color'],
					'type'        => 'option',
					'transport'   => 'postMessage',
					'sanitize_callback' => 'develope_sanitize_rgba_color',
				)
			);

			$wp_customize->add_setting(
				'develope_settings[subnavigation_text_current_color]', array(
					'default' => $color_defaults['subnavigation_text_current_color'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_hex_color',
					'transport' => 'postMessage',
				)
			);
		}

		if ( ! function_exists( 'develope_colors_customize_register' ) && ! defined( 'DP_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Develope_Customize_Misc_Control(
					$wp_customize,
					'colors_get_addon_desc',
					array(
						'section' => 'body_section',
						'type' => 'addon',
						'label' => __( 'Learn More', 'developress' ),
						'description' => __( 'More options are available for this section in our premium version.', 'developress' ),
						'url' => develope_get_premium_url( 'https://developress.org/premium/#colors', false ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					)
				)
			);
		}

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'develope_layout_panel' ) ) {
				$wp_customize->add_panel( 'develope_layout_panel', array(
					'priority' => 25,
					'title' => __( 'Layout', 'developress' ),
				) );
			}
		}

		$wp_customize->add_section(
			'develope_layout_container',
			array(
				'title' => __( 'Container', 'developress' ),
				'priority' => 10,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[container_width]',
			array(
				'default' => $defaults['container_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_integer',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new Develope_Range_Slider_Control(
				$wp_customize,
				'develope_settings[container_width]',
				array(
					'type' => 'developress-range-slider',
					'label' => __( 'Container Width', 'developress' ),
					'section' => 'develope_layout_container',
					'settings' => array(
						'desktop' => 'develope_settings[container_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 700,
							'max' => 2000,
							'step' => 5,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 0,
				)
			)
		);

		$wp_customize->add_section(
			'develope_top_bar',
			array(
				'title' => __( 'Top Bar', 'developress' ),
				'priority' => 15,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[top_bar_width]',
			array(
				'default' => $defaults['top_bar_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[top_bar_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Width', 'developress' ),
				'section' => 'develope_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'developress' ),
					'contained' => __( 'Contained', 'developress' ),
				),
				'settings' => 'develope_settings[top_bar_width]',
				'priority' => 5,
				'active_callback' => 'develope_is_top_bar_active',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[top_bar_inner_width]',
			array(
				'default' => $defaults['top_bar_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[top_bar_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Inner Width', 'developress' ),
				'section' => 'develope_top_bar',
				'choices' => array(
					'full' => __( 'Full', 'developress' ),
					'contained' => __( 'Contained', 'developress' ),
				),
				'settings' => 'develope_settings[top_bar_inner_width]',
				'priority' => 10,
				'active_callback' => 'develope_is_top_bar_active',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[top_bar_alignment]',
			array(
				'default' => $defaults['top_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[top_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar Alignment', 'developress' ),
				'section' => 'develope_top_bar',
				'choices' => array(
					'left' => __( 'Left', 'developress' ),
					'center' => __( 'Center', 'developress' ),
					'right' => __( 'Right', 'developress' ),
				),
				'settings' => 'develope_settings[top_bar_alignment]',
				'priority' => 15,
				'active_callback' => 'develope_is_top_bar_active',
			)
		);

		$wp_customize->add_section(
			'develope_layout_header',
			array(
				'title' => __( 'Header', 'developress' ),
				'priority' => 20,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_header_helper',
			array(
				'default' 			=> 'current',
				'type' 				=> 'option',
				'sanitize_callback' => 'develope_sanitize_preset_layout',
				'transport' 		=> 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_header_helper',
			array(
				'type' 		=> 'select',
				'label' 	=> __( 'Header Presets', 'developress' ),
				'section' 	=> 'develope_layout_header',
				'choices' 	=> array(
					'current' 				=> __( 'Current', 'developress' ),
					'default'				=> __( 'Default', 'developress' ),
					'nav-before-centered'	=> __( 'Navigation Before - Centered', 'developress' ),
					'nav-after-centered' 	=> __( 'Navigation After - Centered', 'developress' ),
					'nav-right' 		=> __( 'Navigation Right', 'developress' ),
					'nav-left' 		=> __( 'Navigation Left', 'developress' ),
				),
				'settings' 	=> 'develope_header_helper',
				'priority' 	=> 4,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[header_layout_setting]',
			array(
				'default' => $defaults['header_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[header_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Width', 'developress' ),
				'section' => 'develope_layout_header',
				'choices' => array(
					'fluid-header' => __( 'Full', 'developress' ),
					'contained-header' => __( 'Contained', 'developress' ),
				),
				'settings' => 'develope_settings[header_layout_setting]',
				'priority' => 5,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[header_inner_width]',
			array(
				'default' => $defaults['header_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[header_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Header Width', 'developress' ),
				'section' => 'develope_layout_header',
				'choices' => array(
					'contained' => __( 'Contained', 'developress' ),
					'full-width' => __( 'Full', 'developress' ),
				),
				'settings' => 'develope_settings[header_inner_width]',
				'priority' => 6,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[header_alignment_setting]',
			array(
				'default' => $defaults['header_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[header_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Alignment', 'developress' ),
				'section' => 'develope_layout_header',
				'choices' => array(
					'left' => __( 'Left', 'developress' ),
					'center' => __( 'Center', 'developress' ),
					'right' => __( 'Right', 'developress' ),
				),
				'settings' => 'develope_settings[header_alignment_setting]',
				'priority' => 10,
			)
		);

		$wp_customize->add_section(
			'develope_layout_navigation',
			array(
				'title' => __( 'Primary Navigation', 'developress' ),
				'priority' => 30,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_layout_setting]',
			array(
				'default' => $defaults['nav_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Width', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'fluid-nav' => __( 'Full', 'developress' ),
					'contained-nav' => __( 'Contained', 'developress' ),
				),
				'settings' => 'develope_settings[nav_layout_setting]',
				'priority' => 15,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_inner_width]',
			array(
				'default' => $defaults['nav_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Navigation Width', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'contained' => __( 'Contained', 'developress' ),
					'full-width' => __( 'Full', 'developress' ),
				),
				'settings' => 'develope_settings[nav_inner_width]',
				'priority' => 16,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_alignment_setting]',
			array(
				'default' => $defaults['nav_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Alignment', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'left' => __( 'Left', 'developress' ),
					'center' => __( 'Center', 'developress' ),
					'right' => __( 'Right', 'developress' ),
				),
				'settings' => 'develope_settings[nav_alignment_setting]',
				'priority' => 20,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_position_setting]',
			array(
				'default' => $defaults['nav_position_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => ( '' !== develope_get_option( 'nav_position_setting' ) ) ? 'postMessage' : 'refresh',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_position_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Location', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'nav-below-header' => __( 'Below Header', 'developress' ),
					'nav-above-header' => __( 'Above Header', 'developress' ),
					'nav-float-right' => __( 'Float Right', 'developress' ),
					'nav-float-left' => __( 'Float Left', 'developress' ),
					'nav-left-sidebar' => __( 'Left Sidebar', 'developress' ),
					'nav-right-sidebar' => __( 'Right Sidebar', 'developress' ),
					'' => __( 'No Navigation', 'developress' ),
				),
				'settings' => 'develope_settings[nav_position_setting]',
				'priority' => 22,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_drop_point]',
			array(
				'default' => $defaults['nav_drop_point'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_empty_absint',
			)
		);

		$wp_customize->add_control(
			new Develope_Range_Slider_Control(
				$wp_customize,
				'develope_settings[nav_drop_point]',
				array(
					'label' => __( 'Navigation Drop Point', 'developress' ),
					'sub_description' => __( 'The width when the navigation ceases to float and drops below your logo.', 'developress' ),
					'section' => 'develope_layout_navigation',
					'settings' => array(
						'desktop' => 'develope_settings[nav_drop_point]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 500,
							'max' => 2000,
							'step' => 10,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 22,
				)
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_dropdown_type]',
			array(
				'default' => $defaults['nav_dropdown_type'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_dropdown_type]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Dropdown', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'hover' => __( 'Hover', 'developress' ),
					'click' => __( 'Click - Menu Item', 'developress' ),
					'click-arrow' => __( 'Click - Arrow', 'developress' ),
				),
				'settings' => 'develope_settings[nav_dropdown_type]',
				'priority' => 22,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_dropdown_direction]',
			array(
				'default' => $defaults['nav_dropdown_direction'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_dropdown_direction]',
			array(
				'type' => 'select',
				'label' => __( 'Dropdown Direction', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'right' => __( 'Right', 'developress' ),
					'left' => __( 'Left', 'developress' ),
				),
				'settings' => 'develope_settings[nav_dropdown_direction]',
				'priority' => 22,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[nav_search]',
			array(
				'default' => $defaults['nav_search'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[nav_search]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Search', 'developress' ),
				'section' => 'develope_layout_navigation',
				'choices' => array(
					'enable' => __( 'Enable', 'developress' ),
					'disable' => __( 'Disable', 'developress' ),
				),
				'settings' => 'develope_settings[nav_search]',
				'priority' => 23,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[content_layout_setting]',
			array(
				'default' => $defaults['content_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[content_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Content Layout', 'developress' ),
				'section' => 'develope_layout_container',
				'choices' => array(
					'separate-containers' => __( 'Separate Containers', 'developress' ),
					'one-container' => __( 'One Container', 'developress' ),
				),
				'settings' => 'develope_settings[content_layout_setting]',
				'priority' => 25,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[container_alignment]',
			array(
				'default' => $defaults['container_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[container_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Container Alignment', 'developress' ),
				'section' => 'develope_layout_container',
				'choices' => array(
					'boxes' => __( 'Boxes', 'developress' ),
					'text' => __( 'Text', 'developress' ),
				),
				'settings' => 'develope_settings[container_alignment]',
				'priority' => 30,
			)
		);

		$wp_customize->add_section(
			'develope_layout_sidebars',
			array(
				'title' => __( 'Sidebars', 'developress' ),
				'priority' => 40,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[layout_setting]',
			array(
				'default' => $defaults['layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Sidebar Layout', 'developress' ),
				'section' => 'develope_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'developress' ),
					'right-sidebar' => __( 'Content / Sidebar', 'developress' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'developress' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'developress' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'developress' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'developress' ),
				),
				'settings' => 'develope_settings[layout_setting]',
				'priority' => 30,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[blog_layout_setting]',
			array(
				'default' => $defaults['blog_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[blog_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Blog Sidebar Layout', 'developress' ),
				'section' => 'develope_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'developress' ),
					'right-sidebar' => __( 'Content / Sidebar', 'developress' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'developress' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'developress' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'developress' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'developress' ),
				),
				'settings' => 'develope_settings[blog_layout_setting]',
				'priority' => 35,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[single_layout_setting]',
			array(
				'default' => $defaults['single_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[single_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Single Post Sidebar Layout', 'developress' ),
				'section' => 'develope_layout_sidebars',
				'choices' => array(
					'left-sidebar' => __( 'Sidebar / Content', 'developress' ),
					'right-sidebar' => __( 'Content / Sidebar', 'developress' ),
					'no-sidebar' => __( 'Content (no sidebars)', 'developress' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'developress' ),
					'both-left' => __( 'Sidebar / Sidebar / Content', 'developress' ),
					'both-right' => __( 'Content / Sidebar / Sidebar', 'developress' ),
				),
				'settings' => 'develope_settings[single_layout_setting]',
				'priority' => 36,
			)
		);

		$wp_customize->add_section(
			'develope_layout_footer',
			array(
				'title' => __( 'Footer', 'developress' ),
				'priority' => 50,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[footer_layout_setting]',
			array(
				'default' => $defaults['footer_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[footer_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Width', 'developress' ),
				'section' => 'develope_layout_footer',
				'choices' => array(
					'fluid-footer' => __( 'Full', 'developress' ),
					'contained-footer' => __( 'Contained', 'developress' ),
				),
				'settings' => 'develope_settings[footer_layout_setting]',
				'priority' => 40,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[footer_inner_width]',
			array(
				'default' => $defaults['footer_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[footer_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Footer Width', 'developress' ),
				'section' => 'develope_layout_footer',
				'choices' => array(
					'contained' => __( 'Contained', 'developress' ),
					'full-width' => __( 'Full', 'developress' ),
				),
				'settings' => 'develope_settings[footer_inner_width]',
				'priority' => 41,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[footer_widget_setting]',
			array(
				'default' => $defaults['footer_widget_setting'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[footer_widget_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Widgets', 'developress' ),
				'section' => 'develope_layout_footer',
				'choices' => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'settings' => 'develope_settings[footer_widget_setting]',
				'priority' => 45,
			)
		);

		$wp_customize->add_setting(
			'develope_settings[footer_bar_alignment]',
			array(
				'default' => $defaults['footer_bar_alignment'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
				'transport' => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'develope_settings[footer_bar_alignment]',
			array(
				'type' => 'select',
				'label' => __( 'Footer Bar Alignment', 'developress' ),
				'section' => 'develope_layout_footer',
				'choices' => array(
					'left' => __( 'Left','developress' ),
					'center' => __( 'Center','developress' ),
					'right' => __( 'Right','developress' ),
				),
				'settings' => 'develope_settings[footer_bar_alignment]',
				'priority' => 47,
				'active_callback' => 'develope_is_footer_bar_active',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[back_to_top]',
			array(
				'default' => $defaults['back_to_top'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[back_to_top]',
			array(
				'type' => 'select',
				'label' => __( 'Back to Top Button', 'developress' ),
				'section' => 'develope_layout_footer',
				'choices' => array(
					'enable' => __( 'Enable', 'developress' ),
					'' => __( 'Disable', 'developress' ),
				),
				'settings' => 'develope_settings[back_to_top]',
				'priority' => 50,
			)
		);

		$wp_customize->add_section(
			'develope_blog_section',
			array(
				'title' => __( 'Blog', 'developress' ),
				'priority' => 55,
				'panel' => 'develope_layout_panel',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[post_content]',
			array(
				'default' => $defaults['post_content'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_blog_excerpt',
			)
		);

		$wp_customize->add_control(
			'blog_content_control',
			array(
				'type' => 'select',
				'label' => __( 'Content Type', 'developress' ),
				'section' => 'develope_blog_section',
				'choices' => array(
					'full' => __( 'Full', 'developress' ),
					'excerpt' => __( 'Excerpt', 'developress' ),
				),
				'settings' => 'develope_settings[post_content]',
				'priority' => 10,
			)
		);

		if ( ! function_exists( 'develope_blog_customize_register' ) && ! defined( 'DP_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new Develope_Customize_Misc_Control(
					$wp_customize,
					'blog_get_addon_desc',
					array(
						'section' => 'develope_blog_section',
						'type' => 'addon',
						'label' => __( 'Learn more', 'developress' ),
						'description' => __( 'More options are available for this section in our premium version.', 'developress' ),
						'url' => develope_get_premium_url( 'https://developress.org/premium/#blog', false ),
						'priority' => 30,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					)
				)
			);
		}

		$wp_customize->add_section(
			'develope_general_section',
			array(
				'title' => __( 'General', 'developress' ),
				'priority' => 99,
			)
		);

		if ( ! apply_filters( 'develope_fontawesome_essentials', false ) ) {
			$wp_customize->add_setting(
				'develope_settings[font_awesome_essentials]',
				array(
					'default' => $defaults['font_awesome_essentials'],
					'type' => 'option',
					'sanitize_callback' => 'develope_sanitize_checkbox',
				)
			);

			$wp_customize->add_control(
				'develope_settings[font_awesome_essentials]',
				array(
					'type' => 'checkbox',
					'label' => __( 'Load essential icons only', 'developress' ),
					'description' => __( 'Load essential Font Awesome icons instead of the full library.', 'developress' ),
					'section' => 'develope_general_section',
					'settings' => 'develope_settings[font_awesome_essentials]',
				)
			);
		}

		$wp_customize->add_setting(
			'develope_settings[icons]',
			array(
				'default' => $defaults['icons'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'develope_settings[icons]',
			array(
				'type' => 'select',
				'label' => __( 'Icon Type', 'developress' ),
				'section' => 'develope_general_section',
				'choices' => array(
					'svg' => __( 'SVG', 'developress' ),
					'font' => __( 'Font', 'developress' ),
				),
				'settings' => 'develope_settings[icons]',
			)
		);

		$wp_customize->add_setting(
			'develope_settings[combine_css]',
			array(
				'default' => $defaults['combine_css'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_checkbox',
			)
		);

		/* Do no show the option right now, until we integrate Tailwind 
		$wp_customize->add_control(
			'develope_settings[combine_css]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Combine CSS', 'developress' ),
				'description' => __( 'Reduce the number of CSS file requests and use a lite version of our grid system.', 'developress' ),
				'section' => 'develope_general_section',
			)
		);
		*/
		$wp_customize->add_setting(
			'develope_settings[dynamic_css_cache]',
			array(
				'default' => $defaults['dynamic_css_cache'],
				'type' => 'option',
				'sanitize_callback' => 'develope_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'develope_settings[dynamic_css_cache]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Cache dynamic CSS', 'developress' ),
				'description' => __( 'Cache CSS generated by your options to boost performance.', 'developress' ),
				'section' => 'develope_general_section',
			)
		);
	}
}
