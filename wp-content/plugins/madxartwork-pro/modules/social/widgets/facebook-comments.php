<?php
namespace madxartworkPro\Modules\Social\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Widget_Base;
use madxartworkPro\Modules\Social\Classes\Facebook_SDK_Manager;
use madxartworkPro\Modules\Social\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Facebook_Comments extends Widget_Base {

	public function get_name() {
		return 'facebook-comments';
	}

	public function get_title() {
		return __( 'Facebook Comments', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-facebook-comments';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'facebook', 'comments', 'embed' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments Box', 'madxartwork-pro' ),
			]
		);

		Facebook_SDK_Manager::add_app_id_control( $this );

		$this->add_control(
			'comments_number',
			[
				'label' => __( 'Comment Count', 'madxartwork-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 100,
				'default' => '10',
				'description' => __( 'Minimum number of comments: 5', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'order_by',
			[
				'label' => __( 'Order By', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'social',
				'options' => [
					'social' => __( 'Social', 'madxartwork-pro' ),
					'reverse_time' => __( 'Reverse Time', 'madxartwork-pro' ),
					'time' => __( 'Time', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_TYPE_CURRENT_PAGE => __( 'Current Page', 'madxartwork-pro' ),
					Module::URL_TYPE_CUSTOM => __( 'Custom', 'madxartwork-pro' ),
				],
				'default' => Module::URL_TYPE_CURRENT_PAGE,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'url_format',
			[
				'label' => __( 'URL Format', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_FORMAT_PLAIN => __( 'Plain Permalink', 'madxartwork-pro' ),
					Module::URL_FORMAT_PRETTY => __( 'Pretty Permalink', 'madxartwork-pro' ),
				],
				'default' => Module::URL_FORMAT_PLAIN,
				'condition' => [
					'url_type' => Module::URL_TYPE_CURRENT_PAGE,
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'madxartwork-pro' ),
				'placeholder' => __( 'https://your-link.com', 'madxartwork-pro' ),
				'label_block' => true,
				'condition' => [
					'url_type' => Module::URL_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::URL_TYPE_CURRENT_PAGE === $settings['url_type'] ) {
			$permalink = Facebook_SDK_Manager::get_permalink( $settings );
		} else {
			if ( ! filter_var( $settings['url'], FILTER_VALIDATE_URL ) ) {
				echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'madxartwork-pro' ); // XSS ok.

				return;
			}

			$permalink = esc_url( $settings['url'] );
		}

		$attributes = [
			'class' => 'madxartwork-facebook-widget fb-comments',
			'data-href' => $permalink,
			'data-numposts' => $settings['comments_number'],
			'data-order-by' => $settings['order_by'],
			// The style prevent's the `widget.handleEmptyWidget` to set it as an empty widget
			'style' => 'min-height: 1px',
		];

		$this->add_render_attribute( 'embed_div', $attributes );

		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
	}

	public function render_plain_content() {}
}
