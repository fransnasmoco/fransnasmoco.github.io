<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-madxartwork-repeater-row">
	<div class="madxartwork-repeater-row-tools">
		<# if ( itemActions.drag_n_drop ) {  #>
			<div class="madxartwork-repeater-row-handle-sortable">
				<i class="eicon-ellipsis-v" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Drag & Drop', 'madxartwork' ); ?></span>
			</div>
		<# } #>
		<div class="madxartwork-repeater-row-item-title"></div>
		<# if ( itemActions.duplicate ) {  #>
			<div class="madxartwork-repeater-row-tool madxartwork-repeater-tool-duplicate">
				<i class="eicon-copy" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Duplicate', 'madxartwork' ); ?></span>
			</div>
		<# }
		if ( itemActions.remove ) {  #>
			<div class="madxartwork-repeater-row-tool madxartwork-repeater-tool-remove">
				<i class="eicon-close" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Remove', 'madxartwork' ); ?></span>
			</div>
		<# } #>
	</div>
	<div class="madxartwork-repeater-row-controls"></div>
</script>
