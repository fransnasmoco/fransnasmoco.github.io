<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$user = wp_get_current_user();

$ajax = Plugin::$instance->common->get_component( 'ajax' );

$beta_tester_email = $user->user_email;

/**
 * Print beta tester dialog.
 *
 * Display a dialog box to suggest the user to opt-in to the beta testers newsletter.
 *
 * Fired by `admin_footer` filter.
 *
 * @since  2.6.0
 * @access public
 */
?>
<script type="text/template" id="tmpl-madxartwork-beta-tester">
	<form id="madxartwork-beta-tester-form" method="post">
		<input type="hidden" name="_nonce" value="<?php echo $ajax->create_nonce(); ?>">
		<input type="hidden" name="action" value="madxartwork_beta_tester_signup" />
		<div id="madxartwork-beta-tester-form__caption"><?php echo __( 'Get Beta Updates', 'madxartwork' ); ?></div>
		<div id="madxartwork-beta-tester-form__description"><?php echo __( 'As a beta tester, you’ll receive an update that includes a testing version of madxartwork and its content directly to your Email', 'madxartwork' ); ?></div>
		<div id="madxartwork-beta-tester-form__input-wrapper">
			<input id="madxartwork-beta-tester-form__email" name="beta_tester_email" type="email" placeholder="<?php echo __( 'Your Email', 'madxartwork' ); ?>" required value="<?php echo $beta_tester_email; ?>" />
			<button id="madxartwork-beta-tester-form__submit" class="madxartwork-button madxartwork-button-success">
				<span class="madxartwork-state-icon">
					<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</span>
				<?php echo __( 'Sign Up', 'madxartwork' ); ?>
			</button>
		</div>
		<div id="madxartwork-beta-tester-form__terms">
			<?php echo sprintf( __( 'By clicking Sign Up, you agree to madxartwork\'s <a href="%1$s">Terms of Service</a> and <a href="%2$s">Privacy Policy</a>', 'madxartwork' ), Beta_Testers::NEWSLETTER_TERMS_URL, Beta_Testers::NEWSLETTER_PRIVACY_URL ); ?>
		</div>
	</form>
</script>
