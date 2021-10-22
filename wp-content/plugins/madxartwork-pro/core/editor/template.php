<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-madxartwork-pro-template-library-activate-license-button">
	<a class="madxartwork-template-library-template-action madxartwork-button madxartwork-button-go-pro" href="<?php echo \madxartworkPro\License\Admin::get_url(); ?>" target="_blank">
		<i class="eicon-external-link-square"></i>
		<span class="madxartwork-button-title"><?php _e( 'Activate License', 'madxartwork-pro' ); ?></span>
	</a>
</script>
