<?php
namespace madxartworkPro\Modules\ThemeBuilder\Documents;

use madxartwork\Core\DocumentTypes\Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Header_Footer_Base extends Theme_Section_Document {

	public function get_css_wrapper_selector() {
		return '.madxartwork-' . $this->get_main_id();
	}

	protected static function get_editor_panel_categories() {
		// Move to top as active.
		$categories = [
			'theme-elements' => [
				'title' => __( 'Site', 'madxartwork-pro' ),
				'active' => true,
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}

	protected function _register_controls() {
		parent::_register_controls();

		Post::register_style_controls( $this );

		$this->update_control(
			'section_page_style',
			[
				'label' => __( 'Style', 'madxartwork-pro' ),
			]
		);
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = $this->get_name();

		return $config;
	}
}
