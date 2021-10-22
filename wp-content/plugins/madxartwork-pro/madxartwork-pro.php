<?php
/**
 * Plugin Name: madxartwork Pro
 * Description: madxartwork Pro brings a whole new design experience to WordPress. Customize your entire theme: header, footer, single post, archive and 404 page, all with one page builder.
 * Plugin URI: https://madxartwork.com/
 * Author: madxartwork.com
 * Version: 2.7.3
 * Author URI: https://madxartwork.com/
 *
 * Text Domain: madxartwork-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
update_option( 'madxartwork_pro_license_key', 'f05958872E193feb37a505a84ce' );
set_transient( 'madxartwork_pro_license_data', [ 'license' => 'valid', 'expires' => '01.01.2030' ] );
set_transient( 'timeout_madxartwork_pro_license_data', 1893456000 );
define( 'madxartwork_PRO_VERSION', '2.7.3' );
define( 'madxartwork_PRO_PREVIOUS_STABLE_VERSION', '2.6.5' );

define( 'madxartwork_PRO__FILE__', __FILE__ );
define( 'madxartwork_PRO_PLUGIN_BASE', plugin_basename( madxartwork_PRO__FILE__ ) );
define( 'madxartwork_PRO_PATH', plugin_dir_path( madxartwork_PRO__FILE__ ) );
define( 'madxartwork_PRO_ASSETS_PATH', madxartwork_PRO_PATH . 'assets/' );
define( 'madxartwork_PRO_MODULES_PATH', madxartwork_PRO_PATH . 'modules/' );
define( 'madxartwork_PRO_URL', plugins_url( '/', madxartwork_PRO__FILE__ ) );
define( 'madxartwork_PRO_ASSETS_URL', madxartwork_PRO_URL . 'assets/' );
define( 'madxartwork_PRO_MODULES_URL', madxartwork_PRO_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function madxartwork_pro_load_plugin() {
	load_plugin_textdomain( 'madxartwork-pro' );

	if ( ! did_action( 'madxartwork/loaded' ) ) {
		add_action( 'admin_notices', 'madxartwork_pro_fail_load' );

		return;
	}

	$madxartwork_version_required = '2.7.3';
	if ( ! version_compare( madxartwork_VERSION, $madxartwork_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'madxartwork_pro_fail_load_out_of_date' );

		return;
	}

	$madxartwork_version_recommendation = '2.7.3';
	if ( ! version_compare( madxartwork_VERSION, $madxartwork_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'madxartwork_pro_admin_notice_upgrade_recommendation' );
	}

	require madxartwork_PRO_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'madxartwork_pro_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function madxartwork_pro_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'madxartwork/madxartwork.php';

	if ( _is_madxartwork_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'madxartwork Pro is not working because you need to activate the madxartwork plugin.', 'madxartwork-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate madxartwork Now', 'madxartwork-pro' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=madxartwork' ), 'install-plugin_madxartwork' );

		$message = '<p>' . __( 'madxartwork Pro is not working because you need to install the madxartwork plugin.', 'madxartwork-pro' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install madxartwork Now', 'madxartwork-pro' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function madxartwork_pro_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'madxartwork/madxartwork.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'madxartwork Pro is not working because you are using an old version of madxartwork.', 'madxartwork-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update madxartwork Now', 'madxartwork-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function madxartwork_pro_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'madxartwork/madxartwork.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of madxartwork is available. For better performance and compatibility of madxartwork Pro, we recommend updating to the latest version.', 'madxartwork-pro' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update madxartwork Now', 'madxartwork-pro' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_madxartwork_installed' ) ) {

	function _is_madxartwork_installed() {
		$file_path = 'madxartwork/madxartwork.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
