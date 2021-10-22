<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-madxartwork-panel-revisions">
	<div class="madxartwork-panel-box">
	<div class="madxartwork-panel-scheme-buttons">
			<div class="madxartwork-panel-scheme-button-wrapper madxartwork-panel-scheme-discard">
				<button class="madxartwork-button" disabled>
					<i class="eicon-close" aria-hidden="true"></i>
					<?php echo __( 'Discard', 'madxartwork' ); ?>
				</button>
			</div>
			<div class="madxartwork-panel-scheme-button-wrapper madxartwork-panel-scheme-save">
				<button class="madxartwork-button madxartwork-button-success" disabled>
					<?php echo __( 'Apply', 'madxartwork' ); ?>
				</button>
			</div>
		</div>
	</div>

	<div class="madxartwork-panel-box">
		<div class="madxartwork-panel-heading">
			<div class="madxartwork-panel-heading-title"><?php echo __( 'Revisions', 'madxartwork' ); ?></div>
		</div>
		<div id="madxartwork-revisions-list" class="madxartwork-panel-box-content"></div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-revisions-no-revisions">
	<i class="madxartwork-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
	<div class="madxartwork-nerd-box-title"><?php echo __( 'No Revisions Saved Yet', 'madxartwork' ); ?></div>
	<div class="madxartwork-nerd-box-message">{{{ madxartwork.translate( madxartwork.config.revisions_enabled ? 'no_revisions_1' : 'revisions_disabled_1' ) }}}</div>
	<div class="madxartwork-nerd-box-message">{{{ madxartwork.translate( madxartwork.config.revisions_enabled ? 'no_revisions_2' : 'revisions_disabled_2' ) }}}</div>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-revisions-loading">
	<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-madxartwork-panel-revisions-revision-item">
	<div class="madxartwork-revision-item__wrapper {{ type }}">
		<div class="madxartwork-revision-item__gravatar">{{{ gravatar }}}</div>
		<div class="madxartwork-revision-item__details">
			<div class="madxartwork-revision-date">{{{ date }}}</div>
			<div class="madxartwork-revision-meta"><span>{{{ madxartwork.translate( type ) }}}</span> <?php echo __( 'By', 'madxartwork' ); ?> {{{ author }}}</div>
		</div>
		<div class="madxartwork-revision-item__tools">
			<# if ( 'current' === type ) { #>
				<i class="madxartwork-revision-item__tools-current eicon-star" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Current', 'madxartwork' ); ?></span>
			<# } else { #>
				<i class="madxartwork-revision-item__tools-delete eicon-close" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Delete', 'madxartwork' ); ?></span>
			<# } #>

			<i class="madxartwork-revision-item__tools-spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
		</div>
	</div>
</script>
