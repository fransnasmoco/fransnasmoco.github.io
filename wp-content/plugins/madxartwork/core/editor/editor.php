<?php
namespace madxartwork\Core\Editor;

use madxartwork\Core\Common\Modules\Ajax\Module as Ajax;
use madxartwork\Core\Debug\Loading_Inspection_Manager;
use madxartwork\Core\Responsive\Responsive;
use madxartwork\Core\Settings\Manager as SettingsManager;
use madxartwork\Icons_Manager;
use madxartwork\Plugin;
use madxartwork\Schemes_Manager;
use madxartwork\Settings;
use madxartwork\Shapes;
use madxartwork\TemplateLibrary\Source_Local;
use madxartwork\Tools;
use madxartwork\User;
use madxartwork\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork editor.
 *
 * madxartwork editor handler class is responsible for initializing madxartwork
 * editor and register all the actions needed to display the editor.
 *
 * @since 1.0.0
 */
class Editor {

	/**
	 * The nonce key for madxartwork editor.
	 * @deprecated 2.3.0
	 */
	const EDITING_NONCE_KEY = 'madxartwork-editing';

	/**
	 * User capability required to access madxartwork editor.
	 */
	const EDITING_CAPABILITY = 'edit_posts';

	/**
	 * Post ID.
	 *
	 * Holds the ID of the current post being edited.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var int Post ID.
	 */
	private $_post_id;

	/**
	 * Whether the edit mode is active.
	 *
	 * Used to determine whether we are in edit mode.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var bool Whether the edit mode is active.
	 */
	private $_is_edit_mode;

	/**
	 * @var Notice_Bar
	 */
	public $notice_bar;

	/**
	 * Init.
	 *
	 * Initialize madxartwork editor. Registers all needed actions to run madxartwork,
	 * removes conflicting actions etc.
	 *
	 * Fired by `admin_action_madxartwork` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool $die Optional. Whether to die at the end. Default is `true`.
	 */
	public function init( $die = true ) {
		if ( empty( $_REQUEST['post'] ) ) { // WPCS: CSRF ok.
			return;
		}

		$this->_post_id = absint( $_REQUEST['post'] );

		if ( ! $this->is_edit_mode( $this->_post_id ) ) {
			return;
		}

		Loading_Inspection_Manager::instance()->register_inspections();

		// Send MIME Type header like WP admin-header.
		@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );

		// Temp: Allow plugins to know that the editor route is ready. TODO: Remove on 2.7.3.
		define( 'madxartwork_EDITOR_USE_ROUTER', true );

		// Use requested id and not the global in order to avoid conflicts with plugins that changes the global post.
		query_posts( [
			'p' => $this->_post_id,
			'post_type' => get_post_type( $this->_post_id ),
		] );

		Plugin::$instance->db->switch_to_post( $this->_post_id );

		$document = Plugin::$instance->documents->get( $this->_post_id );

		Plugin::$instance->documents->switch_to_document( $document );

		add_filter( 'show_admin_bar', '__return_false' );

		// Remove all WordPress actions
		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_print_styles' );
		remove_all_actions( 'wp_print_head_scripts' );
		remove_all_actions( 'wp_footer' );

		// Handle `wp_head`
		add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
		add_action( 'wp_head', 'wp_print_styles', 8 );
		add_action( 'wp_head', 'wp_print_head_scripts', 9 );
		add_action( 'wp_head', 'wp_site_icon' );
		add_action( 'wp_head', [ $this, 'editor_head_trigger' ], 30 );

		// Handle `wp_footer`
		add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );
		add_action( 'wp_footer', 'wp_auth_check_html', 30 );
		add_action( 'wp_footer', [ $this, 'wp_footer' ] );

		// Handle `wp_enqueue_scripts`
		remove_all_actions( 'wp_enqueue_scripts' );

		// Also remove all scripts hooked into after_wp_tiny_mce.
		remove_all_actions( 'after_wp_tiny_mce' );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 999999 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 999999 );

		// Change mode to Builder
		Plugin::$instance->db->set_is_madxartwork_page( $this->_post_id );

		// Post Lock
		if ( ! $this->get_locked_user( $this->_post_id ) ) {
			$this->lock_post( $this->_post_id );
		}

		// Setup default heartbeat options
		add_filter( 'heartbeat_settings', function( $settings ) {
			$settings['interval'] = 15;
			return $settings;
		} );

		// Tell to WP Cache plugins do not cache this request.
		Utils::do_not_cache();

		do_action( 'madxartwork/editor/init' );

		$this->print_editor_template();

		// From the action it's an empty string, from tests its `false`
		if ( false !== $die ) {
			die;
		}
	}

	/**
	 * Retrieve post ID.
	 *
	 * Get the ID of the current post.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return int Post ID.
	 */
	public function get_post_id() {
		return $this->_post_id;
	}

	/**
	 * Redirect to new URL.
	 *
	 * Used as a fallback function for the old URL structure of madxartwork page
	 * edit URL.
	 *
	 * Fired by `template_redirect` action.
	 *
	 * @since 1.6.0
	 * @access public
	 */
	public function redirect_to_new_url() {
		if ( ! isset( $_GET['madxartwork'] ) ) {
			return;
		}

		$document = Plugin::$instance->documents->get( get_the_ID() );

		if ( ! $document ) {
			wp_die( __( 'Document not found.', 'madxartwork' ) );
		}

		if ( ! $document->is_editable_by_current_user() || ! $document->is_built_with_madxartwork() ) {
			return;
		}

		wp_safe_redirect( $document->get_edit_url() );
		die;
	}

	/**
	 * Whether the edit mode is active.
	 *
	 * Used to determine whether we are in the edit mode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id Optional. Post ID. Default is `null`, the current
	 *                     post ID.
	 *
	 * @return bool Whether the edit mode is active.
	 */
	public function is_edit_mode( $post_id = null ) {
		if ( null !== $this->_is_edit_mode ) {
			return $this->_is_edit_mode;
		}

		if ( empty( $post_id ) ) {
			$post_id = $this->_post_id;
		}

		$document = Plugin::$instance->documents->get( $post_id );

		if ( ! $document || ! $document->is_editable_by_current_user() ) {
			return false;
		}

		// Ajax request as Editor mode
		$actions = [
			'madxartwork',

			// Templates
			'madxartwork_get_templates',
			'madxartwork_save_template',
			'madxartwork_get_template',
			'madxartwork_delete_template',
			'madxartwork_import_template',
			'madxartwork_library_direct_actions',
		];

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Lock post.
	 *
	 * Mark the post as currently being edited by the current user.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id The ID of the post being edited.
	 */
	public function lock_post( $post_id ) {
		if ( ! function_exists( 'wp_set_post_lock' ) ) {
			require_once ABSPATH . 'wp-admin/includes/post.php';
		}

		wp_set_post_lock( $post_id );
	}

	/**
	 * Get locked user.
	 *
	 * Check what user is currently editing the post.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id The ID of the post being edited.
	 *
	 * @return \WP_User|false User information or false if the post is not locked.
	 */
	public function get_locked_user( $post_id ) {
		if ( ! function_exists( 'wp_check_post_lock' ) ) {
			require_once ABSPATH . 'wp-admin/includes/post.php';
		}

		$locked_user = wp_check_post_lock( $post_id );
		if ( ! $locked_user ) {
			return false;
		}

		return get_user_by( 'id', $locked_user );
	}

	/**
	 * Print Editor Template.
	 *
	 * Include the wrapper template of the editor.
	 *
	 * @since 2.2.0
	 * @access public
	 */
	public function print_editor_template() {
		include madxartwork_PATH . 'includes/editor-templates/editor-wrapper.php';
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers all the editor scripts and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		remove_action( 'wp_enqueue_scripts', [ $this, __FUNCTION__ ], 999999 );

		// Set the global data like $post, $authordata and etc
		setup_postdata( $this->_post_id );

		global $wp_styles, $wp_scripts;

		$plugin = Plugin::$instance;

		// Reset global variable
		$wp_styles = new \WP_Styles(); // WPCS: override ok.
		$wp_scripts = new \WP_Scripts(); // WPCS: override ok.

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'madxartwork_TESTS' ) && madxartwork_TESTS ) ? '' : '.min';

		wp_register_script(
			'madxartwork-editor-modules',
			madxartwork_ASSETS_URL . 'js/editor-modules' . $suffix . '.js',
			[
				'madxartwork-common-modules',
			],
			madxartwork_VERSION,
			true
		);
		// Hack for waypoint with editor mode.
		wp_register_script(
			'madxartwork-waypoints',
			madxartwork_ASSETS_URL . 'lib/waypoints/waypoints-for-editor.js',
			[
				'jquery',
			],
			'4.0.2',
			true
		);

		wp_register_script(
			'perfect-scrollbar',
			madxartwork_ASSETS_URL . 'lib/perfect-scrollbar/js/perfect-scrollbar' . $suffix . '.js',
			[],
			'1.4.0',
			true
		);

		wp_register_script(
			'jquery-easing',
			madxartwork_ASSETS_URL . 'lib/jquery-easing/jquery-easing' . $suffix . '.js',
			[
				'jquery',
			],
			'1.3.2',
			true
		);

		wp_register_script(
			'nprogress',
			madxartwork_ASSETS_URL . 'lib/nprogress/nprogress' . $suffix . '.js',
			[],
			'0.2.0',
			true
		);

		wp_register_script(
			'tipsy',
			madxartwork_ASSETS_URL . 'lib/tipsy/tipsy' . $suffix . '.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);

		wp_register_script(
			'jquery-madxartwork-select2',
			madxartwork_ASSETS_URL . 'lib/e-select2/js/e-select2.full' . $suffix . '.js',
			[
				'jquery',
			],
			'4.0.6-rc.1',
			true
		);

		wp_register_script(
			'flatpickr',
			madxartwork_ASSETS_URL . 'lib/flatpickr/flatpickr' . $suffix . '.js',
			[
				'jquery',
			],
			'1.12.0',
			true
		);

		wp_register_script(
			'ace',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ace.js',
			[],
			'1.2.5',
			true
		);

		wp_register_script(
			'ace-language-tools',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ext-language_tools.js',
			[
				'ace',
			],
			'1.2.5',
			true
		);

		wp_register_script(
			'jquery-hover-intent',
			madxartwork_ASSETS_URL . 'lib/jquery-hover-intent/jquery-hover-intent' . $suffix . '.js',
			[],
			'1.0.0',
			true
		);

		wp_register_script(
			'nouislider',
			madxartwork_ASSETS_URL . 'lib/nouislider/nouislider' . $suffix . '.js',
			[],
			'13.0.0',
			true
		);

		wp_register_script(
			'madxartwork-editor',
			madxartwork_ASSETS_URL . 'js/editor' . $suffix . '.js',
			[
				'madxartwork-common',
				'madxartwork-editor-modules',
				'wp-auth-check',
				'jquery-ui-sortable',
				'jquery-ui-resizable',
				'perfect-scrollbar',
				'nprogress',
				'tipsy',
				'imagesloaded',
				'heartbeat',
				'jquery-madxartwork-select2',
				'flatpickr',
				'ace',
				'ace-language-tools',
				'jquery-hover-intent',
				'nouislider',
			],
			madxartwork_VERSION,
			true
		);

		/**
		 * Before editor enqueue scripts.
		 *
		 * Fires before madxartwork editor scripts are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/before_enqueue_scripts' );

		$document = Plugin::$instance->documents->get_doc_or_auto_save( $this->_post_id );

		// Get document data *after* the scripts hook - so plugins can run compatibility before get data, but *before* enqueue the editor script - so elements can enqueue their own scripts that depended in editor script.
		$editor_data = $document->get_elements_raw_data( null, true );

		// Tweak for WP Admin menu icons
		wp_print_styles( 'editor-buttons' );

		$locked_user = $this->get_locked_user( $this->_post_id );

		if ( $locked_user ) {
			$locked_user = $locked_user->display_name;
		}

		$page_title_selector = get_option( 'madxartwork_page_title_selector' );

		if ( empty( $page_title_selector ) ) {
			$page_title_selector = 'h1.entry-title';
		}

		$post_type_object = get_post_type_object( $document->get_main_post()->post_type );
		$current_user_can_publish = current_user_can( $post_type_object->cap->publish_posts );

		$config = [
			'version' => madxartwork_VERSION,
			'home_url' => home_url(),
			'data' => $editor_data,
			'document' => $document->get_config(),
			'autosave_interval' => AUTOSAVE_INTERVAL,
			'current_user_can_publish' => $current_user_can_publish,
			'tabs' => $plugin->controls_manager->get_tabs(),
			'controls' => $plugin->controls_manager->get_controls_data(),
			'elements' => $plugin->elements_manager->get_element_types_config(),
			'widgets' => $plugin->widgets_manager->get_widget_types_config(),
			'schemes' => [
				'items' => $plugin->schemes_manager->get_registered_schemes_data(),
				'enabled_schemes' => Schemes_Manager::get_enabled_schemes(),
			],
			'icons' => [
				'libraries' => Icons_Manager::get_icon_manager_tabs_config(),
				'goProURL' => Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=icon-library-go-pro&utm_campaign=gopro&utm_medium=wp-dash' ),
			],
			'fa4_to_fa5_mapping_url' => madxartwork_ASSETS_URL . 'lib/font-awesome/migration/mapping.js',
			'default_schemes' => $plugin->schemes_manager->get_schemes_defaults(),
			'settings' => SettingsManager::get_settings_managers_config(),
			'system_schemes' => $plugin->schemes_manager->get_system_schemes(),
			'wp_editor' => $this->get_wp_editor_config(),
			'settings_page_link' => Settings::get_url(),
			'tools_page_link' => Tools::get_url(),
			'madxartwork_site' => 'https://go.madxartwork.com/about-madxartwork/',
			'docs_madxartwork_site' => 'https://go.madxartwork.com/docs/',
			'help_the_content_url' => 'https://go.madxartwork.com/the-content-missing/',
			'help_right_click_url' => 'https://go.madxartwork.com/meet-right-click/',
			'help_flexbox_bc_url' => 'https://go.madxartwork.com/flexbox-layout-bc/',
			'additional_shapes' => Shapes::get_additional_shapes_for_config(),
			'locked_user' => $locked_user,
			'user' => [
				'restrictions' => $plugin->role_manager->get_user_restrictions_array(),
				'is_administrator' => current_user_can( 'manage_options' ),
				'introduction' => User::get_introduction_meta(),
			],
			'preview' => [
				'help_preview_error_url' => 'https://go.madxartwork.com/preview-not-loaded/',
				'help_preview_http_error_url' => 'https://go.madxartwork.com/preview-not-loaded/#permissions',
				'help_preview_http_error_500_url' => 'https://go.madxartwork.com/500-error/',
				'debug_data' => Loading_Inspection_Manager::instance()->run_inspections(),
			],
			'locale' => get_locale(),
			'rich_editing_enabled' => filter_var( get_user_meta( get_current_user_id(), 'rich_editing', true ), FILTER_VALIDATE_BOOLEAN ),
			'page_title_selector' => $page_title_selector,
			'tinymceHasCustomConfig' => class_exists( 'Tinymce_Advanced' ),
			'inlineEditing' => Plugin::$instance->widgets_manager->get_inline_editing_config(),
			'dynamicTags' => Plugin::$instance->dynamic_tags->get_config(),
			'editButtons' => get_option( 'madxartwork_edit_buttons' ),
			'i18n' => [
				'madxartwork' => __( 'madxartwork', 'madxartwork' ),
				'delete' => __( 'Delete', 'madxartwork' ),
				'cancel' => __( 'Cancel', 'madxartwork' ),
				'got_it' => __( 'Got It', 'madxartwork' ),
				/* translators: %s: Element type. */
				'add_element' => __( 'Add %s', 'madxartwork' ),
				/* translators: %s: Element name. */
				'edit_element' => __( 'Edit %s', 'madxartwork' ),
				/* translators: %s: Element type. */
				'duplicate_element' => __( 'Duplicate %s', 'madxartwork' ),
				/* translators: %s: Element type. */
				'delete_element' => __( 'Delete %s', 'madxartwork' ),
				'flexbox_attention_header' => __( 'Note: Flexbox Changes', 'madxartwork' ),
				'flexbox_attention_message' => __( 'madxartwork 2.5 introduces key changes to the layout using CSS Flexbox. Your existing pages might have been affected, please review your page before publishing.', 'madxartwork' ),

				// Menu.
				'about_madxartwork' => __( 'About madxartwork', 'madxartwork' ),
				'color_picker' => __( 'Color Picker', 'madxartwork' ),
				'madxartwork_settings' => __( 'Dashboard Settings', 'madxartwork' ),
				'global_colors' => __( 'Default Colors', 'madxartwork' ),
				'global_fonts' => __( 'Default Fonts', 'madxartwork' ),
				'global_style' => __( 'Style', 'madxartwork' ),
				'global_settings' => __( 'Global Settings', 'madxartwork' ),
				'settings' => __( 'Settings', 'madxartwork' ),
				'go_to' => __( 'Go To', 'madxartwork' ),
				'view_page' => __( 'View Page', 'madxartwork' ),
				'exit_to_dashboard' => __( 'Exit To Dashboard', 'madxartwork' ),

				// Elements.
				'inner_section' => __( 'Inner Section', 'madxartwork' ),

				// Control Order.
				'asc' => __( 'Ascending order', 'madxartwork' ),
				'desc' => __( 'Descending order', 'madxartwork' ),

				// Clear Page.
				'clear_page' => __( 'Delete All Content', 'madxartwork' ),
				'dialog_confirm_clear_page' => __( 'Attention: We are going to DELETE ALL CONTENT from this page. Are you sure you want to do that?', 'madxartwork' ),

				// Enable SVG uploads.
				'enable_svg' => __( 'Enable SVG Uploads', 'madxartwork' ),
				'dialog_confirm_enable_svg' => __( 'Before you enable SVG upload, note that SVG files include a security risk. madxartwork does run a process to remove possible malicious code, but there is still risk involved when using such files.', 'madxartwork' ),

				// Enable fontawesome 5 if needed.
				'enable_fa5' => __( 'madxartwork\'s New Icon Library', 'madxartwork' ),
				'dialog_confirm_enable_fa5' => __( 'madxartwork v2.6 includes an upgrade from Font Awesome 4 to 5. In order to continue using icons, be sure to click "Upgrade".', 'madxartwork' ) . ' <a href="https://go.madxartwork.com/fontawesome-migration/" target="_blank">' . __( 'Learn More', 'madxartwork' ) . '</a>',

				// Panel Preview Mode.
				'back_to_editor' => __( 'Show Panel', 'madxartwork' ),
				'preview' => __( 'Hide Panel', 'madxartwork' ),

				// Inline Editing.
				'type_here' => __( 'Type Here', 'madxartwork' ),

				// Library.
				'an_error_occurred' => __( 'An error occurred', 'madxartwork' ),
				'category' => __( 'Category', 'madxartwork' ),
				'delete_template' => __( 'Delete Template', 'madxartwork' ),
				'delete_template_confirm' => __( 'Are you sure you want to delete this template?', 'madxartwork' ),
				'import_template_dialog_header' => __( 'Import Document Settings', 'madxartwork' ),
				'import_template_dialog_message' => __( 'Do you want to also import the document settings of the template?', 'madxartwork' ),
				'import_template_dialog_message_attention' => __( 'Attention: Importing may override previous settings.', 'madxartwork' ),
				'library' => __( 'Library', 'madxartwork' ),
				'no' => __( 'No', 'madxartwork' ),
				'page' => __( 'Page', 'madxartwork' ),
				/* translators: %s: Template type. */
				'save_your_template' => __( 'Save Your %s to Library', 'madxartwork' ),
				'save_your_template_description' => __( 'Your designs will be available for export and reuse on any page or website', 'madxartwork' ),
				'section' => __( 'Section', 'madxartwork' ),
				'templates_empty_message' => __( 'This is where your templates should be. Design it. Save it. Reuse it.', 'madxartwork' ),
				'templates_empty_title' => __( 'Haven’t Saved Templates Yet?', 'madxartwork' ),
				'templates_no_favorites_message' => __( 'You can mark any pre-designed template as a favorite.', 'madxartwork' ),
				'templates_no_favorites_title' => __( 'No Favorite Templates', 'madxartwork' ),
				'templates_no_results_message' => __( 'Please make sure your search is spelled correctly or try a different words.', 'madxartwork' ),
				'templates_no_results_title' => __( 'No Results Found', 'madxartwork' ),
				'templates_request_error' => __( 'The following error(s) occurred while processing the request:', 'madxartwork' ),
				'yes' => __( 'Yes', 'madxartwork' ),
				'blocks' => __( 'Blocks', 'madxartwork' ),
				'pages' => __( 'Pages', 'madxartwork' ),
				'my_templates' => __( 'My Templates', 'madxartwork' ),

				// Incompatible Device.
				'device_incompatible_header' => __( 'Your browser isn\'t compatible', 'madxartwork' ),
				'device_incompatible_message' => __( 'Your browser isn\'t compatible with all of madxartwork\'s editing features. We recommend you switch to another browser like Chrome or Firefox.', 'madxartwork' ),
				'proceed_anyway' => __( 'Proceed Anyway', 'madxartwork' ),

				// Preview not loaded.
				'learn_more' => __( 'Learn More', 'madxartwork' ),
				'preview_el_not_found_header' => __( 'Sorry, the content area was not found in your page.', 'madxartwork' ),
				'preview_el_not_found_message' => __( 'You must call \'the_content\' function in the current template, in order for madxartwork to work on this page.', 'madxartwork' ),

				// Gallery.
				'delete_gallery' => __( 'Reset Gallery', 'madxartwork' ),
				'dialog_confirm_gallery_delete' => __( 'Are you sure you want to reset this gallery?', 'madxartwork' ),
				/* translators: %s: The number of images. */
				'gallery_images_selected' => __( '%s Images Selected', 'madxartwork' ),
				'gallery_no_images_selected' => __( 'No Images Selected', 'madxartwork' ),
				'insert_media' => __( 'Insert Media', 'madxartwork' ),

				// Take Over.
				/* translators: %s: User name. */
				'dialog_user_taken_over' => __( '%s has taken over and is currently editing. Do you want to take over this page editing?', 'madxartwork' ),
				'go_back' => __( 'Go Back', 'madxartwork' ),
				'take_over' => __( 'Take Over', 'madxartwork' ),

				// Revisions.
				/* translators: %s: Template type. */
				'dialog_confirm_delete' => __( 'Are you sure you want to remove this %s?', 'madxartwork' ),

				// Saver.
				'before_unload_alert' => __( 'Please note: All unsaved changes will be lost.', 'madxartwork' ),
				'published' => __( 'Published', 'madxartwork' ),
				'publish' => __( 'Publish', 'madxartwork' ),
				'save' => __( 'Save', 'madxartwork' ),
				'saved' => __( 'Saved', 'madxartwork' ),
				'update' => __( 'Update', 'madxartwork' ),
				'enable' => __( 'Enable', 'madxartwork' ),
				'submit' => __( 'Submit', 'madxartwork' ),
				'working_on_draft_notification' => __( 'This is just a draft. Play around and when you\'re done - click update.', 'madxartwork' ),
				'keep_editing' => __( 'Keep Editing', 'madxartwork' ),
				'have_a_look' => __( 'Have a look', 'madxartwork' ),
				'view_all_revisions' => __( 'View All Revisions', 'madxartwork' ),
				'dismiss' => __( 'Dismiss', 'madxartwork' ),
				'saving_disabled' => __( 'Saving has been disabled until you’re reconnected.', 'madxartwork' ),

				// Ajax
				'server_error' => __( 'Server Error', 'madxartwork' ),
				'server_connection_lost' => __( 'Connection Lost', 'madxartwork' ),
				'unknown_error' => __( 'Unknown Error', 'madxartwork' ),

				// Context Menu
				'duplicate' => __( 'Duplicate', 'madxartwork' ),
				'copy' => __( 'Copy', 'madxartwork' ),
				'paste' => __( 'Paste', 'madxartwork' ),
				'copy_style' => __( 'Copy Style', 'madxartwork' ),
				'paste_style' => __( 'Paste Style', 'madxartwork' ),
				'reset_style' => __( 'Reset Style', 'madxartwork' ),
				'save_as_global' => __( 'Save as a Global', 'madxartwork' ),
				'save_as_block' => __( 'Save as Template', 'madxartwork' ),
				'new_column' => __( 'Add New Column', 'madxartwork' ),
				'copy_all_content' => __( 'Copy All Content', 'madxartwork' ),
				'delete_all_content' => __( 'Delete All Content', 'madxartwork' ),
				'navigator' => __( 'Navigator', 'madxartwork' ),

				// Right Click Introduction
				'meet_right_click_header' => __( 'Meet Right Click', 'madxartwork' ),
				'meet_right_click_message' => __( 'Now you can access all editing actions using right click.', 'madxartwork' ),

				// Hotkeys screen
				'keyboard_shortcuts' => __( 'Keyboard Shortcuts', 'madxartwork' ),

				// Deprecated Control
				'deprecated_notice' => __( 'The <strong>%1$s</strong> widget has been deprecated since %2$s %3$s.', 'madxartwork' ),
				'deprecated_notice_replacement' => __( 'It has been replaced by <strong>%1$s</strong>.', 'madxartwork' ),
				'deprecated_notice_last' => __( 'Note that %1$s will be completely removed once %2$s %3$s is released.', 'madxartwork' ),

				//Preview Debug
				'preview_debug_link_text' => __( 'Click here for preview debug', 'madxartwork' ),

				'icon_library' => __( 'Icon Library', 'madxartwork' ),
				'my_libraries' => __( 'My Libraries', 'madxartwork' ),
				'upload' => __( 'Upload', 'madxartwork' ),
				'icons_promotion' => __( 'Become a Pro user to upload unlimited font icon folders to your website.', 'madxartwork' ),
				'go_pro' => __( 'Go Pro', 'madxartwork' ),
				'custom_positioning' => __( 'Custom Positioning', 'madxartwork' ),

				// TODO: Remove.
				'autosave' => __( 'Autosave', 'madxartwork' ),
				'madxartwork_docs' => __( 'Documentation', 'madxartwork' ),
				'reload_page' => __( 'Reload Page', 'madxartwork' ),
				'session_expired_header' => __( 'Timeout', 'madxartwork' ),
				'session_expired_message' => __( 'Your session has expired. Please reload the page to continue editing.', 'madxartwork' ),
				'soon' => __( 'Soon', 'madxartwork' ),
				'unknown_value' => __( 'Unknown Value', 'madxartwork' ),
			],
		];

		$localized_settings = [];

		/**
		 * Localize editor settings.
		 *
		 * Filters the editor localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $localized_settings Localized settings.
		 * @param int   $post_id            The ID of the current post being edited.
		 */
		$localized_settings = apply_filters( 'madxartwork/editor/localize_settings', $localized_settings, $this->_post_id );

		if ( ! empty( $localized_settings ) ) {
			$config = array_replace_recursive( $config, $localized_settings );
		}

		Utils::print_js_config( 'madxartwork-editor', 'madxartworkConfig', $config );

		wp_enqueue_script( 'madxartwork-editor' );

		$plugin->controls_manager->enqueue_control_scripts();

		/**
		 * After editor enqueue scripts.
		 *
		 * Fires after madxartwork editor scripts are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/after_enqueue_scripts' );
	}

	/**
	 * Enqueue styles.
	 *
	 * Registers all the editor styles and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		/**
		 * Before editor enqueue styles.
		 *
		 * Fires before madxartwork editor styles are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/before_enqueue_styles' );

		$suffix = Utils::is_script_debug() ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'font-awesome',
			madxartwork_ASSETS_URL . 'lib/font-awesome/css/font-awesome' . $suffix . '.css',
			[],
			'4.7.0'
		);

		wp_register_style(
			'madxartwork-select2',
			madxartwork_ASSETS_URL . 'lib/e-select2/css/e-select2' . $suffix . '.css',
			[],
			'4.0.6-rc.1'
		);

		wp_register_style(
			'google-font-roboto',
			'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700',
			[],
			madxartwork_VERSION
		);

		wp_register_style(
			'flatpickr',
			madxartwork_ASSETS_URL . 'lib/flatpickr/flatpickr' . $suffix . '.css',
			[],
			'1.12.0'
		);

		wp_register_style(
			'madxartwork-editor',
			madxartwork_ASSETS_URL . 'css/editor' . $direction_suffix . $suffix . '.css',
			[
				'madxartwork-common',
				'madxartwork-select2',
				'madxartwork-icons',
				'wp-auth-check',
				'google-font-roboto',
				'flatpickr',
			],
			madxartwork_VERSION
		);

		wp_enqueue_style( 'madxartwork-editor' );

		if ( Responsive::has_custom_breakpoints() ) {
			$breakpoints = Responsive::get_breakpoints();

			wp_add_inline_style( 'madxartwork-editor', '.madxartwork-device-tablet #madxartwork-preview-responsive-wrapper { width: ' . $breakpoints['md'] . 'px; }' );
		}

		/**
		 * After editor enqueue styles.
		 *
		 * Fires after madxartwork editor styles are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/after_enqueue_styles' );
	}

	/**
	 * Get WordPress editor config.
	 *
	 * Config the default WordPress editor with custom settings for madxartwork use.
	 *
	 * @since 1.9.0
	 * @access private
	 */
	private function get_wp_editor_config() {
		// Remove all TinyMCE plugins.
		remove_all_filters( 'mce_buttons', 10 );
		remove_all_filters( 'mce_external_plugins', 10 );

		if ( ! class_exists( '\_WP_Editors', false ) ) {
			require ABSPATH . WPINC . '/class-wp-editor.php';
		}

		// WordPress 4.8 and higher
		if ( method_exists( '\_WP_Editors', 'print_tinymce_scripts' ) ) {
			\_WP_Editors::print_default_editor_scripts();
			\_WP_Editors::print_tinymce_scripts();
		}
		ob_start();

		wp_editor(
			'%%EDITORCONTENT%%',
			'madxartworkwpeditor',
			[
				'editor_class' => 'madxartwork-wp-editor',
				'editor_height' => 250,
				'drag_drop_upload' => true,
			]
		);

		$config = ob_get_clean();

		// Don't call \_WP_Editors methods again
		remove_action( 'admin_print_footer_scripts', [ '_WP_Editors', 'editor_js' ], 50 );
		remove_action( 'admin_print_footer_scripts', [ '_WP_Editors', 'print_default_editor_scripts' ], 45 );

		\_WP_Editors::editor_js();

		return $config;
	}

	/**
	 * Editor head trigger.
	 *
	 * Fires the 'madxartwork/editor/wp_head' action in the head tag in madxartwork
	 * editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_head_trigger() {
		/**
		 * madxartwork editor head.
		 *
		 * Fires on madxartwork editor head tag.
		 *
		 * Used to prints scripts or any other data in the head tag.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/wp_head' );
	}

	/**
	 * Add editor template.
	 *
	 * Registers new editor templates.
	 *
	 * @since 1.0.0
	 * @deprecated 2.3.0 Use `Plugin::$instance->common->add_template()`
	 * @access public
	 *
	 * @param string $template Can be either a link to template file or template
	 *                         HTML content.
	 * @param string $type     Optional. Whether to handle the template as path
	 *                         or text. Default is `path`.
	 */
	public function add_editor_template( $template, $type = 'path' ) {
		 _deprecated_function( __METHOD__, '2.3.0', 'Plugin::$instance->common->add_template()' );

		$common = Plugin::$instance->common;

		if ( $common ) {
			Plugin::$instance->common->add_template( $template, $type );
		}
	}

	/**
	 * WP footer.
	 *
	 * Prints madxartwork editor with all the editor templates, and render controls,
	 * widgets and content elements.
	 *
	 * Fired by `wp_footer` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_footer() {
		$plugin = Plugin::$instance;

		$plugin->controls_manager->render_controls();
		$plugin->widgets_manager->render_widgets_content();
		$plugin->elements_manager->render_elements_content();

		$plugin->schemes_manager->print_schemes_templates();

		$plugin->dynamic_tags->print_templates();

		$this->init_editor_templates();

		/**
		 * madxartwork editor footer.
		 *
		 * Fires on madxartwork editor before closing the body tag.
		 *
		 * Used to prints scripts or any other HTML before closing the body tag.
		 *
		 * @since 1.0.0
		 */
		do_action( 'madxartwork/editor/footer' );
	}

	/**
	 * Set edit mode.
	 *
	 * Used to update the edit mode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool $edit_mode Whether the edit mode is active.
	 */
	public function set_edit_mode( $edit_mode ) {
		$this->_is_edit_mode = $edit_mode;
	}

	/**
	 * Editor constructor.
	 *
	 * Initializing madxartwork editor and redirect from old URL structure of
	 * madxartwork editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->notice_bar = new Notice_Bar();

		add_action( 'admin_action_madxartwork', [ $this, 'init' ] );
		add_action( 'template_redirect', [ $this, 'redirect_to_new_url' ] );

		// Handle autocomplete feature for URL control.
		add_filter( 'wp_link_query_args', [ $this, 'filter_wp_link_query_args' ] );
		add_filter( 'wp_link_query', [ $this, 'filter_wp_link_query' ] );
	}

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function filter_wp_link_query_args( $query ) {
		$library_cpt_key = array_search( Source_Local::CPT, $query['post_type'], true );
		if ( false !== $library_cpt_key ) {
			unset( $query['post_type'][ $library_cpt_key ] );
		}

		return $query;
	}

	/**
	 * @since 2.2.0
	 * @access public
	 */
	public function filter_wp_link_query( $results ) {
		if ( isset( $_POST['editor'] ) && 'madxartwork' === $_POST['editor'] ) {
			$post_type_object = get_post_type_object( 'post' );
			$post_label = $post_type_object->labels->singular_name;

			foreach ( $results as & $result ) {
				if ( 'post' === get_post_type( $result['ID'] ) ) {
					$result['info'] = $post_label;
				}
			}
		}

		return $results;
	}

	/**
	 * Create nonce.
	 *
	 * If the user has edit capabilities, it creates a cryptographic token to
	 * give him access to madxartwork editor.
	 *
	 * @since 1.8.1
	 * @since 1.8.7 The `$post_type` parameter was introduces.
	 * @deprecated 2.3.0 Use `Plugin::$instance->common->get_component( 'ajax' )->create_nonce()` instead
	 * @access public
	 *
	 * @param string $post_type The post type to check capabilities.
	 *
	 * @return null|string The nonce token, or `null` if the user has no edit
	 *                     capabilities.
	 */
	public function create_nonce( $post_type ) {
		 _deprecated_function( __METHOD__, '2.3.0', 'Plugin::$instance->common->get_component( \'ajax\' )->create_nonce()' );

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		return $ajax->create_nonce();
	}

	/**
	 * Verify nonce.
	 *
	 * The user is given an amount of time to use the token, so therefore, since
	 * the user ID and `$action` remain the same, the independent variable is
	 * the time.
	 *
	 * @since 1.8.1
	 * @deprecated 2.3.0
	 * @access public
	 *
	 * @param string $nonce Nonce to verify.
	 *
	 * @return false|int If the nonce is invalid it returns `false`. If the
	 *                   nonce is valid and generated between 0-12 hours ago it
	 *                   returns `1`. If the nonce is valid and generated
	 *                   between 12-24 hours ago it returns `2`.
	 */
	public function verify_nonce( $nonce ) {
		 _deprecated_function( __METHOD__, '2.3.0', 'wp_verify_nonce()' );

		return wp_verify_nonce( $nonce );
	}

	/**
	 * Verify request nonce.
	 *
	 * Whether the request nonce verified or not.
	 *
	 * @since 1.8.1
	 * @deprecated 2.3.0 Use `Plugin::$instance->common->get_component( 'ajax' )->verify_request_nonce()` instead
	 * @access public
	 *
	 * @return bool True if request nonce verified, False otherwise.
	 */
	public function verify_request_nonce() {
		 _deprecated_function( __METHOD__, '2.3.0', 'Plugin::$instance->common->get_component( \'ajax\' )->verify_request_nonce()' );

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		return $ajax->verify_request_nonce();
	}

	/**
	 * Verify ajax nonce.
	 *
	 * Verify request nonce and send a JSON request, if not verified returns an
	 * error.
	 *
	 * @since 1.9.0
	 * @deprecated 2.3.0
	 * @access public
	 */
	public function verify_ajax_nonce() {
		 _deprecated_function( __METHOD__, '2.3.0' );

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		if ( ! $ajax->verify_request_nonce() ) {
			wp_send_json_error( new \WP_Error( 'token_expired', 'Nonce token expired.' ) );
		}
	}

	/**
	 * Init editor templates.
	 *
	 * Initialize default madxartwork templates used in the editor panel.
	 *
	 * @since 1.7.0
	 * @access private
	 */
	private function init_editor_templates() {
		$template_names = [
			'global',
			'panel',
			'panel-elements',
			'repeater',
			'templates',
			'navigator',
			'hotkeys',
		];

		foreach ( $template_names as $template_name ) {
			Plugin::$instance->common->add_template( madxartwork_PATH . "includes/editor-templates/$template_name.php" );
		}
	}
}
