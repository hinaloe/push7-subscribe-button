<?php

class Push7SBTest extends WP_UnitTestCase {

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

	public function test_default_option() {
		$this->assertSame( Push7_Subscribe_Button::get_appid_inc_official(), self::OUR_APPID );
		$this->assertSame( Push7_Subscribe_Button::get_appid(), self::OUR_APPID );
	}

	public function test_official_plugin_option_should_be_able_to_use() {
		$option = get_option( Push7_Subscribe_Button::PLUGIN_OPTIONS );
		$this->assertSame( $option->appid, self::OUR_APPID );
		delete_option( Push7_Subscribe_Button::PLUGIN_OPTIONS );
		$this->assertSame( Push7_Subscribe_Button::get_appid_inc_official(), self::OFFICIAL_APPID );

	}

	public function test_format_official_button_type() {

		$method = new ReflectionMethod( 'Push7_Subscribe_Button', 'format_official_button_type' );
		$method->setAccessible( true );
		$this->assertSame( $method->invoke( null, '' ), 'n' );
		$this->assertSame( $method->invoke( null, 'n' ), 'n' );
		$this->assertSame( $method->invoke( null, 'r' ), 'right' );
		$this->assertSame( $method->invoke( null, 'right' ), 'right' );
		$this->assertSame( $method->invoke( null, 'count' ), 'right' );
		$this->assertSame( $method->invoke( null, 'top' ), 'top' );
		$this->assertSame( $method->invoke( null, 'vertical' ), 'top' );
		$this->assertSame( $method->invoke( null, 'balloon' ), 'top' );


	}

	public function test_official_button() {
		$this->assertSame( Push7_Subscribe_Button::get_official_button( Push7_Subscribe_Button::get_appid_inc_official() ),
			'<div class="p7button" data-button-text="" data-align="n"></div>'
		);
	}

	public function test_shortcode() {
		$this->assertSame( do_shortcode( '[push7-sb type=s]' ), '<div class="p7button" data-button-text="" data-align="n"></div>' );
		$this->assertSame( do_shortcode( '[push7-sb id=shortappid type=balloon]' ), '<div class="p7button" data-button-text="" data-align="top"></div>' );
	}

}

