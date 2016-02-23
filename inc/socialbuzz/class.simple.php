<?php


namespace Push7SubscribeButtoon\SocialBuzz;

require_once dirname( __FILE__ ) . '/class.base.php';

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/18
 * Time: 17:08
 */

/**
 * Class SocialSimple
 * @package Push7SubscribeButtoon\SocialBuzz
 */
class SocialSimple extends Base {

	/**
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( is_singular() ) {
			wp_enqueue_style( 'push7-custom-button' );
		}
	}

	/**
	 * @return string
	 */
	public function get_template() {
		return sprintf( '<div class=push7-sb-sbz-simple id=push7-sb-sbz>%s</div>',
			sprintf( __( '%1$s Push Notification', 'push7-subscribe-button' ), \Push7_Subscribe_Button::get_official_button( \Push7_Subscribe_Button::get_appid_inc_official(), 'r' ) )
		);

	}
}
