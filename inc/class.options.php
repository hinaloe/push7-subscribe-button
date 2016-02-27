<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/27
 * Time: 15:40
 */
final class Push7_Subscribe_Button_Options {

	/**
	 * @var static
	 */
	private static $options = null;

	/**
	 * @var string
	 */
	private $appid;

	/**
	 * @var bool
	 */
	private $enable_social_buzz;

	/**
	 * @var string
	 */
	private $social_buzz_mode;

	/**
	 * @var string
	 */
	private $social_buzz_message;

	/**
	 * @var string[]
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
