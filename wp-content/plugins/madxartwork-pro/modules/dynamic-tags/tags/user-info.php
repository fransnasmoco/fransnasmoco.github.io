<?php
namespace madxartworkPro\Modules\DynamicTags\Tags;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Tag;
use madxartworkPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return __( 'User Info', 'madxartwork-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'madxartwork-pro' ),
					'id' => __( 'ID', 'madxartwork-pro' ),
					'display_name' => __( 'Display Name', 'madxartwork-pro' ),
					'login' => __( 'Username', 'madxartwork-pro' ),
					'first_name' => __( 'First Name', 'madxartwork-pro' ),
					'last_name' => __( 'Last Name', 'madxartwork-pro' ),
					'description' => __( 'Bio', 'madxartwork-pro' ),
					'email' => __( 'Email', 'madxartwork-pro' ),
					'url' => __( 'Website', 'madxartwork-pro' ),
					'meta' => __( 'User Meta', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'madxartwork-pro' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}
}
