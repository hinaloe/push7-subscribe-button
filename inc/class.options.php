<?php

/**
 * Push7_Subscribe_Button_Options class for load/gen plugin settings
 *
 * @package Push7_Subscribe_Button
 * @since 0.0.1-dev
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/27
 * Time: 15:40
 */

/**
 * Class Push7_Subscribe_Button_Options
 *
 * @property-read string $appid Push7 APPNO
 * @property-read bool $enable_social_buzz Is enable Social-buzz module
 * @property-read string $social_buzz_mode Social Buzz Mode slug
 * @property-read string $social_buzz_message Message of Social-buzz
 * @property-read array $social_buzz_posttype posttype names for use social-buzz module
 *
 * @since 0.0.1-dev
 *
 */
final class Push7_Subscribe_Button_Options {

	/**
	 * Singleton Instance
	 *
	 * @since 0.0.1-dev
	 * @access private
	 * @var static
	 */
	private static $options = null;

	/**
	 * @since 0.0.1-dev
	 * @access private
	 * @var string
	 */
	private $appid;

	/**
	 * @since 0.0.1-dev
	 * @access private
	 * @var bool
	 */
	private $enable_social_buzz;

	/**
	 * @since 0.0.1-dev
	 * @access private
	 * @var string
	 */
	private $social_buzz_mode;

	/**
	 * @since 0.0.1-dev
	 * @access private
	 * @var string
	 */
	private $social_buzz_message;

	/**
	 * @since 0.0.1-dev
	 * @access private
	 * @var array {@type string}
	 */
	private $social_buzz_posttype;


	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}
		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE );

		return null;
	}

	/**
	 * Push7_Subscribe_Button_Options constructor.
	 *
	 * @since 0.0.1-dev
	 *
	 * @param array
	 */
	private function __construct( array $options ) {
		$this->appid                = $options['appid'];
		$this->enable_social_buzz   = $options['enable_social_buzz'];
		$this->social_buzz_mode     = $options['social_buzz_mode'];
		$this->social_buzz_message  = $options['social_buzz_message'];
		$this->social_buzz_posttype = $options['social_buzz_posttype'];

	}


	/**
	 * @since 0.0.1-dev
	 * @return array options
	 */
	private static function merege_option() {
		$default = array(
			'appid'                => null,
			'enable_social_buzz'   => false,
			'social_buzz_mode'     => 'simple',
			'social_buzz_message'  => __( 'Subscribe Latest Update with Push Notification!', 'push7-subscribe-button' ),
			'social_buzz_posttype' => array( 'post' ),

		);

		/**
		 * Filter default options
		 *
		 * @since 0.0.1-dev
		 *
		 * @param $default string[]
		 */
		$default         = apply_filters( 'push7_sb_default_options', $default );
		$current_options = get_option( Push7_Subscribe_Button::PLUGIN_OPTIONS, array() );
		if ( $current_options instanceof self ) {
			$current_options = get_object_vars( $current_options );
		}

		return array_merge( $default, $current_options );


	}

	/**
	 * @since 0.0.1-dev
	 * @return Push7_Subscribe_Button_Options
	 */
	public static function get_options() {
		if ( ! self::$options ) {
			self::$options = new self( self::merege_option() );
		}

		return self::$options;
	}

	public static function invalidate_options() {
		self::$options = null;
	}


	/**
	 * for save options
	 *
	 * @param array $options_in
	 *
	 * @return Push7_Subscribe_Button_Options
	 */
	public static function create_options( $options_in ) {
		if ( ! is_array( $options_in ) ) {
			if ( $options_in instanceof static ) {
				$options_in = get_object_vars( $options_in );
			} else {
				throw new RuntimeException( 'Invalid action.' );
			}
		}
		$options['appid']               = empty( $options_in['appid'] ) ? '' : $options_in['appid'];
		$options['enable_social_buzz']  =
			empty( $options_in['enable_social_buzz'] ) ? false :
				( 'true' === $options_in['enable_social_buzz'] ? true :
					( 'on' === $options_in['enable_social_buzz'] ? true :
						( true === $options_in['enable_social_buzz'] ? true :
							false ) ) );
		$options['social_buzz_message'] = empty( $options_in['social_buzz_message'] ) ? '' : $options_in['social_buzz_message'];
		$options['social_buzz_mode']    = ! empty( $options_in['social_buzz_mode'] ) && in_array( $options_in['social_buzz_mode'], array_keys( Push7_Subscribe_Button::get_sbztypes() ), true ) ? $options_in['social_buzz_mode'] : null;
		if ( empty( $options_in['social_buzz_posttype'] ) or ! is_array( $options_in['social_buzz_posttype'] ) ) {
			$options['social_buzz_posttype'] = array();
		} else {
			$post_types = get_post_types();
			foreach ( $options_in['social_buzz_posttype'] as $post_type ) {
				if ( in_array( $post_type, $post_types, true ) ) {
					$options['social_buzz_posttype'][] = $post_type;
				}
			}
		}

		return new self( $options );

	}


}
