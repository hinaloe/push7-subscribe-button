<?php

/**
 * SocialBuzz Base Module for extend
 *
 * @package Push7_Subscribe_Button
 * @since 0.0.1-dev
 */

namespace Push7SubscribeButtoon\SocialBuzz;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/18
 * Time: 3:40
 */

/**
 * Class Base
 * @subpackage Push7SubscribeButtoon\SocialBuzz
 * @since 0.0.1-dev
 */
abstract class Base {

	private static $instances = array();

	public static function get_instance() {
		$name = get_called_class();
		if ( ! isset( self::$instances[ $name ] ) ) {
			self::$instances[ $name ] = new $name;
		}

		return self::$instances[ $name ];
	}

	private function __construct() {
		$this->add_actions();
	}

	/**
	 * Filter to the_content
	 *
	 * @param string $content
	 *
	 * @return string
	 * @since 0.0.1-dev
	 */
	final function the_content( $content ) {
		global $wp_current_filter;
		if ( ! is_singular() || in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
			return $content;
		}

		if ( ! in_array( get_post_type(), \Push7_Subscribe_Button_Options::get_options()->social_buzz_posttype ) ) {
			return $content;
		}

		if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return $content;
		}

		/**
		 * Filter button template after content like social-buzz
		 *
		 * @param string $template
		 * @param Base $button
		 *
		 * @since 0.0.1-dev
		 * @global \WP_Post $post
		 */
		$content .= apply_filters( 'push7_sb_sb_template', $this->get_template(), $this );

		return $content;


	}

	/**
	 * @return void
	 * @since 0.0.1-dev
	 */
	abstract public function enqueue_scripts();

	/**
	 * @return string
	 * @since 0.0.1-dev
	 */
	abstract public function get_template();


	/**
	 * register actions
	 *
	 * @since 0.0.1-dev
	 */
	protected function add_actions() {
		/**
		 * Push7 Social Buzz Module priority
		 *
		 * @param int $priority Social Buzz Module current Priority (default 14)
		 */
		$priority = (int) apply_filters( 'push7_sb_sb_priority', 14 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'display_head' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), $priority );
		add_action( 'wp_footer', array( $this, 'display_footer' ) );

	}

	/**
	 * on Header
	 *
	 * @since 0.0.1-dev
	 */
	public function display_head() {
	}

	/**
	 * on Footer
	 *
	 * @since 0.0.1-dev
	 */
	public function display_footer() {
	}
}
