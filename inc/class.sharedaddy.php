<?php

/**
 * Load and register Jetpack Sharedaddy Services
 *
 * @package Push7_Subscribe_Button
 * @since 0.0.1-dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'jetpack_modules_loaded', array( 'Push7SB_Jetpack', 'init' ), 11 );

/**
 * Class Push7SB_Jetpack
 *
 * Jetpack Integration
 * @since 0.0.1-dev
 */
class Push7SB_Jetpack {

	/**
	 * @var self
	 * @since 0.0.1-dev
	 */
	private static $instance;


	/**
	 * @return Push7SB_Jetpack
	 * @since 0.0.1-dev
	 */
	public static function init() {
		if ( ! class_exists( 'Jetpack' ) or ! Jetpack::is_module_active( 'sharedaddy' ) ) {
			return null; // End.
		}


		if ( ! static::$instance ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Push7SB_Jetpack constructor.
	 *
	 * @since 0.0.1-dev
	 */
	private function __construct() {
		add_filter( 'sharing_services', array( $this, 'add_sharing_services' ) );
	}

	/**
	 * @param array $services
	 *
	 * @return array
	 *
	 * @ref /jetpack/modules/sharedaddy/sharing-service.php:64
	 * @since 0.0.1-dev
	 */
	public function add_sharing_services( array $services ) {
		require_once dirname( __FILE__ ) . '/class.sharedaddy.service.php';
		if ( ! array_key_exists( 'push7', $services ) ) {
			$services['push7'] = 'Share_Push7';
		}

		return $services;
	}
}
