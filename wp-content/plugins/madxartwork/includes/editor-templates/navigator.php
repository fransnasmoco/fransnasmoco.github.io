<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-madxartwork-navigator">
	<div id="madxartwork-navigator__header">
		<i id="madxartwork-navigator__toggle-all" class="eicon-expand" data-madxartwork-action="expand"></i>
		<div id="madxartwork-navigator__header__title"><?php echo __( 'Navigator', 'madxartwork' ); ?></div>
		<i id="madxartwork-navigator__close" class="eicon-close"></i>
	</div>
	<div id="madxartwork-navigator__elements"></div>
	<div id="madxartwork-navigator__footer">
		<i class="eicon-ellipsis-h"></i>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-navigator__elements">
	<# if ( obj.elType ) { #>
		<div class="madxartwork-navigator__item">
			<div class="madxartwork-navigator__element__list-toggle">
				<i class="eicon-sort-down"></i>
			</div>
			<#
			if ( icon ) { #>
				<div class="madxartwork-navigator__element__element-type">
					<i class="{{{ icon }}}"></i>
				</div>
			<# } #>
			<div class="madxartwork-navigator__element__title">
				<span class="madxartwork-navigator__element__title__text">{{{ title }}}</span>
			</div>
			<div class="madxartwork-navigator__element__toggle">
				<i class="eicon-eye"></i>
			</div>
			<div class="madxartwork-navigator__element__indicators"></div>
		</div>
	<# } #>
	<div class="madxartwork-navigator__elements"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-navigator__elements--empty">
	<div class="madxartwork-empty-view__title"><?php echo __( 'Empty', 'madxartwork' ); ?></div>
</script>

<script type="text/template" id="tmpl-madxartwork-navigator__root--empty">
	<i class="madxartwork-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="madxartwork-nerd-box-title"><?php echo __( 'Easy Navigation is Here!', 'madxartwork' ); ?></div>
	<div class="madxartwork-nerd-box-message"><?php echo __( 'Once you fill your page with content, this window will give you an overview display of all the page elements. This way, you can easily move around any section, column, or widget.', 'madxartwork' ); ?></div>
</script>
