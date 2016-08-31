/// <reference path="../../typings/browser.d.ts" />
/**
 * Created by hina on 2016/02/13.
 */
var p7sb;
(function (p7sb) {
    "use strict";
    /**
     * jQuery shortcut
     * @type {JQueryStatic}
     */
    var $ = jQuery;
    var Push7SubscribeButton = (function () {
        /**
         * Push7SubscribeButton constructor
         */
        function Push7SubscribeButton() {
            var _this = this;
            $(document.body).on('post-load', function (e) { return _this.onLoadInfinityScroll(e); });
            p7sb.update = this.updateCount;
            this.updateCount();
        }
        /**
         * Update Button Count & URL
         */
        Push7SubscribeButton.prototype.updateCount = function () {
            var $nodes = $('a.share-push7');
            $nodes.each(function (index, element) {
                if (element.dataset['loaded'])
                    return;
                var href = element.href;
                if (!href || typeof href !== 'string' || href.indexOf('appid=') === -1)
                    return;
                var appid = href.substr(href.indexOf('appid=') + 6);
                if (appid.indexOf('&') !== -1) {
                    appid = appid.substr(0, appid.indexOf('&'));
                }
                if (appid.length <= 0)
                    return;
                var $elements = $nodes.filter("[href$=\"appid=" + appid + "\"]");
                $elements.attr('data-loaded', 'loaded');
                var url = 'https://api.push7.jp/api/v1/:app_id/head'.replace(':app_id', appid);
                $.when($.ajax({
                    url: url,
                    dataType: 'json'
                }).then(function (res) {
                    if (res.error) {
                        return $.Deferred().reject(res.error);
                    }
                    $elements.attr('href', "https://" + (res.alias ? res.alias : res.domain));
                    $elements.each(function (index, elem) {
                        Push7SubscribeButton.addCount(elem, res.subscribers);
                    });
                })).fail(function () { $elements.parent().remove(); });
            });
        };
        /**
         * inject count
         * @param element parent element
         * @param count
         */
        Push7SubscribeButton.addCount = function (element, count) {
            var c = document.createElement('span');
            c.className = 'share-count';
            c.textContent = Push7SubscribeButton.formatCount(count);
            element.firstChild.appendChild(c);
        };
        Push7SubscribeButton.prototype.onLoadInfinityScroll = function (e) {
            this.updateCount();
        };
        Push7SubscribeButton.formatCount = function (count) {
            if (count < 1000) {
                return count.toString();
            }
            if (count >= 1000 && count < 10000) {
                return String(count).substring(0, 1) + 'K+';
            }
            return '10K+';
        };
        return Push7SubscribeButton;
    }());
    jQuery(function () { new Push7SubscribeButton(); });
})(p7sb || (p7sb = {}));
