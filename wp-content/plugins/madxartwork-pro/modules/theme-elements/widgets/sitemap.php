<?php
namespace madxartworkPro\Modules\ThemeElements\Widgets;

use madxartwork\Controls_Manager;
use madxartworkPro\Modules\QueryControl\Module as Query_Module;
use madxartworkPro\Modules\QueryControl\Controls\Group_Control_Query;
use madxartworkPro\Modules\QueryControl\Classes\madxartwork_Post_Query;
use madxartwork\Repeater;
use madxartworkPro\Classes\Utils;
use madxartwork\Group_Control_Typography;
use madxartwork\Scheme_Color;
use madxartwork\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork sitemap widget.
 *
 * madxartwork widget that displays an HTML sitemap.
 *
 */
class Sitemap extends Base {

	public function get_name() {
		return 'sitemap';
	}

	public function get_title() {
		return __( 'Sitemap', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-sitemap';
	}

	public function get_keywords() {
		return [ 'taxonomy', 'custom post type', 'cpt', 'sitemap', 'site map', 'link', 'menu', 'tree' ];
	}

	private function register_sitemap_tab() {
		$this->start_controls_section(
			'sitemap_section',
			[
				'label' => __( 'Sitemap', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_post_type_controls();

		$this->add_control(
			'sitemap_layout_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->register_layout_controls();

		$this->end_controls_section();

		$this->register_additional_settings_section();
	}

	private function register_additional_settings_section() {
		$this->start_controls_section(
			'sitemap_query_section',
			[
				'label' => __( 'Additional Options', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sitemap_exclude',
			[
				'label' => __( 'Exclude', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'current_post' => __( 'Current Post', 'madxartwork-pro' ),
					'manual_selection' => __( 'Manual Selection', 'madxartwork-pro' ),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'sitemap_exclude_ids',
			[
				'label' => __( 'Search & Select', 'madxartwork-pro' ),
				'type' => Query_Module::QUERY_CONTROL_ID,
				'options' => [],
				'label_block' => true,
				'multiple' => true,
				'autocomplete' => [
					'object' => Query_Module::QUERY_OBJECT_POST,
				],
				'condition' => [
					'sitemap_exclude' => 'manual_selection',
				],
			]
		);

		$this->add_control(
			'sitemap_password_protected',
			[
				'label' => __( 'Protected Posts', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'madxartwork-pro' ),
				'label_on' => __( 'Show', 'madxartwork-pro' ),
			]
		);

		$this->end_controls_section();
	}

	private function register_layout_controls() {
		$this->add_responsive_control(
			'sitemap_columns',
			[
				'label' => __( 'Columns', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '4',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-section' => 'flex-basis: calc( 1 / {{VALUE}} * 100% );',
				],
			]
		);

		$this->add_control(
			'sitemap_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'sitemap_add_nofollow',
			[
				'label' => __( 'Add nofollow', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
	}

	private function register_post_type_controls() {
		$supported_taxonomies = [];

		$public_types = Utils::get_public_post_types();

		foreach ( $public_types as $type => $title ) {
			$taxonomies = get_object_taxonomies( $type, 'objects' );
			foreach ( $taxonomies as $key => $tax ) {
				if ( ! in_array( $tax->name, $supported_taxonomies ) ) {
					$label = $tax->label . ' (' . $tax->name . ')';
					$supported_taxonomies[ $tax->name ] = $label;
				}
			}
		}

		$repeater = new Repeater();

		$repeater->add_control(
			'sitemap_type_selector',
			[
				'label' => __( 'Type', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_type',
				'options' => [
					'post_type' => __( 'Post Type', 'madxartwork-pro' ),
					'taxonomy' => __( 'Taxonomy', 'madxartwork-pro' ),
				],
			]
		);

		$repeater->add_control(
			'sitemap_source_post_type',
			[
				'label' => __( 'Source', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'page',
				'options' => $public_types,
				'condition' => [
					'sitemap_type_selector' => 'post_type',
				],
			]
		);

		$repeater->add_control(
			'sitemap_source_taxonomy',
			[
				'label' => __( 'Source', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $supported_taxonomies,
				'condition' => [
					'sitemap_type_selector' => 'taxonomy',
				],
			]
		);

		$repeater->add_control(
			'sitemap_title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'sitemap_orderby_post_type',
			[
				'label' => __( 'Order By', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date' => __( 'Date', 'madxartwork-pro' ),
					'post_title' => __( 'Title', 'madxartwork-pro' ),
					'menu_order' => __( 'Menu Order', 'madxartwork-pro' ),
					'rand' => __( 'Random', 'madxartwork-pro' ),
				],
				'condition' => [
					'sitemap_type_selector' => 'post_type',
				],
			]
		);

		$repeater->add_control(
			'sitemap_orderby_taxonomy',
			[
				'label' => __( 'Order By', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'id' => __( 'ID', 'madxartwork-pro' ),
					'name' => __( 'Name', 'madxartwork-pro' ),
				],
				'condition' => [
					'sitemap_type_selector' => 'taxonomy',
				],
			]
		);

		$repeater->add_control(
			'sitemap_order',
			[
				'label' => __( 'Order', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'madxartwork-pro' ),
					'desc' => __( 'DESC', 'madxartwork-pro' ),
				],
			]
		);

		$repeater->add_control(
			'sitemap_hide_empty',
			[
				'label' => __( 'Hide Empty', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'sitemap_type_selector' => 'taxonomy',
				],
			]
		);

		$repeater->add_control(
			'sitemap_hierarchical',
			[
				'label' => __( 'Hierarchical View', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'sitemap_depth',
			[
				'label' => __( 'Depth', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => __( 'All', 'madxartwork-pro' ),
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
				],
				'condition' => [
					'sitemap_hierarchical' => 'yes',
				],
			]
		);

		$this->add_control(
			'sitemap_items',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'sitemap_type_selector' => 'post_type',
						'sitemap_title' => __( 'Pages', 'madxartwork-pro' ),
						'sitemap_source_post_type' => 'page',
					],
					[
						'sitemap_type_selector' => 'taxonomy',
						'sitemap_title' => __( 'Categories', 'madxartwork-pro' ),
						'sitemap_source_taxonomy' => 'category',
					],
				],
				'title_field' => '{{{ sitemap_title }}}',
			]
		);
	}

	private function register_style_tab() {
		$this->start_controls_section(
			'section_sitemap_style',
			[
				'label' => __( 'List', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sitemap_list_indent',
			[
				'label' => __( 'Indent', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .madxartwork-sitemap-section ul' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .madxartwork-sitemap-section ul' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sitemap_section_padding',
			[
				'label' => __( 'Padding', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sitemap_title_style',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sitemap_title_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitemap_title_typography',
				'selector' => '{{WRAPPER}} .madxartwork-sitemap-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'sitemap_list_style',
			[
				'label' => __( 'List Item', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sitemap_list_item_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-item, {{WRAPPER}} span.madxartwork-sitemap-list, {{WRAPPER}} .madxartwork-sitemap-item a' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sitemap_list_item_typography',
				'selector' => '{{WRAPPER}} .madxartwork-sitemap-item, {{WRAPPER}} span.madxartwork-sitemap-list, {{WRAPPER}} .madxartwork-sitemap-item a',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'sitemap_bullet_style',
			[
				'label' => __( 'Bullet', 'madxartwork-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sitemap_bullet_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-item' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'sitemap_list_item_bullet_style',
			[
				'label' => __( 'Style', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'disc',
				'label_block' => true,
				'options' => [
					'disc' => [
						'title' => __( 'Disc', 'madxartwork-pro' ),
						'icon' => 'eicon-circle',
					],
					'circle' => [
						'title' => __( 'Circle', 'madxartwork-pro' ),
						'icon' => 'eicon-circle-o',
					],
					'square' => [
						'title' => __( 'Square', 'madxartwork-pro' ),
						'icon' => 'eicon-square',
					],
					'none' => [
						'title' => __( 'None', 'madxartwork-pro' ),
						'icon' => 'eicon-ban',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-sitemap-list, {{WRAPPER}} .madxartwork-sitemap-list .children' => 'list-style-type: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function _register_controls() {
		$this->register_sitemap_tab();
		$this->register_style_tab();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['sitemap_items'] ) ) {
			return;
		}

		$title_tag = $settings['sitemap_title_tag'];

		$posts_query = [
			'post__not_in' => $settings['sitemap_exclude_ids'],
			'has_password' => 'yes' === $settings['sitemap_password_protected'] ? null : false,
		];

		$this->add_render_attribute( [
			'category_link' => [
				'class' => 'madxartwork-sitemap-category-title',
			],
			'wrapper' => [
				'class' => 'madxartwork-sitemap-wrap',
			],
		] );

		if ( 'yes' === $settings['sitemap_add_nofollow'] ) {
			$this->add_render_attribute( 'a', 'rel', 'nofollow' );
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
		foreach ( $settings['sitemap_items'] as $sitemap_item ) {
			echo $this->render_sitemap_item( $sitemap_item, $title_tag, $posts_query );
		}
		echo '</div>';
	}

	private function get_list_title( $current_title, $item_type, $is_taxonomy ) {
		if ( '' !== $current_title ) {
			return $current_title;
		}

		if ( $is_taxonomy ) {
			$obj = get_taxonomy( $item_type );
			if ( false === $obj ) {
				return '';
			}
			return $obj->label;
		}

		$obj = get_post_type_object( $item_type );
		if ( null === $obj ) {
			return '';
		}
		if ( '' === $obj->labels->name ) {
			return $obj->labels->singular_name;
		}

		return $obj->labels->name;
	}

	private function render_sitemap_item( $sitemap_item, $title_tag, $query_args ) {
		$hierarchical = 'yes' === $sitemap_item['sitemap_hierarchical'];
		$max_depth = $sitemap_item['sitemap_depth'];
		$query_args['orderby'] = $sitemap_item['sitemap_orderby_post_type'];
		$query_args['order'] = $sitemap_item['sitemap_order'];
		$is_taxonomy = 'taxonomy' === $sitemap_item['sitemap_type_selector'];
		$item_type = $is_taxonomy ? $sitemap_item['sitemap_source_taxonomy'] : $sitemap_item['sitemap_source_post_type'];
		$title = $this->get_list_title( $sitemap_item['sitemap_title'], $item_type, $is_taxonomy );

		$this->add_render_attribute( [
			'section' . $item_type => [
				'class' => [
					'madxartwork-sitemap-section',
				],
			],
			'list' . $item_type => [
				'class' => [
					'madxartwork-sitemap-list',
					'madxartwork-sitemap-' . $item_type . '-list',
				],
			],
			$title_tag . $item_type => [
				'class' => [
					'madxartwork-sitemap-title',
					'madxartwork-sitemap-' . $item_type . '-title',
				],
			],
			'item' . $item_type => [
				'class' => [
					'madxartwork-sitemap-item',
					'madxartwork-sitemap-item-' . $item_type,
				],
			],
		] );

		$items_html = '';

		if ( $is_taxonomy ) {
			$items_html .= $this->sitemap_html_taxonomies( $item_type, $hierarchical, $max_depth, $sitemap_item, $query_args );
		} else {
			$items_html .= $this->sitemap_html_post_types( $item_type, $hierarchical, $max_depth, $query_args );
		}

		$title = empty( $title ) ? '' : sprintf( '<%s %s>%s</%1$s>', $title_tag, $this->get_render_attribute_string( $title_tag . $item_type ), $title );

		$html = sprintf( '<div %s>%s', $this->get_render_attribute_string( 'section' . $item_type ), $title );
		if ( empty( $items_html ) ) {
			$html .= sprintf( '<span %s>%s</span>', $this->get_render_attribute_string( 'list' . $item_type ), __( 'None', 'madxartwork-pro' ) );
		} else {
			$html .= sprintf( '<ul %s>%s</ul>', $this->get_render_attribute_string( 'list' . $item_type ), $items_html );
		}
		$html .= '</div>';

		return $html;
	}

	private function sitemap_html_taxonomies( $taxonomy, $hierarchical, $max_depth, $item_settings, $query_args ) {
		$query_args['hide_empty'] = 'yes' === $item_settings['sitemap_hide_empty'];
		$query_args['show_option_none'] = '';
		$query_args['taxonomy'] = $taxonomy;
		$query_args['title_li'] = '';
		$query_args['echo'] = false;
		$query_args['depth'] = $max_depth;
		$query_args['hierarchical'] = $hierarchical;
		$query_args['orderby'] = $item_settings['sitemap_orderby_taxonomy'];

		$taxonomy_list = wp_list_categories( $query_args );
		$taxonomy_list = $this->add_sitemap_item_classes( 'item' . $taxonomy, $taxonomy_list );

		return $taxonomy_list;
	}

	/**
	 * @param string $post_type
	 * @param array  $query_args
	 *
	 * @return \WP_Query
	 */
	private function query_by_post_type( $post_type, $query_args ) {
		$args = [
			'posts_per_page' => -1,
			'update_post_meta_cache' => false,
			'post_type' => $post_type,
			'filter' => 'ids',
			'post_status' => 'publish',
		];

		$args = array_merge( $query_args, $args );

		$query = new \WP_Query( $args );

		return $query;
	}

	/**
	 * @param string $post_type
	 * @param bool   $hierarchical
	 * @param int    $depth
	 * @param array  $query_args
	 *
	 * @return string
	 */
	private function sitemap_html_post_types( $post_type, $hierarchical, $depth, $query_args ) {
		$html = '';

		$query_result = $this->query_by_post_type( $post_type, $query_args );

		if ( empty( $query_result ) ) {
			return '';
		}

		if ( $query_result->have_posts() ) {
			if ( ! $hierarchical ) {
				$depth = -1;
			}
			$walker = new \Walker_Page();
			$walker->tree_type = $post_type;
			$walker_str = $walker->walk( $query_result->posts, $depth );
			$html .= $this->add_sitemap_item_classes( 'item' . $post_type, $walker_str );
		}

		return $html;
	}

	private function add_sitemap_item_classes( $element, $str ) {
		$element_str = $this->get_render_attribute_string( $element );
		/**  remove trailing " */
		$element_str = substr_replace( $element_str, ' ', -1, 1 );
		$source = [
			'class="',
		];
		$replace = [
			$element_str,
		];

		if ( 'yes' === $this->get_settings_for_display( 'sitemap_add_nofollow' ) ) {
			$source[] = 'href=';
			$replace[] = 'rel="nofollow" href=';
		}

		return str_replace( $source, $replace, $str );
	}
}


