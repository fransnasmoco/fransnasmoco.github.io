<?php
namespace madxartworkPro\Core\Upgrade;

use madxartwork\Core\Upgrade\Manager as Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends Upgrades_Manager {

	public function get_action() {
		return 'madxartwork_pro_updater';
	}

	public function get_plugin_name() {
		return 'madxartwork-pro';
	}

	public function get_plugin_label() {
		return __( 'madxartwork Pro', 'madxartwork-pro' );
	}

	public function get_new_version() {
		return madxartwork_PRO_VERSION;
	}

	public function get_version_option_name() {
		return 'madxartwork_pro_version';
	}

	public function get_upgrades_class() {
		return 'madxartworkPro\Core\Upgrade\Upgrades';
	}
}
