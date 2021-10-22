<?php
namespace madxartwork\Core\Upgrade;

use madxartwork\Core\Base\DB_Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends DB_Upgrades_Manager {

	// todo: remove in future releases
	public function should_upgrade() {
		if ( ( 'madxartwork' === $this->get_plugin_name() ) && version_compare( get_option( $this->get_version_option_name() ), '2.4.2', '<' ) ) {
			delete_option( 'madxartwork_log' );
		}

		return parent::should_upgrade();
	}

	public function get_name() {
		return 'upgrade';
	}

	public function get_action() {
		return 'madxartwork_updater';
	}

	public function get_plugin_name() {
		return 'madxartwork';
	}

	public function get_plugin_label() {
		return __( 'madxartwork', 'madxartwork' );
	}

	public function get_updater_label() {
		return sprintf( '<strong>%s </strong> &#8211;', __( 'madxartwork Data Updater', 'madxartwork' ) );
	}

	public function get_new_version() {
		return madxartwork_VERSION;
	}

	public function get_version_option_name() {
		return 'madxartwork_version';
	}

	public function get_upgrades_class() {
		return 'madxartwork\Core\Upgrade\Upgrades';
	}
}
