<?php

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
	 */
	final function the_content( $content ) {
		global $wp_current_filter;
		if ( ! is_singular() || in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
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
		 * @global \WP_Post $post
		 */
		$content .= apply_filters( 'push7_sb_sb_template', $this->get_template(), $this );

		return $content;


	}

	/**
	 * @return void
	 */
	abstract public function enqueue_scripts();

	/**
	 * @return string
	 */
	abstract public function get_template();


	protected function add_actions() {
		$priority = (int) apply_filters( 'push7_sb_sb_priority', 14 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'display_head' ) );
		add_filter( 'the_content', array( $this, 'the_content' ), $priority );
		add_action( 'wp_footer', array( $this, 'display_footer' ) );

	}

	/**
	 * on Header
	 */
	public function display_head() {
	}

	/**
	 * on Footer
	 */
	public function display_footer() {
	}
}
