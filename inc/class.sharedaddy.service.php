<?php

/**
 * Created by IntelliJ IDEA.
 * User: hina
 * Date: 2016/02/15
 * Time: 1:15
 */
class Share_Push7 extends Sharing_Source {

	public $shortname = 'push7';

	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style ) {
			$this->smart = true;
		} else {
			$this->smart = false;
		}
	}


	public function get_name() {
		return _x( 'Push Notification', 'as sharing source', 'push7-subscribe-button' );
	}

	public function has_custom_button_style() {
		return $this->smart;
	}

	public function get_display( $post ) {
		if ( $this->smart ) {
			return Push7_Subscribe_Button::get_official_button( Push7_Subscribe_Button::get_appid_inc_official() );
		} else {
			return $this->get_link( 'about:blank', _x( 'Subscribe', 'share to', 'push7-subscribe-button' ), __( 'Click to subscribe push notification with Push7', 'push7-subscribe-button' ), 'appid=' . Push7_Subscribe_Button::get_appid_inc_official(), 'sharing-push7-' . $post->ID );
		}
	}

	public function display_footer() {
		if ( $this->smart ) {
			wp_enqueue_script( 'push7-subscribe-button' );
		} else {
			?>
			<style>a.share-push7::before {
					content: ' ';
					background-image: url(data:image/svg+xml;base64,PHN2ZyBzdHlsZT0iIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMTMuNDYgMjE0LjMyIj48ZGVmcz48c3R5bGU+LmNscy0xIHtmaWxsOiAjNTU1O308L3N0eWxlPjwvZGVmcz48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iNjQuNjEgMCAwIDEyMS4zNCA2Ni4xOCAxMjEuMzQgNDAuOTcgMjE0LjMyIDExMy40NiA4Ni42NyA0NC4xMiA4Ni42NyA2NC42MSAwIi8+PC9zdmc+);
					background-size: contain;
					background-repeat: no-repeat;
					background-position: center;
					width: 16px;
					height: 16px;
				}
				.sd-social-icon .sd-content ul li[class*='share-'] a.share-push7.sd-button.share-icon.no-text {
					background: #EEAC00;
				}
				a.share-push7.share-icon.no-text::before{
					background-image: url(data:image/svg+xml;base64,PHN2ZyBzdHlsZT0iIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMTMuNDYgMjE0LjMyIj48ZGVmcz48c3R5bGU+LmNscy0xIHtmaWxsOiAjZmZmO308L3N0eWxlPjwvZGVmcz48cG9seWdvbiBjbGFzcz0iY2xzLTEiIHBvaW50cz0iNjQuNjEgMCAwIDEyMS4zNCA2Ni4xOCAxMjEuMzQgNDAuOTcgMjE0LjMyIDExMy40NiA4Ni42NyA0NC4xMiA4Ni42NyA2NC42MSAwIi8+PC9zdmc+);
				}
			</style>
			<?php
			wp_enqueue_script( 'push7-custom-button' );
			$this->js_dialog( $this->shortname );
		}
	}
}
