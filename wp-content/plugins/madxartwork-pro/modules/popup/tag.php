<?php
namespace madxartworkPro\Modules\Popup;

use madxartwork\Controls_Manager;
use madxartwork\Core\DynamicTags\Tag as DynamicTagsTag;
use madxartworkPro\Modules\DynamicTags\Module as DynamicTagsModule;
use madxartworkPro\Modules\LinkActions\Module as LinkActionsModule;
use madxartworkPro\Modules\QueryControl\Module as QueryControlModule;
use madxartwork\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tag extends DynamicTagsTag {

	public function get_name() {
		return 'popup';
	}

	public function get_title() {
		return __( 'Popup', 'madxartwork-pro' );
	}

	public function get_group() {
		return DynamicTagsModule::ACTION_GROUP;
	}

	public function get_categories() {
		return [ DynamicTagsModule::URL_CATEGORY ];
	}

	public function _register_controls() {
		$this->add_control(
			'action',
			[
				'label' => __( 'Action', 'madxartwork-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'open',
				'options' => [
					'open' => __( 'Open Popup', 'madxartwork-pro' ),
					'close' => __( 'Close Popup', 'madxartwork-pro' ),
					'toggle' => __( 'Toggle Popup', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_control(
			'popup',
			[
				'label' => __( 'Popup', 'madxartwork-pro' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
					'query' => [
						'posts_per_page' => 20,
						'meta_query' => [
							[
								'key' => Document::TYPE_META_KEY,
								'value' => 'popup',
							],
						],
					],
				],
				'label_block' => true,
				'condition' => [
					'action' => [ 'open', 'toggle' ],
				],
			]
		);

		$this->add_control(
			'do_not_show_again',
			[
				'label' => __( 'Don\'t Show Again', 'madxartwork-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'action' => 'close',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_active_settings();

		if ( 'close' === $settings['action'] ) {
			$this->print_close_popup_link( $settings );

			return;
		}

		$this->print_open_popup_link( $settings );
	}

	// Keep Empty to avoid default advanced section
	protected function register_advanced_section() {}

	private function print_open_popup_link( array $settings ) {
		if ( ! $settings['popup'] ) {
			return;
		}

		$link_action_url = LinkActionsModule::create_action_url( 'popup:open', [
			'id' => $settings['popup'],
			'toggle' => 'toggle' === $settings['action'],
		] );

		Module::add_popup_to_location( $settings['popup'] );

		echo $link_action_url;
	}

	private function print_close_popup_link( array $settings ) {
		echo LinkActionsModule::create_action_url( 'popup:close', [ 'do_not_show_again' => $settings['do_not_show_again'] ] );
	}
}
