<?php
namespace madxartwork\Modules\Library;

use madxartwork\Core\Base\Module as BaseModule;
use madxartwork\Modules\Library\Documents;
use madxartwork\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork library module.
 *
 * madxartwork library module handler class is responsible for registering and
 * managing madxartwork library modules.
 *
 * @since 2.0.0
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * Retrieve the library module name.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'library';
	}

	/**
	 * Library module constructor.
	 *
	 * Initializing madxartwork library module.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function __construct() {
		Plugin::$instance->documents
			->register_document_type( 'not-supported', Documents\Not_Supported::get_class_full_name() )
			->register_document_type( 'page', Documents\Page::get_class_full_name() )
			->register_document_type( 'section', Documents\Section::get_class_full_name() );
	}
}
