<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-madxartwork-template-library-header-actions">
	<div id="madxartwork-template-library-header-import" class="madxartwork-templates-modal__header__item">
		<i class="eicon-upload-circle-o" aria-hidden="true" title="<?php esc_attr_e( 'Import Template', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Import Template', 'madxartwork' ); ?></span>
	</div>
	<div id="madxartwork-template-library-header-sync" class="madxartwork-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Sync Library', 'madxartwork' ); ?></span>
	</div>
	<div id="madxartwork-template-library-header-save" class="madxartwork-templates-modal__header__item">
		<i class="eicon-save-o" aria-hidden="true" title="<?php esc_attr_e( 'Save', 'madxartwork' ); ?>"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Save', 'madxartwork' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-header-menu">
	<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="madxartwork-component-tab madxartwork-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-header-preview">
	<div id="madxartwork-template-library-header-preview-insert-wrapper" class="madxartwork-templates-modal__header__item">
		{{{ madxartwork.templates.layout.getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'madxartwork' ); ?></span>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-loading">
	<div class="madxartwork-loader-wrapper">
		<div class="madxartwork-loader">
			<div class="madxartwork-loader-boxes">
				<div class="madxartwork-loader-box"></div>
				<div class="madxartwork-loader-box"></div>
				<div class="madxartwork-loader-box"></div>
				<div class="madxartwork-loader-box"></div>
			</div>
		</div>
		<div class="madxartwork-loading-title"><?php echo __( 'Loading', 'madxartwork' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-templates">
	<#
		var activeSource = madxartwork.templates.getFilter('source');
	#>
	<div id="madxartwork-template-library-toolbar">
		<# if ( 'remote' === activeSource ) {
			var activeType = madxartwork.templates.getFilter('type');
			#>
			<div id="madxartwork-template-library-filter-toolbar-remote" class="madxartwork-template-library-filter-toolbar">
				<# if ( 'page' === activeType ) { #>
					<div id="madxartwork-template-library-order">
						<input type="radio" id="madxartwork-template-library-order-new" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order" value="date">
						<label for="madxartwork-template-library-order-new" class="madxartwork-template-library-order-label"><?php echo __( 'New', 'madxartwork' ); ?></label>
						<input type="radio" id="madxartwork-template-library-order-trend" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order" value="trendIndex">
						<label for="madxartwork-template-library-order-trend" class="madxartwork-template-library-order-label"><?php echo __( 'Trend', 'madxartwork' ); ?></label>
						<input type="radio" id="madxartwork-template-library-order-popular" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order" value="popularityIndex">
						<label for="madxartwork-template-library-order-popular" class="madxartwork-template-library-order-label"><?php echo __( 'Popular', 'madxartwork' ); ?></label>
					</div>
				<# } else {
					var config = madxartwork.templates.getConfig( activeType );
					if ( config.categories ) { #>
						<div id="madxartwork-template-library-filter">
							<select id="madxartwork-template-library-filter-subtype" class="madxartwork-template-library-filter-select" data-madxartwork-filter="subtype">
								<option></option>
								<# config.categories.forEach( function( category ) {
									var selected = category === madxartwork.templates.getFilter( 'subtype' ) ? ' selected' : '';
									#>
									<option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
								<# } ); #>
							</select>
						</div>
					<# }
				} #>
				<div id="madxartwork-template-library-my-favorites">
					<# var checked = madxartwork.templates.getFilter( 'favorite' ) ? ' checked' : ''; #>
					<input id="madxartwork-template-library-filter-my-favorites" type="checkbox"{{{ checked }}}>
					<label id="madxartwork-template-library-filter-my-favorites-label" for="madxartwork-template-library-filter-my-favorites">
						<i class="eicon" aria-hidden="true"></i>
						<?php echo __( 'My Favorites', 'madxartwork' ); ?>
					</label>
				</div>
			</div>
		<# } else { #>
			<div id="madxartwork-template-library-filter-toolbar-local" class="madxartwork-template-library-filter-toolbar"></div>
		<# } #>
		<div id="madxartwork-template-library-filter-text-wrapper">
			<label for="madxartwork-template-library-filter-text" class="madxartwork-screen-only"><?php echo __( 'Search Templates:', 'madxartwork' ); ?></label>
			<input id="madxartwork-template-library-filter-text" placeholder="<?php echo esc_attr__( 'Search', 'madxartwork' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>
	<# if ( 'local' === activeSource ) { #>
		<div id="madxartwork-template-library-order-toolbar-local">
			<div class="madxartwork-template-library-local-column-1">
				<input type="radio" id="madxartwork-template-library-order-local-title" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order-local" value="title" data-default-ordering-direction="asc">
				<label for="madxartwork-template-library-order-local-title" class="madxartwork-template-library-order-label"><?php echo __( 'Name', 'madxartwork' ); ?></label>
			</div>
			<div class="madxartwork-template-library-local-column-2">
				<input type="radio" id="madxartwork-template-library-order-local-type" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order-local" value="type" data-default-ordering-direction="asc">
				<label for="madxartwork-template-library-order-local-type" class="madxartwork-template-library-order-label"><?php echo __( 'Type', 'madxartwork' ); ?></label>
			</div>
			<div class="madxartwork-template-library-local-column-3">
				<input type="radio" id="madxartwork-template-library-order-local-author" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order-local" value="author" data-default-ordering-direction="asc">
				<label for="madxartwork-template-library-order-local-author" class="madxartwork-template-library-order-label"><?php echo __( 'Created By', 'madxartwork' ); ?></label>
			</div>
			<div class="madxartwork-template-library-local-column-4">
				<input type="radio" id="madxartwork-template-library-order-local-date" class="madxartwork-template-library-order-input" name="madxartwork-template-library-order-local" value="date">
				<label for="madxartwork-template-library-order-local-date" class="madxartwork-template-library-order-label"><?php echo __( 'Creation Date', 'madxartwork' ); ?></label>
			</div>
			<div class="madxartwork-template-library-local-column-5">
				<div class="madxartwork-template-library-order-label"><?php echo __( 'Actions', 'madxartwork' ); ?></div>
			</div>
		</div>
	<# } #>
	<div id="madxartwork-template-library-templates-container"></div>
	<# if ( 'remote' === activeSource ) { #>
		<div id="madxartwork-template-library-footer-banner">
			<i class="eicon-nerd" aria-hidden="true"></i>
			<div class="madxartwork-excerpt"><?php echo __( 'Stay tuned! More awesome templates coming real soon.', 'madxartwork' ); ?></div>
		</div>
	<# } #>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-template-remote">
	<div class="madxartwork-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="madxartwork-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img src="{{ thumbnail }}">
		<# } #>
		<div class="madxartwork-template-library-template-preview">
			<i class="eicon-zoom-in" aria-hidden="true"></i>
		</div>
	</div>
	<div class="madxartwork-template-library-template-footer">
		{{{ madxartwork.templates.layout.getTemplateActionButton( obj ) }}}
		<div class="madxartwork-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
		<div class="madxartwork-template-library-favorite">
			<input id="madxartwork-template-library-template-{{ template_id }}-favorite-input" class="madxartwork-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
			<label for="madxartwork-template-library-template-{{ template_id }}-favorite-input" class="madxartwork-template-library-template-favorite-label">
				<i class="eicon-heart-o" aria-hidden="true"></i>
				<span class="madxartwork-screen-only"><?php echo __( 'Favorite', 'madxartwork' ); ?></span>
			</label>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-template-local">
	<div class="madxartwork-template-library-template-name madxartwork-template-library-local-column-1">{{{ title }}}</div>
	<div class="madxartwork-template-library-template-meta madxartwork-template-library-template-type madxartwork-template-library-local-column-2">{{{ madxartwork.translate( type ) }}}</div>
	<div class="madxartwork-template-library-template-meta madxartwork-template-library-template-author madxartwork-template-library-local-column-3">{{{ author }}}</div>
	<div class="madxartwork-template-library-template-meta madxartwork-template-library-template-date madxartwork-template-library-local-column-4">{{{ human_date }}}</div>
	<div class="madxartwork-template-library-template-controls madxartwork-template-library-local-column-5">
		<div class="madxartwork-template-library-template-preview">
			<i class="eicon-eye" aria-hidden="true"></i>
			<span class="madxartwork-template-library-template-control-title"><?php echo __( 'Preview', 'madxartwork' ); ?></span>
		</div>
		<button class="madxartwork-template-library-template-action madxartwork-template-library-template-insert madxartwork-button madxartwork-button-success">
			<i class="eicon-file-download" aria-hidden="true"></i>
			<span class="madxartwork-button-title"><?php echo __( 'Insert', 'madxartwork' ); ?></span>
		</button>
		<div class="madxartwork-template-library-template-more-toggle">
			<i class="eicon-ellipsis-h" aria-hidden="true"></i>
			<span class="madxartwork-screen-only"><?php echo __( 'More actions', 'madxartwork' ); ?></span>
		</div>
		<div class="madxartwork-template-library-template-more">
			<div class="madxartwork-template-library-template-delete">
				<i class="eicon-trash-o" aria-hidden="true"></i>
				<span class="madxartwork-template-library-template-control-title"><?php echo __( 'Delete', 'madxartwork' ); ?></span>
			</div>
			<div class="madxartwork-template-library-template-export">
				<a href="{{ export_link }}">
					<i class="eicon-sign-out" aria-hidden="true"></i>
					<span class="madxartwork-template-library-template-control-title"><?php echo __( 'Export', 'madxartwork' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-insert-button">
	<a class="madxartwork-template-library-template-action madxartwork-template-library-template-insert madxartwork-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="madxartwork-button-title"><?php echo __( 'Insert', 'madxartwork' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-get-pro-button">
	<a class="madxartwork-template-library-template-action madxartwork-button madxartwork-button-go-pro" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=panel-library&utm_campaign=gopro&utm_medium=wp-dash' ); ?>" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="madxartwork-button-title"><?php echo __( 'Go Pro', 'madxartwork' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-save-template">
	<div class="madxartwork-template-library-blank-icon">
		<i class="eicon-library-save" aria-hidden="true"></i>
		<span class="madxartwork-screen-only"><?php echo __( 'Save', 'madxartwork' ); ?></span>
	</div>
	<div class="madxartwork-template-library-blank-title">{{{ title }}}</div>
	<div class="madxartwork-template-library-blank-message">{{{ description }}}</div>
	<form id="madxartwork-template-library-save-template-form">
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
		<input id="madxartwork-template-library-save-template-name" name="title" placeholder="<?php echo esc_attr__( 'Enter Template Name', 'madxartwork' ); ?>" required>
		<button id="madxartwork-template-library-save-template-submit" class="madxartwork-button madxartwork-button-success">
			<span class="madxartwork-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<?php echo __( 'Save', 'madxartwork' ); ?>
		</button>
	</form>
	<div class="madxartwork-template-library-blank-footer">
		<?php echo __( 'Want to learn more about the madxartwork library?', 'madxartwork' ); ?>
		<a class="madxartwork-template-library-blank-footer-link" href="https://go.madxartwork.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'madxartwork' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-import">
	<form id="madxartwork-template-library-import-form">
		<div class="madxartwork-template-library-blank-icon">
			<i class="eicon-library-upload" aria-hidden="true"></i>
		</div>
		<div class="madxartwork-template-library-blank-title"><?php echo __( 'Import Template to Your Library', 'madxartwork' ); ?></div>
		<div class="madxartwork-template-library-blank-message"><?php echo __( 'Drag & drop your .JSON or .zip template file', 'madxartwork' ); ?></div>
		<div id="madxartwork-template-library-import-form-or"><?php echo __( 'or', 'madxartwork' ); ?></div>
		<label for="madxartwork-template-library-import-form-input" id="madxartwork-template-library-import-form-label" class="madxartwork-button madxartwork-button-success"><?php echo __( 'Select File', 'madxartwork' ); ?></label>
		<input id="madxartwork-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
		<div class="madxartwork-template-library-blank-footer">
			<?php echo __( 'Want to learn more about the madxartwork library?', 'madxartwork' ); ?>
			<a class="madxartwork-template-library-blank-footer-link" href="https://go.madxartwork.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'madxartwork' ); ?></a>
		</div>
	</form>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-templates-empty">
	<div class="madxartwork-template-library-blank-icon">
		<i class="eicon-nerd" aria-hidden="true"></i>
	</div>
	<div class="madxartwork-template-library-blank-title"></div>
	<div class="madxartwork-template-library-blank-message"></div>
	<div class="madxartwork-template-library-blank-footer">
		<?php echo __( 'Want to learn more about the madxartwork library?', 'madxartwork' ); ?>
		<a class="madxartwork-template-library-blank-footer-link" href="https://go.madxartwork.com/docs-library/" target="_blank"><?php echo __( 'Click here', 'madxartwork' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-madxartwork-template-library-preview">
	<iframe></iframe>
</script>
