<?php
namespace madxartworkPro\Modules\Forms\Classes;

use madxartwork\Widget_Base;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Honeypot field
 */
class Honeypot_Handler {

	public function add_field_type( $field_types ) {
		$field_types['honeypot'] = __( 'Honeypot', 'madxartwork-pro' );

		return $field_types;
	}

	public function hide_label( $item, $item_index, $widget ) {
		if ( 'honeypot' === $item['field_type'] ) {
			$widget->set_render_attribute( 'field-group' . $item_index, 'class', 'madxartwork-field-type-text' );
			$item['field_label'] = false;
		}

		return $item;
	}

	/**
	 * @param string      $item
	 * @param integer     $item_index
	 * @param Widget_Base $widget
	 */
	public function render_field( $item, $item_index, $widget ) {
		$widget->set_render_attribute( 'input' . $item_index, 'type', 'text' );
		$widget->add_render_attribute( 'input' . $item_index, 'style', 'display:none !important;' );

		echo '<input size="1" ' . $widget->get_render_attribute_string( 'input' . $item_index ) . '>';
	}

	/**
	 * @param Form_Record  $record
	 * @param Ajax_Handler $ajax_handler
	 */
	public function validation( $record, $ajax_handler ) {
		$fields = $record->get_field( [
			'type' => 'honeypot',
		] );

		if ( empty( $fields ) ) {
			return;
		}

		$field = current( $fields );

		if ( ! empty( $field['value'] ) ) {
			$ajax_handler->add_error( $field['id'], __( 'Invalid Form.', 'madxartwork-pro' ) );
		}

		// If success - remove the field form list (don't send it in emails and etc )
		$record->remove_field( $field['id'] );
	}

	public function update_controls( Widget_Base $widget ) {
		$madxartwork = Plugin::madxartwork();

		$control_data = $madxartwork->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		foreach ( $control_data['fields'] as $index => $field ) {
			if ( 'required' === $field['name'] || 'width' === $field['name'] ) {
				$control_data['fields'][ $index ]['conditions']['terms'][] = [
					'name' => 'field_type',
					'operator' => '!in',
					'value' => [
						'honeypot',
					],
				];
			}
		}

		$widget->update_control( 'form_fields', $control_data );
	}

	public function __construct() {
		add_filter( 'madxartwork_pro/forms/field_types', [ $this, 'add_field_type' ] );
		add_action( 'madxartwork_pro/forms/render/item', [ $this, 'hide_label' ], 10, 3 );
		add_action( 'madxartwork_pro/forms/render_field/honeypot', [ $this, 'render_field' ], 10, 3 );
		add_action( 'madxartwork_pro/forms/validation', [ $this, 'validation' ], 10, 2 );
		add_action( 'madxartwork/element/form/section_form_fields/before_section_end', [ $this, 'update_controls' ] );
	}
}
