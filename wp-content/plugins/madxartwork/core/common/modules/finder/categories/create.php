<?php
namespace madxartwork\Core\Common\Modules\Finder\Categories;

use madxartwork\Core\Common\Modules\Finder\Base_Category;
use madxartwork\TemplateLibrary\Source_Local;
use madxartwork\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Create Category
 *
 * Provides items related to creation of new posts/pages/templates etc.
 */
class Create extends Base_Category {

	/**
	 * Get title.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Create', 'madxartwork' );
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
		$madxartwork_supported_post_types = get_post_types_by_support( 'madxartwork' );

		$items = [];

		foreach ( $madxartwork_supported_post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );

			// If there is an old post type from inactive plugins
			if ( ! $post_type_object ) {
				continue;
			}

			if ( Source_Local::CPT === $post_type ) {
				$url = admin_url( Source_Local::ADMIN_MENU_SLUG . '#add_new' );
			} else {
				$url = Utils::get_create_new_post_url( $post_type );
			}

			$items[ $post_type ] = [
				/* translators: %s the title of the post type */
				'title' => sprintf( __( 'Add New %s', 'madxartwork' ), $post_type_object->labels->singular_name ),
				'icon' => 'plus-circle-o',
				'url' => $url,
				'keywords' => [ 'post', 'page', 'template', 'new', 'create' ],
			];
		}

		return $items;
	}
}
