<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartwork\Controls_Manager;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Single_Elements extends Widget_Base {

	public function get_name() {
		return 'wc-single-elements';
	}

	public function get_title() {
		return __( 'Woo - Single Elements', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/* Deprecated Widget */
	public function show_in_panel() {
		return false;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Element', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'element',
			[
				'label' => __( 'Element', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . __( 'Select', 'madxartwork-pro' ) . ' —',
					'woocommerce_output_product_data_tabs' => __( 'Data Tabs', 'madxartwork-pro' ),
					'woocommerce_template_single_title' => __( 'Title', 'madxartwork-pro' ),
					'woocommerce_template_single_rating' => __( 'Rating', 'madxartwork-pro' ),
					'woocommerce_template_single_price' => __( 'Price', 'madxartwork-pro' ),
					'woocommerce_template_single_excerpt' => __( 'Excerpt', 'madxartwork-pro' ),
					'woocommerce_template_single_meta' => __( 'Meta', 'madxartwork-pro' ),
					'woocommerce_template_single_sharing' => __( 'Sharing', 'madxartwork-pro' ),
					'woocommerce_show_product_sale_flash' => __( 'Sale Flash', 'madxartwork-pro' ),
					'woocommerce_product_additional_information_tab' => __( 'Additional Information Tab', 'madxartwork-pro' ),
					'woocommerce_upsell_display' => __( 'Upsell', 'madxartwork-pro' ),
					'wc_get_stock_html' => __( 'Stock Status', 'madxartwork-pro' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function remove_description_tab( $tabs ) {
		unset( $tabs['description'] );

		return $tabs;
	}

	private function get_element() {
		global $product;
		$product = wc_get_product();
		$settings = $this->get_settings();
		$html = '';

		switch ( $settings['element'] ) {
			case '':
				break;

			case 'wc_get_stock_html':
				$html = wc_get_stock_html( $product );
				break;

			case 'woocommerce_output_product_data_tabs':
				add_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 /* after default tabs*/ );
				ob_start();
				woocommerce_output_product_data_tabs();
				// Wrap with the internal woocommerce `product` class
				$html = '<div class="product">' . ob_get_clean() . '</div>';
				remove_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 );
				break;

			case 'woocommerce_template_single_rating':
				$is_edit_mode = Plugin::madxartwork()->editor->is_edit_mode();

				if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
					if ( $is_edit_mode ) {
						$html = __( 'Admin Notice:', 'madxartwork-pro' ) . ' ' . __( 'Please enable the Review Rating', 'madxartwork-pro' );
					}
					break;
				}

				ob_start();
				woocommerce_template_single_rating();
				$html = ob_get_clean();
				if ( '' === $html && $is_edit_mode ) {
					$html = __( 'Admin Notice:', 'madxartwork-pro' ) . ' ' . __( 'No Rating Reviews', 'madxartwork-pro' );
				}
				break;

			default:
				if ( is_callable( $settings['element'] ) ) {
					$html = call_user_func( $settings['element'] );
				}
		}

		return $html;
	}

	protected function render() {
		echo $this->get_element();
	}

	public function render_plain_content() {}
}
