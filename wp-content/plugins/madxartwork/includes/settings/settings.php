<?php
namespace madxartwork;

use madxartwork\Core\Responsive\Responsive;
use madxartwork\Core\Settings\General\Manager as General_Settings_Manager;
use madxartwork\Core\Settings\Manager;
use madxartwork\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork "Settings" page in WordPress Dashboard.
 *
 * madxartwork settings page handler class responsible for creating and displaying
 * madxartwork "Settings" page in WordPress dashboard.
 *
 * @since 1.0.0
 */
class Settings extends Settings_Page {

	/**
	 * Settings page ID for madxartwork settings.
	 */
	const PAGE_ID = 'madxartwork';

	/**
	 * Go Pro menu priority.
	 */
	const MENU_PRIORITY_GO_PRO = 502;

	/**
	 * Settings page field for update time.
	 */
	const UPDATE_TIME_FIELD = '_madxartwork_settings_update_time';

	/**
	 * Settings page general tab slug.
	 */
	const TAB_GENERAL = 'general';

	/**
	 * Settings page style tab slug.
	 */
	const TAB_STYLE = 'style';

	/**
	 * Settings page integrations tab slug.
	 */
	const TAB_INTEGRATIONS = 'integrations';

	/**
	 * Settings page advanced tab slug.
	 */
	const TAB_ADVANCED = 'advanced';

	/**
	 * Register admin menu.
	 *
	 * Add new madxartwork Settings admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		global $menu;

		$menu[] = [ '', 'read', 'separator-madxartwork', '', 'wp-menu-separator madxartwork' ]; // WPCS: override ok.

		add_menu_page(
			__( 'madxartwork', 'madxartwork' ),
			__( 'madxartwork', 'madxartwork' ),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ],
			'',
			'58.5'
		);
	}

	/**
	 * Reorder the madxartwork menu items in admin.
	 * Based on WC.
	 *
	 * @since 2.4.0
	 *
	 * @param array $menu_order Menu order.
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array.
		$madxartwork_menu_order = [];

		// Get the index of our custom separator.
		$madxartwork_separator = array_search( 'separator-madxartwork', $menu_order, true );

		// Get index of library menu.
		$madxartwork_library = array_search( Source_Local::ADMIN_MENU_SLUG, $menu_order, true );

		// Loop through menu order and do some rearranging.
		foreach ( $menu_order as $index => $item ) {
			if ( 'madxartwork' === $item ) {
				$madxartwork_menu_order[] = 'separator-madxartwork';
				$madxartwork_menu_order[] = $item;
				$madxartwork_menu_order[] = Source_Local::ADMIN_MENU_SLUG;

				unset( $menu_order[ $madxartwork_separator ] );
				unset( $menu_order[ $madxartwork_library ] );
			} elseif ( ! in_array( $item, [ 'separator-madxartwork' ], true ) ) {
				$madxartwork_menu_order[] = $item;
			}
		}

		// Return order.
		return $madxartwork_menu_order;
	}

	/**
	 * Register madxartwork Pro sub-menu.
	 *
	 * Add new madxartwork Pro sub-menu under the main madxartwork menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_pro_menu() {
		add_submenu_page(
			self::PAGE_ID,
			__( 'Custom Fonts', 'madxartwork' ),
			__( 'Custom Fonts', 'madxartwork' ),
			'manage_options',
			'madxartwork_custom_fonts',
			[ $this, 'madxartwork_custom_fonts' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			__( 'Custom Icons', 'madxartwork' ),
			__( 'Custom Icons', 'madxartwork' ),
			'manage_options',
			'madxartwork_custom_icons',
			[ $this, 'madxartwork_custom_icons' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			'',
			'<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . __( 'Go Pro', 'madxartwork' ),
			'manage_options',
			'go_madxartwork_pro',
			[ $this, 'handle_external_redirects' ]
		);

		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, __( 'Theme Templates', 'madxartwork' ), __( 'Theme Builder', 'madxartwork' ), 'manage_options', 'theme_templates', [ $this, 'madxartwork_theme_templates' ] );
		add_submenu_page( Source_Local::ADMIN_MENU_SLUG, __( 'Popups', 'madxartwork' ), __( 'Popups', 'madxartwork' ), 'manage_options', 'popup_templates', [ $this, 'madxartwork_popups' ] );
	}

	/**
	 * Register madxartwork knowledge base sub-menu.
	 *
	 * Add new madxartwork knowledge base sub-menu under the main madxartwork menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function register_knowledge_base_menu() {
		add_submenu_page(
			self::PAGE_ID,
			'',
			__( 'Getting Started', 'madxartwork' ),
			'manage_options',
			'madxartwork-getting-started',
			[ $this, 'madxartwork_getting_started' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			'',
			__( 'Get Help', 'madxartwork' ),
			'manage_options',
			'go_knowledge_base_site',
			[ $this, 'handle_external_redirects' ]
		);
	}

	/**
	 * Go madxartwork Pro.
	 *
	 * Redirect the madxartwork Pro page the clicking the madxartwork Pro menu link.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 2.0.3
	 * @access public
	 */
	public function handle_external_redirects() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		if ( 'go_madxartwork_pro' === $_GET['page'] ) {
			wp_redirect( Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=wp-menu&utm_campaign=gopro&utm_medium=wp-dash' ) );
			die;
		}

		if ( 'go_knowledge_base_site' === $_GET['page'] ) {
			wp_redirect( 'https://go.madxartwork.com/docs-admin-menu/' );
			die;
		}
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the getting started page.
	 *
	 * @since 2.2.0
	 * @access public
	 */
	public function madxartwork_getting_started() {
		if ( User::is_current_user_can_edit_post_type( 'page' ) ) {
			$create_new_label = __( 'Create Your First Page', 'madxartwork' );
			$create_new_cpt = 'page';
		} elseif ( User::is_current_user_can_edit_post_type( 'post' ) ) {
			$create_new_label = __( 'Create Your First Post', 'madxartwork' );
			$create_new_cpt = 'post';
		}

		?>
		<div class="wrap">
			<div class="e-getting-started">
				<div class="e-getting-started__box postbox">
					<div class="e-getting-started__header">
						<div class="e-getting-started__title">
							<div class="e-logo-wrapper"><i class="eicon-madxartwork"></i></div>

							<?php echo __( 'Getting Started', 'madxartwork' ); ?>
						</div>
						<a class="e-getting-started__skip" href="<?php echo esc_url( admin_url() ); ?>">
							<i class="eicon-close" aria-hidden="true" title="<?php esc_attr_e( 'Skip', 'madxartwork' ); ?>"></i>
							<span class="madxartwork-screen-only"><?php echo __( 'Skip', 'madxartwork' ); ?></span>
						</a>
					</div>
					<div class="e-getting-started__content">
						<div class="e-getting-started__content--narrow">
							<h2><?php echo __( 'Welcome to madxartwork', 'madxartwork' ); ?></h2>
							<p><?php echo __( 'We recommend you watch this 2 minute getting started video, and then try the editor yourself by dragging and dropping elements to create your first page.', 'madxartwork' ); ?></p>
						</div>

						<div class="e-getting-started__video">
							<iframe width="620" height="350" src="https://www.youtube-nocookie.com/embed/nZlgNmbC-Cw?rel=0&amp;controls=1&amp;modestbranding=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>

						<div class="e-getting-started__actions e-getting-started__content--narrow">
							<?php if ( ! empty( $create_new_cpt ) ) : ?>
							<a href="<?php echo esc_url( Utils::get_create_new_post_url( $create_new_cpt ) ); ?>" class="button button-primary button-hero"><?php echo esc_html( $create_new_label ); ?></a>
							<?php endif; ?>

							<a href="https://go.madxartwork.com/getting-started/" target="_blank" class="button button-secondary button-hero"><?php echo __( 'Get the Full Guide', 'madxartwork' ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the custom fonts page.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function madxartwork_custom_fonts() {
		?>
		<div class="wrap">
			<div class="madxartwork-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Add Your Custom Fonts', 'madxartwork' ); ?></h2>
				<p><?php echo __( 'Custom Fonts allows you to add your self-hosted fonts and use them on your madxartwork projects to create a unique brand language.', 'madxartwork' ); ?></p>
				<a class="madxartwork-button madxartwork-button-default madxartwork-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=wp-custom-fonts&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the custom icons page.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function madxartwork_custom_icons() {
		?>
		<div class="wrap">
			<div class="madxartwork-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Add Your Custom Icons', 'madxartwork' ); ?></h2>
				<p><?php echo __( 'Don\'t rely solely on the FontAwesome icons everyone else is using! Differentiate your website and your style with custom icons you can upload from your favorite icons source.', 'madxartwork' ); ?></p>
				<a class="madxartwork-button madxartwork-button-default madxartwork-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/pro/?utm_source=wp-custom-icons&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the Popups page.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function madxartwork_popups() {
		?>
		<div class="wrap">
			<div class="madxartwork-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Get Popup Builder', 'madxartwork' ); ?></h2>
				<p><?php echo __( 'Popup Builder lets you take advantage of all the amazing features in madxartwork, so you can build beautiful & highly converting popups. Go pro and start designing your popups today.', 'madxartwork' ); ?></p>
				<a class="madxartwork-button madxartwork-button-default madxartwork-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/popup-builder/?utm_source=popup-templates&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Display settings page.
	 *
	 * Output the content for the Theme Templates page.
	 *
	 * @since 2.4.0
	 * @access public
	 */
	public function madxartwork_theme_templates() {
		?>
		<div class="wrap">
			<div class="madxartwork-blank_state">
				<i class="eicon-nerd-chuckle"></i>
				<h2><?php echo __( 'Get Theme Builder', 'madxartwork' ); ?></h2>
				<p><?php echo __( 'Theme Builder is the industry leading all-in-one solution that lets you customize every part of your WordPress theme visually: Header, Footer, Single, Archive & WooCommerce.', 'madxartwork' ); ?></p>
				<a class="madxartwork-button madxartwork-button-default madxartwork-button-go-pro" target="_blank" href="<?php echo Utils::get_pro_link( 'https://madxartwork.com/theme-builder/?utm_source=theme-templates&utm_campaign=gopro&utm_medium=wp-dash' ); ?>"><?php echo __( 'Go Pro', 'madxartwork' ); ?></a>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * On admin init.
	 *
	 * Preform actions on WordPress admin initialization.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function on_admin_init() {
		$this->handle_external_redirects();

		// Save general settings in one list for a future usage
		$this->handle_general_settings_update();

		$this->maybe_remove_all_admin_notices();
	}

	/**
	 * Change "Settings" menu name.
	 *
	 * Update the name of the Settings admin menu from "madxartwork" to "Settings".
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_menu_change_name() {
		global $submenu;

		if ( isset( $submenu['madxartwork'] ) ) {
			// @codingStandardsIgnoreStart
			$submenu['madxartwork'][0][0] = __( 'Settings', 'madxartwork' );
			// @codingStandardsIgnoreEnd
		}
	}

	/**
	 * Update CSS print method.
	 *
	 * Clear post CSS cache.
	 *
	 * Fired by `add_option_madxartwork_css_print_method` and
	 * `update_option_madxartwork_css_print_method` actions.
	 *
	 * @since 1.7.5
	 * @access public
	 */
	public function update_css_print_method() {
		Plugin::$instance->files_manager->clear_cache();
	}

	/**
	 * Create tabs.
	 *
	 * Return the settings page tabs, sections and fields.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return array An array with the settings page tabs, sections and fields.
	 */
	protected function create_tabs() {
		$validations_class_name = __NAMESPACE__ . '\Settings_Validations';

		$default_breakpoints = Responsive::get_default_breakpoints();

		return [
			self::TAB_GENERAL => [
				'label' => __( 'General', 'madxartwork' ),
				'sections' => [
					'general' => [
						'fields' => [
							self::UPDATE_TIME_FIELD => [
								'full_field_id' => self::UPDATE_TIME_FIELD,
								'field_args' => [
									'type' => 'hidden',
								],
								'setting_args' => [ $validations_class_name, 'current_time' ],
							],
							'cpt_support' => [
								'label' => __( 'Post Types', 'madxartwork' ),
								'field_args' => [
									'type' => 'checkbox_list_cpt',
									'std' => [ 'page', 'post' ],
									'exclude' => [ 'attachment', 'madxartwork_library' ],
								],
								'setting_args' => [ $validations_class_name, 'checkbox_list' ],
							],
							'disable_color_schemes' => [
								'label' => __( 'Disable Default Colors', 'madxartwork' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'sub_desc' => __( 'Checking this box will disable madxartwork\'s Default Colors, and make madxartwork inherit the colors from your theme.', 'madxartwork' ),
								],
							],
							'disable_typography_schemes' => [
								'label' => __( 'Disable Default Fonts', 'madxartwork' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'sub_desc' => __( 'Checking this box will disable madxartwork\'s Default Fonts, and make madxartwork inherit the fonts from your theme.', 'madxartwork' ),
								],
							],
						],
					],
					'usage' => [
						'label' => __( 'Improve madxartwork', 'madxartwork' ),
						'fields' => [
							'allow_tracking' => [
								'label' => __( 'Usage Data Tracking', 'madxartwork' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'default' => '',
									'sub_desc' => __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'madxartwork' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://go.madxartwork.com/usage-data-tracking/', __( 'Learn more.', 'madxartwork' ) ),
								],
								'setting_args' => [ __NAMESPACE__ . '\Tracker', 'check_for_settings_optin' ],
							],
						],
					],
				],
			],
			self::TAB_STYLE => [
				'label' => __( 'Style', 'madxartwork' ),
				'sections' => [
					'style' => [
						'fields' => [
							'default_generic_fonts' => [
								'label' => __( 'Default Generic Fonts', 'madxartwork' ),
								'field_args' => [
									'type' => 'text',
									'std' => 'Sans-serif',
									'class' => 'medium-text',
									'desc' => __( 'The list of fonts used if the chosen font is not available.', 'madxartwork' ),
								],
							],
							'container_width' => [
								'label' => __( 'Content Width', 'madxartwork' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'min' => 300,
										'placeholder' => '1140',
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									'desc' => __( 'Sets the default width of the content area (Default: 1140)', 'madxartwork' ),
								],
							],
							'space_between_widgets' => [
								'label' => __( 'Space Between Widgets', 'madxartwork' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => '20',
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									'desc' => __( 'Sets the default space between widgets (Default: 20)', 'madxartwork' ),
								],
							],
							'stretched_section_container' => [
								'label' => __( 'Stretched Section Fit To', 'madxartwork' ),
								'field_args' => [
									'type' => 'text',
									'attributes' => [
										'placeholder' => 'body',
										'class' => 'medium-text',
									],
									'desc' => __( 'Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.', 'madxartwork' ),
								],
							],
							'page_title_selector' => [
								'label' => __( 'Page Title Selector', 'madxartwork' ),
								'field_args' => [
									'type' => 'text',
									'attributes' => [
										'placeholder' => 'h1.entry-title',
										'class' => 'medium-text',
									],
									'desc' => __( 'madxartwork lets you hide the page title. This works for themes that have "h1.entry-title" selector. If your theme\'s selector is different, please enter it above.', 'madxartwork' ),
								],
							],
							'viewport_lg' => [
								'label' => __( 'Tablet Breakpoint', 'madxartwork' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => $default_breakpoints['lg'],
										'min' => $default_breakpoints['md'] + 1,
										'max' => $default_breakpoints['xl'] - 1,
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									/* translators: %d: Breakpoint value */
									'desc' => sprintf( __( 'Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: %dpx).', 'madxartwork' ), $default_breakpoints['lg'] ),
								],
							],
							'viewport_md' => [
								'label' => __( 'Mobile Breakpoint', 'madxartwork' ),
								'field_args' => [
									'type' => 'number',
									'attributes' => [
										'placeholder' => $default_breakpoints['md'],
										'min' => $default_breakpoints['sm'] + 1,
										'max' => $default_breakpoints['lg'] - 1,
										'class' => 'medium-text',
									],
									'sub_desc' => 'px',
									/* translators: %d: Breakpoint value */
									'desc' => sprintf( __( 'Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: %dpx).', 'madxartwork' ), $default_breakpoints['md'] ),
								],
							],
							'global_image_lightbox' => [
								'label' => __( 'Image Lightbox', 'madxartwork' ),
								'field_args' => [
									'type' => 'checkbox',
									'value' => 'yes',
									'std' => 'yes',
									'sub_desc' => __( 'Open all image links in a lightbox popup window. The lightbox will automatically work on any link that leads to an image file.', 'madxartwork' ),
									'desc' => __( 'You can customize the lightbox design by going to: Top-left hamburger icon > Global Settings > Lightbox.', 'madxartwork' ),
								],
							],
						],
					],
				],
			],
			self::TAB_INTEGRATIONS => [
				'label' => __( 'Integrations', 'madxartwork' ),
				'sections' => [],
			],
			self::TAB_ADVANCED => [
				'label' => __( 'Advanced', 'madxartwork' ),
				'sections' => [
					'advanced' => [
						'fields' => [
							'css_print_method' => [
								'label' => __( 'CSS Print Method', 'madxartwork' ),
								'field_args' => [
									'class' => 'madxartwork_css_print_method',
									'type' => 'select',
									'options' => [
										'external' => __( 'External File', 'madxartwork' ),
										'internal' => __( 'Internal Embedding', 'madxartwork' ),
									],
									'desc' => '<div class="madxartwork-css-print-method-description" data-value="external" style="display: none">' . __( 'Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).', 'madxartwork' ) . '</div><div class="madxartwork-css-print-method-description" data-value="internal" style="display: none">' . __( 'Use internal CSS that is embedded in the head of the page. For troubleshooting server configuration conflicts and managing development environments.', 'madxartwork' ) . '</div>',
								],
							],
							'editor_break_lines' => [
								'label' => __( 'Switch Editor Loader Method', 'madxartwork' ),
								'field_args' => [
									'type' => 'select',
									'options' => [
										'' => __( 'Disable', 'madxartwork' ),
										1 => __( 'Enable', 'madxartwork' ),
									],
									'desc' => __( 'For troubleshooting server configuration conflicts.', 'madxartwork' ),
								],
							],
							'edit_buttons' => [
								'label' => __( 'Editing Handles', 'madxartwork' ),
								'field_args' => [
									'type' => 'select',
									'std' => '',
									'options' => [
										'' => __( 'Hide', 'madxartwork' ),
										'on' => __( 'Show', 'madxartwork' ),
									],
									'desc' => __( 'Show editing handles when hovering over the element edit button', 'madxartwork' ),
								],
							],
							'allow_svg' => [
								'label' => __( 'Enable SVG Uploads', 'madxartwork' ),
								'field_args' => [
									'type' => 'select',
									'std' => '',
									'options' => [
										'' => __( 'Disable', 'madxartwork' ),
										1 => __( 'Enable', 'madxartwork' ),
									],
									'desc' => __( 'Please note! Allowing uploads of any files (SVG included) is a potential security risk.', 'madxartwork' ) . '<br>' . __( 'madxartwork will try to sanitize the SVG files, removing potential malicious code and scripts.', 'madxartwork' ) . '<br>' . __( 'We recommend you only enable this feature if you understand the security risks involved.', 'madxartwork' ),
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Get settings page title.
	 *
	 * Retrieve the title for the settings page.
	 *
	 * @since 1.5.0
	 * @access protected
	 *
	 * @return string Settings page title.
	 */
	protected function get_page_title() {
		return __( 'madxartwork', 'madxartwork' );
	}

	/**
	 * Handle general settings update.
	 *
	 * Save general settings in one list for a future usage.
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function handle_general_settings_update() {
		if ( ! empty( $_POST['option_page'] ) && self::PAGE_ID === $_POST['option_page'] && ! empty( $_POST['action'] ) && 'update' === $_POST['action'] ) {
			check_admin_referer( 'madxartwork-options' );

			$saved_general_settings = get_option( General_Settings_Manager::META_KEY );

			if ( ! $saved_general_settings ) {
				$saved_general_settings = [];
			}

			$general_settings = Manager::get_settings_managers( 'general' )->get_model()->get_settings();

			foreach ( $general_settings as $setting_key => $setting ) {
				if ( ! empty( $_POST[ $setting_key ] ) ) {
					$pure_setting_key = str_replace( 'madxartwork_', '', $setting_key );

					$saved_general_settings[ $pure_setting_key ] = $_POST[ $setting_key ];
				}
			}

			update_option( General_Settings_Manager::META_KEY, $saved_general_settings );
		}
	}

	/**
	 * @since 2.2.0
	 * @access private
	 */
	private function maybe_remove_all_admin_notices() {
		$madxartwork_pages = [
			'madxartwork-getting-started',
			'madxartwork-role-manager',
			'madxartwork_custom_fonts',
			'madxartwork-license',
		];

		if ( empty( $_GET['page'] ) || ! in_array( $_GET['page'], $madxartwork_pages, true ) ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
	}

	/**
	 * Settings page constructor.
	 *
	 * Initializing madxartwork "Settings" page.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', [ $this, 'on_admin_init' ] );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 20 );
		add_action( 'admin_menu', [ $this, 'admin_menu_change_name' ], 200 );
		add_action( 'admin_menu', [ $this, 'register_pro_menu' ], self::MENU_PRIORITY_GO_PRO );
		add_action( 'admin_menu', [ $this, 'register_knowledge_base_menu' ], 501 );

		// Clear CSS Meta after change print method.
		add_action( 'add_option_madxartwork_css_print_method', [ $this, 'update_css_print_method' ] );
		add_action( 'update_option_madxartwork_css_print_method', [ $this, 'update_css_print_method' ] );

		add_filter( 'custom_menu_order', '__return_true' );
		add_filter( 'menu_order', [ $this, 'menu_order' ] );

		foreach ( Responsive::get_editable_breakpoints() as $breakpoint_key => $breakpoint ) {
			foreach ( [ 'add', 'update' ] as $action ) {
				add_action( "{$action}_option_madxartwork_viewport_{$breakpoint_key}", [ 'madxartwork\Core\Responsive\Responsive', 'compile_stylesheet_templates' ] );
			}
		}
	}
}
