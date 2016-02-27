/**
 * Created by hina on 2016/02/26.
 */
module p7sb {
	"use strict";
	module admin {
		jQuery(($:JQueryStatic)=> {
			const $panel = $('.push7ssb-option');
			const $isSbz = $panel.find('#push7ssb_enable_sbz');
			if (!$panel.length)return;
			const toggleSocialBuzz = ()=> {
				const $sbzArea = $panel.find('.social_buzz');
				$isSbz.prop('checked') ? $sbzArea.show(550) : $sbzArea.hide(550);
			};
			const toggleMessage = () => {
				const $msg = $panel.find('#push7ssb_sbz_message').parents('tr');
				$panel.find('#push7ssb_sbz_mode').val() === 'withthumb' ? $msg.show(550) : $msg.hide(550);
			};
			if ($isSbz.prop('checked')) toggleSocialBuzz();
			$isSbz.change(e=>toggleSocialBuzz());
			toggleMessage();
			$panel.find('#push7ssb_sbz_mode').change(toggleMessage);

		});
	}
}
