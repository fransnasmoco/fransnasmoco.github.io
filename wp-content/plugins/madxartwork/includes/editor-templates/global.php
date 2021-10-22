<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-madxartwork-empty-preview">
	<div class="madxartwork-first-add">
		<div class="madxartwork-icon eicon-plus"></div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-preview">
	<div class="madxartwork-section-wrap"></div>
</script>

<script type="text/template" id="tmpl-madxartwork-add-section">
	<div class="madxartwork-add-section-inner">
		<div class="madxartwork-add-section-close">
			<i class="eicon-close" aria-hidden="true"></i>
			<span class="madxartwork-screen-only"><?php echo __( 'Close', 'madxartwork' ); ?></span>
		</div>
		<div class="madxartwork-add-new-section">
			<div class="madxartwork-add-section-area-button madxartwork-add-section-button" title="<?php echo __( 'Add New Section', 'madxartwork' ); ?>">
				<i class="eicon-plus"></i>
			</div>
			<div class="madxartwork-add-section-area-button madxartwork-add-template-button" title="<?php echo __( 'Add Template', 'madxartwork' ); ?>">
				<i class="eicon-folder"></i>
			</div>
			<div class="madxartwork-add-section-drag-title"><?php echo __( 'Drag widget here', 'madxartwork' ); ?></div>
		</div>
		<div class="madxartwork-select-preset">
			<div class="madxartwork-select-preset-title"><?php echo __( 'Select your Structure', 'madxartwork' ); ?></div>
			<ul class="madxartwork-select-preset-list">
				<#
					var structures = [ 10, 20, 30, 40, 21, 22, 31, 32, 33, 50, 60, 34 ];

					_.each( structures, function( structure ) {
					var preset = madxartwork.presetsFactory.getPresetByStructure( structure ); #>

					<li class="madxartwork-preset madxartwork-column madxartwork-col-16" data-structure="{{ structure }}">
						{{{ madxartwork.presetsFactory.getPresetSVG( preset.preset ).outerHTML }}}
					</li>
					<# } ); #>
			</ul>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-tag-controls-stack-empty">
	<?php echo __( 'This tag has no settings.', 'madxartwork' ); ?>
</script>
