<?php
namespace madxartworkPro;

use madxartwork\Rollback;
use madxartwork\Settings;
use madxartwork\Tools;
use madxartwork\Utils;
use madxartworkPro\License\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Admin {

	/**
	 * Enqueue admin styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		$suffix = Utils::is_script_debug() ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'madxartwork-pro-admin',
			madxartwork_PRO_ASSETS_URL . 'css/admin' . $direction_suffix . $suffix . '.css',
			[],
			madxartwork_PRO_VERSION
		);

		wp_enqueue_style( 'madxartwork-pro-admin' );
	}

	public function enqueue_scripts() {
		$suffix = Utils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'madxartwork-pro-admin',
			madxartwork_PRO_URL . 'assets/js/admin' . $suffix . '.js',
			[
				'madxartwork-common',
			],
			madxartwork_PRO_VERSION,
			true
		);

		$locale_settings = [];

		/**
		 * Localize admin settings.
		 *
		 * Filters the admin localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'madxartwork_pro/admin/localize_settings', $locale_settings );

		Utils::print_js_config(
			'madxartwork-pro-admin',
			'madxartworkProConfig',
			$locale_settings
		);
	}

	public function remove_go_pro_menu() {
		remove_action( 'admin_menu', [ Plugin::madxartwork()->settings, 'register_pro_menu' ], Settings::MENU_PRIORITY_GO_PRO );
	}

	public function register_admin_tools_fields( Tools $tools ) {
		// Rollback
		$tools->add_fields( 'versions', 'rollback', [
			'rollback_pro_separator' => [
				'field_args' => [
					'type' => 'raw_html',
					'html' => '<hr>',
				],
			],
			'rollback_pro' => [
				'label' => __( 'Rollback Pro Version', 'madxartwork-pro' ),
				'field_args' => [
					'type' => 'raw_html',
					'html' => sprintf( '<a href="%s" class="button madxartwork-button-spinner madxartwork-rollback-button">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=madxartwork_pro_rollback' ), 'madxartwork_pro_rollback' ), sprintf( __( 'Reinstall Pro v%s', 'madxartwork-pro' ), madxartwork_PRO_PREVIOUS_STABLE_VERSION ) ),
					'desc' => '<span style="color: red;">' . __( 'Warning: Please backup your database before making the rollback.', 'madxartwork-pro' ) . '</span>',
				],
			],
		] );
	}

	public function post_madxartwork_pro_rollback() {
		check_admin_referer( 'madxartwork_pro_rollback' );

		$plugin_slug = basename( madxartwork_PRO__FILE__, '.php' );

		$package_url = API::get_previous_package_url();
		if ( is_wp_error( $package_url ) ) {
			wp_die( $package_url );
		}

		$rollback = new Rollback( [
			'version' => madxartwork_PRO_PREVIOUS_STABLE_VERSION,
			'plugin_name' => madxartwork_PRO_PLUGIN_BASE,
			'plugin_slug' => $plugin_slug,
			'package_url' => $package_url,
		] );

		$rollback->run();

		wp_die( '', __( 'Rollback to Previous Version', 'madxartwork-pro' ), [ 'response' => 200 ] );
	}

	public function plugin_action_links( $links ) {
		unset( $links['go_pro'] );

		return $links;
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( madxartwork_PRO_PLUGIN_BASE === $plugin_file ) {
			$plugin_slug = basename( madxartwork_PRO__FILE__, '.php' );
			$plugin_name = __( 'madxartwork Pro', 'madxartwork-pro' );

			$row_meta = [
				'view-details' => sprintf( '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
					esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_slug . '&TB_iframe=true&width=600&height=550' ) ),
					/* translators: %s: Plugin name - madxartwork Pro. */
					esc_attr( sprintf( __( 'More information about %s', 'madxartwork-pro' ), $plugin_name ) ),
					esc_attr( $plugin_name ),
					__( 'View details', 'madxartwork-pro' )
				),
				'changelog' => '<a href="https://go.madxartwork.com/pro-changelog/" title="' . esc_attr( __( 'View madxartwork Pro Changelog', 'madxartwork-pro' ) ) . '" target="_blank">' . __( 'Changelog', 'madxartwork-pro' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	public function change_tracker_params( $params ) {
		unset( $params['is_first_time'] );

		return $params;
	}

	public function add_finder_items( array $categories ) {
		$settings_url = Settings::get_url();

		$categories['settings']['items']['integrations'] = [
			'title' => __( 'Integrations', 'madxartwork-pro' ),
			'icon' => 'integration',
			'url' => $settings_url . '#tab-integrations',
			'keywords' => [ 'integrations', 'settings', 'typekit', 'facebook', 'recaptcha', 'mailchimp', 'drip', 'activecampaign', 'getresponse', 'convertkit', 'madxartwork' ],
		];

		return $categories;
	}

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_menu', [ $this, 'remove_go_pro_menu' ], 0 );

		add_action( 'madxartwork/admin/after_create_settings/' . Tools::PAGE_ID, [ $this, 'register_admin_tools_fields' ], 50 );

		add_filter( 'plugin_action_links_' . madxartwork_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

		add_filter( 'madxartwork/finder/categories', [ $this, 'add_finder_items' ] );

		add_filter( 'madxartwork/tracker/send_tracking_data_params', [ $this, 'change_tracker_params' ], 200 );
		add_action( 'admin_post_madxartwork_pro_rollback', [ $this, 'post_madxartwork_pro_rollback' ] );
	}
}
