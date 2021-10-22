<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-madxartwork-templates-modal__header">
	<div class="madxartwork-templates-modal__header__logo-area"></div>
	<div class="madxartwork-templates-modal__header__menu-area"></div>
	<div class="madxartwork-templates-modal__header__items-area">
		<# if ( closeType ) { #>
			<div class="madxartwork-templates-modal__header__close madxartwork-templates-modal__header__close--{{{ closeType }}} madxartwork-templates-modal__header__item">
				<# if ( 'skip' === closeType ) { #>
				<span><?php echo __( 'Skip', 'madxartwork' ); ?></span>
				<# } #>
				<i class="eicon-close" aria-hidden="true" title="<?php echo __( 'Close', 'madxartwork' ); ?>"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Close', 'madxartwork' ); ?></span>
			</div>
		<# } #>
		<div id="madxartwork-template-library-header-tools"></div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-templates-modal__header__logo">
	<span class="madxartwork-templates-modal__header__logo__icon-wrapper">
		<i class="eicon-madxartwork"></i>
	</span>
	<span class="madxartwork-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
