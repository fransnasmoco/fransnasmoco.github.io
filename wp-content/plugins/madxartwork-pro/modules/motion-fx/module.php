<?php

namespace madxartworkPro\Modules\MotionFX;

use madxartwork\Controls_Manager;
use madxartwork\Element_Base;
use madxartwork\Element_Column;
use madxartwork\Element_Section;
use madxartworkPro\Base\Module_Base;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}
	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  2.5.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'motion-fx';
	}

	public function register_controls_group() {
		Plugin::madxartwork()->controls_manager->add_group_control( Controls_Group::get_type(), new Controls_Group() );
	}

	public function add_controls_group_to_element( Element_Base $element ) {
		$exclude = [];

		$selector = '{{WRAPPER}}';

		if ( $element instanceof Element_Section ) {
			$exclude[] = 'motion_fx_mouse';
		} elseif ( $element instanceof Element_Column ) {
			$selector .= ' > .madxartwork-column-wrap';
		} else {
			$selector .= ' > .madxartwork-widget-container';
		}

		$element->add_group_control(
			Controls_Group::get_type(),
			[
				'name' => 'motion_fx',
				'selector' => $selector,
				'exclude' => $exclude,
			]
		);
	}

	public function add_controls_group_to_element_background( Element_Base $element ) {
		$element->start_injection( [
			'of' => 'background_bg_width_mobile',
		] );

		$element->add_group_control(
			Controls_Group::get_type(),
			[
				'name' => 'background_motion_fx',
				'exclude' => [
					'rotateZ_effect',
					'tilt_effect',
					'transform_origin_x',
					'transform_origin_y',
				],
			]
		);

		$options = [
			'separator' => 'before',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => 'background_background',
						'value' => 'classic',
					],
					[
						'terms' => [
							[
								'name' => 'background_background',
								'value' => 'gradient',
							],
							[
								'name' => 'background_color',
								'operator' => '!==',
								'value' => '',
							],
							[
								'name' => 'background_color_b',
								'operator' => '!==',
								'value' => '',
							],
						],
					],
				],
			],
		];

		$element->update_control( 'background_motion_fx_motion_fx_scrolling', $options );

		$element->update_control( 'background_motion_fx_motion_fx_mouse', $options );

		$element->end_injection();
	}

	public function localize_settings( array $settings ) {
		$settings['i18n']['motion_effects'] = __( 'Motion Effects', 'madxartwork-pro' );

		return $settings;
	}

	private function add_actions() {
		add_action( 'madxartwork/controls/controls_registered', [ $this, 'register_controls_group' ] );

		add_action( 'madxartwork/element/section/section_effects/after_section_start', [ $this, 'add_controls_group_to_element' ] );
		add_action( 'madxartwork/element/column/section_effects/after_section_start', [ $this, 'add_controls_group_to_element' ] );
		add_action( 'madxartwork/element/common/section_effects/after_section_start', [ $this, 'add_controls_group_to_element' ] );

		add_action( 'madxartwork/element/section/section_background/before_section_end', [ $this, 'add_controls_group_to_element_background' ] );
		add_action( 'madxartwork/element/column/section_style/before_section_end', [ $this, 'add_controls_group_to_element_background' ] );

		add_filter( 'madxartwork_pro/editor/localize_settings', [ $this, 'localize_settings' ] );
	}
}
