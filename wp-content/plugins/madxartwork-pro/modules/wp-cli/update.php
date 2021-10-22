<?php
namespace madxartworkPro\Modules\WpCli;

use madxartwork\Modules\WpCli\Update as UpdateBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * madxartwork Page Builder Pro cli tools.
 */
class Update extends UpdateBase {

	protected function get_update_db_manager_class() {
		return '\madxartworkPro\Core\Upgrade\Manager';
	}
}
