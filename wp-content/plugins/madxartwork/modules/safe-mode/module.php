<?php
namespace madxartwork\Modules\SafeMode;

use madxartwork\Plugin;
use madxartwork\Settings;
use madxartwork\Tools;
use madxartwork\TemplateLibrary\Source_Local;
use madxartwork\Core\Common\Modules\Ajax\Module as Ajax;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \madxartwork\Core\Base\Module {

	const OPTION_ENABLED = 'madxartwork_safe_mode';
	const MU_PLUGIN_FILE_NAME = 'madxartwork-safe-mode.php';
	const DOCS_HELPED_URL = 'https://go.madxartwork.com/safe-mode-helped/';
	const DOCS_DIDNT_HELP_URL = 'https://go.madxartwork.com/safe-mode-didnt-helped/';
	const DOCS_MU_PLUGINS_URL = 'https://go.madxartwork.com/safe-mode-mu-plugins/';
	const DOCS_TRY_SAFE_MODE_URL = 'https://go.madxartwork.com/safe-mode/';

	const EDITOR_NOTICE_TIMEOUT = 10000; /* ms */

	public function get_name() {
		return 'safe-mode';
	}

	public function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'enable_safe_mode', [ $this, 'ajax_enable_safe_mode' ] );
		$ajax->register_ajax_action( 'disable_safe_mode', [ $this, 'disable_safe_mode' ] );
	}

	/**
	 * @param Tools $tools_page
	 */
	public function add_admin_button( $tools_page ) {
		$tools_page->add_fields( Settings::TAB_GENERAL, 'tools', [
			'safe_mode' => [
				'label' => __( 'Safe Mode', 'madxartwork' ),
				'field_args' => [
					'type' => 'select',
					'std' => $this->is_enabled(),
					'options' => [
						'' => __( 'Disable', 'madxartwork' ),
						'global' => __( 'Enable', 'madxartwork' ),

					],
					'desc' => __( 'Safe Mode allows you to troubleshoot issues by only loading the editor, without loading the theme or any other plugin.', 'madxartwork' ),
				],
			],
		] );
	}

	public function on_update_safe_mode( $value ) {
		if ( 'yes' === $value || 'global' === $value ) {
			$this->enable_safe_mode();
		} else {
			$this->disable_safe_mode();
		}

		return $value;
	}

	public function ajax_enable_safe_mode( $data ) {
		// It will run `$this->>update_safe_mode`.
		update_option( 'madxartwork_safe_mode', 'yes' );

		$document = Plugin::$instance->documents->get( $data['editor_post_id'] );

		if ( $document ) {
			return add_query_arg( 'madxartwork-mode', 'safe', $document->get_edit_url() );
		}

		return false;
	}

	public function enable_safe_mode() {
		WP_Filesystem();

		$this->update_allowed_plugins();

		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			wp_mkdir_p( WPMU_PLUGIN_DIR );
			add_option( 'madxartwork_safe_mode_created_mu_dir', true );
		}

		if ( ! is_dir( WPMU_PLUGIN_DIR ) ) {
			wp_die( __( 'Cannot enable Safe Mode', 'madxartwork' ) );
		}

		$results = copy_dir( __DIR__ . '/mu-plugin/', WPMU_PLUGIN_DIR );

		if ( is_wp_error( $results ) ) {
			return false;
		}
	}

	public function disable_safe_mode() {
		$file_path = WP_CONTENT_DIR . '/mu-plugins/madxartwork-safe-mode.php';
		if ( file_exists( $file_path ) ) {
			unlink( $file_path );
		}

		if ( get_option( 'madxartwork_safe_mode_created_mu_dir' ) ) {
			// It will be removed only if it's empty and don't have other mu-plugins.
			@rmdir( WPMU_PLUGIN_DIR );
		}

		delete_option( 'madxartwork_safe_mode' );
		delete_option( 'madxartwork_safe_mode_allowed_plugins' );
		delete_option( 'theme_mods_madxartwork-safe' );
		delete_option( 'madxartwork_safe_mode_created_mu_dir' );
	}

	public function filter_preview_url( $url ) {
		return add_query_arg( 'madxartwork-mode', 'safe', $url );
	}

	public function filter_template() {
		return madxartwork_PATH . 'modules/page-templates/templates/canvas.php';
	}

	public function print_safe_mode_css() {
		?>
		<style>
			.madxartwork-safe-mode-toast {
				position: absolute;
				z-index: 10000; /* Over the loading layer */
				bottom: 10px;
				width: 400px;
				line-height: 30px;
				background: white;
				padding: 20px 25px 25px;
				box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
				border-radius: 5px;
				font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
			}

			body.rtl .madxartwork-safe-mode-toast {
				left: 10px;
			}

			body:not(.rtl) .madxartwork-safe-mode-toast {
				right: 10px;
			}

			#madxartwork-try-safe-mode {
				display: none;
			}

			.madxartwork-safe-mode-toast .madxartwork-toast-content {
				font-size: 13px;
				line-height: 22px;
				color: #6D7882;
			}

			.madxartwork-safe-mode-toast .madxartwork-toast-content a {
				color: #138FFF;
			}

			.madxartwork-safe-mode-toast .madxartwork-toast-content hr {
				margin: 15px auto;
				border: 0 none;
				border-top: 1px solid #F1F3F5;
			}

			.madxartwork-safe-mode-toast header {
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap;
				margin-bottom: 20px;
			}

			.madxartwork-safe-mode-toast header > * {
				margin-top: 10px;
			}

			.madxartwork-safe-mode-toast .madxartwork-safe-mode-button {
				display: inline-block;
				font-weight: 500;
				font-size: 11px;
				text-transform: uppercase;
				color: white;
				padding: 10px 15px;
				line-height: 1;
				background: #A4AFB7;
				border-radius: 3px;
			}

			#madxartwork-try-safe-mode .madxartwork-safe-mode-button {
				background: #39B54A;
			}

			.madxartwork-safe-mode-toast header i {
				font-size: 25px;
				color: #fcb92c;
			}

			body:not(.rtl) .madxartwork-safe-mode-toast header i {
				margin-right: 10px;
			}

			body.rtl .madxartwork-safe-mode-toast header i {
				margin-left: 10px;
			}

			.madxartwork-safe-mode-toast header h2 {
				flex-grow: 1;
				font-size: 18px;
				color: #6D7882;
			}

			.madxartwork-safe-mode-list-item {
				margin-top: 10px;
				list-style: outside;
			}

			body:not(.rtl) .madxartwork-safe-mode-list-item {
				margin-left: 15px;
			}

			body.rtl .madxartwork-safe-mode-list-item {
				margin-right: 15px;
			}

			.madxartwork-safe-mode-list-item b {
				font-size: 14px;
			}

			.madxartwork-safe-mode-list-item-content {
				font-style: italic;
				color: #a4afb7;
			}

			.madxartwork-safe-mode-list-item-title {
				font-weight: 500;
			}

			.madxartwork-safe-mode-mu-plugins {
				background-color: #f1f3f5;
				margin-top: 20px;
				padding: 10px 15px;
			}
		</style>
		<?php
	}

	public function print_safe_mode_notice() {
		echo $this->print_safe_mode_css();
		?>
		<div class="madxartwork-safe-mode-toast" id="madxartwork-safe-mode-message">
			<header>
				<i class="eicon-warning"></i>
				<h2><?php echo __( 'Safe Mode ON', 'madxartwork' ); ?></h2>
				<a class="madxartwork-safe-mode-button madxartwork-disable-safe-mode" target="_blank" href="<?php echo $this->get_admin_page_url(); ?>">
					<?php echo __( 'Disable Safe Mode', 'madxartwork' ); ?>
				</a>
			</header>

			<div class="madxartwork-toast-content">
				<ul class="madxartwork-safe-mode-list">
					<li class="madxartwork-safe-mode-list-item">
						<div class="madxartwork-safe-mode-list-item-title"><?php echo __( 'Editor successfully loaded?', 'madxartwork' ); ?></div>
						<div class="madxartwork-safe-mode-list-item-content"><?php echo __( 'The issue was probably caused by one of your plugins or theme.', 'madxartwork' ); ?> <?php printf( __( '<a href="%s" target="_blank">Click here</a> to troubleshoot', 'madxartwork' ), self::DOCS_HELPED_URL ); ?></div>
					</li>
					<li class="madxartwork-safe-mode-list-item">
						<div class="madxartwork-safe-mode-list-item-title"><?php echo __( 'Still experiencing issues?', 'madxartwork' ); ?></div>
						<div class="madxartwork-safe-mode-list-item-content"><?php printf( __( '<a href="%s" target="_blank">Click here</a> to troubleshoot', 'madxartwork' ), self::DOCS_DIDNT_HELP_URL ); ?></div>
					</li>
				</ul>
				<?php
				$mu_plugins = wp_get_mu_plugins();

				if ( 1 < count( $mu_plugins ) ) : ?>
					<div class="madxartwork-safe-mode-mu-plugins"><?php printf( __( 'Please note! We couldn\'t deactivate all of your plugins on Safe Mode. Please <a href="%s" target="_blank">read more</a> about this issue.', 'madxartwork' ), self::DOCS_MU_PLUGINS_URL ); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<script>
			var madxartworkSafeMode = function() {
				var attachEvents = function() {
				  jQuery( '.madxartwork-disable-safe-mode' ).on( 'click', function( e ) {
						if ( ! madxartworkCommon || ! madxartworkCommon.ajax ) {
							return;
						}

						e.preventDefault();

						madxartworkCommon.ajax.addRequest(
							'disable_safe_mode', {
								success: function() {
									if ( -1 === location.href.indexOf( 'madxartwork-mode=safe' ) ) {
										location.reload();
									} else {
										// Need to remove the URL from browser history.
										location.replace( location.href.replace( '&madxartwork-mode=safe', '' ) );
									}
								},
								error: function() {
									alert( 'An error occurred' );
								},
							},
							true
						);
					} );
				};

				var init = function() {
					attachEvents();
				};

				init();
			};

			new madxartworkSafeMode();
		</script>
		<?php
	}

	public function print_try_safe_mode() {
		if ( ! $this->is_allowed_post_type() ) {
			return;
		}

		echo $this->print_safe_mode_css();
		?>
		<div class="madxartwork-safe-mode-toast" id="madxartwork-try-safe-mode">
			<header>
				<i class="eicon-warning"></i>
				<h2><?php echo __( 'Can\'t Edit?', 'madxartwork' ); ?></h2>
				<a class="madxartwork-safe-mode-button madxartwork-enable-safe-mode" target="_blank" href="<?php echo $this->get_admin_page_url(); ?>">
					<?php echo __( 'Enable Safe Mode', 'madxartwork' ); ?>
				</a>
			</header>
			<div class="madxartwork-toast-content">
				<?php echo __( 'Having problems loading madxartwork? Please enable Safe Mode to troubleshoot.', 'madxartwork' ); ?>
				<a href="<?php echo self::DOCS_TRY_SAFE_MODE_URL; ?>" target="_blank"><?php echo __( 'Learn More', 'madxartwork' ); ?></a>
			</div>
		</div>

		<script>
			var madxartworkTrySafeMode = function() {
				var attachEvents = function() {
					jQuery( '.madxartwork-enable-safe-mode' ).on( 'click', function( e ) {
						if ( ! madxartworkCommon || ! madxartworkCommon.ajax ) {
							return;
						}

						e.preventDefault();

						madxartworkCommon.ajax.addRequest(
							'enable_safe_mode', {
								data: {
									editor_post_id: '<?php echo Plugin::$instance->editor->get_post_id(); ?>',
								},
								success: function( url ) {
									location.assign( url );
								},
								error: function() {
									alert( 'An error occurred' );
								},
							},
							true
						);
					} );
				};

				var ismadxartworkLoaded = function() {
					if ( 'undefined' === typeof madxartwork ) {
						return false;
					}

					if ( ! madxartwork.loaded ) {
						return false;
					}

					if ( jQuery( '#madxartwork-loading' ).is( ':visible' ) ) {
						return false;
					}

					return true;
				};

				var handleTrySafeModeNotice = function() {
					var $notice = jQuery( '#madxartwork-try-safe-mode' );

					if ( ismadxartworkLoaded() ) {
						$notice.remove();
						return;
					}

					if ( ! $notice.data( 'visible' ) ) {
						$notice.show().data( 'visible', true );
					}

					// Re-check after 500ms.
					setTimeout( handleTrySafeModeNotice, 500 );
				};

				var init = function() {
					setTimeout( handleTrySafeModeNotice, <?php echo self::EDITOR_NOTICE_TIMEOUT; ?> );

					attachEvents();
				};

				init();
			};

			new madxartworkTrySafeMode();
		</script>

		<?php
	}

	public function run_safe_mode() {
		remove_action( 'madxartwork/editor/footer', [ $this, 'print_try_safe_mode' ] );

		// Avoid notices like for comment.php.
		add_filter( 'deprecated_file_trigger_error', '__return_false' );

		add_filter( 'template_include', [ $this, 'filter_template' ], 999 );
		add_filter( 'madxartwork/document/urls/preview', [ $this, 'filter_preview_url' ] );
		add_action( 'madxartwork/editor/footer', [ $this, 'print_safe_mode_notice' ] );
		add_action( 'madxartwork/editor/before_enqueue_scripts', [ $this, 'register_scripts' ], 11 /* After Common Scripts */ );
	}

	public function register_scripts() {
		wp_add_inline_script( 'madxartwork-common', 'madxartworkCommon.ajax.addRequestConstant( "madxartwork-mode", "safe" );' );
	}

	private function is_enabled() {
		return get_option( self::OPTION_ENABLED, '' );
	}

	private function get_admin_page_url() {
		// A fallback URL if the Js doesn't work.
		return Tools::get_url();
	}

	public function plugin_action_links( $actions ) {
		$actions['disable'] = '<a href="' . self::get_admin_page_url() . '">' . __( 'Disable Safe Mode', 'madxartwork' ) . '</a>';

		return $actions;
	}

	public function on_deactivated_plugin( $plugin ) {
		if ( madxartwork_PLUGIN_BASE === $plugin ) {
			$this->disable_safe_mode();
			return;
		}

		$allowed_plugins = get_option( 'madxartwork_safe_mode_allowed_plugins', [] );
		$plugin_key = array_search( $plugin, $allowed_plugins, true );

		if ( $plugin_key ) {
			unset( $allowed_plugins[ $plugin_key ] );
			update_option( 'madxartwork_safe_mode_allowed_plugins', $allowed_plugins );
		}
	}

	public function update_allowed_plugins() {
		$allowed_plugins = [
			'madxartwork' => madxartwork_PLUGIN_BASE,
		];

		if ( defined( 'madxartwork_PRO_PLUGIN_BASE' ) ) {
			$allowed_plugins['madxartwork_pro'] = madxartwork_PRO_PLUGIN_BASE;
		}

		if ( defined( 'WC_PLUGIN_BASENAME' ) ) {
			$allowed_plugins['woocommerce'] = WC_PLUGIN_BASENAME;
		}

		update_option( 'madxartwork_safe_mode_allowed_plugins', $allowed_plugins );
	}

	public function __construct() {
		add_action( 'madxartwork/admin/after_create_settings/madxartwork-tools', [ $this, 'add_admin_button' ] );
		add_action( 'madxartwork/ajax/register_actions', [ $this, 'register_ajax_actions' ] );

		$plugin_file = self::MU_PLUGIN_FILE_NAME;
		add_filter( "plugin_action_links_{$plugin_file}", [ $this, 'plugin_action_links' ] );

		// Use pre_update, in order to catch cases that $value === $old_value and it not updated.
		add_filter( 'pre_update_option_madxartwork_safe_mode', [ $this, 'on_update_safe_mode' ], 10, 2 );

		add_action( 'madxartwork/safe_mode/init', [ $this, 'run_safe_mode' ] );
		add_action( 'madxartwork/editor/footer', [ $this, 'print_try_safe_mode' ] );

		if ( $this->is_enabled() ) {
			add_action( 'activated_plugin', [ $this, 'update_allowed_plugins' ] );
			add_action( 'deactivated_plugin', [ $this, 'on_deactivated_plugin' ] );
		}
	}

	private function is_allowed_post_type() {
		$allowed_post_types = [
			'post',
			'page',
			'product',
			Source_Local::CPT,
		];

		$current_post_type = get_post_type( Plugin::$instance->editor->get_post_id() );

		return in_array( $current_post_type, $allowed_post_types );
	}
}
