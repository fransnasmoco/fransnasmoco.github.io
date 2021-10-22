<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;
use madxartwork\Scheme_Color;
use madxartwork\Scheme_Typography;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Archive_Products_Deprecated
 * @deprecated 2.4.1 use Archive_Products
 */
class Archive_Products_Deprecated extends Products {

	public function get_name() {
		return 'woocommerce-archive-products';
	}

	public function get_title() {
		return __( 'Archive Products (deprecated)', 'madxartwork-pro' );
	}

	public function get_categories() {
		return [
			'woocommerce-elements-archive',
		];
	}

	/* Deprecated Widget */
	public function show_in_panel() {
		return false;
	}

	protected function _register_controls() {
		$this->deprecated_notice( Plugin::get_title(), '2.5.0', '', __( 'Archive Products', 'madxartwork-pro' ) );

		parent::_register_controls();

		$this->start_injection( [
			'at' => 'before',
			'of' => 'columns',
		] );

		$this->add_control(
			'wc_notice_do_not_use_customizer',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Note that these layout settings will override settings made in Appearance > Customize', 'madxartwork-pro' ),
				'content_classes' => 'madxartwork-panel-alert madxartwork-panel-alert-warning',
			]
		);

		$this->end_injection();

		$this->update_control(
			'rows',
			[
				'default' => 4,
			],
			[
				'recursive' => true,
			]
		);

		$this->update_control(
			'paginate',
			[
				'default' => 'yes',
			]
		);

		$this->update_control(
			'section_query',
			[
				'type' => 'hidden',
			]
		);

		$this->update_control(
			'query_post_type',
			[
				'default' => 'current_query',
			]
		);

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => __( 'Advanced', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'nothing_found_message',
			[
				'label' => __( 'Nothing Found Message', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'It seems we can\'t find what you\'re looking for.', 'madxartwork-pro' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_nothing_found_style',
			[
				'tab' => Controls_Manager::TAB_STYLE,
				'label' => __( 'Nothing Found Message', 'madxartwork-pro' ),
				'condition' => [
					'nothing_found_message!' => '',
				],
			]
		);

		$this->add_control(
			'nothing_found_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-products-nothing-found' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nothing_found_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .madxartwork-products-nothing-found',
			]
		);

		$this->end_controls_section();
	}

	public function render_no_results() {
		echo '<div class="madxartwork-nothing-found madxartwork-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
	}

	protected function render() {
		add_action( 'woocommerce_shortcode_products_loop_no_results', [ $this, 'render_no_results' ] );

		parent::render();
	}
}
