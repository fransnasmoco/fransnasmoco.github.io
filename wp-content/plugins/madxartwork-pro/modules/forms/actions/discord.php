<?php
namespace madxartworkPro\Modules\Forms\Actions;

use madxartwork\Controls_Manager;
use madxartworkPro\Modules\Forms\Classes\Action_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Discord extends Action_Base {

	public function get_name() {
		return 'discord';
	}

	public function get_label() {
		return __( 'Discord', 'madxartwork-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_discord',
			[
				'label' => __( 'Discord', 'madxartwork-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'discord_webhook',
			[
				'label' => __( 'Webhook URL', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'https://discordapp.com/api/webhooks/',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter the webhook URL that will receive the form\'s submitted data.', 'madxartwork-pro' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>.', 'https://support.discordapp.com/hc/en-us/articles/228383668-Intro-to-Webhooks', __( 'Click here for Instructions', 'madxartwork-pro' ) ),
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			'discord_username',
			[
				'label' => __( 'Username', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'discord_avatar_url',
			[
				'label' => __( 'Avatar URL', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'discord_title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'discord_content',
			[
				'label' => __( 'Description', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$widget->add_control(
			'discord_form_data',
			[
				'label' => __( 'Form Data', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$widget->add_control(
			'discord_ts',
			[
				'label' => __( 'Timestamp', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$widget->add_control(
			'discord_webhook_color',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'alpha' => false,
				'default' => '#D30C5C',
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['discord_avatar_url'],
			$element['discord_content'],
			$element['discord_webhook_color'],
			$element['discord_username'],
			$element['discord_form_data'],
			$element['discord_ts'],
			$element['discord_title'],
			$element['discord_webhook']
		);
	}

	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		if ( empty( $settings['discord_webhook'] ) || false === strpos( $settings['discord_webhook'], 'https://discordapp.com/api/webhooks/' ) ) {
			return;
		}

		$page_url = isset( $_POST['referrer'] ) ? $_POST['referrer'] : site_url();
		$color = isset( $settings['discord_webhook_color'] ) ? hexdec( ltrim( $settings['discord_webhook_color'], '#' ) ) : hexdec( '9c0244' );

		// Build discord  webhook data
		$embeds = [
			'title' => isset( $settings['discord_title'] ) ? $settings['discord_title'] : __( 'A new Submission', 'madxartwork-pro' ),
			'description' => isset( $settings['discord_content'] ) ? $settings['discord_content'] : __( 'A new Form Submission has been received', 'madxartwork-pro' ),
			'author' => [
				'name'     => isset( $settings['discord_username'] ) ? $settings['discord_username'] : __( 'madxartwork Forms', 'madxartwork-pro' ),
				'url'      => $page_url,
				'icon_url' => isset( $settings['discord_avatar_url'] ) ? $settings['discord_avatar_url'] : null,
			],
			'url' => $page_url,
			'color' => $color,
		];

		if ( ! empty( $settings['discord_form_data'] ) && 'yes' === $settings['discord_form_data'] ) {
			// prepare Form Data
			$raw_fields = $record->get( 'fields' );
			$fields = [];
			foreach ( $raw_fields as $id => $field ) {
				$fields[] = [
					'name'   => $id,
					'value'  => $field['value'],
					'inline' => false,
				];
			}

			$embeds['fields'] = array_values( $fields );
		}

		if ( ! empty( $settings['discord_ts'] ) && 'yes' === $settings['discord_ts'] ) {
			$embeds['timestamp'] = date( \DateTime::ISO8601 );
			$embeds['footer'] = [
				'text' => sprintf( __( 'Powered by %s', 'madxartwork-pro' ), 'madxartwork' ),
				'icon_url' => is_ssl() ? madxartwork_ASSETS_URL . 'images/logo-icon.png' : null,
			];
		}

		$webhook_data = [
			'embeds' => array_values( [ $embeds ] ),
		];

		$webhook_data = apply_filters( 'madxartwork_pro/forms/discord/webhook_args', $webhook_data );

		$response = wp_remote_post( $settings['discord_webhook'], [
			'body' => wp_json_encode( $webhook_data ),
		]);

		if ( 204 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			$ajax_handler->add_admin_error_message( 'Discord Webhook Error' );
		}
	}
}
