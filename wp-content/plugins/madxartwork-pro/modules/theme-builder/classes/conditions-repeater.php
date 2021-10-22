<?php
namespace madxartworkPro\Modules\ThemeBuilder\Classes;

use madxartwork\Control_Repeater;
use madxartwork\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Conditions_Repeater extends Control_Repeater {

	const CONTROL_TYPE = 'conditions_repeater';

	public function get_type() {
		return self::CONTROL_TYPE;
	}

	protected function get_default_settings() {
		return array_merge( parent::get_default_settings(), [
			'render_type' => 'none',
			'fields' => [
				[
					'name' => 'type',
					'type' => Controls_Manager::SELECT,
					'default' => 'include',
					'options' => [
						'include' => __( 'Include', 'madxartwork-pro' ),
						'exclude' => __( 'Exclude', 'madxartwork-pro' ),
					],
				],
				[
					'name' => 'name',
					'type' => Controls_Manager::SELECT,
					'default' => 'general',
					'groups' => [
						[
							'label' => __( 'General', 'madxartwork-pro' ),
							'options' => [],
						],
					],
				],
				[
					'name' => 'sub_name',
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => __( 'All', 'madxartwork-pro' ),
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'name',
								'operator' => '!==',
								'value' => '',
							],
						],
					],
				],
				[
					'name' => 'sub_id',
					'type' => Controls_Manager::SELECT,
					'options' => [
						'' => __( 'All', 'madxartwork-pro' ),
					],
					'conditions' => [
						'terms' => [
							[
								'name' => 'sub_name',
								'operator' => '!==',
								'value' => '',
							],
						],
					],
				],
			],
		] );
	}
}
