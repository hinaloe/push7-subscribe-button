<?php

/**
 * Created by PhpStorm.
 * User: hina
 * Date: 2017/04/01
 * Time: 17:27
 */
class Push7OfficialCompatTest extends WP_UnitTestCase {

	const OFFICIAL_APPID = 'SAMPLE_APPNO';
	const OUR_APPID = 'OV_SAMPLE_APPNO';

	const OFFICIAL_OPTION_APPID = 'push7_appno';
	const OUR_OPTION_APHID = 'push7sb_appno';

	public function setUp() {
		parent::setUp();
		update_option( self::OFFICIAL_OPTION_APPID, self::OFFICIAL_APPID );
		update_option( Push7_Subscribe_Button::PLUGIN_OPTIONS, Push7_Subscribe_Button_Options::create_options( array(
			'appid' => self::OUR_APPID,
		) ), true );
		Push7_Subscribe_Button_Options::invalidate_options();

	}

	public function test_sdk_force_disabled() {
		update_option( 'push7_sdk_enabled', 'true' );
		$this->assertSame( get_option( 'push7_sdk_enabled' ), 'false' );
	}

}
