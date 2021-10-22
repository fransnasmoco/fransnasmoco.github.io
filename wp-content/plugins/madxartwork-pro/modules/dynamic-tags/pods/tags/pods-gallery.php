<?php
namespace madxartworkPro\Modules\DynamicTags\Pods\Tags;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Data_Tag;
use madxartworkPro\Modules\DynamicTags\Pods\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Gallery extends Data_Tag {

	public function get_name() {
		return 'pods-gallery';
	}

	public function get_title() {
		return __( 'Pods', 'madxartwork-pro' ) . ' ' . __( 'Gallery Field', 'madxartwork-pro' );
	}

	public function get_categories() {
		return [ Module::GALLERY_CATEGORY ];
	}

	public function get_group() {
		return Module::PODS_GROUP;
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return false;
		}

		$images = [];

		list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );
		/**
		 * @var \Pods
		 */
		$pod = pods( $pod_name, get_the_ID() );

		if ( false === $pod ) {
			return [];
		}

		$galley_images = $pod->field( $meta_key );

		if ( empty( $galley_images ) || ! is_array( $galley_images ) ) {
			return $images;
		}

		foreach ( $galley_images as $image ) {
			$images[] = [
				'id' => $image['ID'],
			];
		}

		return $images;
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label' => __( 'Key', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'groups' => Module::get_control_options( $this->get_supported_fields() ),
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'pods_gallery',
		];
	}
}
