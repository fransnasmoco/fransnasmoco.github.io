<?php
namespace madxartwork\Core\Common\Modules\Connect\Apps;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Connect extends Common_App {

	/**
	 * @since 2.3.0
	 * @access protected
	 */
	protected function get_slug() {
		return 'connect';
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function render_admin_widget() {
		if ( $this->is_connected() ) {
			$remote_user = $this->get( 'user' );
			$title = sprintf( __( 'Connected to madxartwork as %s', 'madxartwork' ), '<strong>' . $remote_user->email . '</strong>' ) . get_avatar( $remote_user->email, 20, '' );
			$label = __( 'Disconnect', 'madxartwork' );
			$url = $this->get_admin_url( 'disconnect' );
			$attr = '';
		} else {
			$title = __( 'Connect to madxartwork', 'madxartwork' );
			$label = __( 'Connect', 'madxartwork' );
			$url = $this->get_admin_url( 'authorize' );
			$attr = 'class="madxartwork-connect-popup"';
		}

		echo '<h1>' . __( 'Connect', 'madxartwork' ) . '</h1>';

		echo sprintf( '%s <a %s href="%s">%s</a>', $title, $attr, esc_attr( $url ), esc_html( $label ) );
	}
}
