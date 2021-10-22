<?php
namespace madxartworkPro\Modules\ThemeElements\Widgets;

use madxartwork\Controls_Manager;
use madxartworkPro\Modules\QueryControl\Module as QueryControlModule;
use madxartworkPro\Modules\ThemeElements\Module;
use madxartworkPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Comments extends Base {

	public function get_name() {
		return 'post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'madxartwork-pro' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'comments', 'post', 'response', 'form' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'_skin',
			[
				'type' => Controls_Manager::HIDDEN,
			]
		);

		$this->add_control(
			'skin_temp',
			[
				'label' => __( 'Skin', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Theme Comments', 'madxartwork-pro' ),
				],
				'description' => __( 'The Theme Comments skin uses the currently active theme comments design and layout to display the comment form and comments.', 'madxartwork-pro' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::SOURCE_TYPE_CURRENT_POST => __( 'Current Post', 'madxartwork-pro' ),
					Module::SOURCE_TYPE_CUSTOM => __( 'Custom', 'madxartwork-pro' ),
				],
				'default' => Module::SOURCE_TYPE_CURRENT_POST,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source_custom',
			[
				'label' => __( 'Search & Select', 'madxartwork-pro' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'label_block' => true,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_POST,
				],
				'condition' => [
					'source_type' => Module::SOURCE_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			$post_id = (int) $settings['source_custom'];
			Plugin::madxartwork()->db->switch_to_post( $post_id );
		}

		if ( ! comments_open() && ( Plugin::madxartwork()->preview->is_preview_mode() || Plugin::madxartwork()->editor->is_edit_mode() ) ) :
			?>
			<div class="madxartwork-alert madxartwork-alert-danger" role="alert">
				<span class="madxartwork-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'madxartwork-pro' ); ?>
				</span>
				<span class="madxartwork-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'madxartwork-pro' ); ?>
				</span>
			</div>
			<?php
		else :
			comments_template();
		endif;

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			Plugin::madxartwork()->db->restore_current_post();
		}
	}
}
