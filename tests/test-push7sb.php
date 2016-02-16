<?php

class Push7SBTest extends WP_UnitTestCase {

	const OFFICIAL_APPID = 'SAMPLE_APPNO';
	const OUR_APPID = 'OV_SAMPLE_APPNO';

	const OFFICIAL_OPTION_APPID = 'push7_appno';
	const OUR_OPTION_APHID = 'push7sb_appno';


	public function setUp() {
		parent::setUp();
		update_option( self::OFFICIAL_OPTION_APPID, self::OFFICIAL_APPID );
		update_option( self::OUR_OPTION_APHID, self::OUR_APPID );

	}

	public function test_default_option() {
		$this->assertSame( Push7_Subscribe_Button::get_appid_inc_official(), self::OUR_APPID );
	}

	public function test_official_plugin_option_should_be_able_to_use() {
		$our_appid = get_option( self::OUR_OPTION_APHID );
		$this->assertSame( $our_appid, self::OUR_APPID );
		$this->assertTrue( delete_option( self::OUR_OPTION_APHID ) );
		$this->assertSame( Push7_Subscribe_Button::get_appid_inc_official(), self::OFFICIAL_APPID );
		$this->assertTrue( update_option( self::OUR_OPTION_APHID, $our_appid ) );

	}

	public function test_format_official_button_type() {

		$method = new ReflectionMethod( 'Push7_Subscribe_Button', 'format_official_button_type' );
		$method->setAccessible( true );
		$this->assertSame( $method->invoke( null, '' ), 'n' );
		$this->assertSame( $method->invoke( null, 'n' ), 'n' );
		$this->assertSame( $method->invoke( null, 'r' ), 'r' );
		$this->assertSame( $method->invoke( null, 'right' ), 'r' );
		$this->assertSame( $method->invoke( null, 'count' ), 'r' );
		$this->assertSame( $method->invoke( null, 't' ), 't' );
		$this->assertSame( $method->invoke( null, 'vertical' ), 't' );
		$this->assertSame( $method->invoke( null, 'balloon' ), 't' );


	}

	public function test_official_button() {
		$this->assertSame( Push7_Subscribe_Button::get_official_button( Push7_Subscribe_Button::get_appid_inc_official() ),
			'<div class="p7-b" data-p7id="' . self::OUR_APPID . '" data-p7c="n"></div>'
		);
	}

	public function test_shortcode() {
		$this->assertSame( do_shortcode( '[push7-sb type=s]' ), '<div class="p7-b" data-p7id="' . self::OUR_APPID . '" data-p7c="n"></div>' );
		$this->assertSame( do_shortcode( '[push7-sb id=shortappid type=balloon]' ), '<div class="p7-b" data-p7id="shortappid" data-p7c="t"></div>' );
	}

}

