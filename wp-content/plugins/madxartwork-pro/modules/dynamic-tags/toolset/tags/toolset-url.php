<?php
namespace madxartworkPro\Modules\DynamicTags\Toolset\Tags;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Data_Tag;
use madxartworkPro\Modules\DynamicTags\Toolset\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Toolset_URL extends Data_Tag {

	public function get_name() {
		return 'toolset-url';
	}

	public function get_title() {
		return __( 'Toolset', 'madxartwork-pro' ) . ' ' . __( 'URL Field', 'madxartwork-pro' );
	}

	public function get_group() {
		return Module::TOOLSET_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		// Toolset Embedded version loads its bootstrap later
		if ( ! function_exists( 'types_render_field' ) ) {
			return;
		}

		$key = $this->get_settings( 'key' );
		if ( empty( $key ) ) {
			return;
		}

		list( $field_group, $field_key ) = explode( ':', $key );

		$field = wpcf_admin_fields_get_field( $field_key );

		if ( $field && ! empty( $field['type'] ) ) {
			$value = '';
			switch ( $field['type'] ) {
				case 'email':
					$value = 'mailto:' . types_render_field( $field_key, [ 'output' => 'raw' ] );
					break;
				case 'image':
					$value = types_render_field( $field_key, [ 'url' => true ] );
					break;
				default:
					$value = types_render_field( $field_key, [ 'output' => 'raw' ] );
			} // End switch().
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return wp_kses_post( $value );
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

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'madxartwork-pro' ),
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'email',
			'image',
			'file',
			'audio',
			'url',
		];
	}
}
