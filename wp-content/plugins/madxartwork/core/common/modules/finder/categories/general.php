<?php
namespace madxartwork\Core\Common\Modules\Finder\Categories;

use madxartwork\Core\Common\Modules\Finder\Base_Category;
use madxartwork\Core\RoleManager\Role_Manager;
use madxartwork\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * General Category
 *
 * Provides general items related to madxartwork Admin.
 */
class General extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'General', 'madxartwork' );
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
		return [
			'saved-templates' => [
				'title' => _x( 'Saved Templates', 'Template Library', 'madxartwork' ),
				'icon' => 'library-save',
				'url' => Source_Local::get_admin_url(),
				'keywords' => [ 'template', 'section', 'page', 'library' ],
			],
			'system-info' => [
				'title' => __( 'System Info', 'madxartwork' ),
				'icon' => 'info',
				'url' => admin_url( 'admin.php?page=madxartwork-system-info' ),
				'keywords' => [ 'system', 'info', 'environment', 'madxartwork' ],
			],
			'role-manager' => [
				'title' => __( 'Role Manager', 'madxartwork' ),
				'icon' => 'person',
				'url' => Role_Manager::get_url(),
				'keywords' => [ 'role', 'manager', 'user', 'madxartwork' ],
			],
			'knowledge-base' => [
				'title' => __( 'Knowledge Base', 'madxartwork' ),
				'url' => admin_url( 'admin.php?page=go_knowledge_base_site' ),
				'keywords' => [ 'help', 'knowledge', 'docs', 'madxartwork' ],
			],
		];
	}
}
