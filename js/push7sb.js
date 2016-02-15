/// <reference path="../../typings/browser.d.ts" />
/**
 * Created by hina on 2016/02/13.
 */
var p7sb;
(function (p7sb) {
    /**
     * jQuery shortcut
     * @type {JQueryStatic}
     */
    var $ = jQuery;
    "use strict";
    var Push7SubscribeButton = (function () {
        /**
         * Push7SubscribeButton constructor
         */
        function Push7SubscribeButton() {
            $(document.body).on('post-load', this.onLoadInfinityScroll);
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
                element.dataset['loaded'] = 'loaded';
                var href = element.href;
                if (!href || typeof href !== 'string' || href.indexOf('appid=') === -1)
                    return;
                var appid = href.substr(href.indexOf('appid=') + 6);
                if (appid.indexOf('&') !== -1) {
                    appid = appid.substr(0, appid.indexOf('&'));
                }
                if (appid.length <= 0)
                    return;
                var url = 'https://api.push7.jp/api/v1/:app_id/head'.replace(':app_id', appid);
                $.when($.ajax({
                    url: url,
                    dataType: 'json'
                }).then(function (res) {
                    if (res.error) {
                        return $.Deferred().reject(res.error);
                    }
                    element.href = "https://" + (res.alias ? res.alias : res.domain);
                    Push7SubscribeButton.addCount(element, res.subscribers);
                })).fail(function () { return element.parentNode.removeChild(element); });
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
            c.textContent = Push7SubscribeButton.format_count(count);
            element.firstChild.appendChild(c);
        };
        Push7SubscribeButton.prototype.onLoadInfinityScroll = function (e) {
            this.updateCount();
        };
        Push7SubscribeButton.format_count = function (count) {
            if (count < 1000) {
                return count.toString();
            }
            if (count >= 1000 && count < 10000) {
                return String(count).substring(0, 1) + 'K+';
            }
            return '10K+';
        };
        return Push7SubscribeButton;
    })();
    jQuery(function () { return new Push7SubscribeButton(); });
})(p7sb || (p7sb = {}));
