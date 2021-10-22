<?php
namespace madxartworkPro\Modules\Posts;

use madxartworkPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'posts';
	}

	public function get_widgets() {
		return [
			'Posts',
			'Portfolio',
		];
	}
}
