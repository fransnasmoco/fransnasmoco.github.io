<?php
namespace madxartworkPro\Modules\Forms\Actions;

use madxartwork\Controls_Manager;
use madxartworkPro\Modules\Forms\Classes\Action_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Webhook extends Action_Base {

	public function get_name() {
		return 'webhook';
	}

	public function get_label() {
		return __( 'Webhook', 'madxartwork-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_webhook',
			[
				'label' => __( 'Webhook', 'madxartwork-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'webhooks',
			[
				'label' => __( 'Webhook URL', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-webhook-url.com', 'madxartwork-pro' ),
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter the integration URL (like Zapier) that will receive the form\'s submitted data.', 'madxartwork-pro' ),
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			'webhooks_advanced_data',
			[
				'label' => __( 'Advanced Data', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'render_type' => 'none',
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {}

	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		if ( empty( $settings['webhooks'] ) ) {
			return;
		}

		if ( isset( $settings['webhooks_advanced_data'] ) && 'yes' === $settings['webhooks_advanced_data'] ) {
			$body['form'] = [
				'id' => $settings['id'],
				'name' => $settings['form_name'],
			];

			$body['fields'] = $record->get( 'fields' );
			$body['meta'] = $record->get( 'meta' );
		} else {
			$body = $record->get_formatted_data( true );
			$body['form_id'] = $settings['id'];
			$body['form_name'] = $settings['form_name'];
		}

		$args = [
			'body' => $body,
		];

		/**
		 * Forms webhook request arguments.
		 *
		 * Filters the request arguments delivered by the form webhook when executing
		 * an ajax request.
		 *
		 * @since 1.0.0
		 *
		 * @param array       $args   Webhook request arguments.
		 * @param Form_Record $record An instance of the form record.
		 */
		$args = apply_filters( 'madxartwork_pro/forms/webhooks/request_args', $args, $record );

		$response = wp_remote_post( $settings['webhooks'], $args );

		/**
		 * madxartwork form webhook response.
		 *
		 * Fires when the webhook response is retrieved.
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_Error|array $response The response or WP_Error on failure.
		 * @param Form_Record     $record   An instance of the form record.
		 */
		do_action( 'madxartwork_pro/forms/webhooks/response', $response, $record );

		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			$ajax_handler->add_admin_error_message( 'Webhook Error' );
		}
	}
}
