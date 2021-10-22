<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-madxartwork-panel-history-page">
	<div id="madxartwork-panel-elements-navigation" class="madxartwork-panel-navigation">
		<div class="madxartwork-component-tab madxartwork-panel-navigation-tab" data-tab="actions"><?php echo __( 'Actions', 'madxartwork' ); ?></div>
		<div class="madxartwork-component-tab madxartwork-panel-navigation-tab" data-tab="revisions"><?php echo __( 'Revisions', 'madxartwork' ); ?></div>
	</div>
	<div id="madxartwork-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-history-tab">
	<div id="madxartwork-history-list"></div>
	<div class="madxartwork-history-revisions-message"><?php echo __( 'Switch to Revisions tab for older versions', 'madxartwork' ); ?></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-history-no-items">
	<i class="madxartwork-nerd-box-icon eicon-nerd"></i>
	<div class="madxartwork-nerd-box-title"><?php echo __( 'No History Yet', 'madxartwork' ); ?></div>
	<div class="madxartwork-nerd-box-message"><?php echo __( 'Once you start working, you\'ll be able to redo / undo any action you make in the editor.', 'madxartwork' ); ?></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-history-item">
	<div class="madxartwork-history-item__details">
		<span class="madxartwork-history-item__title">{{{ title }}}</span>
		<span class="madxartwork-history-item__subtitle">{{{ subTitle }}}</span>
		<span class="madxartwork-history-item__action">{{{ action }}}</span>
	</div>
	<div class="madxartwork-history-item__icon">
		<span class="eicon" aria-hidden="true"></span>
	</div>
</script>
