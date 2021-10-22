<?php
namespace madxartworkPro\Modules\Woocommerce\Tags;

use madxartwork\Core\DynamicTags\Tag;
use madxartworkPro\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Tag extends Tag {
	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}

	public function get_categories() {
		return [ \madxartwork\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
}
