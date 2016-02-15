<?php

/**
 * Load and register Jetpack Sharedaddy Services
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'jetpack_modules_loaded', array( 'Push7SB_Jetpack', 'init' ), 11 );


class Push7SB_Jetpack {

	/**
	 * @var self
	 */
	static $instance;


	/**
	 * @return Push7SB_Jetpack
	 */
	public static function init() {
		if ( ! class_exists( 'Jetpack' ) or ! Jetpack::is_module_active( 'sharedaddy' ) ) {
			return null; // end
		}


		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Push7SB_Jetpack constructor.
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
	 */
	public function add_sharing_services( array $services ) {
		require_once dirname( __FILE__ ) . '/class.sharedaddy.service.php';
		if ( ! array_key_exists( 'push7', $services ) ) {
			$services['push7'] = 'Share_Push7';
		}

		return $services;
	}
}
