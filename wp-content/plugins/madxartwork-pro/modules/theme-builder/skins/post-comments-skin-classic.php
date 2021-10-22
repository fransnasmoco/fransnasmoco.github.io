<?php
namespace madxartworkPro\Modules\ThemeBuilder\Skins;

use madxartwork\Skin_Base;
use madxartwork\Controls_Manager;
use madxartwork\Group_Control_Border;
use madxartwork\Group_Control_Typography;
use madxartwork\Scheme_Color;
use madxartwork\Scheme_Typography;
use madxartworkPro\Modules\ThemeBuilder\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Comments_Skin_Classic extends Skin_Base {

	public function get_id() {
		return 'skin-classic';
	}

	public function get_title() {
		return 'Skin Classic';
	}

	protected function _register_controls_actions() {
		add_action( 'madxartwork/element/post-comments/section_content/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'madxartwork-pro' ),
				'default' => '[field id="comment-count"]',
				'label_block' => true,
				'dynamic' => [
					'types' => [
						'text',
					],
					'apply_on' => 'value',
					'allow_free_text' => true,
					'allow_multiple' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'show_gravatar',
			[
				'label' => __( 'Gravatar', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'madxartwork-pro' ),
				'label_on' => __( 'Show', 'madxartwork-pro' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Comments', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '10',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_background_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'row_border_color',
			[
				'label' => __( 'Border Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'row_border_width',
			[
				'label' => __( 'Border Width', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_border_radius',
			[
				'label' => __( 'Border Radius', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => __( 'Meta', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_spacing',
			[
				'label' => __( 'Spacing', 'madxartwork-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '0',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-meta' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-meta' => 'color: {{VALUE}};',
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
				'name' => 'meta_typography',
				'selector' => '{{WRAPPER}} .madxartwork-comment .comment-meta',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-content' => 'color: {{VALUE}};',
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
				'name' => 'content__typography',
				'selector' => '{{WRAPPER}} .madxartwork-comment .comment-content',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_reply_button_style',
			[
				'label' => __( 'Reply Button', 'madxartwork-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_reply_button_style' );

		$this->start_controls_tab(
			'tab_reply_button_normal',
			[
				'label' => __( 'Normal', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'reply_button_text_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reply_button_typography',
				'label' => __( 'Typography', 'madxartwork-pro' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .madxartwork-comment .comment-reply-link',
			]
		);

		$this->add_control(
			'reply_button_background_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'reply_button_border',
				'label' => __( 'Border', 'madxartwork-pro' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .madxartwork-comment .comment-reply-link',
			]
		);

		$this->add_control(
			'reply_button_border_radius',
			[
				'label' => __( 'Border Radius', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'reply_button_text_padding',
			[
				'label' => __( 'Text Padding', 'madxartwork-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_reply_button_hover',
			[
				'label' => __( 'Hover', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'reply_button_background_hover_color',
			[
				'label' => __( 'Background Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'reply_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'madxartwork-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .madxartwork-comment .comment-reply-link:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'reply_button_border_border!' ) => '',
				],
			]
		);

		$this->add_control(
			'reply_button_hover_animation',
			[
				'label' => __( 'Animation', 'madxartwork-pro' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render() {

		Module::instance()->get_preview_manager()->switch_to_preview_query();

		// Hack to remove template comment form
		$comments_template_callback = function() {
			return __DIR__ . '/../views/comments-template.php';
		};

		add_filter( 'comments_template', $comments_template_callback );

		// The `comments_template` doesn't has an API to pass current widget instance, so make it global
		$GLOBALS['post_comment_skin_classic'] = $this;

		comments_template();

		remove_filter( 'comments_template', $comments_template_callback );

		unset( $GLOBALS['post_comment_skin_classic'] );

		Module::instance()->get_preview_manager()->restore_current_query();
	}

	public function comment_callback( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$class = 'madxartwork-comment';
		if ( ! empty( $args['has_children'] ) ) {
			$class .= ' parent';
		}
		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $class, $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 < $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					/* translators: %s: Comment author link. */
					printf( __( '%s <span class="says">says:</span>', 'madxartwork-pro' ),
						sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: Comment date, 2: Comment time. */
							printf( __( '%1$s at %2$s', 'madxartwork-pro' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit', 'madxartwork-pro' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'madxartwork-pro' ); ?></p>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			comment_reply_link( array_merge( $args, [
				'add_below' => 'div-comment',
				'depth' => $depth,
				'max_depth' => $args['max_depth'],
				'before' => '<div class="reply">',
				'after' => '</div>',
			] ) );
			?>
		</article><!-- .comment-body -->
		<?php
	}
}
