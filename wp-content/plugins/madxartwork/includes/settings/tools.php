<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork "Tools" page in WordPress Dashboard.
 *
 * madxartwork settings page handler class responsible for creating and displaying
 * madxartwork "Tools" page in WordPress dashboard.
 *
 * @since 1.0.0
 */
class Tools extends Settings_Page {

	/**
	 * Settings page ID for madxartwork tools.
	 */
	const PAGE_ID = 'madxartwork-tools';

	/**
	 * Register admin menu.
	 *
	 * Add new madxartwork Tools admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		add_submenu_page(
			Settings::PAGE_ID,
			__( 'Tools', 'madxartwork' ),
			__( 'Tools', 'madxartwork' ),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ]
		);
	}

	/**
	 * Clear cache.
	 *
	 * Delete post meta containing the post CSS file data. And delete the actual
	 * CSS files from the upload directory.
	 *
	 * Fired by `wp_ajax_madxartwork_clear_cache` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_madxartwork_clear_cache() {
		check_ajax_referer( 'madxartwork_clear_cache', '_nonce' );

		Plugin::$instance->files_manager->clear_cache();

		wp_send_json_success();
	}

	/**
	 * Replace URLs.
	 *
	 * Sends an ajax request to replace old URLs to new URLs. This method also
	 * updates all the madxartwork data.
	 *
	 * Fired by `wp_ajax_madxartwork_replace_url` action.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function ajax_madxartwork_replace_url() {
		check_ajax_referer( 'madxartwork_replace_url', '_nonce' );

		$from = ! empty( $_POST['from'] ) ? $_POST['from'] : '';
		$to = ! empty( $_POST['to'] ) ? $_POST['to'] : '';

		try {
			$results = Utils::replace_urls( $from, $to );
			wp_send_json_success( $results );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * madxartwork version rollback.
	 *
	 * Rollback to previous madxartwork version.
	 *
	 * Fired by `admin_post_madxartwork_rollback` action.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function post_madxartwork_rollback() {
		check_admin_referer( 'madxartwork_rollback' );

		$rollback_versions = $this->get_rollback_versions();
		if ( empty( $_GET['version'] ) || ! in_array( $_GET['version'], $rollback_versions ) ) {
			wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'madxartwork' ) );
		}

		$plugin_slug = basename( madxartwork__FILE__, '.php' );

		$rollback = new Rollback(
			[
				'version' => $_GET['version'],
				'plugin_name' => madxartwork_PLUGIN_BASE,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $_GET['version'] ),
			]
		);

		$rollback->run();

		wp_die(
			'', __( 'Rollback to Previous Version', 'madxartwork' ), [
				'response' => 200,
			]
		);
	}

	/**
	 * Tools page constructor.
	 *
	 * Initializing madxartwork "Tools" page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 205 );

		if ( ! empty( $_POST ) ) {
			add_action( 'wp_ajax_madxartwork_clear_cache', [ $this, 'ajax_madxartwork_clear_cache' ] );
			add_action( 'wp_ajax_madxartwork_replace_url', [ $this, 'ajax_madxartwork_replace_url' ] );
		}

		add_action( 'admin_post_madxartwork_rollback', [ $this, 'post_madxartwork_rollback' ] );
	}

	private function get_rollback_versions() {
		$rollback_versions = get_transient( 'madxartwork_rollback_versions_' . madxartwork_VERSION );
		if ( false === $rollback_versions ) {
			$max_versions = 30;

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			$plugin_information = plugins_api(
				'plugin_information', [
					'slug' => 'madxartwork',
				]
			);

			if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
				return [];
			}

			krsort( $plugin_information->versions );

			$rollback_versions = [];

			$current_index = 0;
			foreach ( $plugin_information->versions as $version => $download_link ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				if ( preg_match( '/(trunk|beta|rc)/i', strtolower( $version ) ) ) {
					continue;
				}

				if ( version_compare( $version, madxartwork_VERSION, '>=' ) ) {
					continue;
				}

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'madxartwork_rollback_versions_' . madxartwork_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}

	/**
	 * Create tabs.
	 *
	 * Return the tools page tabs, sections and fields.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return array An array with the page tabs, sections and fields.
	 */
	protected function create_tabs() {
		$rollback_html = '<select class="madxartwork-rollback-select">';

		foreach ( $this->get_rollback_versions() as $version ) {
			$rollback_html .= "<option value='{$version}'>$version</option>";
		}
		$rollback_html .= '</select>';

		return [
			'general' => [
				'label' => __( 'General', 'madxartwork' ),
				'sections' => [
					'tools' => [
						'fields' => [
							'clear_cache' => [
								'label' => __( 'Regenerate CSS', 'madxartwork' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<button data-nonce="%s" class="button madxartwork-button-spinner" id="madxartwork-clear-cache-button">%s</button>', wp_create_nonce( 'madxartwork_clear_cache' ), __( 'Regenerate Files', 'madxartwork' ) ),
									'desc' => __( 'Styles set in madxartwork are saved in CSS files in the uploads folder. Recreate those files, according to the most recent settings.', 'madxartwork' ),
								],
							],
							'reset_api_data' => [
								'label' => __( 'Sync Library', 'madxartwork' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<button data-nonce="%s" class="button madxartwork-button-spinner" id="madxartwork-library-sync-button">%s</button>', wp_create_nonce( 'madxartwork_reset_library' ), __( 'Sync Library', 'madxartwork' ) ),
									'desc' => __( 'madxartwork Library automatically updates on a daily basis. You can also manually update it by clicking on the sync button.', 'madxartwork' ),
								],
							],
						],
					],
				],
			],
			'replace_url' => [
				'label' => __( 'Replace URL', 'madxartwork' ),
				'sections' => [
					'replace_url' => [
						'callback' => function() {
							$intro_text = sprintf(
								/* translators: %s: Codex URL */
								__( '<strong>Important:</strong> It is strongly recommended that you <a target="_blank" href="%s">backup your database</a> before using Replace URL.', 'madxartwork' ),
								'https://codex.wordpress.org/WordPress_Backups'
							);
							$intro_text = '<div>' . $intro_text . '</div>';

							echo '<h2>' . esc_html__( 'Replace URL', 'madxartwork' ) . '</h2>';
							echo $intro_text;
						},
						'fields' => [
							'replace_url' => [
								'label' => __( 'Update Site Address (URL)', 'madxartwork' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf( '<input type="text" name="from" placeholder="http://old-url.com" class="medium-text"><input type="text" name="to" placeholder="http://new-url.com" class="medium-text"><button data-nonce="%s" class="button madxartwork-button-spinner" id="madxartwork-replace-url-button">%s</button>', wp_create_nonce( 'madxartwork_replace_url' ), __( 'Replace URL', 'madxartwork' ) ),
									'desc' => __( 'Enter your old and new URLs for your WordPress installation, to update all madxartwork data (Relevant for domain transfers or move to \'HTTPS\').', 'madxartwork' ),
								],
							],
						],
					],
				],
			],
			'versions' => [
				'label' => __( 'Version Control', 'madxartwork' ),
				'sections' => [
					'rollback' => [
						'label' => __( 'Rollback to Previous Version', 'madxartwork' ),
						'callback' => function() {
							$intro_text = sprintf(
								/* translators: %s: madxartwork version */
								__( 'Experiencing an issue with madxartwork version %s? Rollback to a previous version before the issue appeared.', 'madxartwork' ),
								madxartwork_VERSION
							);
							$intro_text = '<p>' . $intro_text . '</p>';

							echo $intro_text;
						},
						'fields' => [
							'rollback' => [
								'label' => __( 'Rollback Version', 'madxartwork' ),
								'field_args' => [
									'type' => 'raw_html',
									'html' => sprintf(
										$rollback_html . '<a data-placeholder-text="' . __( 'Reinstall', 'madxartwork' ) . ' v{VERSION}" href="#" data-placeholder-url="%s" class="button madxartwork-button-spinner madxartwork-rollback-button">%s</a>',
										wp_nonce_url( admin_url( 'admin-post.php?action=madxartwork_rollback&version=VERSION' ), 'madxartwork_rollback' ),
										__( 'Reinstall', 'madxartwork' )
									),
									'desc' => '<span style="color: red;">' . __( 'Warning: Please backup your database before making the rollback.', 'madxartwork' ) . '</span>',
								],
							],
						],
					],
					'beta' => [
						'label' => __( 'Become a Beta Tester', 'madxartwork' ),
						'callback' => function() {
							$intro_text = __( 'Turn-on Beta Tester, to get notified when a new beta version of madxartwork or E-Pro is available. The Beta version will not install automatically. You always have the option to ignore it.', 'madxartwork' );
							$intro_text = '<p>' . $intro_text . '</p>';
							$newsletter_opt_in_text = sprintf( __( 'Click <a id="beta-tester-first-to-know" href="%s">here</a> to join our First-To-Know email updates', 'madxartwork' ), '#' );

							echo $intro_text;
							echo $newsletter_opt_in_text;
						},
						'fields' => [
							'beta' => [
								'label' => __( 'Beta Tester', 'madxartwork' ),
								'field_args' => [
									'type' => 'select',
									'default' => 'no',
									'options' => [
										'no' => __( 'Disable', 'madxartwork' ),
										'yes' => __( 'Enable', 'madxartwork' ),
									],
									'desc' => '<span style="color: red;">' . __( 'Please Note: We do not recommend updating to a beta version on production sites.', 'madxartwork' ) . '</span>',
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Get tools page title.
	 *
	 * Retrieve the title for the tools page.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return string Tools page title.
	 */
	protected function get_page_title() {
		return __( 'Tools', 'madxartwork' );
	}
}
