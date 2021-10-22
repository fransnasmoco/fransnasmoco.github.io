<?php
/**
 * Plugin Name: madxartwork
 * Description: The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.
 * Plugin URI: https://madxartwork.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: madxartwork.com
 * Version: 2.7.6
 * Author URI: https://madxartwork.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: madxartwork
 *
 * @package madxartwork
 * @category Core
 *
 * madxartwork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * madxartwork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'madxartwork_VERSION', '2.7.6' );
define( 'madxartwork_PREVIOUS_STABLE_VERSION', '2.6.8' );

define( 'madxartwork__FILE__', __FILE__ );
define( 'madxartwork_PLUGIN_BASE', plugin_basename( madxartwork__FILE__ ) );
define( 'madxartwork_PATH', plugin_dir_path( madxartwork__FILE__ ) );

if ( defined( 'madxartwork_TESTS' ) && madxartwork_TESTS ) {
	define( 'madxartwork_URL', 'file://' . madxartwork_PATH );
} else {
	define( 'madxartwork_URL', plugins_url( '/', madxartwork__FILE__ ) );
}

define( 'madxartwork_MODULES_PATH', plugin_dir_path( madxartwork__FILE__ ) . '/modules' );
define( 'madxartwork_ASSETS_PATH', madxartwork_PATH . 'assets/' );
define( 'madxartwork_ASSETS_URL', madxartwork_URL . 'assets/' );

add_action( 'plugins_loaded', 'madxartwork_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	add_action( 'admin_notices', 'madxartwork_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', 'madxartwork_fail_wp_version' );
} else {
	require madxartwork_PATH . 'includes/plugin.php';
}

/**
 * Load madxartwork textdomain.
 *
 * Load gettext translate for madxartwork text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function madxartwork_load_plugin_textdomain() {
	load_plugin_textdomain( 'madxartwork' );
}

/**
 * madxartwork admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function madxartwork_fail_php_version() {
	/* translators: %s: PHP version */
	$message = sprintf( esc_html__( 'madxartwork requires PHP version %s+, plugin is currently NOT RUNNING.', 'madxartwork' ), '5.4' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * madxartwork admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.5.0
 *
 * @return void
 */
function madxartwork_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message = sprintf( esc_html__( 'madxartwork requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'madxartwork' ), '4.7' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
