<?php
namespace madxartwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * madxartwork accordion widget.
 *
 * madxartwork widget that displays a collapsible display of content in an
 * accordion style, showing only one item at a time.
 *
 * @since 1.0.0
 */
class Widget_Accordion extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve accordion widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'accordion';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve accordion widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Accordion', 'madxartwork' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve accordion widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-accordion';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'accordion', 'tabs', 'toggle' ];
	}

	/**
	 * Register accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Accordion', 'madxartwork' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label' => __( 'Title & Description', 'madxartwork' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Accordion Title', 'madxartwork' ),
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => __( 'Content', 'madxartwork' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Accordion Content', 'madxartwork' ),
				'show_label' => false,
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Accordion Items', 'madxartwork' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => __( 'Accordion #1', 'madxartwork' ),
						'tab_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork' ),
					],
					[
						'tab_title' => __( 'Accordion #2', 'madxartwork' ),
						'tab_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'madxartwork' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'madxartwork' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => __( 'Icon', 'madxartwork' ),
				'type' => Controls_Manager::ICONS,
				'separator' => 'before',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'selected_active_icon',
			[
				'label' => __( 'Active Icon', 'madxartwork' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label' => __( 'Title HTML Tag', 'madxartwork' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'div',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Accordion', 'madxartwork' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'madxartwork' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item .madxartwork-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item .madxartwork-tab-title.madxartwork-active' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item .madxartwork-tab-content' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-item .madxartwork-tab-title.madxartwork-active' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			[
				'label' => __( 'Title', 'madxartwork' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_background',
			[
				'label' => __( 'Background', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => __( 'Active Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title.madxartwork-active' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'madxartwork' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_icon',
			[
				'label' => __( 'Icon', 'madxartwork' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'madxartwork' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Start', 'madxartwork' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'End', 'madxartwork' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => is_rtl() ? 'right' : 'left',
				'toggle' => false,
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title .madxartwork-accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title .madxartwork-accordion-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_active_color',
			[
				'label' => __( 'Active Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title.madxartwork-active .madxartwork-accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-title.madxartwork-active .madxartwork-accordion-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label' => __( 'Spacing', 'madxartwork' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-icon.madxartwork-accordion-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-accordion-icon.madxartwork-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_content',
			[
				'label' => __( 'Content', 'madxartwork' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label' => __( 'Background', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'madxartwork' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-content' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-content',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'madxartwork' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-accordion .madxartwork-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// add old default
			$settings['icon'] = 'fa fa-plus';
			$settings['icon_active'] = 'fa fa-minus';
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$has_icon = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );
		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="madxartwork-accordion" role="tablist">
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;

				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

				$this->add_render_attribute( $tab_title_setting_key, [
					'id' => 'madxartwork-tab-title-' . $id_int . $tab_count,
					'class' => [ 'madxartwork-tab-title' ],
					'data-tab' => $tab_count,
					'role' => 'tab',
					'aria-controls' => 'madxartwork-tab-content-' . $id_int . $tab_count,
				] );

				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'madxartwork-tab-content-' . $id_int . $tab_count,
					'class' => [ 'madxartwork-tab-content', 'madxartwork-clearfix' ],
					'data-tab' => $tab_count,
					'role' => 'tabpanel',
					'aria-labelledby' => 'madxartwork-tab-title-' . $id_int . $tab_count,
				] );

				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
				?>
				<div class="madxartwork-accordion-item">
					<<?php echo $settings['title_html_tag']; ?> <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?>>
						<?php if ( $has_icon ) : ?>
							<span class="madxartwork-accordion-icon madxartwork-accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>" aria-hidden="true">
							<?php
							if ( $is_new || $migrated ) { ?>
								<span class="madxartwork-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
								<span class="madxartwork-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
							<?php } else { ?>
								<i class="madxartwork-accordion-icon-closed <?php echo esc_attr( $settings['icon'] ); ?>"></i>
								<i class="madxartwork-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>
							<?php } ?>
							</span>
						<?php endif; ?>
						<a href=""><?php echo $item['tab_title']; ?></a>
					</<?php echo $settings['title_html_tag']; ?>>
					<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="madxartwork-accordion" role="tablist">
			<#
			if ( settings.tabs ) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 ),
					iconHTML = madxartwork.helpers.renderIcon( view, settings.selected_icon, {}, 'i' , 'object' ),
					iconActiveHTML = madxartwork.helpers.renderIcon( view, settings.selected_active_icon, {}, 'i' , 'object' ),
					migrated = madxartwork.helpers.isIconMigrated( settings, 'selected_icon' );

				_.each( settings.tabs, function( item, index ) {
					var tabCount = index + 1,
						tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
						tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

					view.addRenderAttribute( tabTitleKey, {
						'id': 'madxartwork-tab-title-' + tabindex + tabCount,
						'class': [ 'madxartwork-tab-title' ],
						'tabindex': tabindex + tabCount,
						'data-tab': tabCount,
						'role': 'tab',
						'aria-controls': 'madxartwork-tab-content-' + tabindex + tabCount
					} );

					view.addRenderAttribute( tabContentKey, {
						'id': 'madxartwork-tab-content-' + tabindex + tabCount,
						'class': [ 'madxartwork-tab-content', 'madxartwork-clearfix' ],
						'data-tab': tabCount,
						'role': 'tabpanel',
						'aria-labelledby': 'madxartwork-tab-title-' + tabindex + tabCount
					} );

					view.addInlineEditingAttributes( tabContentKey, 'advanced' );
					#>
					<div class="madxartwork-accordion-item">
						<{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
							<# if ( settings.icon || settings.selected_icon ) { #>
							<span class="madxartwork-accordion-icon madxartwork-accordion-icon-{{ settings.icon_align }}" aria-hidden="true">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
									<span class="madxartwork-accordion-icon-closed">{{{ iconHTML.value }}}</span>
									<span class="madxartwork-accordion-icon-opened">{{{ iconActiveHTML.value }}}</span>
								<# } else { #>
									<i class="madxartwork-accordion-icon-closed {{ settings.icon }}"></i>
									<i class="madxartwork-accordion-icon-opened {{ settings.icon_active }}"></i>
								<# } #>
							</span>
							<# } #>
							<a href="">{{{ item.tab_title }}}</a>
						</{{{ settings.title_html_tag }}}>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					</div>
					<#
				} );
			} #>
		</div>
		<?php
	}
}
