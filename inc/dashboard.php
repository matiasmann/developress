<?php
/**
 * Builds our admin page.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'develope_create_menu' ) ) {
	add_action( 'admin_menu', 'develope_create_menu' );
	/**
	 * Adds our "DeveloPress" dashboard menu item
	 *
	 * @since 0.1
	 */
	function develope_create_menu() {
		$develope_page = add_theme_page( 'DeveloPress', 'DeveloPress', apply_filters( 'develope_dashboard_page_capability', 'edit_theme_options' ), 'develope-options', 'develope_settings_page' );
		add_action( "admin_print_styles-$develope_page", 'develope_options_styles' );
	}
}

if ( ! function_exists( 'develope_options_styles' ) ) {
	/**
	 * Adds any necessary scripts to the DP dashboard page
	 *
	 * @since 0.1
	 */
	function develope_options_styles() {
		wp_enqueue_style( 'develope-options', get_template_directory_uri() . '/css/admin/style.css', array(), DEVELOPE_VERSION );
	}
}

if ( ! function_exists( 'develope_settings_page' ) ) {
	/**
	 * Builds the content of our DP dashboard page
	 *
	 * @since 0.1
	 */
	function develope_settings_page() {
		?>
		<div class="wrap">
			<div class="metabox-holder">
				<div class="dp-masthead clearfix">
					<div class="dp-container">
						<div class="dp-title">
							<a href="<?php echo develope_get_premium_url( 'https://developress.org' ); // WPCS: XSS ok, sanitization ok. ?>" target="_blank">DeveloPress</a> <span class="dp-version"><?php echo DEVELOPE_VERSION; // WPCS: XSS ok ?></span>
						</div>
						<div class="dp-masthead-links">
							<?php if ( ! defined( 'DP_PREMIUM_VERSION' ) ) : ?>
								<a style="font-weight: bold;" href="<?php echo develope_get_premium_url( 'https://developress.org/premium/' ); // WPCS: XSS ok, sanitization ok. ?>" target="_blank"><?php esc_html_e( 'Premium', 'developress' );?></a>
							<?php endif; ?>
							<a href="<?php echo esc_url( 'https://developress.org/support' ); ?>" target="_blank"><?php esc_html_e( 'Support', 'developress' ); ?></a>
							<a href="<?php echo esc_url( 'https://docs.developress.com' ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'developress' );?></a>
						</div>
					</div>
				</div>

				<?php
				/**
				 * develope_dashboard_after_header hook.
				 *
				 * @since 2.0
				 */
				do_action( 'develope_dashboard_after_header' );
				?>

				<div class="dp-container">
					<div class="postbox-container clearfix" style="float: none;">
						<div class="grid-container grid-parent">

							<?php
							/**
							 * develope_dashboard_inside_container hook.
							 *
							 * @since 2.0
							 */
							do_action( 'develope_dashboard_inside_container' );
							?>

							<div class="form-metabox grid-70" style="padding-left: 0;">
								<h2 style="height:0;margin:0;"><!-- admin notices below this element --></h2>
								<form method="post" action="options.php">
									<?php settings_fields( 'develope-settings-group' ); ?>
									<?php do_settings_sections( 'develope-settings-group' ); ?>
									<div class="customize-button hide-on-desktop">
										<?php
										printf( '<a id="develope_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
											esc_url( admin_url( 'customize.php' ) ),
											esc_html__( 'Customize', 'developress' )
										);
										?>
									</div>

									<?php
									/**
									 * develope_inside_options_form hook.
									 *
									 * @since 0.1
									 */
									do_action( 'develope_inside_options_form' );
									?>
								</form>

								<?php
								$modules = array(
									'Backgrounds' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#backgrounds', false ),
									),
									'Blog' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#blog', false ),
									),
									'Colors' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#colors', false ),
									),
									'Copyright' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#copyright', false ),
									),
									'Disable Elements' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#disable-elements', false ),
									),
									'Elements' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#elements', false ),
									),
									'Import / Export' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#import-export', false ),
									),
									'Menu Plus' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#menu-plus', false ),
									),
									'Secondary Nav' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#secondary-nav', false ),
									),
									'Sections' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#sections', false ),
									),
									'Site Library' => array(
											'url' => develope_get_premium_url( 'https://developress.org/site-library', false ),
									),
									'Spacing' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#spacing', false ),
									),
									'Typography' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#typography', false ),
									),
									'WooCommerce' => array(
											'url' => develope_get_premium_url( 'https://developress.org/premium/#woocommerce', false ),
									),
								);

								if ( ! defined( 'DP_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox develope-metabox">
										<h3 class="hndle"><?php esc_html_e( 'Premium Modules', 'developress' ); ?></h3>
										<div class="inside" style="margin:0;padding:0;">
											<div class="premium-addons">
												<?php foreach( $modules as $module => $info ) { ?>
												<div class="add-on activated dp-clear addon-container grid-parent">
													<div class="addon-name column-addon-name" style="">
														<a href="<?php echo esc_url( $info['url'] ); ?>" target="_blank"><?php echo esc_html( $module ); ?></a>
													</div>
													<div class="addon-action addon-addon-action" style="text-align:right;">
														<a href="<?php echo esc_url( $info['url'] ); ?>" target="_blank"><?php esc_html_e( 'Learn more', 'developress' ); ?></a>
													</div>
												</div>
												<div class="dp-clear"></div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php
								endif;

								/**
								 * develope_options_items hook.
								 *
								 * @since 0.1
								 */
								do_action( 'develope_options_items' );

								$typography_section = 'customize.php?autofocus[section]=font_section';
								$colors_section = 'customize.php?autofocus[section]=body_section';

								if ( function_exists( 'developress_is_module_active' ) ) {
									if ( developress_is_module_active( 'develope_package_typography', 'DEVELOPE_TYPOGRAPHY' ) ) {
										$typography_section = 'customize.php?autofocus[panel]=develope_typography_panel';
									}

									if ( developress_is_module_active( 'develope_package_colors', 'DEVELOPE_COLORS' ) ) {
										$colors_section = 'customize.php?autofocus[panel]=develope_colors_panel';
									}
								}

								$quick_settings = array(
									'logo' => array(
										'title' => __( 'Upload Logo', 'developress' ),
										'icon' => 'dashicons-format-image',
										'url' => admin_url( 'customize.php?autofocus[control]=custom_logo' ),
									),
									'typography' => array(
										'title' => __( 'Customize Fonts', 'developress' ),
										'icon' => 'dashicons-editor-textcolor',
										'url' => admin_url( $typography_section ),
									),
									'colors' => array(
										'title' => __( 'Customize Colors', 'developress' ),
										'icon' => 'dashicons-admin-customizer',
										'url' => admin_url( $colors_section ),
									),
									'layout' => array(
										'title' => __( 'Layout Options', 'developress' ),
										'icon' => 'dashicons-layout',
										'url' => admin_url( 'customize.php?autofocus[panel]=develope_layout_panel' ),
									),
									'all' => array(
										'title' => __( 'All Options', 'developress' ),
										'icon' => 'dashicons-admin-generic',
										'url' => admin_url( 'customize.php' ),
									),
								);
								?>
							</div>

							<div class="develope-right-sidebar grid-30" style="padding-right: 0;">
								<div class="postbox develope-metabox start-customizing">
									<h3 class="hndle"><?php esc_html_e( 'Start Customizing', 'developress' ); ?></h3>
									<div class="inside">
										<ul>
											<?php
											foreach ( $quick_settings as $key => $data ) {
												printf(
													'<li><span class="dashicons %1$s"></span> <a href="%2$s">%3$s</a></li>',
													esc_attr( $data['icon'] ),
													esc_url( $data['url'] ),
													esc_html( $data['title'] )
												);
											}
											?>
										</ul>

										<p><?php esc_html_e( 'Want to learn more about the theme? Check out our extensive documentation.', 'developress' ); ?></p>
										<a href="https://docs.developress.com"><?php esc_html_e( 'Visit documentation &rarr;', 'developress' ); ?></a>
									</div>
								</div>

								<?php
								/**
								 * develope_admin_right_panel hook.
								 *
								 * @since 0.1
								 */
								do_action( 'develope_admin_right_panel' );
								?>

								<div class="postbox develope-metabox" id="gen-delete">
									<h3 class="hndle"><?php esc_html_e( 'Reset Settings', 'developress' );?></h3>
									<div class="inside">
										<p><?php esc_html_e( 'Deleting your settings can not be undone.', 'developress' ); ?></p>
										<form method="post">
											<p><input type="hidden" name="develope_reset_customizer" value="develope_reset_customizer_settings" /></p>
											<p>
												<?php
												$warning = 'return confirm("' . esc_html__( 'Warning: This will delete your settings.', 'developress' ) . '")';
												wp_nonce_field( 'develope_reset_customizer_nonce', 'develope_reset_customizer_nonce' );
												submit_button( esc_attr__( 'Reset', 'developress' ), 'button-primary', 'submit', false,
													array(
														'onclick' => esc_js( $warning )
													)
												);
												?>
											</p>

										</form>
										<?php
										/**
										 * develope_delete_settings_form hook.
										 *
										 * @since 0.1
										 */
										do_action( 'develope_delete_settings_form' );
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dp-options-footer">
						<span>
							<?php
							printf( // WPCS: XSS ok
								/* translators: %s: Heart icon */
								_x( 'Made with %s by DeveloPress', 'made with love', 'developress' ),
								'<span style="color:#D04848" class="dashicons dashicons-heart"></span>'
							);
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'develope_reset_customizer_settings' ) ) {
	add_action( 'admin_init', 'develope_reset_customizer_settings' );
	/**
	 * Reset customizer settings
	 *
	 * @since 0.1
	 */
	function develope_reset_customizer_settings() {
		if ( empty( $_POST['develope_reset_customizer'] ) || 'develope_reset_customizer_settings' !== $_POST['develope_reset_customizer'] ) {
			return;
		}

		$nonce = isset( $_POST['develope_reset_customizer_nonce'] ) ? sanitize_key( $_POST['develope_reset_customizer_nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'develope_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		delete_option( 'develope_settings' );
		delete_option( 'develope_dynamic_css_output' );
		delete_option( 'develope_dynamic_css_cached_version' );
		remove_theme_mod( 'font_body_variants' );
		remove_theme_mod( 'font_body_category' );

		wp_safe_redirect( admin_url( 'themes.php?page=develope-options&status=reset' ) );
		exit;
	}
}

if ( ! function_exists( 'develope_admin_errors' ) ) {
	add_action( 'admin_notices', 'develope_admin_errors' );
	/**
	 * Add our admin notices
	 *
	 * @since 0.1
	 */
	function develope_admin_errors() {
		$screen = get_current_screen();

		if ( 'appearance_page_develope-options' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			 add_settings_error( 'develope-notices', 'true', esc_html__( 'Settings saved.', 'developress' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'imported' == $_GET['status'] ) {
			 add_settings_error( 'develope-notices', 'imported', esc_html__( 'Import successful.', 'developress' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'reset' == $_GET['status'] ) {
			 add_settings_error( 'develope-notices', 'reset', esc_html__( 'Settings removed.', 'developress' ), 'updated' );
		}

		settings_errors( 'develope-notices' );
	}
}
