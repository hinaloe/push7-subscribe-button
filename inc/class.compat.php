<?php
/**
 * Created by PhpStorm.
 * User: hina
 * Date: 2017/04/01
 * Time: 17:13
 */

namespace Push7SubscribeButtoon;


class Compat {
	private static $ins;

	private function __construct() {
		$this->install();
	}

	public static function init() {
		if ( ! isset( static::$ins ) ) {
			static::$ins = new static();
		}

		return static::$ins;
	}

	private function install() {
		add_filter( 'pre_option_push7_sdk_enabled', array( $this, 'disable_official_plugin_sdk' ) );
	}

	public function disable_official_plugin_sdk() {
		return 'false';
	}
}