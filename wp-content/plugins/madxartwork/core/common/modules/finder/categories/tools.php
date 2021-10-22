<?php

namespace madxartwork\Core\Common\Modules\Finder\Categories;

use madxartwork\Core\Common\Modules\Finder\Base_Category;
use madxartwork\Core\RoleManager\Role_Manager;
use madxartwork\Tools as madxartworkTools;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Tools Category
 *
 * Provides items related to madxartwork's tools.
 */
class Tools extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Tools', 'madxartwork' );
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
		$tools_url = madxartworkTools::get_url();

		return [
			'tools' => [
				'title' => __( 'Tools', 'madxartwork' ),
				'icon' => 'tools',
				'url' => $tools_url,
				'keywords' => [ 'tools', 'regenerate css', 'safe mode', 'debug bar', 'sync library', 'madxartwork' ],
			],
			'replace-url' => [
				'title' => __( 'Replace URL', 'madxartwork' ),
				'icon' => 'tools',
				'url' => $tools_url . '#tab-replace_url',
				'keywords' => [ 'tools', 'replace url', 'domain', 'madxartwork' ],
			],
			'version-control' => [
				'title' => __( 'Version Control', 'madxartwork' ),
				'icon' => 'time-line',
				'url' => $tools_url . '#tab-versions',
				'keywords' => [ 'tools', 'version', 'control', 'rollback', 'beta', 'madxartwork' ],
			],
			'maintenance-mode' => [
				'title' => __( 'Maintenance Mode', 'madxartwork' ),
				'icon' => 'tools',
				'url' => $tools_url . '#tab-maintenance_mode',
				'keywords' => [ 'tools', 'maintenance', 'coming soon', 'madxartwork' ],
			],
		];
	}
}
