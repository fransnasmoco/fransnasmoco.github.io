<?php
namespace madxartworkPro\Core\Editor;

use madxartwork\Core\Base\App;
use madxartworkPro\License\Admin as License_Admin;
use madxartworkPro\License\API as License_API;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Editor extends App {

	/**
	 * Get app name.
	 *
	 * Retrieve the app name.
	 *
	 * @return string app name.
	 * @since  2.6.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'pro-editor';
	}

	public function __construct() {
		add_action( 'madxartwork/init', [ $this, 'on_madxartwork_init' ] );
		add_action( 'madxartwork/editor/init', [ $this, 'on_madxartwork_editor_init' ] );
		add_action( 'madxartwork/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'madxartwork/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	public function get_init_settings() {
		$is_license_active = false;

		$license_key = License_Admin::get_license_key();

		if ( ! empty( $license_key ) ) {
			$license_data = License_API::get_license_data();

			if ( ! empty( $license_data['license'] ) && License_API::STATUS_VALID === $license_data['license'] ) {
				$is_license_active = true;
			}
		}

		$settings = [
			'i18n' => [],
			'isActive' => true,
			'useComponentsRouter' => defined( 'madxartwork_EDITOR_USE_ROUTER' ) && madxartwork_EDITOR_USE_ROUTER,
			'urls' => [
				'modules' => madxartwork_PRO_MODULES_URL,
			],
		];

		/**
		 * Editor settings.
		 *
		 * Filters the editor settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings settings.
		 */
		$settings = apply_filters( 'madxartwork_pro/editor/localize_settings', $settings );

		return $settings;
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'madxartwork-pro',
			$this->get_css_assets_url( 'editor', null, 'default', true ),
			[
				'madxartwork-editor',
			],
			madxartwork_PRO_VERSION
		);
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'madxartwork-pro',
			$this->get_js_assets_url( 'editor' ),
			[
				'backbone-marionette',
				'madxartwork-common-modules',
				'madxartwork-editor-modules',
			],
			madxartwork_PRO_VERSION,
			true
		);

		$this->print_config( 'madxartwork-pro' );
	}

	public function on_madxartwork_init() {
		Plugin::madxartwork()->editor->notice_bar = new Notice_Bar();
	}

	public function on_madxartwork_editor_init() {
		Plugin::madxartwork()->common->add_template( __DIR__ . '/template.php' );
	}

	protected function get_assets_base_url() {
		return madxartwork_PRO_URL;
	}
}
