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
	 * @since 0.0.1-dev
	 */
	public $shortname = 'push7';

	/**
	 * Share_Push7 constructor.
	 *
	 * @param $id
	 * @param array $settings
	 * @since 0.0.1-dev
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
	 * @since 0.0.1-dev
	 */
	public function get_name() {
		return _x( 'Push Notification', 'as sharing source', 'simple-push-subscribe-button' );
	}

	/**
	 * @return bool
	 * @since 0.0.1-dev
	 */
	public function has_custom_button_style() {
		return $this->smart;
	}

	/**
	 * Get Button for front
	 *
	 * @param $post WP_Post
	 * @since 0.0.1-dev
	 *
	 * @return string
	 */
	public function get_display( $post ) {
		if ( $this->smart ) {
			return Push7_Subscribe_Button::get_official_button( Push7_Subscribe_Button::get_appid_inc_official() );
		} else {
			return $this->get_link(
				'about:blank',
				/**
				 * Filter Push7 Share Button title text
				 *
				 * @param string $text
				 * @since 0.0.1-dev
				 */
				apply_filters( 'push7_sb_share_title', _x( 'Subscribe', 'share to', 'simple-push-subscribe-button' ) ),
				/**
				 * Filter Push7 Share Button description
				 *
				 * @param string $description
				 * @since 0.0.1-dev
				 */
				apply_filters( 'push7_sb_share_description', __( 'Click to subscribe push notification with Push7', 'simple-push-subscribe-button' ) ),
				'appid=' . Push7_Subscribe_Button::get_appid_inc_official(), 'sharing-push7-' . $post->ID
			);
		}
	}

	public function display_header() {
		if ( ! $this->smart ) {
			wp_enqueue_style( 'push7-custom-button' );
		}
	}

	/**
	 * Footer action
	 * @since 0.0.1-dev
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
