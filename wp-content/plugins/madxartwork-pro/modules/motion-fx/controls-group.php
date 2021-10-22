<?php

namespace madxartworkPro\Modules\MotionFX;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Controls_Group extends Group_Control_Base {

	protected static $fields;

	/**
	 * Get group control type.
	 *
	 * Retrieve the group control type.
	 *
	 * @since  2.5.0
	 * @access public
	 * @static
	 */
	public static function get_type() {
		return 'motion_fx';
	}

	/**
	 * Init fields.
	 *
	 * Initialize group control fields.
	 *
	 * @since  2.5.0
	 * @access protected
	 */
	protected function init_fields() {
		$fields = [
			'motion_fx_scrolling' => [
				'label' => __( 'Scrolling Effects', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'madxartwork-pro' ),
				'label_on' => __( 'On', 'madxartwork-pro' ),
				'render_type' => 'ui',
				'frontend_available' => true,
			],
		];

		$this->prepare_effects( 'scrolling', $fields );

		$transform_origin_conditions = [
			'terms' => [
				[
					'name' => 'motion_fx_scrolling',
					'value' => 'yes',
				],
				[
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'rotateZ_effect',
							'value' => 'yes',
						],
						[
							'name' => 'scale_effect',
							'value' => 'yes',
						],
					],
				],
			],
		];

		$fields['transform_origin_x'] = [
			'label' => __( 'X Anchor Point', 'madxartwork-pro' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'options' => [
				'left' => [
					'title' => __( 'Left', 'madxartwork-pro' ),
					'icon' => 'eicon-h-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'madxartwork-pro' ),
					'icon' => 'eicon-h-align-center',
				],
				'right' => [
					'title' => __( 'Right', 'madxartwork-pro' ),
					'icon' => 'eicon-h-align-right',
				],
			],
			'conditions' => $transform_origin_conditions,
			'label_block' => false,
			'toggle' => false,
			'render_type' => 'ui',
		];

		$fields['transform_origin_y'] = [
			'label' => __( 'Y Anchor Point', 'madxartwork-pro' ),
			'type' => Controls_Manager::CHOOSE,
			'default' => 'center',
			'options' => [
				'top' => [
					'title' => __( 'Top', 'madxartwork-pro' ),
					'icon' => 'eicon-v-align-top',
				],
				'center' => [
					'title' => __( 'Center', 'madxartwork-pro' ),
					'icon' => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => __( 'Bottom', 'madxartwork-pro' ),
					'icon' => 'eicon-v-align-bottom',
				],
			],
			'conditions' => $transform_origin_conditions,
			'selectors' => [
				'{{SELECTOR}}' => 'transform-origin: {{transform_origin_x.VALUE}} {{VALUE}}',
			],
			'label_block' => false,
			'toggle' => false,
		];

		$fields['devices'] = [
			'label' => __( 'Apply Effects On', 'madxartwork-pro' ),
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,
			'label_block' => 'true',
			'default' => [ 'desktop', 'tablet', 'mobile' ],
			'options' => [
				'desktop' => __( 'Desktop', 'madxartwork-pro' ),
				'tablet' => __( 'Tablet', 'madxartwork-pro' ),
				'mobile' => __( 'Mobile', 'madxartwork-pro' ),
			],
			'condition' => [
				'motion_fx_scrolling' => 'yes',
			],
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$fields['range'] = [
			'label' => __( 'Effects relative to', 'madxartwork-pro' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'Default', 'madxartwork-pro' ),
				'viewport' => __( 'Viewport', 'madxartwork-pro' ),
				'page' => __( 'Entire Page', 'madxartwork-pro' ),
			],
			'condition' => [
				'motion_fx_scrolling' => 'yes',
			],
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$fields['motion_fx_mouse'] = [
			'label' => __( 'Mouse Effects', 'madxartwork-pro' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'Off', 'madxartwork-pro' ),
			'label_on' => __( 'On', 'madxartwork-pro' ),
			'separator' => 'before',
			'render_type' => 'none',
			'frontend_available' => true,
		];

		$this->prepare_effects( 'mouse', $fields );

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}

	private function get_scrolling_effects() {
		return [
			'translateY' => [
				'label' => __( 'Vertical Scroll', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'Up', 'madxartwork-pro' ),
							'negative' => __( 'Down', 'madxartwork-pro' ),
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'translateX' => [
				'label' => __( 'Horizontal Scroll', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'To Left', 'madxartwork-pro' ),
							'negative' => __( 'To Right', 'madxartwork-pro' ),
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'opacity' => [
				'label' => __( 'Transparency', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Fade In',
							'in-out' => 'Fade Out',
							'in-out-in' => 'Fade Out In',
							'out-in-out' => 'Fade In Out',
						],
					],
					'level' => [
						'label' => __( 'Level', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 10,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'range' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'blur' => [
				'label' => __( 'Blur', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Fade In',
							'in-out' => 'Fade Out',
							'in-out-in' => 'Fade Out In',
							'out-in-out' => 'Fade In Out',
						],
					],
					'level' => [
						'label' => __( 'Level', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 7,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 15,
							],
						],
					],
					'range' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'rotateZ' => [
				'label' => __( 'Rotate', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'To Left', 'madxartwork-pro' ),
							'negative' => __( 'To Right', 'madxartwork-pro' ),
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
					'affectedRange' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 0,
								'end' => 100,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
			'scale' => [
				'label' => __( 'Scale', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'out-in',
						'options' => [
							'out-in' => 'Scale Up',
							'in-out' => 'Scale Down',
							'in-out-in' => 'Scale Down Up',
							'out-in-out' => 'Scale Up Down',
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'min' => -10,
								'max' => 10,
							],
						],
					],
					'range' => [
						'label' => __( 'Viewport', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'sizes' => [
								'start' => 20,
								'end' => 80,
							],
							'unit' => '%',
						],
						'labels' => [
							__( 'Bottom', 'madxartwork-pro' ),
							__( 'Top', 'madxartwork-pro' ),
						],
						'scales' => 1,
						'handles' => 'range',
					],
				],
			],
		];
	}

	private function get_mouse_effects() {
		return [
			'mouseTrack' => [
				'label' => __( 'Mouse Track', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'default' => '',
						'options' => [
							'' => __( 'Opposite', 'madxartwork-pro' ),
							'negative' => __( 'Direct', 'madxartwork-pro' ),
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
				],
			],
			'tilt' => [
				'label' => __( '3D Tilt', 'madxartwork-pro' ),
				'fields' => [
					'direction' => [
						'label' => __( 'Direction', 'madxartwork-pro' ),
						'type' => Controls_Manager::SELECT,
						'default' => '',
						'options' => [
							'' => __( 'Direct', 'madxartwork-pro' ),
							'negative' => __( 'Opposite', 'madxartwork-pro' ),
						],
					],
					'speed' => [
						'label' => __( 'Speed', 'madxartwork-pro' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 4,
						],
						'range' => [
							'px' => [
								'max' => 10,
								'step' => 0.1,
							],
						],
					],
				],
			],
		];
	}

	private function prepare_effects( $effects_group, array &$fields ) {
		$method_name = "get_{$effects_group}_effects";

		$effects = $this->$method_name();

		foreach ( $effects as $effect_name => $effect_args ) {
			$args = [
				'label' => $effect_args['label'],
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'motion_fx_' . $effects_group => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			];

			if ( ! empty( $effect_args['separator'] ) ) {
				$args['separator'] = $effect_args['separator'];
			}

			$fields[ $effect_name . '_effect' ] = $args;

			$effect_fields = $effect_args['fields'];

			$first_field = & $effect_fields[ key( $effect_fields ) ];

			$first_field['popover']['start'] = true;

			end( $effect_fields );

			$last_field = & $effect_fields[ key( $effect_fields ) ];

			$last_field['popover']['end'] = true;

			reset( $effect_fields );

			foreach ( $effect_fields as $field_name => $field ) {
				$field = array_merge( $field, [
					'condition' => [
						'motion_fx_' . $effects_group => 'yes',
						$effect_name . '_effect' => 'yes',
					],
					'render_type' => 'none',
					'frontend_available' => true,
				] );

				$fields[ $effect_name . '_' . $field_name ] = $field;
			}
		}
	}
}
