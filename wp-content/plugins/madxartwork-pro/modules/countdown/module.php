<?php
namespace madxartworkPro\Modules\Countdown;

use madxartworkPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Countdown',
		];
	}

	public function get_name() {
		return 'countdown';
	}
}
