<?php
namespace madxartwork\Core\Admin;

use madxartwork\Api;
use madxartwork\Core\Base\Module;
use madxartwork\Tracker;
use madxartwork\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Feedback extends Module {

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'current_screen', function () {
			if ( ! $this->is_plugins_screen() ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_feedback_dialog_scripts' ] );

			add_filter( 'madxartwork/admin/localize_settings', [ $this, 'localize_feedback_dialog_settings' ] );
		} );

		// Ajax.
		add_action( 'wp_ajax_madxartwork_deactivate_feedback', [ $this, 'ajax_madxartwork_deactivate_feedback' ] );

		// Review Plugin
		add_action( 'admin_notices', [ $this, 'admin_notices' ], 20 );
	}

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  1.7.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'feedback';
	}

	/**
	 * Enqueue feedback dialog scripts.
	 *
	 * Registers the feedback dialog scripts and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_feedback_dialog_scripts() {
		add_action( 'admin_footer', [ $this, 'print_deactivate_feedback_dialog' ] );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'madxartwork-admin-feedback',
			madxartwork_ASSETS_URL . 'js/admin-feedback' . $suffix . '.js',
			[
				'madxartwork-common',
			],
			madxartwork_VERSION,
			true
		);

		wp_enqueue_script( 'madxartwork-admin-feedback' );
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function localize_feedback_dialog_settings( $localized_settings ) {
		$localized_settings['i18n']['submit_n_deactivate'] = __( 'Submit & Deactivate', 'madxartwork' );
		$localized_settings['i18n']['skip_n_deactivate'] = __( 'Skip & Deactivate', 'madxartwork' );

		return $localized_settings;
	}

	/**
	 * Print deactivate feedback dialog.
	 *
	 * Display a dialog box to ask the user why he deactivated madxartwork.
	 *
	 * Fired by `admin_footer` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_deactivate_feedback_dialog() {
		$deactivate_reasons = [
			'no_longer_needed' => [
				'title' => __( 'I no longer need the plugin', 'madxartwork' ),
				'input_placeholder' => '',
			],
			'found_a_better_plugin' => [
				'title' => __( 'I found a better plugin', 'madxartwork' ),
				'input_placeholder' => __( 'Please share which plugin', 'madxartwork' ),
			],
			'couldnt_get_the_plugin_to_work' => [
				'title' => __( 'I couldn\'t get the plugin to work', 'madxartwork' ),
				'input_placeholder' => '',
			],
			'temporary_deactivation' => [
				'title' => __( 'It\'s a temporary deactivation', 'madxartwork' ),
				'input_placeholder' => '',
			],
			'madxartwork_pro' => [
				'title' => __( 'I have madxartwork Pro', 'madxartwork' ),
				'input_placeholder' => '',
				'alert' => __( 'Wait! Don\'t deactivate madxartwork. You have to activate both madxartwork and madxartwork Pro in order for the plugin to work.', 'madxartwork' ),
			],
			'other' => [
				'title' => __( 'Other', 'madxartwork' ),
				'input_placeholder' => __( 'Please share the reason', 'madxartwork' ),
			],
		];

		?>
		<div id="madxartwork-deactivate-feedback-dialog-wrapper">
			<div id="madxartwork-deactivate-feedback-dialog-header">
				<i class="eicon-madxartwork-square" aria-hidden="true"></i>
				<span id="madxartwork-deactivate-feedback-dialog-header-title"><?php echo __( 'Quick Feedback', 'madxartwork' ); ?></span>
			</div>
			<form id="madxartwork-deactivate-feedback-dialog-form" method="post">
				<?php
				wp_nonce_field( '_madxartwork_deactivate_feedback_nonce' );
				?>
				<input type="hidden" name="action" value="madxartwork_deactivate_feedback" />

				<div id="madxartwork-deactivate-feedback-dialog-form-caption"><?php echo __( 'If you have a moment, please share why you are deactivating madxartwork:', 'madxartwork' ); ?></div>
				<div id="madxartwork-deactivate-feedback-dialog-form-body">
					<?php foreach ( $deactivate_reasons as $reason_key => $reason ) : ?>
						<div class="madxartwork-deactivate-feedback-dialog-input-wrapper">
							<input id="madxartwork-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="madxartwork-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="<?php echo esc_attr( $reason_key ); ?>" />
							<label for="madxartwork-deactivate-feedback-<?php echo esc_attr( $reason_key ); ?>" class="madxartwork-deactivate-feedback-dialog-label"><?php echo esc_html( $reason['title'] ); ?></label>
							<?php if ( ! empty( $reason['input_placeholder'] ) ) : ?>
								<input class="madxartwork-feedback-text" type="text" name="reason_<?php echo esc_attr( $reason_key ); ?>" placeholder="<?php echo esc_attr( $reason['input_placeholder'] ); ?>" />
							<?php endif; ?>
							<?php if ( ! empty( $reason['alert'] ) ) : ?>
								<div class="madxartwork-feedback-text"><?php echo esc_html( $reason['alert'] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Ajax madxartwork deactivate feedback.
	 *
	 * Send the user feedback when madxartwork is deactivated.
	 *
	 * Fired by `wp_ajax_madxartwork_deactivate_feedback` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function ajax_madxartwork_deactivate_feedback() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], '_madxartwork_deactivate_feedback_nonce' ) ) {
			wp_send_json_error();
		}

		$reason_text = '';
		$reason_key = '';

		if ( ! empty( $_POST['reason_key'] ) ) {
			$reason_key = $_POST['reason_key'];
		}

		if ( ! empty( $_POST[ "reason_{$reason_key}" ] ) ) {
			$reason_text = $_POST[ "reason_{$reason_key}" ];
		}

		Api::send_feedback( $reason_key, $reason_text );

		wp_send_json_success();
	}

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function admin_notices() {
		$notice_id = 'rate_us_feedback';

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( 'dashboard' !== get_current_screen()->id || User::is_user_notice_viewed( $notice_id ) || Tracker::is_notice_shown() ) {
			return;
		}

		$madxartwork_pages = new \WP_Query( [
			'post_type' => 'any',
			'post_status' => 'publish',
			'fields' => 'ids',
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key' => '_madxartwork_edit_mode',
			'posts_per_page' => 11,
			'meta_value' => 'builder',
		] );

		if ( 10 >= $madxartwork_pages->post_count ) {
			return;
		}

		$dismiss_url = add_query_arg( [
			'action' => 'madxartwork_set_admin_notice_viewed',
			'notice_id' => esc_attr( $notice_id ),
		], admin_url( 'admin-post.php' ) );

		?>
		<div class="notice updated is-dismissible madxartwork-message madxartwork-message-dismissed" data-notice_id="<?php echo esc_attr( $notice_id ); ?>">
			<div class="madxartwork-message-inner">
				<div class="madxartwork-message-icon">
					<div class="e-logo-wrapper">
						<i class="eicon-madxartwork" aria-hidden="true"></i>
					</div>
				</div>
				<div class="madxartwork-message-content">
					<p><strong><?php echo __( 'Congrats!', 'madxartwork' ); ?></strong> <?php _e( 'You created over 10 pages with madxartwork. Great job! If you can spare a minute, please help us by leaving a five star review on WordPress.org.', 'madxartwork' ); ?></p>
					<p class="madxartwork-message-actions">
						<a href="https://go.madxartwork.com/admin-review/" target="_blank" class="button button-primary"><?php _e( 'Happy To Help', 'madxartwork' ); ?></a>
						<a href="<?php echo esc_url_raw( $dismiss_url ); ?>" class="button madxartwork-button-notice-dismiss"><?php _e( 'Hide Notification', 'madxartwork' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @since 2.3.0
	 * @access protected
	 */
	protected function get_init_settings() {
		if ( ! $this->is_plugins_screen() ) {
			return [];
		}

		return [ 'is_tracker_opted_in' => Tracker::is_allow_track() ];
	}

	/**
	 * @since 2.3.0
	 * @access private
	 */
	private function is_plugins_screen() {
		return in_array( get_current_screen()->id, [ 'plugins', 'plugins-network' ] );
	}
}
