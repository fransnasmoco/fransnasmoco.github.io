<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// TODO: Remove after moving to Route API
?>
<script type="text/template" id="tmpl-madxartwork-publish">
	<# if ( screens.length > 1 ) { #>
	<div id="madxartwork-publish__tabs" class="madxartwork-templates-modal__sidebar">
		<# screens.forEach( function( screen ) { #>
		<div class="madxartwork-publish__tab" data-screen="{{ screen.name }}">
			<div class="madxartwork-publish__tab__image">
				<img src="{{ screen.image }}">
			</div>
			<div class="madxartwork-publish__tab__content">
				<div class="madxartwork-publish__tab__title">{{{ screen.title }}}</div>
				<div class="madxartwork-publish__tab__description">{{{ screen.description }}}</div>
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="madxartwork-publish__screen" class="madxartwork-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-component-publish">
	<# if ( Object.keys( tabs ).length > 1 ) { #>
	<div id="madxartwork-publish__tabs" class="madxartwork-templates-modal__sidebar">
		<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="madxartwork-component-tab madxartwork-publish__tab" data-tab="{{ tab }}">
			<div class="madxartwork-publish__tab__image">
				<img src="{{ args.image }}">
			</div>
			<div class="madxartwork-publish__tab__content">
				<div class="madxartwork-publish__tab__title">{{{ args.title }}}</div>
				<div class="madxartwork-publish__tab__description">{{{ args.description }}}</div>
			</div>
		</div>
		<# } ); #>
	</div>
	<# } #>
	<div id="madxartwork-publish__screen" class="madxartwork-templates-modal__content"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-theme-builder-conditions-view">
	<div class="madxartwork-template-library-blank-icon">
		<img src="<?php echo madxartwork_PRO_MODULES_URL; ?>theme-builder/assets/images/conditions-tab.svg">
	</div>
	<div class="madxartwork-template-library-blank-title">{{{ madxartworkPro.translate( 'conditions_title' ) }}}</div>
	<div class="madxartwork-template-library-blank-message">{{{ madxartworkPro.translate( 'conditions_description' ) }}}</div>
	<div id="madxartwork-theme-builder-conditions">
		<div id="madxartwork-theme-builder-conditions-controls"></div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-theme-builder-conditions-repeater-row">
	<div class="madxartwork-theme-builder-conditions-repeater-row-controls"></div>
	<div class="madxartwork-repeater-row-tool madxartwork-repeater-tool-remove">
		<i class="eicon-close" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php esc_html_e( 'Remove this item', 'madxartwork-pro' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-theme-builder-button-preview">
	<i class="eicon-eye tooltip-target" aria-hidden="true"  data-tooltip="<?php esc_attr_e( 'Preview Changes', 'madxartwork-pro' ); ?>"></i>
	<span class="madxartwork-screen-only">
		<?php esc_attr_e( 'Preview Changes', 'madxartwork-pro' ); ?>
	</span>
	<div class="madxartwork-panel-footer-sub-menu-wrapper">
		<div class="madxartwork-panel-footer-sub-menu">
			<div id="madxartwork-panel-footer-theme-builder-button-preview-settings" class="madxartwork-panel-footer-sub-menu-item">
				<i class="eicon-wrench" aria-hidden="true"></i>
				<span class="madxartwork-title"><?php esc_html_e( 'Settings', 'madxartwork-pro' ); ?></span>
			</div>
			<div id="madxartwork-panel-footer-theme-builder-button-open-preview" class="madxartwork-panel-footer-sub-menu-item">
				<i class="eicon-editor-external-link" aria-hidden="true"></i>
				<span class="madxartwork-title"><?php esc_html_e( 'Preview', 'madxartwork-pro' ); ?></span>
			</div>
		</div>
	</div>
</script>
