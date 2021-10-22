<?php
namespace madxartwork;

use madxartwork\Core\Responsive\Responsive;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$document = Plugin::$instance->documents->get( Plugin::$instance->editor->get_post_id() );
?>
<script type="text/template" id="tmpl-madxartwork-panel">
	<div id="madxartwork-mode-switcher"></div>
	<header id="madxartwork-panel-header-wrapper"></header>
	<main id="madxartwork-panel-content-wrapper"></main>
	<footer id="madxartwork-panel-footer">
		<div class="madxartwork-panel-container">
		</div>
	</footer>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-menu">
	<div id="madxartwork-panel-page-menu-content"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-menu-group">
	<div class="madxartwork-panel-menu-group-title">{{{ title }}}</div>
	<div class="madxartwork-panel-menu-items"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-menu-item">
	<div class="madxartwork-panel-menu-item-icon">
		<i class="{{ icon }}"></i>
	</div>
	<# if ( 'undefined' === typeof type || 'link' !== type ) { #>
		<div class="madxartwork-panel-menu-item-title">{{{ title }}}</div>
	<# } else {
		let target = ( 'undefined' !== typeof newTab && newTab ) ? '_blank' : '_self';
	#>
		<a href="{{ link }}" target="{{ target }}"><div class="madxartwork-panel-menu-item-title">{{{ title }}}</div></a>
	<# } #>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-header">
	<div id="madxartwork-panel-header-menu-button" class="madxartwork-header-button">
		<i class="madxartwork-icon eicon-menu-bar tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Menu', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Menu', 'madxartwork' ); ?></span>
	</div>
	<div id="madxartwork-panel-header-title"></div>
	<div id="madxartwork-panel-header-add-button" class="madxartwork-header-button">
		<i class="madxartwork-icon eicon-apps tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Widgets Panel', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Widgets Panel', 'madxartwork' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-footer-content">
	<div id="madxartwork-panel-footer-settings" class="madxartwork-panel-footer-tool madxartwork-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'Settings', 'madxartwork' ); ?>">
		<i class="eicon-cog" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php printf( __( '%s Settings', 'madxartwork' ), $document::get_title() ); ?></span>
	</div>
	<div id="madxartwork-panel-footer-navigator" class="madxartwork-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e( 'Navigator', 'madxartwork' ); ?>">
		<i class="eicon-navigator" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Navigator', 'madxartwork' ); ?></span>
	</div>
	<div id="madxartwork-panel-footer-history" class="madxartwork-panel-footer-tool madxartwork-leave-open tooltip-target" data-tooltip="<?php esc_attr_e( 'History', 'madxartwork' ); ?>">
		<i class="eicon-history" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'History', 'madxartwork' ); ?></span>
	</div>
	<div id="madxartwork-panel-footer-responsive" class="madxartwork-panel-footer-tool madxartwork-toggle-state">
		<i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e( 'Responsive Mode', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only">
			<?php echo __( 'Responsive Mode', 'madxartwork' ); ?>
		</span>
		<div class="madxartwork-panel-footer-sub-menu-wrapper">
			<div class="madxartwork-panel-footer-sub-menu">
				<div class="madxartwork-panel-footer-sub-menu-item" data-device-mode="desktop">
					<i class="madxartwork-icon eicon-device-desktop" aria-hidden="true"></i>
					<span class="madxartwork-title"><?php echo __( 'Desktop', 'madxartwork' ); ?></span>
					<span class="madxartwork-description"><?php echo __( 'Default Preview', 'madxartwork' ); ?></span>
				</div>
				<div class="madxartwork-panel-footer-sub-menu-item" data-device-mode="tablet">
					<i class="madxartwork-icon eicon-device-tablet" aria-hidden="true"></i>
					<span class="madxartwork-title"><?php echo __( 'Tablet', 'madxartwork' ); ?></span>
					<?php $breakpoints = Responsive::get_breakpoints(); ?>
					<span class="madxartwork-description"><?php echo sprintf( __( 'Preview for %s', 'madxartwork' ), $breakpoints['md'] . 'px' ); ?></span>
				</div>
				<div class="madxartwork-panel-footer-sub-menu-item" data-device-mode="mobile">
					<i class="madxartwork-icon eicon-device-mobile" aria-hidden="true"></i>
					<span class="madxartwork-title"><?php echo __( 'Mobile', 'madxartwork' ); ?></span>
					<span class="madxartwork-description"><?php echo sprintf( __( 'Preview for %s', 'madxartwork' ), '360px' ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<div id="madxartwork-panel-footer-saver-preview" class="madxartwork-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e( 'Preview Changes', 'madxartwork' ); ?>">
		<span id="madxartwork-panel-footer-saver-preview-label">
			<i class="eicon-eye" aria-hidden="true"></i>
			<span class="madxartwork-screen-only"><?php echo __( 'Preview Changes', 'madxartwork' ); ?></span>
		</span>
	</div>
	<div id="madxartwork-panel-footer-saver-publish" class="madxartwork-panel-footer-tool">
		<button id="madxartwork-panel-saver-button-publish" class="madxartwork-button madxartwork-button-success madxartwork-disabled">
			<span class="madxartwork-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<span id="madxartwork-panel-saver-button-publish-label">
				<?php echo __( 'Publish', 'madxartwork' ); ?>
			</span>
		</button>
	</div>
	<div id="madxartwork-panel-footer-saver-options" class="madxartwork-panel-footer-tool madxartwork-toggle-state">
		<button id="madxartwork-panel-saver-button-save-options" class="madxartwork-button madxartwork-button-success tooltip-target madxartwork-disabled" data-tooltip="<?php esc_attr_e( 'Save Options', 'madxartwork' ); ?>">
			<i class="eicon-caret-up" aria-hidden="true"></i>
			<span class="madxartwork-screen-only"><?php echo __( 'Save Options', 'madxartwork' ); ?></span>
		</button>
		<div class="madxartwork-panel-footer-sub-menu-wrapper">
			<p class="madxartwork-last-edited-wrapper">
				<span class="madxartwork-state-icon">
					<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</span>
				<span class="madxartwork-last-edited">
					{{{ madxartwork.config.document.last_edited }}}
				</span>
			</p>
			<div class="madxartwork-panel-footer-sub-menu">
				<div id="madxartwork-panel-footer-sub-menu-item-save-draft" class="madxartwork-panel-footer-sub-menu-item madxartwork-disabled">
					<i class="madxartwork-icon eicon-save" aria-hidden="true"></i>
					<span class="madxartwork-title"><?php echo __( 'Save Draft', 'madxartwork' ); ?></span>
				</div>
				<div id="madxartwork-panel-footer-sub-menu-item-save-template" class="madxartwork-panel-footer-sub-menu-item">
					<i class="madxartwork-icon eicon-folder" aria-hidden="true"></i>
					<span class="madxartwork-title"><?php echo __( 'Save as Template', 'madxartwork' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-mode-switcher-content">
	<input id="madxartwork-mode-switcher-preview-input" type="checkbox">
	<label for="madxartwork-mode-switcher-preview-input" id="madxartwork-mode-switcher-preview">
		<i class="eicon" aria-hidden="true" title="<?php esc_attr_e( 'Hide Panel', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Hide Panel', 'madxartwork' ); ?></span>
	</label>
</script>

<script type="text/template" id="tmpl-editor-content">
	<div class="madxartwork-panel-navigation">
		<# _.each( elementData.tabs_controls, function( tabTitle, tabSlug ) {
			if ( 'content' !== tabSlug && ! madxartwork.userCan( 'design' ) ) {
				return;
			}
			$e.bc.ensureTab( 'panel/editor', tabSlug );
			#>
			<div class="madxartwork-component-tab madxartwork-panel-navigation-tab madxartwork-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
				<a href="#">{{{ tabTitle }}}</a>
			</div>
		<# } ); #>
	</div>
	<# if ( elementData.reload_preview ) { #>
		<div class="madxartwork-update-preview">
			<div class="madxartwork-update-preview-title"><?php echo __( 'Update changes to page', 'madxartwork' ); ?></div>
			<div class="madxartwork-update-preview-button-wrapper">
				<button class="madxartwork-update-preview-button madxartwork-button madxartwork-button-success"><?php echo __( 'Apply', 'madxartwork' ); ?></button>
			</div>
		</div>
	<# } #>
	<div id="madxartwork-controls"></div>
	<# if ( elementData.help_url ) { #>
		<div id="madxartwork-panel__editor__help">
			<a id="madxartwork-panel__editor__help__link" href="{{ elementData.help_url }}" target="_blank">
				<?php echo __( 'Need Help', 'madxartwork' ); ?>
				<i class="eicon-help-o"></i>
			</a>
		</div>
	<# } #>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-schemes-disabled">
	<i class="madxartwork-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="madxartwork-nerd-box-title">{{{ '<?php echo __( '%s are disabled', 'madxartwork' ); ?>'.replace( '%s', disabledTitle ) }}}</div>
	<div class="madxartwork-nerd-box-message"><?php printf( __( 'You can enable it from the <a href="%s" target="_blank">madxartwork settings page</a>.', 'madxartwork' ), Settings::get_url() ); ?></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-scheme-color-item">
	<div class="madxartwork-panel-scheme-color-input-wrapper">
		<input type="text" class="madxartwork-panel-scheme-color-value" value="{{ value }}" data-alpha="true" />
	</div>
	<div class="madxartwork-panel-scheme-color-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-scheme-typography-item">
	<div class="madxartwork-panel-heading">
		<div class="madxartwork-panel-heading-toggle">
			<i class="eicon" aria-hidden="true"></i>
		</div>
		<div class="madxartwork-panel-heading-title">{{{ title }}}</div>
	</div>
	<div class="madxartwork-panel-scheme-typography-items madxartwork-panel-box-content">
		<?php
		$scheme_fields_keys = Group_Control_Typography::get_scheme_fields_keys();

		$typography_group = Plugin::$instance->controls_manager->get_control_groups( 'typography' );
		$typography_fields = $typography_group->get_fields();

		$scheme_fields = array_intersect_key( $typography_fields, array_flip( $scheme_fields_keys ) );

		foreach ( $scheme_fields as $option_name => $option ) :
			?>
			<div class="madxartwork-panel-scheme-typography-item">
				<div class="madxartwork-panel-scheme-item-title madxartwork-control-title"><?php echo $option['label']; ?></div>
				<div class="madxartwork-panel-scheme-typography-item-value">
					<?php if ( 'select' === $option['type'] ) : ?>
						<select name="<?php echo esc_attr( $option_name ); ?>" class="madxartwork-panel-scheme-typography-item-field">
							<?php foreach ( $option['options'] as $field_key => $field_value ) : ?>
								<option value="<?php echo esc_attr( $field_key ); ?>"><?php echo $field_value; ?></option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'font' === $option['type'] ) : ?>
						<select name="<?php echo esc_attr( $option_name ); ?>" class="madxartwork-panel-scheme-typography-item-field">
							<option value=""><?php echo __( 'Default', 'madxartwork' ); ?></option>
							<?php foreach ( Fonts::get_font_groups() as $group_type => $group_label ) : ?>
								<optgroup label="<?php echo esc_attr( $group_label ); ?>">
									<?php foreach ( Fonts::get_fonts_by_groups( [ $group_type ] ) as $font_title => $font_type ) : ?>
										<option value="<?php echo esc_attr( $font_title ); ?>"><?php echo $font_title; ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'text' === $option['type'] ) : ?>
						<input name="<?php echo esc_attr( $option_name ); ?>" class="madxartwork-panel-scheme-typography-item-field" />
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-control-responsive-switchers">
	<div class="madxartwork-control-responsive-switchers">
		<#
			var devices = responsive.devices || [ 'desktop', 'tablet', 'mobile' ];

			_.each( devices, function( device ) { #>
				<a class="madxartwork-responsive-switcher madxartwork-responsive-switcher-{{ device }}" data-device="{{ device }}">
					<i class="eicon-device-{{ device }}"></i>
				</a>
			<# } );
		#>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-control-dynamic-switcher">
	<div class="madxartwork-control-dynamic-switcher-wrapper">
		<div class="madxartwork-control-dynamic-switcher">
			<?php echo __( 'Dynamic', 'madxartwork' ); ?>
			<i class="eicon-database"></i>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-control-dynamic-cover">
	<div class="madxartwork-dynamic-cover__settings">
		<i class="eicon-{{ hasSettings ? 'wrench' : 'database' }}"></i>
	</div>
	<div class="madxartwork-dynamic-cover__title" title="{{{ title + ' ' + content }}}">{{{ title + ' ' + content }}}</div>
	<# if ( isRemovable ) { #>
		<div class="madxartwork-dynamic-cover__remove">
			<i class="eicon-close-circle"></i>
		</div>
	<# } #>
</script>
