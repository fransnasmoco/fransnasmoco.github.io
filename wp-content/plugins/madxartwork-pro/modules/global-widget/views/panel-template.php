<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<script type="text/template" id="tmpl-madxartwork-panel-global-widget">
	<div id="madxartwork-global-widget-locked-header" class="madxartwork-nerd-box madxartwork-panel-nerd-box">
		<i class="madxartwork-nerd-box-icon madxartwork-panel-nerd-box-icon eicon-nerd" aria-invalid="true"></i>
		<div class="madxartwork-nerd-box-title madxartwork-panel-nerd-box-title"><?php echo __( 'Your Widget is Now Locked', 'madxartwork-pro' ); ?></div>
		<div class="madxartwork-nerd-box-message madxartwork-panel-nerd-box-message"><?php _e( 'Edit this global widget to simultaneously update every place you used it, or unlink it so it gets back to being regular widget.', 'madxartwork-pro' ); ?></div>
	</div>
	<div id="madxartwork-global-widget-locked-tools">
		<div id="madxartwork-global-widget-locked-edit" class="madxartwork-global-widget-locked-tool">
			<div class="madxartwork-global-widget-locked-tool-description"><?php echo __( 'Edit global widget', 'madxartwork-pro' ); ?></div>
			<button class="madxartwork-button madxartwork-button-success"><?php _e( 'Edit', 'madxartwork-pro' ); ?></button>
		</div>
		<div id="madxartwork-global-widget-locked-unlink" class="madxartwork-global-widget-locked-tool">
			<div class="madxartwork-global-widget-locked-tool-description"><?php echo __( 'Unlink from global', 'madxartwork-pro' ); ?></div>
			<button class="madxartwork-button"><?php _e( 'Unlink', 'madxartwork-pro' ); ?></button>
		</div>
	</div>
	<div id="madxartwork-global-widget-loading" class="madxartwork-hidden">
		<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php _e( 'Loading', 'madxartwork-pro' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-global-widget-no-templates">
	<i class="madxartwork-nerd-box-icon madxartwork-panel-nerd-box-icon eicon-nerd" aria-invalid="true"></i>
	<div class="madxartwork-nerd-box-title madxartwork-panel-nerd-box-title"><?php _e( 'Save Your First Global Widget', 'madxartwork-pro' ); ?></div>
	<div class="madxartwork-nerd-box-message madxartwork-panel-nerd-box-message"><?php _e( 'Save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'madxartwork-pro' ); ?></div>
</script>
