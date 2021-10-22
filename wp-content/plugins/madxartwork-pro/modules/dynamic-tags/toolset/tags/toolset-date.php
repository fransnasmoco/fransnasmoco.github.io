<?php
namespace madxartworkPro\Modules\DynamicTags\Toolset\Tags;

use madxartwork\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Toolset_Date extends Toolset_Base {

	public function get_name() {
		return 'toolset-date';
	}

	public function get_title() {
		return __( 'Toolset', 'madxartwork-pro' ) . ' ' . __( 'Date Field', 'madxartwork-pro' );
	}

	public function render() {
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
		$value = '';

		if ( $field && ! empty( $field['type'] ) && 'date' === $field['type'] ) {

			$format = $this->get_settings( 'format' );

			$timestamp = types_render_field( $field_key, [
				'output' => 'raw',
				'style' => 'text',
			] );

			if ( empty( $timestamp ) ) {
				return $value;
			}

			if ( 'human' === $format ) {
				/* translators: %s: Human readable date/time. */
				$value = human_time_diff( $timestamp );
			} else {
				switch ( $format ) {
					case 'default':
						$date_format = get_option( 'date_format' );
						break;
					case 'custom':
						$date_format = $this->get_settings( 'custom_format' );
						break;
					default:
						$date_format = $format;
						break;
				}

				$value = date( $date_format, $timestamp );
			}
		}
		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->add_control(
			'format',
			[
				'label' => __( 'Format', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'madxartwork-pro' ),
					'F j, Y' => date( 'F j, Y' ),
					'Y-m-d' => date( 'Y-m-d' ),
					'm/d/Y' => date( 'm/d/Y' ),
					'd/m/Y' => date( 'd/m/Y' ),
					'human' => __( 'Human Readable', 'madxartwork-pro' ),
					'custom' => __( 'Custom', 'madxartwork-pro' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => __( 'Custom Format', 'madxartwork-pro' ),
				'default' => '',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'madxartwork-pro' ) ),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	protected function get_supported_fields() {
		return [ 'date' ];
	}
}
