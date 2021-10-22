<?php
/**
 * PowerPack Elements Common Widget.
 *
 * @package PowerPack Elements
 */

namespace PowerpackElements\Base;

use madxartwork\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Powerpack_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 0.0.1
	 */
	public function get_categories() {
		return [ 'powerpack-elements' ];
	}
}
