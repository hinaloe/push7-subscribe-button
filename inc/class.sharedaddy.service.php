<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/15
 * Time: 1:15
 */

/**
 * Class Share_Push7
 *
 * Push7 for Jetpack Share button
 */
class Share_Push7 extends Sharing_Source {

	/**
	 * @var string shortname
	 */
	public $shortname = 'push7';

	/**
	 * Share_Push7 constructor.
	 *
	 * @param $id
	 * @param array $settings
	 */
	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style ) {
			$this->smart = true;
		} else {
			$this->smart = false;
		}
	}


	/**
	 * Sharing source name
	 *
	 * @return string
	 */
	public function get_name() {
		return _x( 'Push Notification', 'as sharing source', 'push7-subscribe-button' );
	}

	/**
	 * @return bool
	 */
	public function has_custom_button_style() {
		return $this->smart;
	}

	/**
	 * Get Button for front
	 *
	 * @param $post WP_Post
	 *
	 * @return string
	 */
	public function get_display( $post ) {
		if ( $this->smart ) {
			return Push7_Subscribe_Button::get_official_button( Push7_Subscribe_Button::get_appid_inc_official() );
		} else {
			return $this->get_link( 'about:blank', _x( 'Subscribe', 'share to', 'push7-subscribe-button' ), __( 'Click to subscribe push notification with Push7', 'push7-subscribe-button' ), 'appid=' . Push7_Subscribe_Button::get_appid_inc_official(), 'sharing-push7-' . $post->ID );
	public function display_header() {
		if ( ! $this->smart ) {
			wp_enqueue_style( 'push7-custom-button' );
		}
	}

	/**
	 * Footer action
	 */
	public function display_footer() {
		if ( $this->smart ) {
			wp_enqueue_script( 'push7-subscribe-button' );
		} else {
			wp_enqueue_script( 'push7-custom-button' );
			$this->js_dialog( $this->shortname );
		}
	}
}
