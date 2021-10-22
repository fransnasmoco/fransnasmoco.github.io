<?php
namespace madxartworkPro\Modules\ShareButtons\Widgets;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Typography;
use madxartwork\Icons_Manager;
use madxartwork\Repeater;
use madxartworkPro\Base\Base_Widget;
use madxartworkPro\Modules\ShareButtons\Module;
use madxartwork\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Share_Buttons extends Base_Widget {

	private static $networks_class_dictionary = [
		'google' => 'fa fa-google-plus',
		'pocket' => 'fa fa-get-pocket',
		'email' => 'fa fa-envelope',
	];

	private static $networks_icon_mapping = [
		'google' => 'fab fa-google-plus-g',
		'pocket' => 'fab fa-get-pocket',
		'email' => 'fas fa-envelope',
		'print' => 'fas fa-print',
	];

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'madxartwork-icons-fa-solid',
				'madxartwork-icons-fa-brands',
			];
		}
		return [];
	}

	private static function get_network_class( $network_name ) {
		$prefix = 'fa ';
		if ( Icons_Manager::is_migration_allowed() ) {
			if ( isset( self::$networks_icon_mapping[ $network_name ] ) ) {
				return self::$networks_icon_mapping[ $network_name ];
			}
			$prefix = 'fab ';
		}
		if ( isset( self::$networks_class_dictionary[ $network_name ] ) ) {
			return self::$networks_class_dictionary[ $network_name ];
		}

		return $prefix . 'fa-' . $network_name;
	}

	public function get_name() {
		return 'share-buttons';
	}

	public function get_title() {
		return __( 'Share Buttons', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-share';
	}

	public function get_keywords() {
		return [ 'sharing', 'social', 'icon', 'button', 'like' ];
	}

	public function get_script_depends() {
		return [ 'social-share' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_buttons_content',
			[
				'label' => __( 'Share Buttons', 'madxartwork-pro' ),
			]
		);

		$repeater = new Repeater();

		$networks = Module::get_networks();

		$networks_names = array_keys( $networks );

		$repeater->add_control(
			'button',
			[
				'label' => __( 'Network', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_reduce( $networks_names, function( $options, $network_name ) use ( $networks ) {
					$options[ $network_name ] = $networks[ $network_name ]['title'];

					return $options;
				}, [] ),
				'default' => 'facebook',
			]
		);

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Custom Label', 'madxartwork-pro' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'share_buttons',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'button' => 'facebook',
					],
					[
						'button' => 'google',
					],
					[
						'button' => 'twitter',
					],
					[
						'button' => 'linkedin',
					],
				],
				'title_field' => '<i class="{{ madxartworkPro.modules.shareButtons.getNetworkClass( button ) }}" aria-hidden="true"></i> {{{ madxartworkPro.modules.shareButtons.getNetworkTitle( obj ) }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'icon-text' => 'Icon & Text',
					'icon' => 'Icon',
					'text' => 'Text',
				],
				'default' => 'icon-text',
				'separator' => 'before',
				'prefix_class' => 'madxartwork-share-buttons--view-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => __( 'Label', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'madxartwork-pro' ),
				'label_off' => __( 'Hide', 'madxartwork-pro' ),
				'default' => 'yes',
				'condition' => [
					'view' => 'icon-text',
				],
			]
		);

		$this->add_control(
			'skin',
			[
				'label' => __( 'Skin', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'gradient' => __( 'Gradient', 'madxartwork-pro' ),
					'minimal' => __( 'Minimal', 'madxartwork-pro' ),
					'framed' => __( 'Framed', 'madxartwork-pro' ),
					'boxed' => __( 'Boxed Icon', 'madxartwork-pro' ),
					'flat' => __( 'Flat', 'madxartwork-pro' ),
				],
				'default' => 'gradient',
				'prefix_class' => 'madxartwork-share-buttons--skin-',
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => __( 'Shape', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'square' => __( 'Square', 'madxartwork-pro' ),
					'rounded' => __( 'Rounded', 'madxartwork-pro' ),
					'circle' => __( 'Circle', 'madxartwork-pro' ),
				],
				'default' => 'square',
				'prefix_class' => 'madxartwork-share-buttons--shape-',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => 'Auto',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'madxartwork-grid%s-',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'madxartwork-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'madxartwork-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'madxartwork-share-buttons%s--align-',
				'condition' => [
					'columns' => '0',
				],
			]
		);

		$this->add_control(
			'share_url_type',
			[
				'label' => __( 'Target URL', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_page' => __( 'Current Page', 'madxartwork-pro' ),
					'custom' => __( 'Custom', 'madxartwork-pro' ),
				],
				'default' => 'current_page',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'share_url',
			[
				'label' => __( 'Link', 'madxartwork-pro' ),
				'type' => Controls_Manager::URL,
				'show_external' => false,
				'placeholder' => __( 'https://your-link.com', 'madxartwork-pro' ),
				'condition' => [
					'share_url_type' => 'custom',
				],
				'show_label' => false,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			[
				'label' => __( 'Share Buttons', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.madxartwork-grid-0) .madxartwork-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.madxartwork-grid-0 .madxartwork-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.madxartwork-grid-tablet-0 .madxartwork-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.madxartwork-grid-mobile-0 .madxartwork-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.madxartwork-grid-0 .madxartwork-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.madxartwork-grid-tablet-0 .madxartwork-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.madxartwork-grid-mobile-0 .madxartwork-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.madxartwork-grid-0) .madxartwork-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.madxartwork-grid-0 .madxartwork-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.madxartwork-grid-tablet-0 .madxartwork-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.madxartwork-grid-mobile-0 .madxartwork-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label' => __( 'Button Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.05,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-share-btn' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 0.5,
						'max' => 4,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'size_units' => [ 'em', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-share-btn__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => __( 'Button Height', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'em' => [
						'min' => 1,
						'max' => 7,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'size_units' => [ 'em', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-share-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_size',
			[
				'label' => __( 'Border Size', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
					'em' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-share-btn' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => [ 'framed', 'boxed' ],
				],
			]
		);

		$this->add_control(
			'color_source',
			[
				'label' => __( 'Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'official' => __( 'Official', 'madxartwork-pro' ),
					'custom' => __( 'Custom', 'madxartwork-pro' ),
				],
				'default' => 'official',
				'prefix_class' => 'madxartwork-share-buttons--color-',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'tabs_button_style',
			[
				'condition' => [
					'color_source' => 'custom',
				],
			]
		);

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __( 'Primary Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn .madxartwork-share-btn__icon,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn .madxartwork-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.madxartwork-share-buttons--skin-framed .madxartwork-share-btn,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'secondary_color',
			[
				'label' => __( 'Secondary Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn__icon, 
					 {{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn__text, 
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn__icon,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn__text,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn__icon,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn__icon' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'primary_color_hover',
			[
				'label' => __( 'Primary Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn:hover,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.madxartwork-share-buttons--skin-framed .madxartwork-share-btn:hover,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn:hover,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn:hover .madxartwork-share-btn__icon, 
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn:hover .madxartwork-share-btn__icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'secondary_color_hover',
			[
				'label' => __( 'Secondary Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn:hover .madxartwork-share-btn__icon, 
					 {{WRAPPER}}.madxartwork-share-buttons--skin-flat .madxartwork-share-btn:hover .madxartwork-share-btn__text, 
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn:hover .madxartwork-share-btn__icon,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-gradient .madxartwork-share-btn:hover .madxartwork-share-btn__text,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-boxed .madxartwork-share-btn:hover .madxartwork-share-btn__icon,
					 {{WRAPPER}}.madxartwork-share-buttons--skin-minimal .madxartwork-share-btn:hover .madxartwork-share-btn__icon' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .madxartwork-share-btn__title',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->add_control(
			'text_padding',
			[
				'label' => __( 'Text Padding', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.madxartwork-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'view' => 'text',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_active_settings();

		if ( empty( $settings['share_buttons'] ) ) {
			return;
		}

		$button_classes = 'madxartwork-share-btn';

		$show_text = 'text' === $settings['view'] || 'yes' === $settings['show_label'];
		?>
		<div class="madxartwork-grid">
			<?php
			foreach ( $settings['share_buttons'] as $button ) {
				$network_name = $button['button'];

				$social_network_class = ' madxartwork-share-btn_' . $network_name;

				?>
				<div class="madxartwork-grid-item">
					<div class="<?php echo esc_attr( $button_classes . $social_network_class ); ?>">
						<?php if ( 'icon' === $settings['view'] || 'icon-text' === $settings['view'] ) : ?>
							<span class="madxartwork-share-btn__icon">
								<i class="<?php echo self::get_network_class( $network_name ); ?>" aria-hidden="true"></i>
								<span class="madxartwork-screen-only"><?php echo sprintf( __( 'Share on %s', 'madxartwork-pro' ), $network_name ); ?></span>
							</span>
						<?php endif; ?>
						<?php if ( $show_text ) : ?>
							<div class="madxartwork-share-btn__text">
								<?php if ( 'yes' === $settings['show_label'] || 'text' === $settings['view'] ) : ?>
									<span class="madxartwork-share-btn__title">
										<?php echo $button['text'] ? $button['text'] : Module::get_networks( $network_name )['title']; ?>
									</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
			var shareButtonsEditorModule = madxartworkPro.modules.shareButtons,
				buttonClass = 'madxartwork-share-btn';

			var showText = 'icon-text' === settings.view ? 'yes' === settings.show_label : 'text' === settings.view;
		#>
		<div class="madxartwork-grid">
			<#
				_.each( settings.share_buttons, function( button ) {
					var networkName = button.button,
						socialNetworkClass = 'madxartwork-share-btn_' + networkName;
					#>
					<div class="madxartwork-grid-item">
						<div class="{{ buttonClass }} {{ socialNetworkClass }}">
							<# if ( 'icon' === settings.view || 'icon-text' === settings.view ) { #>
							<span class="madxartwork-share-btn__icon">
								<i class="{{ shareButtonsEditorModule.getNetworkClass( networkName ) }}" aria-hidden="true"></i>
								<span class="madxartwork-screen-only">Share on {{{ networkName }}}</span>
							</span>
							<# } #>
							<# if ( showText ) { #>
								<div class="madxartwork-share-btn__text">
									<# if ( 'yes' === settings.show_label || 'text' === settings.view ) { #>
										<span class="madxartwork-share-btn__title">{{{ shareButtonsEditorModule.getNetworkTitle( button ) }}}</span>
									<# } #>
								</div>
							<# } #>
						</div>
					</div>
			<#  } ); #>
		</div>
		<?php
	}
}
