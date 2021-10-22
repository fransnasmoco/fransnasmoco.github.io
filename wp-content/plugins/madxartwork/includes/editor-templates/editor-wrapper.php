<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wp_version;

$document = Plugin::$instance->documents->get_current();

$body_classes = [
	'madxartwork-editor-active',
	'madxartwork-editor-' . $document->get_template_type(),
	'wp-version-' . str_replace( '.', '-', $wp_version ),
];

if ( is_rtl() ) {
	$body_classes[] = 'rtl';
}

if ( ! Plugin::$instance->role_manager->user_can( 'design' ) ) {
	$body_classes[] = 'madxartwork-editor-content-only';
}

$notice = Plugin::$instance->editor->notice_bar->get_notice();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo __( 'madxartwork', 'madxartwork' ) . ' | ' . get_the_title(); ?></title>
	<?php wp_head(); ?>
	<script>
		var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
	</script>
</head>
<body class="<?php echo implode( ' ', $body_classes ); ?>">
<div id="madxartwork-editor-wrapper">
	<div id="madxartwork-panel" class="madxartwork-panel"></div>
	<div id="madxartwork-preview">
		<div id="madxartwork-loading">
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
		</div>
		<div id="madxartwork-preview-responsive-wrapper" class="madxartwork-device-desktop madxartwork-device-rotate-portrait">
			<div id="madxartwork-preview-loading">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</div>
			<?php if ( $notice ) { ?>
				<div id="madxartwork-notice-bar">
					<i class="eicon-madxartwork-square"></i>
					<div id="madxartwork-notice-bar__message"><?php echo sprintf( $notice['message'], $notice['action_url'] ); ?></div>
					<div id="madxartwork-notice-bar__action"><a href="<?php echo $notice['action_url']; ?>" target="_blank"><?php echo $notice['action_title']; ?></a></div>
					<i id="madxartwork-notice-bar__close" class="eicon-close"></i>
				</div>
			<?php } // IFrame will be created here by the Javascript later. ?>
		</div>
	</div>
	<div id="madxartwork-navigator"></div>
</div>
<?php
	wp_footer();
	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_print_footer_scripts' );
?>
</body>
</html>
