<?php

/**
 * Plugin Name: Push7 Subscribe Button
 * Version: 0.1-alpha
 * Description: Easy setting Push7 subscribe button
 * Author: hinaloe
 * Author URI: https://hinaloe.net/
 * Plugin URI: https://hinaloe.net/portfolio/push7-subscribe-button
 * Text Domain: push7-subscribe-button
 * Domain Path: /languages
 * @package Push7 Subscribe button
 */
if ( ! defined( 'ABSPATH' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class Push7_Subscribe_Button {

	const APP_ID_PATTERN = '[0-9a-f]{32}';
	const APP_ID_PATTERN_PREG = '/\A[0-9a-f]{32}\z/';
	const PUSH7_APPNO_NAME = 'push7_appno';
	const PLUGIN_OPTIONS = 'push7sb_option';
	const MAIN_ENTRY = __FILE__;

	/**
	 * @return Push7_Subscribe_Button
	 */
	public static function get_instance() {
		static $instance;
		if ( ! $instance instanceof Push7_Subscribe_Button ) {
			$instance = new static;
		}

		return $instance;
	}


	/**
	 * Push7_Subscribe_Button constructor.
	 */
	private function __construct() {
		$this->add_actions();
		$this->jetpack();

	}

	/**
	 * Add action or filter hooks
	 */
	private function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Register scripts
	 */
	public function register_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'push7-subscribe-button', 'https://push.app.push7.jp/static/button/p7.js', array(), false, true );
		wp_register_script( 'push7-custom-button', plugins_url( 'js/push7sb' . $suffix . '.js', __FILE__ ), array( 'jquery' ), false, true );
		wp_register_style( 'push7-custom-button', plugins_url( 'css/front' . $suffix . '.css', __FILE__ ) );
	}

	public function register_widget() {
		register_widget( 'Push7_Subscribe_Button_Widget' );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'push7-subscribe-button', null, basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * @return array
	 */
	public static function get_sbztypes() {
		$modes = array(
			'simple'    => array(
				'file'  => dirname( __FILE__ ) . '/inc/socialbuzz/class.simple.php',
				'class' => '\Push7SubscribeButtoon\SocialBuzz\SocialSimple',
				'name'  => __( 'Simple', 'push7-subscribe-button' ),
			),
			'withthumb' => array(
				'file'  => dirname( __FILE__ ) . '/inc/socialbuzz/class.withthumb.php',
				'class' => '\Push7SubscribeButtoon\SocialBuzz\SocialWithThumb',
				'name'  => __( 'With Thumbnail or Site icon', 'push7-subscribe-button' ),
			),
		);

		return apply_filters( 'push7_sb_socialbuzz_types', $modes );
	}

	/**
	 * Register Shortcode
	 */
	public function register_shortcodes() {
		add_shortcode( 'push7-sb', array( $this, 'shortcode' ) );
	}

	/**
	 * @param array $atts
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {

		$a = shortcode_atts( array(
			'id'   => self::get_appid_inc_official(),
			'type' => self::get_shortcode_type(),
		), $atts );
		wp_enqueue_script( 'push7-subscribe-button' );

		return self::get_official_button( $a['id'], $a['type'] );

	}

	/**
	 * @return void
	 */
	public function jetpack() {
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) ) {
			require_once dirname( __FILE__ ) . '/inc/class.sharedaddy.php';
		}
	}

	public function admin_enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'push7-admin-sharing', plugins_url( 'css/admin-sharing' . $suffix . '.css', __FILE__ ) );
	}

	public static function uninstall() {
		// remove all settings of this plugin (general settings)
		delete_option( self::PLUGIN_OPTIONS );
	}

	/**
	 * @return string
	 */
	public static function get_appid() {
		return Push7_Subscribe_Button_Options::get_options()->appid;
	}

	/**
	 * @return string
	 */
	public static function get_appid_inc_official() {
		$app_id = self::get_appid();

		return apply_filters( 'push7_sb_appid', $app_id ? $app_id : get_option( self::PUSH7_APPNO_NAME, '' ) );
	}

	/**
	 * get option
	 *
	 * @return string
	 */
	public static function get_shortcode_type() {
		return get_option( 'push7_sb_sctype', '' );
	}


	/**
	 * @param string $type
	 *
	 * @return string
	 */
	private static function format_official_button_type( $type ) {
		switch ( $type ) {
			case 'r':
			case 'right':
			case 'count':
				return 'r';
				break;
			case 't':
			case 'vertical':
			case 'balloon':
				return 't';
				break;
			default:
				return 'n';
		}

	}

	/**
	 * @param string $app_id
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_official_button( $app_id, $type = '' ) {
		/**
		 * Filter Push7 Official button
		 *
		 * @param string $button button html to show
		 * @param string $app_id AppId of Push7
		 * @param string $type Button Type
		 */
		return apply_filters( 'push7_sb_official_button', sprintf( '<div class="p7-b" data-p7id="%s" data-p7c="%s"></div>', $app_id, self::format_official_button_type( $type ) ), $app_id, $type );
	}
}

register_uninstall_hook( __FILE__, array( 'Push7_Subscribe_Button', 'uninstall' ) );

add_action( 'plugins_loaded', array( 'Push7_Subscribe_Button', 'get_instance' ) );
require_once dirname( __FILE__ ) . '/inc/class.widget.php';
require_once dirname( __FILE__ ) . '/inc/class.options.php';
