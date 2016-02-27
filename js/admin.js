/**
 * Created by hina on 2016/02/26.
 */
var p7sb;
(function (p7sb) {
    "use strict";
    var admin;
    (function (admin) {
        jQuery(function ($) {
            var $panel = $('.push7ssb-option');
            var $isSbz = $panel.find('#push7ssb_enable_sbz');
            if (!$panel.length)
                return;
            var toggleSocialBuzz = function () {
                var $sbzArea = $panel.find('.social_buzz');
                $isSbz.prop('checked') ? $sbzArea.show(550) : $sbzArea.hide(550);
            };
            var toggleMessage = function () {
                var $msg = $panel.find('#push7ssb_sbz_message').parents('tr');
                $panel.find('#push7ssb_sbz_mode').val() === 'withthumb' ? $msg.show(550) : $msg.hide(550);
            };
            if ($isSbz.prop('checked'))
                toggleSocialBuzz();
            $isSbz.change(function (e) { return toggleSocialBuzz(); });
            toggleMessage();
            $panel.find('#push7ssb_sbz_mode').change(toggleMessage);
        });
    })(admin || (admin = {}));
})(p7sb || (p7sb = {}));
