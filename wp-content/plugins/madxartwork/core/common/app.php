<?php
namespace madxartwork\Core\Common;

use madxartwork\Core\Base\App as BaseApp;
use madxartwork\Core\Common\Modules\Ajax\Module as Ajax;
use madxartwork\Core\Common\Modules\Finder\Module as Finder;
use madxartwork\Core\Common\Modules\Connect\Module as Connect;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * App
 *
 * madxartwork's common app that groups shared functionality, components and configuration
 *
 * @since 2.3.0
 */
class App extends BaseApp {

	private $templates = [];

	/**
	 * App constructor.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function __construct() {
		$this->add_default_templates();

		add_action( 'madxartwork/editor/before_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );

		add_action( 'madxartwork/editor/before_enqueue_styles', [ $this, 'register_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ], 9 );

		add_action( 'madxartwork/editor/footer', [ $this, 'print_templates' ] );
		add_action( 'admin_footer', [ $this, 'print_templates' ] );
		add_action( 'wp_footer', [ $this, 'print_templates' ] );
	}

	/**
	 * Init components
	 *
	 * Initializing common components.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function init_components() {
		$this->add_component( 'ajax', new Ajax() );

		if ( current_user_can( 'manage_options' ) ) {
			if ( ! is_customize_preview() ) {
				$this->add_component( 'finder', new Finder() );
			}

			$this->add_component( 'connect', new Connect() );
		}
	}

	/**
	 * Get name.
	 *
	 * Retrieve the app name.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string Common app name.
	 */
	public function get_name() {
		return 'common';
	}

	/**
	 * Register scripts.
	 *
	 * Register common scripts.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function register_scripts() {
		wp_register_script(
			'madxartwork-common-modules',
			$this->get_js_assets_url( 'common-modules' ),
			[],
			madxartwork_VERSION,
			true
		);

		wp_register_script(
			'backbone-marionette',
			$this->get_js_assets_url( 'backbone.marionette', 'assets/lib/backbone/' ),
			[
				'backbone',
			],
			'2.4.5',
			true
		);

		wp_register_script(
			'backbone-radio',
			$this->get_js_assets_url( 'backbone.radio', 'assets/lib/backbone/' ),
			[
				'backbone',
			],
			'1.0.4',
			true
		);

		wp_register_script(
			'madxartwork-dialog',
			$this->get_js_assets_url( 'dialog', 'assets/lib/dialog/' ),
			[
				'jquery-ui-position',
			],
			'4.7.3',
			true
		);

		wp_enqueue_script(
			'madxartwork-common',
			$this->get_js_assets_url( 'common' ),
			[
				'jquery',
				'jquery-ui-draggable',
				'backbone-marionette',
				'backbone-radio',
				'madxartwork-common-modules',
				'madxartwork-dialog',
			],
			madxartwork_VERSION,
			true
		);

		$this->print_config();
	}

	/**
	 * Register styles.
	 *
	 * Register common styles.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function register_styles() {
		wp_register_style(
			'madxartwork-icons',
			$this->get_css_assets_url( 'madxartwork-icons', 'assets/lib/eicons/css/' ),
			[],
			'5.4.0'
		);

		wp_enqueue_style(
			'madxartwork-common',
			$this->get_css_assets_url( 'common', null, 'default', true ),
			[
				'madxartwork-icons',
			],
			madxartwork_VERSION
		);
	}

	/**
	 * Add template.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @param string $template Can be either a link to template file or template
	 *                         HTML content.
	 * @param string $type     Optional. Whether to handle the template as path
	 *                         or text. Default is `path`.
	 */
	public function add_template( $template, $type = 'path' ) {
		if ( 'path' === $type ) {
			ob_start();

			include $template;

			$template = ob_get_clean();
		}

		$this->templates[] = $template;
	}

	/**
	 * Print Templates
	 *
	 * Prints all registered templates.
	 *
	 * @since 2.3.0
	 * @access public
	 */
	public function print_templates() {
		foreach ( $this->templates as $template ) {
			echo $template;
		}
	}

	/**
	 * Get init settings.
	 *
	 * Define the default/initial settings of the common app.
	 *
	 * @since 2.3.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_init_settings() {
		return [
			'version' => madxartwork_VERSION,
			'isRTL' => is_rtl(),
			'activeModules' => array_keys( $this->get_components() ),
			'urls' => [
				'assets' => madxartwork_ASSETS_URL,
			],
		];
	}

	/**
	 * Add default templates.
	 *
	 * Register common app default templates.
	 * @since 2.3.0
	 * @access private
	 */
	private function add_default_templates() {
		$default_templates = [
			'includes/editor-templates/library-layout.php',
		];

		foreach ( $default_templates as $template ) {
			$this->add_template( madxartwork_PATH . $template );
		}
	}
}
