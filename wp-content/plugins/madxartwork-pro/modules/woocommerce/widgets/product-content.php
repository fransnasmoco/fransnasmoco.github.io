<?php
namespace madxartworkPro\Modules\Woocommerce\Widgets;

use madxartworkPro\Modules\ThemeBuilder\Widgets\Post_Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Content extends Post_Content {

	public function get_name() {
		return 'woocommerce-product-content';
	}

	public function get_title() {
		return __( 'Product Content', 'madxartwork-pro' );
	}

	public function get_categories() {
		return [ 'woocommerce-elements-single' ];
	}

	public function get_keywords() {
		return [ 'content', 'post', 'product' ];
	}
}
