<?php
/**
 * The upsell Customizer section.
 *
 * @package DeveloPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'Develope_Upsell_Section' ) ) {
	/**
	 * Create our upsell section.
	 * Escape your URL in the Customizer using esc_url().
	 *
	 * @since unknown
	 */
	class Develope_Upsell_Section extends WP_Customize_Section {
		public $type = 'dp-upsell-section';
		public $pro_url = '';
		public $pro_text = '';
		public $id = '';

		public function json() {
			$json = parent::json();
			$json['pro_text'] = $this->pro_text;
			$json['pro_url']  = esc_url( $this->pro_url );
			$json['id'] = $this->id;
			return $json;
		}

		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="develope-upsell-accordion-section control-section-{{ data.type }} cannot-expand accordion-section">
				<h3><a href="{{{ data.pro_url }}}" target="_blank">{{ data.pro_text }}</a></h3>
			</li>
			<?php
		}
	}
}

if ( ! function_exists( 'develope_customizer_controls_css' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'develope_customizer_controls_css' );
	/**
	 * Add CSS for our controls
	 *
	 * @since 1.3.41
	 */
	function develope_customizer_controls_css() {
		wp_enqueue_style( 'develope-customizer-controls-css', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/css/upsell-customizer.css', array(), DEVELOPE_VERSION );
		wp_enqueue_script( 'developress-upsell', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/upsell-control.js', array( 'customize-controls' ), false, true );
	}
}
