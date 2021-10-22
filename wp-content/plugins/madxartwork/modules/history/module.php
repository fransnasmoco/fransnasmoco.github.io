<?php
namespace madxartwork\Modules\History;

use madxartwork\Core\Base\Module as BaseModule;
use madxartwork\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork history module.
 *
 * madxartwork history module handler class is responsible for registering and
 * managing madxartwork history modules.
 *
 * @since 1.7.0
 */
class Module extends BaseModule {

	/**
	 * Get module name.
	 *
	 * Retrieve the history module name.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'history';
	}

	/**
	 * Localize settings.
	 *
	 * Add new localized settings for the history module.
	 *
	 * Fired by `madxartwork/editor/localize_settings` filter.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @param array $settings Localized settings.
	 *
	 * @return array Localized settings.
	 */
	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'history' => __( 'History', 'madxartwork' ),
				'template' => __( 'Template', 'madxartwork' ),
				'added' => __( 'Added', 'madxartwork' ),
				'removed' => __( 'Removed', 'madxartwork' ),
				'edited' => __( 'Edited', 'madxartwork' ),
				'moved' => __( 'Moved', 'madxartwork' ),
				'editing_started' => __( 'Editing Started', 'madxartwork' ),
				'style_pasted' => __( 'Style Pasted', 'madxartwork' ),
				'style_reset' => __( 'Style Reset', 'madxartwork' ),
				'all_content' => __( 'All Content', 'madxartwork' ),
			],
		] );

		return $settings;
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function add_templates() {
		Plugin::$instance->common->add_template( __DIR__ . '/views/history-panel-template.php' );
		Plugin::$instance->common->add_template( __DIR__ . '/views/revisions-panel-template.php' );
	}

	/**
	 * History module constructor.
	 *
	 * Initializing madxartwork history module.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'madxartwork/editor/localize_settings', [ $this, 'localize_settings' ] );

		add_action( 'madxartwork/editor/init', [ $this, 'add_templates' ] );
	}
}
