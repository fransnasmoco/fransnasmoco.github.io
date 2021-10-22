<?php
namespace madxartworkPro\Modules\DynamicTags\ACF\Tags;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Data_Tag;
use madxartworkPro\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return __( 'ACF', 'madxartwork-pro' ) . ' ' . __( 'File Field', 'madxartwork-pro' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	protected function get_supported_fields() {
		return [
			'file',
		];
	}
}
