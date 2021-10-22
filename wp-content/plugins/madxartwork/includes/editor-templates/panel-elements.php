<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-madxartwork-panel-elements">
	<div id="madxartwork-panel-elements-loading">
		<i class="eicon-loading eicon-animation-spin"></i>
	</div>
	<div id="madxartwork-panel-elements-navigation" class="madxartwork-panel-navigation">
		<div class="madxartwork-component-tab madxartwork-panel-navigation-tab" data-tab="categories"><?php echo __( 'Elements', 'madxartwork' ); ?></div>
		<div class="madxartwork-component-tab madxartwork-panel-navigation-tab" data-tab="global"><?php echo __( 'Global', 'madxartwork' ); ?></div>
	</div>
	<div id="madxartwork-panel-elements-search-area"></div>
	<div id="madxartwork-panel-elements-wrapper"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-categories">
	<div id="madxartwork-panel-categories"></div>

	<div id="madxartwork-panel-get-pro-elements" class="madxartwork-nerd-box">
		<i class="madxartwork-nerd-box-icon eicon-hypster" aria-hidden="true"></i>
		<div class="madxartwork-nerd-box-message"><?php echo __( 'Get more with madxartwork Pro', 'madxartwork' ); ?></div>
		<a class="madxartwork-button madxartwork-button-default madxartwork-nerd-box-link" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=panel-widgets&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-elements-category">
	<div class="madxartwork-panel-category-title">{{{ title }}}</div>
	<div class="madxartwork-panel-category-items"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-element-search">
	<label for="madxartwork-panel-elements-search-input" class="screen-reader-text"><?php echo __( 'Search Widget:', 'madxartwork' ); ?></label>
	<input type="search" id="madxartwork-panel-elements-search-input" placeholder="<?php esc_attr_e( 'Search Widget...', 'madxartwork' ); ?>" autocomplete="off"/>
	<i class="eicon-search" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-madxartwork-element-library-element">
	<div class="madxartwork-element">
		<div class="icon">
			<i class="{{ icon }}" aria-hidden="true"></i>
		</div>
		<div class="madxartwork-element-title-wrapper">
			<div class="title">{{{ title }}}</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-global">
	<div class="madxartwork-nerd-box">
		<i class="madxartwork-nerd-box-icon eicon-hypster" aria-hidden="true"></i>
		<div class="madxartwork-nerd-box-title"><?php echo __( 'Meet Our Global Widget', 'madxartwork' ); ?></div>
		<div class="madxartwork-nerd-box-message"><?php echo __( 'With this feature, you can save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'madxartwork' ); ?></div>
		<div class="madxartwork-nerd-box-message"><?php echo __( 'This feature is only available on madxartwork Pro.', 'madxartwork' ); ?></div>
		<a class="madxartwork-button madxartwork-button-default madxartwork-nerd-box-link" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=panel-global&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
	</div>
</script>
