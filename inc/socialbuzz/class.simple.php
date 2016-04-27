<?php
/**
 * Social Buzz Simple Mode
 *
 * @since 0.0.1-dev
 * @package Push7_Subscribe_Button
 */


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
 * @since 0.0.1-dev
 */
class SocialSimple extends Base {

	/**
	 * @return void
	 * @since 0.0.1-dev
	 */
	public function enqueue_scripts() {
		if ( is_singular() ) {
			wp_enqueue_style( 'push7-custom-button' );
			wp_enqueue_script( 'push7-subscribe-button' );
		}
	}

	/**
	 * @return string
	 * @since 0.0.1-dev
	 */
	public function get_template() {
		return sprintf( '<div class=push7-sb-sbz-simple id=push7-sb-sbz>%s</div>',
			sprintf( __( '%1$s Push Notification', 'simple-push-subscribe-button' ), \Push7_Subscribe_Button::get_official_button( \Push7_Subscribe_Button::get_appid_inc_official(), 'r' ) )
		);

	}
}
