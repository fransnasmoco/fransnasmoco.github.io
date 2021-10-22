<?php
namespace madxartwork;

use madxartwork\Core\Base\Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$document_types = Plugin::$instance->documents->get_document_types();

$types = [];

$selected = get_query_var( 'madxartwork_library_type' );

foreach ( $document_types as $document_type ) {
	if ( $document_type::get_property( 'show_in_library' ) ) {
		/**
		 * @var Document $instance
		 */
		$instance = new $document_type();

		$types[ $instance->get_name() ] = $document_type::get_title();
	}
}

/**
 * Create new template library dialog types.
 *
 * Filters the dialog types when printing new template dialog.
 *
 * @since 2.0.0
 *
 * @param array    $types          Types data.
 * @param Document $document_types Document types.
 */
$types = apply_filters( 'madxartwork/template-library/create_new_dialog_types', $types, $document_types );
?>
<script type="text/template" id="tmpl-madxartwork-new-template">
	<div id="madxartwork-new-template__description">
		<div id="madxartwork-new-template__description__title"><?php echo __( 'Templates Help You <span>Work Efficiently</span>', 'madxartwork' ); ?></div>
		<div id="madxartwork-new-template__description__content"><?php echo __( 'Use templates to create the different pieces of your site, and reuse them with one click whenever needed.', 'madxartwork' ); ?></div>
		<?php
		/*
		<div id="madxartwork-new-template__take_a_tour">
			<i class="eicon-play-o"></i>
			<a href="#"><?php echo __( 'Take The Video Tour', 'madxartwork' ); ?></a>
		</div>
		*/
		?>
	</div>
	<form id="madxartwork-new-template__form" action="<?php esc_url( admin_url( '/edit.php' ) ); ?>">
		<input type="hidden" name="post_type" value="madxartwork_library">
		<input type="hidden" name="action" value="madxartwork_new_post">
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'madxartwork_action_new_post' ); ?>">
		<div id="madxartwork-new-template__form__title"><?php echo __( 'Choose Template Type', 'madxartwork' ); ?></div>
		<div id="madxartwork-new-template__form__template-type__wrapper" class="madxartwork-form-field">
			<label for="madxartwork-new-template__form__template-type" class="madxartwork-form-field__label"><?php echo __( 'Select the type of template you want to work on', 'madxartwork' ); ?></label>
			<div class="madxartwork-form-field__select__wrapper">
				<select id="madxartwork-new-template__form__template-type" class="madxartwork-form-field__select" name="template_type" required>
					<option value=""><?php echo __( 'Select', 'madxartwork' ); ?>...</option>
					<?php
					foreach ( $types as $value => $type_title ) {
						printf( '<option value="%1$s" %2$s>%3$s</option>', $value, selected( $selected, $value, false ), $type_title );
					}
					?>
				</select>
			</div>
		</div>
		<?php
		/**
		 * Template library dialog fields.
		 *
		 * Fires after madxartwork template library dialog fields are displayed.
		 *
		 * @since 2.0.0
		 */
		do_action( 'madxartwork/template-library/create_new_dialog_fields' );
		?>

		<div id="madxartwork-new-template__form__post-title__wrapper" class="madxartwork-form-field">
			<label for="madxartwork-new-template__form__post-title" class="madxartwork-form-field__label">
				<?php echo __( 'Name your template', 'madxartwork' ); ?>
			</label>
			<div class="madxartwork-form-field__text__wrapper">
				<input type="text" placeholder="<?php echo esc_attr__( 'Enter template name (optional)', 'madxartwork' ); ?>" id="madxartwork-new-template__form__post-title" class="madxartwork-form-field__text" name="post_data[post_title]">
			</div>
		</div>
		<button id="madxartwork-new-template__form__submit" class="madxartwork-button madxartwork-button-success"><?php echo __( 'Create Template', 'madxartwork' ); ?></button>
	</form>
</script>
