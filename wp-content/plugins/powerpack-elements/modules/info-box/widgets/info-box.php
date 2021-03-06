<?php
namespace PowerpackElements\Modules\InfoBox\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// madxartwork Classes
use madxartwork\Controls_Manager;
use madxartwork\Control_Media;
use madxartwork\Utils;
use madxartwork\Group_Control_Background;
use madxartwork\Group_Control_Box_Shadow;
use madxartwork\Group_Control_Border;
use madxartwork\Group_Control_Typography;
use madxartwork\Group_Control_Image_Size;
use madxartwork\Scheme_Typography;
use madxartwork\Scheme_Color;
use madxartwork\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Info Box Widget
 */
class Info_Box extends Powerpack_Widget {
    
    /**
	 * Retrieve info box widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-info-box';
    }

    /**
	 * Retrieve info box widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Info Box', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the info box widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve info box widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-info-box power-pack-admin-icon';
    }

    /**
	 * Register info box widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Icon
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_icon',
            [
                'label'                 => __( 'Icon', 'powerpack' ),
            ]
        );
        
        $this->add_control(
			'icon_type',
			[
				'label'                 => esc_html__( 'Type', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'               => [
					'none' => [
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon' => 'fa fa-ban',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon' => 'fa fa-gear',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon' => 'fa fa-picture-o',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'powerpack' ),
						'icon' => 'fa fa-font',
					],
				],
				'default'               => 'icon',
			]
		);

        $this->add_control(
            'icon',
            [
                'label'                 => __( 'Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-diamond',
                'condition'             => [
                    'icon_type'     => 'icon',
                ],
            ]
        );

        $this->add_control(
            'icon_text',
            [
                'label'                 => __( 'Icon Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => '1',
                'condition'             => [
                    'icon_type'     => 'text',
                ],
            ]
        );

		$this->add_control(
			'image',
			[
				'label'                 => __( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
                'condition'             => [
                    'icon_type' => 'image',
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
                'condition'             => [
                    'icon_type' => 'image',
                ],
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'options'               => [
					'left'        => [
						'title'   => __( 'Icon on Left', 'powerpack' ),
						'icon'    => 'eicon-h-align-left',
					],
					'top'         => [
						'title'   => __( 'Icon on Top', 'powerpack' ),
						'icon'    => 'eicon-v-align-top',
					],
					'right'       => [
						'title'   => __( 'Icon on Right', 'powerpack' ),
						'icon'    => 'eicon-h-align-right',
					],
				],
                'condition'             => [
                    'icon_type!'  => 'none',
                ],
                'prefix_class'          => 'info-box%s-',
                'frontend_available'    => true,
			]
		);

		$this->add_responsive_control(
			'icon_vertical_position',
			[
				'label'                 => __( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Middle', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'powerpack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'(desktop){{WRAPPER}}.info-box-left .pp-info-box .pp-info-box-icon'        => 'align-self: {{VALUE}};',
					'(desktop){{WRAPPER}}.info-box-right .pp-info-box .pp-info-box-icon'       => 'align-self: {{VALUE}};',
					'(tablet){{WRAPPER}}.info-box-tablet-left .pp-info-box .pp-info-box-icon'  => 'align-self: {{VALUE}};',
					'(tablet){{WRAPPER}}.info-box-tablet-right .pp-info-box .pp-info-box-icon' => 'align-self: {{VALUE}};',
					'(mobile){{WRAPPER}}.info-box-mobile-left .pp-info-box .pp-info-box-icon'  => 'align-self: {{VALUE}};',
					'(mobile){{WRAPPER}}.info-box-mobile-right .pp-info-box .pp-info-box-icon' => 'align-self: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'baseline',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
                'condition'             => [
                    'icon_type'         => ['icon', 'image', 'text'],
                    'icon_position'     => ['left', 'right'],
                ],
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Content
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_content',
            [
                'label'                 => __( 'Content', 'powerpack' ),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'                 => __( 'Title', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Title', 'powerpack' ),
            ]
        );

        $this->add_control(
            'sub_heading',
            [
                'label'                 => __( 'Subtitle', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Subtitle', 'powerpack' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'                 => __( 'Description', 'powerpack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Enter info box description', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'divider_title_switch',
            [
                'label'                 => __( 'Title Separator', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'powerpack' ),
                'label_off'             => __( 'Off', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                 => __( 'Title HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h4',
                'options'               => [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
            ]
        );
        
        $this->add_control(
            'sub_title_html_tag',
            [
                'label'                 => __( 'Subtitle HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h5',
                'options'               => [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
                'condition'             => [
                    'sub_heading!'  => '',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Link
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_link',
            [
                'label'                 => __( 'Link', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'link_type',
            [
                'label'                 => __( 'Link Type', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none'      => __( 'None', 'powerpack' ),
                    'box'       => __( 'Box', 'powerpack' ),
                    'icon'      => __( 'Image/Icon', 'powerpack' ),
                    'title'     => __( 'Title', 'powerpack' ),
                    'button'    => __( 'Button', 'powerpack' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'powerpack' ),
                'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ],
				],
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url' => '#',
                ],
                'condition'             => [
                    'link_type!'   => 'none',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'                 => __( 'Button Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Get Started', 'powerpack' ),
                'condition'             => [
                    'link_type'   => 'button',
                ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label'                 => __( 'Button Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    'link_type'   => 'button',
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label'                 => __( 'Icon Position', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'after',
                'options'               => [
                    'after'     => __( 'After', 'powerpack' ),
                    'before'    => __( 'Before', 'powerpack' ),
                ],
                'condition'             => [
                    'link_type'     => 'button',
                    'button_icon!'  => '',
                ],
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Info Box
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_style',
            [
                'label'                 => __( 'Info Box', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify'   => [
						'title' => __( 'Justified', 'powerpack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_info_box_style' );

        $this->start_controls_tab(
            'tab_info_box_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'info_box_bg',
                'label'                 => __( 'Background', 'powerpack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-info-box-container',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'info_box_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-container',
			]
		);

		$this->add_control(
			'info_box_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'info_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-container',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_info_box_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'info_box_bg_hover',
                'label'                 => __( 'Background', 'powerpack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'info_box_border_hover',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
			]
		);

		$this->add_control(
			'info_box_border_radius_hover',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'info_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'info_box_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
                'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Icon Style
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_icon_style',
            [
                'label'                 => __( 'Icon Style', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'icon_type!' => 'none',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label'                 => __( 'Icon Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em' ],
                'condition'             => [
                    'icon_type'     => 'icon',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_img_width',
            [
                'label'                 => __( 'Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 25,
                        'max'   => 600,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 25,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}}.info-box-top .pp-info-box-icon img, {{WRAPPER}}.info-box-left .pp-info-box-icon, {{WRAPPER}}.info-box-right .pp-info-box-icon' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'icon_type'     => 'image',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'icon_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'condition'             => [
                    'icon_type'     => 'text',
                ],
                'selector'              => '{{WRAPPER}} .pp-info-box-icon',
            ]
        );

        $this->start_controls_tabs( 'tabs_icon_style' );

        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'icon_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label'                 => __( 'Icon Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_type!'    => 'image',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
				'selector'              => '{{WRAPPER}} .pp-info-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon, {{WRAPPER}} .pp-info-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'icon_rotation',
            [
                'label'                 => __( 'Icon Rotation', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 360,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'transform: rotate( {{SIZE}}deg );',
                ],
            ]
        );

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon-wrap' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover .fa' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'                 => __( 'Icon Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_type!'    => 'image',
                ],
            ]
        );

		$this->add_control(
			'hover_animation_icon',
			[
				'label'                 => __( 'Icon Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Title
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_title_style',
            [
                'label'                 => __( 'Title', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_heading',
            [
                'label'                 => __( 'Sub Title', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'sub_heading!'  => '',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'subtitle_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Title Separator
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_title_divider_style',
            [
                'label'                 => __( 'Title Separator', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'divider_title_border_type',
            [
                'label'                 => __( 'Border Type', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'none'      => __( 'None', 'powerpack' ),
                    'solid'     => __( 'Solid', 'powerpack' ),
                    'double'    => __( 'Double', 'powerpack' ),
                    'dotted'    => __( 'Dotted', 'powerpack' ),
                    'dashed'    => __( 'Dashed', 'powerpack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-style: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_width',
            [
                'label'                 => __( 'Border Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 30,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 1000,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_border_height',
            [
                'label'                 => __( 'Border Height', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 2,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 20,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_title_border_color',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'divider_title_align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'flex-start'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider-wrap'   => 'display: flex; justify-content: {{VALUE}};',
				],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
			]
		);
        
        $this->add_responsive_control(
            'divider_title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_description_style',
            [
                'label'                 => __( 'Description', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-description' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-description',
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'description_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Button', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
					'link_type'    => 'button',
                    'button_icon!' => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
					'link_type'    => 'button',
                    'button_icon!' => '',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-button:hover',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render info box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'info-box', 'class', 'pp-info-box' );
        
        $this->add_render_attribute( 'info-box-container', 'class', 'pp-info-box-container' );
            
        $this->add_render_attribute( 'title-container', 'class', 'pp-info-box-title-container' );
        
        $pp_if_html_tag = 'div';
        $pp_title_html_tag = 'div';
        $pp_button_html_tag = 'div';
        
        $this->add_inline_editing_attributes( 'icon_text', 'none' );
        $this->add_render_attribute( 'icon_text', 'class', 'pp-icon-text' );
        $this->add_render_attribute( 'heading', 'class', 'pp-info-box-title' );
        $this->add_inline_editing_attributes( 'heading', 'none' );
        $this->add_inline_editing_attributes( 'sub_heading', 'none' );
        $this->add_render_attribute( 'sub_heading', 'class', 'pp-info-box-subtitle' );
        $this->add_inline_editing_attributes( 'description', 'basic' );
        $this->add_render_attribute( 'description', 'class', 'pp-info-box-description' );
        $this->add_inline_editing_attributes( 'button_text', 'none' );
        $this->add_render_attribute( 'button_text', 'class', 'pp-button-text' );
        
        $this->add_render_attribute( 'info-box-button', 'class', [
				'pp-info-box-button',
				'madxartwork-button',
				'madxartwork-size-' . $settings['button_size'],
			]
		);

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-box-button', 'class', 'madxartwork-animation-' . $settings['button_animation'] );
		}
        
        $this->add_render_attribute( 'icon', 'class', 'pp-info-box-icon' );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'madxartwork-animation-' . $settings['hover_animation_icon'] );
		}

        if ( $settings['link_type'] != 'none' ) {
            
            if ( ! empty( $settings['link']['url'] ) ) {
                
                $this->add_render_attribute( 'link', 'href', esc_url( $settings['link']['url'] ) );

                if ( $settings['link']['is_external'] ) {
                    $this->add_render_attribute( 'link', 'target', '_blank' );
                }

                if ( $settings['link']['nofollow'] ) {
                    $this->add_render_attribute( 'link', 'rel', 'nofollow' );
                }

                if ( $settings['link_type'] == 'box' ) {
                    $pp_if_html_tag = 'a';
                }
                elseif ( $settings['link_type'] == 'title' ) {
                    $pp_title_html_tag = 'a';
                }
                elseif ( $settings['link_type'] == 'button' ) {
                    $pp_button_html_tag = 'a';
                }

            }

        }
        ?>
        <<?php echo $pp_if_html_tag . ' ' . $this->get_render_attribute_string( 'info-box-container' ) . $this->get_render_attribute_string( 'link' ); ?>>
        <div <?php echo $this->get_render_attribute_string( 'info-box' ); ?>>
            <?php if ( $settings['icon_type'] != 'none' ) { ?>
                <div class="pp-info-box-icon-wrap">
                    <?php if ( $settings['link_type'] == 'icon' ) { ?>
                        <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                    <?php } ?>
                    <span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                        <?php if ( $settings['icon_type'] == 'icon' ) { ?>
                            <span class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></span>
                        <?php } elseif ( $settings['icon_type'] == 'image' ) {

                                if ( ! empty( $settings['image']['url'] ) ) {
                                    echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
                                }

                        } elseif ( $settings['icon_type'] == 'text' ) { ?>
                            <span class="pp-icon-text">
                                <?php echo $settings['icon_text']; ?>
                            </span>
                        <?php } ?>
                    </span>
                    <?php if ( $settings['link_type'] == 'icon' ) { ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="pp-info-box-content">
                <div class="pp-info-box-title-wrap">
                    <?php
                        if ( ! empty( $settings['heading'] ) ) {
                            echo '<'.$pp_title_html_tag. ' ' . $this->get_render_attribute_string( 'title-container' ) . $this->get_render_attribute_string( 'link' ) . '>';
                            printf( '<%1$s %2$s>', $settings['title_html_tag'], $this->get_render_attribute_string( 'heading' ) );
                            echo $settings['heading'];
                            printf( '</%1$s>', $settings['title_html_tag'] );
                            echo '</'.$pp_title_html_tag. '>';
                        }
        
                        if ( ! empty( $settings['sub_heading'] ) ) {
                            printf( '<%1$s %2$s>', $settings['sub_title_html_tag'], $this->get_render_attribute_string( 'sub_heading' ) );
                            echo $settings['sub_heading'];
                            printf( '</%1$s>', $settings['sub_title_html_tag'] );
                        }
                    ?>
                </div>

                <?php if ( $settings['divider_title_switch'] == 'yes' ) { ?>
                    <div class="pp-info-box-divider-wrap">
                        <div class="pp-info-box-divider"></div>
                    </div>
                <?php } ?>

                <?php if ( ! empty( $settings['description'] ) ) { ?>
                    <div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
                        <?php echo $this->parse_text_editor( nl2br( $settings['description'] ) ); ?>
                    </div>
                <?php } ?>
                <?php if ( $settings['link_type'] == 'button' ) { ?>
                    <?php if ( $settings['button_text'] != '' || $settings['button_icon'] != '' ) { ?>
                        <div class="pp-info-box-footer">
                            <<?php echo $pp_button_html_tag. ' ' . $this->get_render_attribute_string( 'info-box-button' ) .$this->get_render_attribute_string( 'link' ); ?>>
                                <?php if ( ! empty( $settings['button_icon'] ) && $settings['button_icon_position'] == 'before' ) { ?>
                                    <span class="pp-button-icon <?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></span>
                                <?php } ?>
                                <?php if ( ! empty( $settings['button_text'] ) ) { ?>
                                    <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                                        <?php echo esc_attr( $settings['button_text'] ); ?>
                                    </span>
                                <?php } ?>
                                <?php if ( ! empty( $settings['button_icon'] ) && $settings['button_icon_position'] == 'after' ) { ?>
                                    <span class="pp-button-icon <?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></span>
                                <?php } ?>
                            </<?php echo $pp_button_html_tag; ?>>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div><!-- .pp-info-box-content -->
        </div>
        </<?php echo $pp_if_html_tag; ?>>
        <?php
    }

    /**
	 * Render info box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            var pp_if_html_tag = 'div';
            var pp_title_html_tag = 'div';
            var pp_button_html_tag = 'div';

            if ( settings.link.url != '' ) {
                if ( settings.link_type == 'box' ) {
                    var pp_if_html_tag = 'a';
                }
                else if ( settings.link_type == 'title' ) {
                    var pp_title_html_tag = 'a';
                }
                else if ( settings.link_type == 'button' ) {
                    var pp_button_html_tag = 'a';
                }
            }
        #>
        <{{{pp_if_html_tag}}} class="pp-info-box-container" href="{{settings.link.url}}">
            <div class="pp-info-box pp-info-box-{{ settings.icon_position }}">
                <# if ( settings.icon_type != 'none' ) { #>
                    <div class="pp-info-box-icon-wrap">
                        <# if ( settings.link_type == 'icon' ) { #>
                            <a href="{{settings.link.url}}">
                        <# } #>
                        <span class="pp-info-box-icon madxartwork-animation-{{ settings.hover_animation_icon }}">
                            <# if ( settings.icon_type == 'icon' ) { #>
                                <span class="{{{ settings.icon }}}" aria-hidden="true"></span>
                            <# } else if ( settings.icon_type == 'image' ) { #>
                                <#
                                var image = {
                                    id: settings.image.id,
                                    url: settings.image.url,
                                    size: settings.image_size,
                                    dimension: settings.image_custom_dimension,
                                    model: view.getEditModel()
                                };
                                var image_url = madxartwork.imagesManager.getImageUrl( image );
                                #>
                                <img src="{{{ image_url }}}" />
                            <# } else if ( settings.icon_type == 'text' ) { #>
                                <span class="pp-icon-text madxartwork-inline-editing" data-madxartwork-setting-key="icon_text" data-madxartwork-inline-editing-toolbar="none">
                                    {{{ settings.icon_text }}}
                                </span>
                            <# } #>
                        </span>
                        <# if ( settings.link_type == 'icon' ) { #>
                            </a>
                        <# } #>
                    </div>
                <# } #>
                <div class="pp-info-box-content">
                    <div class="pp-info-box-title-wrap">
                        <# if ( settings.heading ) { #>
                            <{{pp_title_html_tag}} class="pp-info-box-title-container" href="{{settings.link.url}}">
                                <{{settings.title_html_tag}} class="pp-info-box-title madxartwork-inline-editing" data-madxartwork-setting-key="heading" data-madxartwork-inline-editing-toolbar="none">
                                    {{{ settings.heading }}}
                                </{{settings.title_html_tag}}>
                            </{{pp_title_html_tag}}>
                        <# } #>
                        <# if ( settings.sub_heading ) { #>
                            <{{settings.sub_title_html_tag}} class="pp-info-box-subtitle madxartwork-inline-editing" data-madxartwork-setting-key="sub_heading" data-madxartwork-inline-editing-toolbar="none">
                                {{{ settings.sub_heading }}}
                            </{{settings.sub_title_html_tag}}>
                        <# } #>
                    </div>

                    <# if ( settings.divider_title_switch == 'yes' ) { #>
                        <div class="pp-info-box-divider-wrap">
                            <div class="pp-info-box-divider"></div>
                        </div>
                    <# } #>

                    <# if ( settings.description ) { #>
                        <div class="pp-info-box-description madxartwork-inline-editing" data-madxartwork-setting-key="description" data-madxartwork-inline-editing-toolbar="basic">
                            {{{ settings.description }}}
                        </div>
                    <# } #>
                    <# if ( settings.link_type == 'button' ) { #>
                        <# if ( settings.button_text != '' || settings.button_icon != '' ) { #>
                            <div class="pp-info-box-footer">
                                <{{pp_button_html_tag}} href="{{ settings.link.url }}" class="pp-info-box-button madxartwork-button madxartwork-size-{{ settings.button_size }} madxartwork-animation-{{ settings.button_animation }}">
                                    <# if ( settings.button_icon && settings.button_icon_position == 'before' ) { #>
                                        <span class="pp-button-icon {{{ settings.button_icon }}}" aria-hidden="true"></span>
                                    <# } #>
                                    <# if ( settings.button_text ) { #>
                                        <span class="pp-button-text madxartwork-inline-editing" data-madxartwork-setting-key="button_text" data-madxartwork-inline-editing-toolbar="none">
                                            {{{ settings.button_text }}}
                                        </span>
                                    <# } #>
                                    <# if ( settings.button_icon && settings.button_icon_position == 'after' ) { #>
                                        <span class="pp-button-icon {{{ settings.button_icon }}}" aria-hidden="true"></span>
                                    <# } #>
                                </{{pp_button_html_tag}}>
                            </div>
                        <# } #>
                    <# } #>
                </div><!-- .pp-info-box-content -->
            </div>
        </{{{pp_if_html_tag}}}>
        <?php
    }

}