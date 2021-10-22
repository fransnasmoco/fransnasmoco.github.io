<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="madxartwork-custom-icons-template-footer">
	<div class="madxartwork-icon-set-footer"><?php echo __( 'Created on:', 'madxartwork-pro' ); ?> {{day}}/{{mm}}/{{year}}, {{hour}}:{{minute}}</div>
</script>

<script type="text/template" id="madxartwork-custom-icons-template-header">
	<div class="madxartwork-icon-set-header">
		<div><span class="madxartwork-icon-set-header-meta"><?php echo __( 'Name:', 'madxartwork-pro' ); ?> </span><span class="madxartwork-icon-set-header-meta-value">{{name}}</span></div>
		<div><span class="madxartwork-icon-set-header-meta"><?php echo __( 'CSS Prefix:', 'madxartwork-pro' ); ?> </span><span class="madxartwork-icon-set-header-meta-value">{{prefix}}</span></div>
		<div><span class="madxartwork-icon-set-header-meta"><?php echo __( 'Icons Count:', 'madxartwork-pro' ); ?> </span><span class="madxartwork-icon-set-header-meta-value">{{count}}</span></div>
		<div class="madxartwork-icon-set-header-meta-remove"><div class="remove"><i class="eicon-trash"></i> <?php echo __( 'Remove', 'madxartwork-pro' ); ?></div></div>
	</div>
</script>

<script type="text/template" id="madxartwork-custom-icons-template-duplicate-prefix">
	<div class="madxartwork-icon-set-duplicate-prefix"><?php echo __( 'The Icon Set prefix already exists in your site. In order to avoid conflicts we recommend to use a unique prefix per Icon Set.', 'madxartwork-pro' ); ?></div>
</script>
