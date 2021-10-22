<?php

namespace madxartworkPro\Modules\Gallery;

use madxartwork\Controls_Manager;
use madxartwork\Element_Base;
use madxartwork\Element_Column;
use madxartwork\Element_Section;
use madxartworkPro\Base\Module_Base;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  2.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'gallery';
	}

	public function get_widgets() {
		return [
			'gallery',
		];
	}
}
