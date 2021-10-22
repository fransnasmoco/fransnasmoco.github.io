<?php
namespace madxartworkPro\Modules\ThemeBuilder\Widgets;

use madxartwork\Widget_Image;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Category_Image extends Widget_Image {

	public function get_name() {
		return 'woocommerce-category-image';
	}

	public function get_title() {
		return __( 'Category Image', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-featured-image';
	}

	public function get_categories() {
		return [ 'woocommerce-elements', 'woocommerce-elements-single' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'category', 'image', 'thumbnail' ];
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->update_control(
			'image',
			[
				'dynamic' => [
					'default' => Plugin::madxartwork()->dynamic_tags->tag_data_to_tag_text( null, 'woocommerce-category-image-tag' ),
				],
			],
			[
				'recursive' => true,
			]
		);
	}

	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' madxartwork-widget-' . parent::get_name();
	}
}
