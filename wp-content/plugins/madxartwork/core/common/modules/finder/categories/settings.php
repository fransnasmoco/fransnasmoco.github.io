<?php

namespace madxartwork\Core\Common\Modules\Finder\Categories;

use madxartwork\Core\Common\Modules\Finder\Base_Category;
use madxartwork\Settings as madxartworkSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings Category
 *
 * Provides items related to madxartwork's settings.
 */
class Settings extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Settings', 'madxartwork' );
	}

	/**
	 * Get category items.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function get_category_items( array $options = [] ) {
		$settings_url = madxartworkSettings::get_url();

		return [
			'general-settings' => [
				'title' => __( 'General Settings', 'madxartwork' ),
				'url' => $settings_url,
				'keywords' => [ 'general', 'settings', 'madxartwork' ],
			],
			'style' => [
				'title' => __( 'Style', 'madxartwork' ),
				'url' => $settings_url . '#tab-style',
				'keywords' => [ 'style', 'settings', 'madxartwork' ],
			],
			'advanced' => [
				'title' => __( 'Advanced', 'madxartwork' ),
				'url' => $settings_url . '#tab-advanced',
				'keywords' => [ 'advanced', 'settings', 'madxartwork' ],
			],
		];
	}
}
