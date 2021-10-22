<?php

namespace madxartworkPro\Modules\Popup\DisplaySettings;

use madxartwork\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Timing extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_timing';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'timing' );

		$this->start_settings_group( 'page_views', __( 'Show after X page views', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'views',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Page Views', 'madxartwork-pro' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sessions', __( 'Show after X sessions', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'sessions',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Sessions', 'madxartwork-pro' ),
				'default' => 2,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'times', __( 'Show up to X times', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'times',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Times', 'madxartwork-pro' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->add_settings_group_control(
			'count',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Count', 'madxartwork-pro' ),
				'options' => [
					'' => __( 'On Open', 'madxartwork-pro' ),
					'close' => __( 'On Close', 'madxartwork-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'url', __( 'When arriving from specific URL', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'action',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __( 'Show', 'madxartwork-pro' ),
					'hide' => __( 'Hide', 'madxartwork-pro' ),
					'regex' => __( 'Regex', 'madxartwork-pro' ),
				],
			]
		);

		$this->add_settings_group_control(
			'url',
			[
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'URL', 'madxartwork-pro' ),
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sources', __( 'Show when arriving from', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'sources',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'search', 'external', 'internal' ],
				'options' => [
					'search' => __( 'Search Engines', 'madxartwork-pro' ),
					'external' => __( 'External Links', 'madxartwork-pro' ),
					'internal' => __( 'Internal Links', 'madxartwork-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'logged_in', __( 'Hide for logged in users', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'users',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => __( 'All Users', 'madxartwork-pro' ),
					'custom' => __( 'Custom', 'madxartwork-pro' ),
				],
			]
		);

		global $wp_roles;

		$roles = array_map( function( $role ) {
			return $role['name'];
		}, $wp_roles->roles );

		$this->add_settings_group_control(
			'roles',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $roles,
				'select2options' => [
					'placeholder' => __( 'Select Roles', 'madxartwork-pro' ),
				],
				'condition' => [
					'users' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'devices', __( 'Show on devices', 'madxartwork-pro' ) );

		$this->add_settings_group_control(
			'devices',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'madxartwork-pro' ),
					'tablet' => __( 'Tablet', 'madxartwork-pro' ),
					'mobile' => __( 'Mobile', 'madxartwork-pro' ),
				],
			]
		);

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
