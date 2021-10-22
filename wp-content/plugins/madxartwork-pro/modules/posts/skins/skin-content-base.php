<?php
namespace madxartworkPro\Modules\Posts\Skins;

use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Image_Size;
use madxartwork\Widget_Base;
use madxartworkPro\Modules\ThemeBuilder\Module as ThemeBuilder;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait Skin_Content_Base {
	protected function _register_controls_actions() {
		$widget_name = $this->parent->get_name();
		add_action( 'madxartwork/element/' . $widget_name . '/section_layout/before_section_end', [ $this, 'register_controls' ] );

		if ( 'archive-posts' === $widget_name ) {
			add_action( 'madxartwork/element/archive-posts/section_layout/after_section_end', [ $this, 'register_style_sections' ] );
		} else {
			add_action( 'madxartwork/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
		}
	}

	public function get_title() {
		return __( 'Full Content', 'madxartwork-pro' );
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->register_post_count_control();
		$this->register_row_gap_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_meta_data_controls();

	}

	public function register_thumbnail_controls() {
		$this->add_control(
			'thumbnail',
			[
				'label' => __( 'Show Thumbnail', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'thumbnail',
				'prefix_class' => 'madxartwork-posts--show-',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size',
				'default' => 'medium',
				'exclude' => [ 'custom' ],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => '',
				],
				'prefix_class' => 'madxartwork-posts--thumbnail-size-',
			]
		);

		$this->add_responsive_control(
			'item_ratio',
			[
				'label' => __( 'Image Ratio', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.66,
				],
				'tablet_default' => [
					'size' => '',
				],
				'mobile_default' => [
					'size' => 0.5,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-posts-container .madxartwork-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',
					'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => '',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => '',
				],
			]
		);
	}

	public function register_design_controls() {
		$this->register_additional_design_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
		$this->update_image_spacing_control();
	}

	public function register_row_gap_control() {
		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-posts-container article' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	}

	// Update selectors for full content
	public function update_image_spacing_control() {
		$image_spacing_control = [
			'selectors' => [
				'{{WRAPPER}} .madxartwork-posts--skin-full_content a.madxartwork-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .madxartwork-posts--skin-archive_full_content a.madxartwork-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			],
		];
		$this->update_control( 'image_spacing', $image_spacing_control );
	}

	protected function render_thumbnail() {
		$thumbnail = $this->get_instance_value( 'thumbnail' );

		// In edit mode we render thumbnail to avoid server side rendering on each change.
		if ( empty( $thumbnail ) && ! Plugin::madxartwork()->editor->is_edit_mode() ) {
			return;
		}

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		?>
		<a class="madxartwork-post__thumbnail__link" href="<?php echo $this->current_permalink; ?>">
			<div class="madxartwork-post__thumbnail"><?php echo $thumbnail_html; ?></div>
		</a>
		<?php
	}

	public function render_post_content( $with_wrapper = false ) {
		static $did_posts = [];
		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}

		// Avoid recursion
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;
		// End avoid recursion

		if ( Plugin::madxartwork()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::madxartwork()->preview->builder_wrapper( '' ); // XSS ok
		} else {
			/**
			 * @var ThemeBuilder ThemeBuilder
			 */
			$document = ThemeBuilder::instance()->get_document( $post->ID );
			// On view theme document show it's preview content.
			if ( $document ) {
				$preview_type = $document->get_settings( 'preview_type' );
				$preview_id = $document->get_settings( 'preview_id' );

				if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
					$post = get_post( $preview_id );

					if ( ! $post ) {
						return;
					}
				}
			}

			$editor = Plugin::madxartwork()->editor;

			// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
			$is_edit_mode = $editor->is_edit_mode();
			$editor->set_edit_mode( false );

			// Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the madxartwork filter has been removed to avoid recursion.
			$content = Plugin::madxartwork()->frontend->get_builder_content( $post->ID, true );

			// Restore edit mode state
			Plugin::madxartwork()->editor->set_edit_mode( $is_edit_mode );

			if ( empty( $content ) ) {
				Plugin::madxartwork()->frontend->remove_content_filter();

				// Split to pages.
				setup_postdata( $post );

				/** This filter is documented in wp-includes/post-template.php */
				echo apply_filters( 'the_content', get_the_content() );

				wp_link_pages( [
					'before' => '<div class="page-links madxartwork-page-links"><span class="page-links-title madxartwork-page-links-title">' . __( 'Pages:', 'madxartwork-pro' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . __( 'Page', 'madxartwork-pro' ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				Plugin::madxartwork()->frontend->add_content_filter();

				return;
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		} // End if().

		if ( $with_wrapper ) {
			echo '<div class="madxartwork-post__content">' . balanceTags( $content, true ) . '</div>';  // XSS ok.
		} else {
			echo $content; // XSS ok.
		}
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_meta_data();
		$this->render_post_content( true );
		$this->render_text_footer();
		$this->render_post_footer();
	}
}
