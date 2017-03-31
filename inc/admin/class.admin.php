<?php
/**
 * Push7SubscribeButtoon\Admin class for Admin Screen
 *
 * @package Push7_Subscribe_Button
 * @since 0.0.1-dev
 */
/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/18
 * Time: 17:43
 */

namespace Push7SubscribeButtoon\Admin;

/**
 * Admin Screen Class
 *
 * @subpackage Push7SubscribeButtoon\Admin
 * @since 0.0.1-dev
 */
final class Admin {

	/**
	 * Singleton instance
	 *
	 * @since 0.0.1-dev
	 * @var static
	 */
	private static $instance;

	/**
	 * Get Singleton instance
	 *
	 * @since 0.0.1-dev
	 * @return self
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static;
		}

		return static::$instance;

	}

	/**
	 * Admin constructor.
	 *
	 * @since 0.0.1-dev
	 */
	private function __construct() {
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( \Push7_Subscribe_Button::MAIN_ENTRY ), array(
			$this,
			'settings_link',
		) );
	}


	/**
	 * Admin-init Action handler
	 *
	 * @since 0.0.1-dev
	 */
	public function init() {
		register_setting( 'push7-ssb', \Push7_Subscribe_Button::PLUGIN_OPTIONS, array(
			'Push7_Subscribe_Button_Options',
			'create_options',
		) );
		add_settings_section( 'push7_ssb_options', __( 'Push7 Subscribe Button', 'simple-push-subscribe-button' ), null, 'push7-ssb' );
		add_settings_section( 'push7_ssb_socialbuzz', __( 'Push7 Large Button', 'simple-push-subscribe-button' ), null, 'push7-ssb-sbz' );

		add_settings_field(
			'push7ssb_appno',
			'<label for="push7ssb_appno">' . __( 'Alt Push7 AppNo (Option)', 'simple-push-subscribe-button' ) . '</label>',
			array( $this, 'render_appno' ),
			'push7-ssb',
			'push7_ssb_options'

		);
		add_settings_field(
			'push7ssb_enable_sbz',
			'<label for="push7ssb_enable_sbz">' . __( 'Large link bellow post', 'simple-push-subscribe-button' ) . '</label>',
			array( $this, 'render_is_enable_sbz' ),
			'push7-ssb',
			'push7_ssb_options'
		);

		add_settings_field(
			'push7ssb_sbz_mode',
			'<label for="push7ssb_sbz_mode">' . _x( 'Type', 'SocialBuzz-Select', 'simple-push-subscribe-button' ) . '</label>',
			array( $this, 'render_sbz_mode' ),
			'push7-ssb-sbz',
			'push7_ssb_socialbuzz'
		);

		add_settings_field(
			'push7ssb_sbz_posttypes',
			'<label for="push7ssb_sbz_posttypes">' . __( 'PostTypes to show it', 'simple-push-subscribe-button' ) . '</label>',
			array( $this, 'render_sbz_posttypes' ),
			'push7-ssb-sbz',
			'push7_ssb_socialbuzz'
		);

		add_settings_field(
			'push7ssb_sbz_message',
			'<label for="push7ssb_sbz_message">' . __( 'Message', 'simple-push-subscribe-button' ) . '</label>',
			array( $this, 'render_sbz_message' ),
			'push7-ssb-sbz',
			'push7_ssb_socialbuzz'
		);


	}

	/**
	 * Admin_menu action handler
	 *
	 * @since 0.0.1-dev
	 */
	public function admin_menu() {
		add_options_page(
			__( 'Push7 Subscribe Button', 'simple-push-subscribe-button' ),
			__( 'Push7 Buttons', 'simple-push-subscribe-button' ),
			'manage_options',
			'push7-ssb',
			array( $this, 'admin_view' )
		);
	}

	/**
	 * Admin_head action handler
	 *
	 * @since 0.0.1-dev
	 */
	public function admin_head() {

	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 0.0.1-dev
	 */
	public function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_style( 'push7-admin-sharing', plugins_url( 'css/admin-sharing' . $suffix . '.css', \Push7_Subscribe_Button::MAIN_ENTRY ) );
		wp_register_script( 'push7-ssb-admin', plugins_url( 'js/admin' . $suffix . '.js', \Push7_Subscribe_Button::MAIN_ENTRY ) );

		$screen = get_current_screen();


		if ( method_exists( '\Debug_Bar_Extender', 'instance' ) ) {
			\Debug_Bar_Extender::instance()->trace_var( $screen, 'Screen' );
		}

		if ( 'settings_page_sharing' === $screen->id ) {
			wp_enqueue_style( 'push7-admin-sharing' );
		}

		if ( 'settings_page_push7-ssb' === $screen->id ) {
			wp_enqueue_style( 'push7-admin-sharing' );
			wp_enqueue_script( 'push7-ssb-admin' );
		}

	}

	/**
	 * Settings_link filter
	 *
	 * @since 0.0.1-dev
	 *
	 * @param array $actions actions.
	 *
	 * @return array
	 */
	public function settings_link( array $actions ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', esc_url( get_admin_url( null, 'options-general.php?page=push7-ssb' ) ), __( 'Settings', 'simple-push-subscribe-button' ) );
		array_unshift( $actions, $settings_link );

		return $actions;
	}

	/**
	 * Plugin Admin screen View
	 *
	 * @since 0.0.1-dev
	 */
	public function admin_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			global $current_user;
			$msg = sprintf( __( "I'm sorry, %s. I'm afraid I can't do that." ), $current_user->display_name );
			echo '<div class="wrap">' . esc_html( $msg ) . '</div>';

			return false;
		}
		?>

		<div class="wrap push7ssb-option">
			<h1><?php esc_html_e( 'Push7 Subscribe Button Settings', 'simple-push-subscribe-button' ) ?></h1>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'push7-ssb' );
				do_settings_sections( 'push7-ssb' ); ?>
				<div class="social_buzz hide">
					<?php do_settings_sections( 'push7-ssb-sbz' ); ?>
				</div>
				<?php submit_button();
				?>

			</form>

		</div>
		<?php
	}


	/**
	 * Render Appno section.
	 */
	public function render_appno() {
		printf( '<input type="text" id="push7ssb_appno" class="regular-text" name="%s" value="%s"
		       placeholder="%s" pattern="%s"
		/>', \Push7_Subscribe_Button::PLUGIN_OPTIONS . '[appid]',
			esc_attr( \Push7_Subscribe_Button_Options::get_options()->appid ),
			esc_attr( get_option( \Push7_Subscribe_Button::PUSH7_APPNO_NAME, '0123456789ABCDEFGEHIJKLMNOPQRSTU' ) ),
			\Push7_Subscribe_Button::APP_ID_PATTERN
		);
		printf( '<p class="description">%s</p>', wp_kses( __( 'Input if you want use subscribe button without Official Plugin, or another APPNO. <br>If, this value is empty, Official plugin setting will use for show button.', 'simple-push-subscribe-button' ), array( 'br' => array() ) ) );
	}

	/**
	 * Render whether enable social-buzz module
	 */
	public function render_is_enable_sbz() {
		printf( '<input type="checkbox" id="push7ssb_enable_sbz" name="%s" value="on" %s ><label for="push7ssb_enable_sbz">%s</label>',
			\Push7_Subscribe_Button::PLUGIN_OPTIONS . '[enable_social_buzz]',
			checked( \Push7_Subscribe_Button_Options::get_options()->enable_social_buzz, true, false ),
			esc_html__( 'Insert large Subscribe button bellow posts. (It likes Social Buzz)', 'simple-push-subscribe-button' )
		);
		printf( '<p class="description">%s</p>', esc_html( 'It is look like SNS button of Viral Media. You can customize the following.', 'simple-push-subscribe-button' ) );

	}

	/**
	 * Render to select social-buzz mode section
	 */
	public function render_sbz_mode() {
		$current = \Push7_Subscribe_Button_Options::get_options()->social_buzz_mode;
		echo '<select name="' . \Push7_Subscribe_Button::PLUGIN_OPTIONS . '[social_buzz_mode]" id="push7ssb_sbz_mode">';
		foreach ( \Push7_Subscribe_Button::get_sbztypes() as $slug => $attrs ) {
			printf( '<option value="%s" %s>%s</option>',
				esc_attr( $slug ),
				selected( $slug, $current, false ),
				esc_html( $attrs['name'] )
			);
		}
		echo '</select>';
	}

	/**
	 * Render what post type enabled social buzz module selection
	 */
	public function render_sbz_posttypes() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$current    = \Push7_Subscribe_Button_Options::get_options()->social_buzz_posttype;
		foreach ( $post_types as $slug => $obj ) {
			printf( '<input type="checkbox" value="%1$s" name="%2$s" %5$s id="%3$s" /><label for="%3$s">%4$s</label><br/>',
				$slug,
				\Push7_Subscribe_Button::PLUGIN_OPTIONS . '[social_buzz_posttype][]',
				'push7ssb_sbz_posttype_' . esc_attr( $slug ),
				esc_attr( $obj->labels->name ),
				checked( in_array( $slug, $current, true ), true, false )
			);
		}

	}

	/**
	 * Render Input the message for social-buzz module
	 */
	public function render_sbz_message() {
		printf( '<input type="text" id="push7ssb_sbz_message" class="regular-text" name="%s" value="%s"
		/>', \Push7_Subscribe_Button::PLUGIN_OPTIONS . '[social_buzz_message]', esc_attr( \Push7_Subscribe_Button_Options::get_options()->social_buzz_message ) );
	}
}
