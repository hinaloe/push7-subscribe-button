<?php

/**
 * Plugin Name: Push7 Subscribe Button
 * Version: 1.0.3
 * Description: Easy setting Push7 subscribe button
 * Author: hinaloe
 * Author URI: https://hinaloe.net/
 * Plugin URI: https://hinaloe.net/portfolio/push7-subscribe-button
 * Text Domain: simple-push-subscribe-button
 * Domain Path: /languages
 * @package Push7 Subscribe button
 */
if ( ! defined( 'ABSPATH' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * Class Push7_Subscribe_Button
 *
 * @since 0.0.1-dev
 */
class Push7_Subscribe_Button {

	const APP_ID_PATTERN = '[0-9a-f]{32}';
	const APP_ID_PATTERN_PREG = '/\A[0-9a-f]{32}\z/';
	const PUSH7_APPNO_NAME = 'push7_appno';
	const PLUGIN_OPTIONS = 'push7sb_option';
	const MAIN_ENTRY = __FILE__;

	/**
	 * get singleton instance
	 *
	 * @since 0.0.1-dev
	 *
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
	 * @since 0.0.1-dev
	 */
	private function __construct() {
		$this->add_actions();
		$this->load_textdomain();
		$this->jetpack();

	}

	/**
	 * Add action or filter hooks
	 * @since 0.0.1-dev
	 */
	private function add_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'init', array( $this, 'load_sbz' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
//		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Register scripts
	 * @since 0.0.1-dev
	 */
	public function register_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'push7-subscribe-button', 'https://push.app.push7.jp/static/button/p7.js', array(), false, true );
		wp_register_script( 'push7-custom-button', plugins_url( 'js/push7sb' . $suffix . '.js', __FILE__ ), array( 'jquery' ), false, true );
		wp_register_style( 'push7-custom-button', plugins_url( 'css/front' . $suffix . '.css', __FILE__ ) );
	}

	/**
	 * Register plugin widgets
	 *
	 * @since 0.0.1-dev
	 */
	public function register_widget() {
		register_widget( 'Push7_Subscribe_Button_Widget' );
	}

	/**
	 * Load textdomain for plugin
	 *
	 * @since 0.0.1-dev
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'simple-push-subscribe-button', null, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Load SocialBuzz Module
	 *
	 * @since 0.0.1-dev
	 */
	public function load_sbz() {
		$option = Push7_Subscribe_Button_Options::get_options();
		if ( $option->enable_social_buzz ) {
			$sbztypes = self::get_sbztypes();
			$sm       = $sbztypes[ $option->social_buzz_mode ];
			if ( ! file_exists( $sm['file'] ) ) {
				trigger_error( $sm['file'] . ' is not found at ' . __FILE__ . ' on line ' . __LINE__ );

				return;
			}
			require_once $sm['file'];
			if ( ! class_exists( $sm['class'] ) ) {
				trigger_error( 'Class ' . $sm['class'] . ' is not found at ' . __FILE__ . ' on line ' . __LINE__ );

				return;

			}
			if ( ! is_callable( array( $sm['class'], 'get_instance' ), null, $name ) ) {
				trigger_error( $name . ' is not callable at ' . __FILE__ . ' on line ' . __LINE__ );

				return;
			}
			call_user_func( array( $sm['class'], 'get_instance' ) );

		}

	}

	/**
	 * @since 0.0.1-dev
	 * @return array
	 */
	public static function get_sbztypes() {
		$modes = array(
			'simple'    => array(
				'file'  => dirname( __FILE__ ) . '/inc/socialbuzz/class.simple.php',
				'class' => '\Push7SubscribeButtoon\SocialBuzz\SocialSimple',
				'name'  => __( 'Simple', 'simple-push-subscribe-button' ),
			),
			'withthumb' => array(
				'file'  => dirname( __FILE__ ) . '/inc/socialbuzz/class.withthumb.php',
				'class' => '\Push7SubscribeButtoon\SocialBuzz\SocialWithThumb',
				'name'  => __( 'With Thumbnail or Site icon', 'simple-push-subscribe-button' ),
			),
		);

		/**
		 * Filter Social buzz modules
		 *
		 * @since 0.0.1-dev
		 *
		 * @param array[] $modes {
		 *
		 *      @type string $file filename
		 *      @type string $class classname
		 *      @type string $name Module name to show
		 * }
		 */
		return apply_filters( 'push7_sb_socialbuzz_types', $modes );
	}

	/**
	 * Register Shortcode
	 *
	 * @since 0.0.1-dev
	 */
	public function register_shortcodes() {
		add_shortcode( 'push7-sb', array( $this, 'shortcode' ) );
	}

	/**
	 * Push7 Button Shortcode Handler
	 *
	 * @since 0.0.1-dev
	 *
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
	 *
	 * @since 0.0.1-dev
	 *
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

	/*
		public function admin_init() {
			require_once dirname( __FILE__ ) . '/inc/admin/class.admin.php';
			\Push7SubscribeButtoon\Admin\Admin::get_instance();
		}*/

	public function admin_menu() {
		require_once dirname( __FILE__ ) . '/inc/admin/class.admin.php';
		\Push7SubscribeButtoon\Admin\Admin::get_instance()->admin_menu();

	}

	/**
	 * Uninstall action hook
	 *
	 * @since 0.0.1-dev
	 */
	public static function uninstall() {
		// remove all settings of this plugin (general settings)
		delete_option( self::PLUGIN_OPTIONS );
	}

	/**
	 * @since 0.0.1-dev
	 * @return string
	 */
	public static function get_appid() {
		return Push7_Subscribe_Button_Options::get_options()->appid;
	}

	/**
	 * @since 0.0.1-dev
	 * @return string
	 */
	public static function get_appid_inc_official() {
		$app_id = self::get_appid();

		/**
		 * Filter Push7 APPNO to use is plugin
		 *
		 * @since 0.0.1-dev
		 *
		 * @param string $appno APPNO
		 */
		return apply_filters( 'push7_sb_appid', $app_id ? $app_id : get_option( self::PUSH7_APPNO_NAME, '' ) );
	}

	/**
	 * get option
	 *
	 * @since 0.0.1-dev
	 *
	 * @return string
	 */
	public static function get_shortcode_type() {
		return get_option( 'push7_sb_sctype', '' );
	}


	/**
	 * @param string $type
	 *
	 * @since 0.0.1-dev
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
	 * Get Push7 Official Button HTML
	 *
	 * @since 0.0.1-dev
	 *
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
