<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Controls_Stack;
use madxartworkPro\Modules\QueryControl\Controls\Group_Control_Query;
use madxartworkPro\Modules\Woocommerce\Classes\Products_Renderer;
use madxartworkPro\Modules\Woocommerce\Classes\Current_Query_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Products extends Products_Base {

	public function get_name() {
		return 'woocommerce-products';
	}

	public function get_title() {
		return __( 'Products', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'archive' ];
	}

	public function get_categories() {
		return [
			'woocommerce-elements',
		];
	}

	protected function register_query_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Query::get_type(),
			[
				'name' => Products_Renderer::QUERY_CONTROL_NAME,
				'post_type' => 'product',
				'presets' => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'product',
						'options' => [
							'current_query' => __( 'Current Query', 'madxartwork-pro' ),
							'product' => __( 'Latest Products', 'madxartwork-pro' ),
							'sale' => __( 'Sale', 'madxartwork-pro' ),
							'featured' => __( 'Featured', 'madxartwork-pro' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'madxartwork-pro' ),
						],
					],
					'orderby' => [
						'default' => 'date',
						'options' => [
							'date' => __( 'Date', 'madxartwork-pro' ),
							'title' => __( 'Title', 'madxartwork-pro' ),
							'price' => __( 'Price', 'madxartwork-pro' ),
							'popularity' => __( 'Popularity', 'madxartwork-pro' ),
							'rating' => __( 'Rating', 'madxartwork-pro' ),
							'rand' => __( 'Random', 'madxartwork-pro' ),
							'menu_order' => __( 'Menu Order', 'madxartwork-pro' ),
						],
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'madxartwork-pro' ),
							'manual_selection' => __( 'Manual Selection', 'madxartwork-pro' ),
							'terms' => __( 'Term', 'madxartwork-pro' ),
						],
					],
					'include' => [
						'options' => [
							'terms' => __( 'Term', 'madxartwork-pro' ),
						],
					],
				],
				'exclude' => [
					'posts_per_page',
					'exclude_authors',
					'authors',
					'offset',
					'related_fallback',
					'related_ids',
					'query_id',
					'avoid_duplicates',
					'ignore_sticky_posts',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'madxartwork-pro' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'madxartwork-pro' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'madxartwork-products-columns%s-',
				'min' => 1,
				'max' => 12,
				'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'required' => true,
				'render_type' => 'template',
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
			]
		);

		$this->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'madxartwork-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'render_type' => 'template',
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
			]
		);

		$this->add_control(
			'paginate',
			[
				'label' => __( 'Pagination', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'allow_order',
			[
				'label' => __( 'Allow Order', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'wc_notice_frontpage',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'madxartwork-pro' ),
				'content_classes' => 'madxartwork-panel-alert madxartwork-panel-alert-info',
				'condition' => [
					'paginate' => 'yes',
					'allow_order' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_result_count',
			[
				'label' => __( 'Show Result Count', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->register_query_controls();

		parent::_register_controls();
	}

	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	protected function render() {

		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // WPCS: override ok.
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content;
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="madxartwork-nothing-found madxartwork-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
		}
	}

	public function render_plain_content() {}
}
