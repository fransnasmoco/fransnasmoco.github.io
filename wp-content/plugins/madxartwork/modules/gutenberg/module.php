<?php
namespace madxartwork\Modules\Gutenberg;

use madxartwork\Core\Base\Module as BaseModule;
use madxartwork\Plugin;
use madxartwork\User;
use madxartwork\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends BaseModule {

	protected $is_gutenberg_editor_active = false;

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function get_name() {
		return 'gutenberg';
	}

	/**
	 * @since 2.1.0
	 * @access public
	 * @static
	 */
	public static function is_active() {
		return function_exists( 'register_block_type' );
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function register_madxartwork_rest_field() {
		register_rest_field( get_post_types( '', 'names' ),
			'gutenberg_madxartwork_mode', [
				'update_callback' => function( $request_value, $object ) {
					if ( ! User::is_current_user_can_edit( $object->ID ) ) {
						return false;
					}

					Plugin::$instance->db->set_is_madxartwork_page( $object->ID, false );

					return true;
				},
			]
		);
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function enqueue_assets() {
		$document = Plugin::$instance->documents->get( get_the_ID() );

		if ( ! $document || ! $document->is_editable_by_current_user() ) {
			return;
		}

		$this->is_gutenberg_editor_active = true;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'madxartwork-gutenberg', madxartwork_ASSETS_URL . 'js/gutenberg' . $suffix . '.js', [ 'jquery' ], madxartwork_VERSION, true );

		$madxartwork_settings = [
			'ismadxartworkMode' => $document->is_built_with_madxartwork(),
			'editLink' => $document->get_edit_url(),
		];
		Utils::print_js_config( 'madxartwork-gutenberg', 'madxartworkGutenbergSettings', $madxartwork_settings );
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function print_admin_js_template() {
		if ( ! $this->is_gutenberg_editor_active ) {
			return;
		}

		?>
		<script id="madxartwork-gutenberg-button-switch-mode" type="text/html">
			<div id="madxartwork-switch-mode">
				<button id="madxartwork-switch-mode-button" type="button" class="button button-primary button-large">
					<span class="madxartwork-switch-mode-on"><?php echo __( '&#8592; Back to WordPress Editor', 'madxartwork' ); ?></span>
					<span class="madxartwork-switch-mode-off">
						<i class="eicon-madxartwork-square" aria-hidden="true"></i>
						<?php echo __( 'Edit with madxartwork', 'madxartwork' ); ?>
					</span>
				</button>
			</div>
		</script>

		<script id="madxartwork-gutenberg-panel" type="text/html">
			<div id="madxartwork-editor"><a id="madxartwork-go-to-edit-page-link" href="#">
					<div id="madxartwork-editor-button" class="button button-primary button-hero">
						<i class="eicon-madxartwork-square" aria-hidden="true"></i>
						<?php echo __( 'Edit with madxartwork', 'madxartwork' ); ?>
					</div>
					<div class="madxartwork-loader-wrapper">
						<div class="madxartwork-loader">
							<div class="madxartwork-loader-boxes">
								<div class="madxartwork-loader-box"></div>
								<div class="madxartwork-loader-box"></div>
								<div class="madxartwork-loader-box"></div>
								<div class="madxartwork-loader-box"></div>
							</div>
						</div>
						<div class="madxartwork-loading-title"><?php echo __( 'Loading', 'madxartwork' ); ?></div>
					</div>
				</a></div>
		</script>
		<?php
	}

	/**
	 * @since 2.1.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_madxartwork_rest_field' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_assets' ] );
		add_action( 'admin_footer', [ $this, 'print_admin_js_template' ] );
	}
}
