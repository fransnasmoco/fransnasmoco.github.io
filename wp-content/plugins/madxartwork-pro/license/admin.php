<?php
namespace madxartworkPro\License;

use madxartwork\Settings;
use madxartworkPro\Core\Connect\Apps\Activate;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Admin {

	const PAGE_ID = 'madxartwork-license';

	public static $updater = null;

	public static function get_errors_details() {
		$license_page_link = self::get_url();

		return [
			API::STATUS_EXPIRED => [
				'title' => __( 'Your License Has Expired', 'madxartwork-pro' ),
				'description' => sprintf( __( '<a href="%s" target="_blank">Renew your license today</a>, to keep getting feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' ), API::RENEW_URL ),
				'button_text' => __( 'Renew License', 'madxartwork-pro' ),
				'button_url' => API::RENEW_URL,
			],
			API::STATUS_DISABLED => [
				'title' => __( 'Your License Is Inactive', 'madxartwork-pro' ),
				'description' => __( '<strong>Your license key has been cancelled</strong> (most likely due to a refund request). Please consider acquiring a new license.', 'madxartwork-pro' ),
				'button_text' => __( 'Activate License', 'madxartwork-pro' ),
				'button_url' => $license_page_link,
			],
			API::STATUS_INVALID => [
				'title' => __( 'License Invalid', 'madxartwork-pro' ),
				'description' => __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ),
				'button_text' => __( 'Reactivate License', 'madxartwork-pro' ),
				'button_url' => $license_page_link,
			],
			API::STATUS_SITE_INACTIVE => [
				'title' => __( 'License Mismatch', 'madxartwork-pro' ),
				'description' => __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL. Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ),
				'button_text' => __( 'Reactivate License', 'madxartwork-pro' ),
				'button_url' => $license_page_link,
			],
		];
	}

	public static function deactivate() {
		API::deactivate_license();

		delete_option( 'madxartwork_pro_license_key' );
		delete_transient( 'madxartwork_pro_license_data' );
	}

	private function print_admin_message( $title, $description, $button_text = '', $button_url = '', $button_class = '' ) {
		?>
		<div class="notice madxartwork-message">
			<div class="madxartwork-message-inner">
				<div class="madxartwork-message-icon">
					<div class="e-logo-wrapper">
						<i class="eicon-madxartwork" aria-hidden="true"></i>
					</div>
				</div>

				<div class="madxartwork-message-content">
					<strong><?php echo $title; ?></strong>
					<p><?php echo $description; ?></p>
				</div>

				<?php if ( ! empty( $button_text ) ) : ?>
					<div class="madxartwork-message-action">
						<a class="madxartwork-button <?php echo $button_class; ?>" href="<?php echo esc_url( $button_url ); ?>"><?php echo $button_text; ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private static function get_hidden_license_key() {
		$input_string = self::get_license_key();

		$start = 5;
		$length = mb_strlen( $input_string ) - $start - 5;

		$mask_string = preg_replace( '/\S/', 'X', $input_string );
		$mask_string = mb_substr( $mask_string, $start, $length );
		$input_string = substr_replace( $input_string, $mask_string, $start, $length );

		return $input_string;
	}

	public static function get_updater_instance() {
		if ( null === self::$updater ) {
			self::$updater = new Updater();
		}

		return self::$updater;
	}

	public static function get_license_key() {
		return trim( get_option( 'madxartwork_pro_license_key' ) );
	}

	public static function set_license_key( $license_key ) {
		return update_option( 'madxartwork_pro_license_key', $license_key );
	}

	public function action_activate_license() {
		check_admin_referer( 'madxartwork-pro-license' );

		if ( empty( $_POST['madxartwork_pro_license_key'] ) ) {
			wp_die( __( 'Please enter your license key.', 'madxartwork-pro' ), __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		$license_key = trim( $_POST['madxartwork_pro_license_key'] );

		$data = API::activate_license( $license_key );

		if ( is_wp_error( $data ) ) {
			wp_die( sprintf( '%s (%s) ', $data->get_error_message(), $data->get_error_code() ), __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		if ( API::STATUS_VALID !== $data['license'] ) {
			$error_msg = API::get_error_message( $data['error'] );
			wp_die( $error_msg, __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		self::set_license_key( $license_key );
		API::set_license_data( $data );

		wp_safe_redirect( $_POST['_wp_http_referer'] );
		die;
	}

	public function action_deactivate_license() {
		check_admin_referer( 'madxartwork-pro-license' );

		$this->deactivate();

		wp_safe_redirect( $_POST['_wp_http_referer'] );
		die;
	}

	public function register_page() {
		$menu_text = __( 'License', 'madxartwork-pro' );

		add_submenu_page(
			Settings::PAGE_ID,
			$menu_text,
			$menu_text,
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_page' ]
		);
	}

	public static function get_url() {
		return admin_url( 'admin.php?page=' . self::PAGE_ID );
	}

	public function display_page() {
		$license_key = self::get_license_key();

		$is_manual_mode = ( isset( $_GET['mode'] ) && 'manually' === $_GET['mode'] );

		if ( $is_manual_mode ) {
			$this->render_manually_activation_widget( $license_key );
			return;
		}

		?>
		<div class="wrap madxartwork-admin-page-license">
			<h2><?php _e( 'License Settings', 'madxartwork-pro' ); ?></h2>

			<form class="madxartwork-license-box" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'madxartwork-pro-license' ); ?>

				<?php if ( empty( $license_key ) ) : ?>

					<h3><?php _e( 'Activate License', 'madxartwork-pro' ); ?></h3>

					<p><?php echo $this->get_activate_message(); ?></p>

					<div class="madxartwork-box-action">
						<a class="button button-primary" href="<?php echo esc_url( $this->get_connect_url() ); ?>">
							<?php echo __( 'Connect & Activate', 'madxartwork-pro' ); ?>
						</a>
					</div>
				<?php else :
					$license_data = API::get_license_data( true ); ?>
					<h3><?php _e( 'Status', 'madxartwork-pro' ); ?>:
						<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Expired', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Mismatch', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_INVALID === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Invalid', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_DISABLED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Disabled', 'madxartwork-pro' ); ?></span>
						<?php else : ?>
							<span style="color: #008000; font-style: italic;"><?php _e( 'Active', 'madxartwork-pro' ); ?></span>
						<?php endif; ?>

						<small>
							<a class="button" href="https://go.madxartwork.com/my-account/">
								<?php echo __( 'My Account', 'madxartwork-pro' ); ?>
							</a>
						</small>
					</h3>

					<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
					<p class="e-row-divider-bottom madxartwork-admin-alert madxartwork-alert-danger"><?php printf( __( '<strong>Your License Has Expired.</strong> <a href="%s" target="_blank">Renew your license today</a> to keep getting feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' ), 'https://go.madxartwork.com/renew/' ); ?></p>
				<?php endif; ?>

					<?php if ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
					<p class="e-row-divider-bottom madxartwork-admin-alert madxartwork-alert-danger"><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ); ?></p>
				<?php endif; ?>

					<?php if ( API::STATUS_INVALID === $license_data['license'] ) : ?>
					<p class="e-row-divider-bottom madxartwork-admin-alert madxartwork-alert-info"><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ); ?></p>
				<?php endif; ?>

					<p class="e-row-stretch e-row-divider-bottom">
						<span>
						<?php
						$connected_user = $this->get_connected_account();

						if ( $connected_user ) :
							echo sprintf( __( 'You\'re connected as %s.', 'madxartwork-pro' ), '<strong>' . $this->get_connected_account() . '</strong>' );
						endif;
						?>

						<?php echo __( 'Want to activate this website by a different license?', 'madxartwork-pro' ); ?>
						</span>
						<a class="button button-primary" href="<?php echo esc_url( $this->get_switch_license_url() ); ?>">
							<?php echo __( 'Switch Account', 'madxartwork-pro' ); ?>
						</a>
					</p>

					<p class="e-row-stretch">
						<span><?php echo __( 'Want to deactivate the license for any reason?', 'madxartwork-pro' ); ?></span>
						<a class="button" href="<?php echo esc_url( $this->get_deactivate_url() ); ?>">
							<?php echo __( 'Disconnect', 'madxartwork-pro' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}

	private function is_block_editor_page() {
		$current_screen = get_current_screen();

		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			return true;
		}

		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			return true;
		}

		return false;
	}

	public function is_license_about_to_expire() {
		$license_data = API::get_license_data();

		if ( ! empty( $license_data['subscriptions'] ) && 'enable' === $license_data['subscriptions'] ) {
			return false;
		}

		if ( 'lifetime' === $license_data['expires'] ) {
			return false;
		}

		return time() > strtotime( '-28 days', strtotime( $license_data['expires'] ) );
	}

	public function admin_license_details() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->is_block_editor_page() ) {
			return;
		}

		$renew_url = API::RENEW_URL;

		$license_key = self::get_license_key();

		if ( empty( $license_key ) ) {
			?>
			<div class="notice madxartwork-message">
				<div class="madxartwork-message-inner">
					<div class="madxartwork-message-icon">
						<div class="e-logo-wrapper">
							<i class="eicon-madxartwork" aria-hidden="true"></i>
						</div>
					</div>

					<div class="madxartwork-message-content">
						<strong><?php echo __( 'Welcome to madxartwork Pro!', 'madxartwork-pro' ); ?></strong>
						<p><?php echo $this->get_activate_message(); ?></p>
					</div>

					<div class="madxartwork-message-action">
						<a class="madxartwork-button" href="<?php echo esc_url( $this->get_connect_url() ); ?>">
							<i class="dashicons dashicons-update" aria-hidden="true"></i>
							<?php echo __( 'Connect & Activate', 'madxartwork-pro' ); ?>
						</a>
					</div>

				</div>
			</div>
			<?php
			return;
		}

		$license_data = API::get_license_data();
		if ( empty( $license_data['license'] ) ) {
			return;
		}

		$errors = self::get_errors_details();

		if ( isset( $errors[ $license_data['license'] ] ) ) {
			$error_data = $errors[ $license_data['license'] ];
			$this->print_admin_message( $error_data['title'], $error_data['description'], $error_data['button_text'], $error_data['button_url'] );

			return;
		}

		if ( API::STATUS_VALID === $license_data['license'] ) {
			if ( $this->is_license_about_to_expire() ) {
				$title = sprintf( __( 'Your License Will Expire in %s.', 'madxartwork-pro' ), human_time_diff( current_time( 'timestamp' ), strtotime( $license_data['expires'] ) ) );
				$description = sprintf( __( '<a href="%s" target="_blank">Renew your license today</a>, to keep getting feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' ), $renew_url );

				$this->print_admin_message( $title, $description, __( 'Renew License', 'madxartwork-pro' ), $renew_url );
			}
		}
	}

	public function filter_library_get_templates_args( $body_args ) {
		$license_key = self::get_license_key();

		if ( ! empty( $license_key ) ) {
			$body_args['license'] = $license_key;
			$body_args['url'] = home_url();
		}

		return $body_args;
	}

	public function handle_tracker_actions() {
		// Show tracker notice after 24 hours from Pro installed time.
		$is_need_to_show = ( $this->get_installed_time() < strtotime( '-24 hours' ) );

		$is_dismiss_notice = ( '1' === get_option( 'madxartwork_tracker_notice' ) );
		$is_dismiss_pro_notice = ( '1' === get_option( 'madxartwork_pro_tracker_notice' ) );

		if ( $is_need_to_show && $is_dismiss_notice && ! $is_dismiss_pro_notice ) {
			delete_option( 'madxartwork_tracker_notice' );
		}

		if ( ! isset( $_GET['madxartwork_tracker'] ) ) {
			return;
		}

		if ( 'opt_out' === $_GET['madxartwork_tracker'] ) {
			update_option( 'madxartwork_pro_tracker_notice', '1' );
		}
	}

	private function get_installed_time() {
		$installed_time = get_option( '_madxartwork_pro_installed_time' );

		if ( ! $installed_time ) {
			$installed_time = time();
			update_option( '_madxartwork_pro_installed_time', $installed_time );
		}

		return $installed_time;
	}

	public function plugin_action_links( $links ) {
		$license_key = self::get_license_key();

		if ( empty( $license_key ) ) {
			$links['active_license'] = sprintf( '<a href="%s" class="madxartwork-plugins-gopro">%s</a>', self::get_connect_url(), __( 'Connect & Activate', 'madxartwork-pro' ) );
		}

		return $links;
	}

	private function handle_dashboard_admin_widget() {
		add_action( 'madxartwork/admin/dashboard_overview_widget/after_version', function() {
			/* translators: %s: madxartwork Pro version. */
			echo '<span class="e-overview__version">' . sprintf( __( 'madxartwork Pro v%s', 'madxartwork-pro' ), madxartwork_PRO_VERSION ) . '</span>';
		} );

		add_filter( 'madxartwork/admin/dashboard_overview_widget/footer_actions', function( $additions_actions ) {
			unset( $additions_actions['go-pro'] );

			return $additions_actions;
		}, 550 );
	}

	public function add_finder_item( array $categories ) {
		$categories['settings']['items']['license'] = [
			'title' => __( 'License', 'madxartwork-pro' ),
			'url' => self::get_url(),
		];

		return $categories;
	}

	private function render_manually_activation_widget( $license_key ) {
		?>
		<div class="wrap madxartwork-admin-page-license">
			<h2><?php _e( 'License Settings', 'madxartwork-pro' ); ?></h2>

			<form class="madxartwork-license-box" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'madxartwork-pro-license' ); ?>

				<h3>
					<?php _e( 'Activate Manually', 'madxartwork-pro' ); ?>
					<?php if ( empty( $license_key ) ) : ?>
						<small>
							<a href="<?php echo $this->get_connect_url(); ?>" class="madxartwork-connect-link">
								<?php _e( 'Connect & Activate', 'madxartwork-pro' ); ?>
							</a>
						</small>
					<?php endif; ?>
				</h3>

				<?php if ( empty( $license_key ) ) : ?>

					<p><?php _e( 'Enter your license key here, to activate madxartwork Pro, and get feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' ); ?></p>

					<ol>
						<li><?php printf( __( 'Log in to <a href="%s" target="_blank">your account</a> to get your license key.', 'madxartwork-pro' ), 'https://go.madxartwork.com/my-license/' ); ?></li>
						<li><?php printf( __( 'If you don\'t yet have a license key, <a href="%s" target="_blank">get madxartwork Pro now</a>.', 'madxartwork-pro' ), 'https://go.madxartwork.com/pro-license/' ); ?></li>
						<li><?php _e( 'Copy the license key from your account and paste it below.', 'madxartwork-pro' ); ?></li>
					</ol>

					<input type="hidden" name="action" value="madxartwork_pro_activate_license"/>

					<label for="madxartwork-pro-license-key"><?php _e( 'Your License Key', 'madxartwork-pro' ); ?></label>

					<input id="madxartwork-pro-license-key" class="regular-text code" name="madxartwork_pro_license_key" type="text" value="" placeholder="<?php esc_attr_e( 'Please enter your license key here', 'madxartwork-pro' ); ?>"/>

					<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Activate', 'madxartwork-pro' ); ?>"/>

					<p class="description"><?php printf( __( 'Your license key should look something like this: %s', 'madxartwork-pro' ), '<code>fb351f05958872E193feb37a505a84be</code>' ); ?></p>

				<?php else :
					$license_data = API::get_license_data( true ); ?>
					<input type="hidden" name="action" value="madxartwork_pro_deactivate_license"/>

					<label for="madxartwork-pro-license-key"><?php _e( 'Your License Key', 'madxartwork-pro' ); ?>:</label>

					<input id="madxartwork-pro-license-key" class="regular-text code" type="text" value="<?php echo esc_attr( self::get_hidden_license_key() ); ?>" disabled/>

					<input type="submit" class="button" value="<?php esc_attr_e( 'Deactivate', 'madxartwork-pro' ); ?>"/>

					<p>
						<?php _e( 'Status', 'madxartwork-pro' ); ?>:
						<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Expired', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Mismatch', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_INVALID === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Invalid', 'madxartwork-pro' ); ?></span>
						<?php elseif ( API::STATUS_DISABLED === $license_data['license'] ) : ?>
							<span style="color: #ff0000; font-style: italic;"><?php _e( 'Disabled', 'madxartwork-pro' ); ?></span>
						<?php else : ?>
							<span style="color: #008000; font-style: italic;"><?php _e( 'Active', 'madxartwork-pro' ); ?></span>
						<?php endif; ?>
					</p>

					<?php if ( API::STATUS_EXPIRED === $license_data['license'] ) : ?>
					<p class="madxartwork-admin-alert madxartwork-alert-danger"><?php printf( __( '<strong>Your License Has Expired.</strong> <a href="%s" target="_blank">Renew your license today</a> to keep getting feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' ), 'https://go.madxartwork.com/renew/' ); ?></p>
				<?php endif; ?>

					<?php if ( API::STATUS_SITE_INACTIVE === $license_data['license'] ) : ?>
					<p class="madxartwork-admin-alert madxartwork-alert-danger"><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ); ?></p>
				<?php endif; ?>

					<?php if ( API::STATUS_INVALID === $license_data['license'] ) : ?>
					<p class="madxartwork-admin-alert madxartwork-alert-info"><?php echo __( '<strong>Your license key doesn\'t match your current domain</strong>. This is most likely due to a change in the domain URL of your site (including HTTPS/SSL migration). Please deactivate the license and then reactivate it again.', 'madxartwork-pro' ); ?></p>
				<?php endif; ?>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}

	private function is_connected() {
		return $this->get_app()->is_connected();
	}

	public function get_connect_url() {
		$action = $this->is_connected() ? 'activate_pro' : 'authorize';

		return $this->get_app()->get_admin_url( $action );
	}

	private function get_activate_manually_url() {
		return add_query_arg( 'mode', 'manually', self::get_url() );
	}

	private function get_switch_license_url() {
		return $this->get_app()->get_admin_url( 'switch_license' );
	}

	private function get_connected_account() {
		$user = $this->get_app()->get( 'user' );
		$email = '';
		if ( $user ) {
			$email = $user->email;
		}
		return $email;
	}

	private function get_deactivate_url() {
		return $this->get_app()->get_admin_url( 'deactivate' );
	}

	private function get_activate_message() {
		return __( 'Please activate your license to get feature updates, premium support and unlimited access to the template library.', 'madxartwork-pro' );
	}

	/**
	 * @return Activate
	 */
	private function get_app() {
		return Plugin::madxartwork()->common->get_component( 'connect' )->get_app( 'activate' );
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_page' ], 800 );
		add_action( 'admin_post_madxartwork_pro_activate_license', [ $this, 'action_activate_license' ] );
		add_action( 'admin_post_madxartwork_pro_deactivate_license', [ $this, 'action_deactivate_license' ] );

		add_action( 'admin_notices', [ $this, 'admin_license_details' ], 20 );

		// Add the license key to Templates Library requests
		add_filter( 'madxartwork/api/get_templates/body_args', [ $this, 'filter_library_get_templates_args' ] );

		add_filter( 'madxartwork/finder/categories', [ $this, 'add_finder_item' ] );

		add_filter( 'plugin_action_links_' . madxartwork_PRO_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );

		add_action( 'admin_init', [ $this, 'handle_tracker_actions' ], 9 );

		$this->handle_dashboard_admin_widget();

		self::get_updater_instance();
	}
}
