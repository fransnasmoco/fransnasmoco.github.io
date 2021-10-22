<?php
namespace madxartworkPro\Core\Admin;

use madxartworkPro\License\API;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Canary_Deployment extends \madxartwork\Core\Admin\Canary_Deployment {

	const CURRENT_VERSION = madxartwork_PRO_VERSION;
	const PLUGIN_BASE = madxartwork_PRO_PLUGIN_BASE;

	protected function get_canary_deployment_remote_info( $force ) {
		$version_info = API::get_version( false );
		$canary_info = [];

		if ( ! is_wp_error( $version_info ) && ! empty( $version_info['canary_deployment'] ) ) {
			$canary_info = $version_info['canary_deployment'];
		}

		return $canary_info;
	}
}
