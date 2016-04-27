<?php
/**
 * Social Buzz With Thumbnail Mode
 *
 * @since 0.0.1-dev
 * @package Push7_Subscribe_Button
 */


namespace Push7SubscribeButtoon\SocialBuzz;

require_once dirname( __FILE__ ) . '/class.base.php';

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/18
 * Time: 17:08
 */

/**
 * Class SocialWithThumb
 * @package Push7SubscribeButtoon\SocialBuzz
 * @since 0.0.1-dev
 */
class SocialWithThumb extends Base {

	/**
	 * @return void
	 * @since 0.0.1-dev
	 */
	public function enqueue_scripts() {
		if ( is_singular() ) {
			wp_enqueue_style( 'push7-custom-button' );
			wp_add_inline_style( 'push7-custom-button', $this->get_css() );
			wp_enqueue_script( 'push7-subscribe-button' );


		}
	}

	/**
	 * @return string
	 * @since 0.0.1-dev
	 */
	public function get_template() {
		return sprintf( '<div class="push7-sb-sbz-with-thumb" id="push7-sb-sbz"><div class="push7-sb-sbz-with-thumb-thumbnail"></div><div class="push7-sb-sbz-with-thumb-subscribe">%s</div></div>',
			sprintf(
				'<p>%s</p><div class="push7ssb-subscribe">%s</div>'
				, \Push7_Subscribe_Button_Options::get_options()->social_buzz_message,
				\Push7_Subscribe_Button::get_official_button( \Push7_Subscribe_Button::get_appid_inc_official(), 'r' )
			)
		);

	}

	/**
	 * @return string Inline CSS
	 * @since 0.0.1-dev
	 */
	private function get_css() {
		if ( has_post_thumbnail() && ! post_password_required() ) {
			$thumb = esc_url( get_the_post_thumbnail_url( null, 'push7ssb-sbz-thumbnail' ) );
		} elseif ( has_site_icon() ) {
			$thumb = esc_url( get_site_icon_url() );
		} else {
			$thumb = '';
		}

		return <<<EOI
.push7-sb-sbz-with-thumb {
	background-image: url({$thumb});
}
.push7-sb-sbz-with-thumb-subscribe {
	background-color: rgba(43,43,43, 0.7);
	color: #ffffff;
}
@media only screen and (min-width : 415px) {
	.push7-sb-sbz-with-thumb-thumbnail {
		background-image: url({$thumb});
	}
	.push7-sb-sbz-with-thumb-subscribe {
		background-color: rgba(43,43,43, 1);
	}
}
EOI;

	}
}
