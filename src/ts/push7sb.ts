/// <reference path="../../typings/browser.d.ts" />
/**
 * Created by hina on 2016/02/13.
 */


module p7sb {
	"use strict";



	/**
	 * Push7 HEAD API Response
	 *
	 */
	export declare interface Push7_HEAD {
		alias: string,
		domain: string,
		icon: string,
		name: string,
		subscribers: number,
		url: string
		error?: string // Known API Bug (2016.02.15)
	}

	/**
	 * jQuery shortcut
	 * @type {JQueryStatic}
     */
	const $ = jQuery;
	/**
	 * self-update push7-jetpack-button count & url
	 */
	export let update:()=>void;

	class Push7SubscribeButton {

		/**
		 * Push7SubscribeButton constructor
		 */
		constructor() {
			$(document.body).on('post-load', this.onLoadInfinityScroll);
			p7sb.update = this.updateCount;
			this.updateCount();


		}

		/**
		 * Update Button Count & URL
		 */
		public updateCount(): void {
			const $nodes = $('a.share-push7');
			$nodes.each((index: number, element: HTMLAnchorElement)=> {
				if (element.dataset['loaded'])return;
				const href = element.href;
				if (!href || typeof href !== 'string' || href.indexOf('appid=') === -1)return;
				let appid = href.substr(href.indexOf('appid=') + 6);
				if (appid.indexOf('&') !== -1) {
					appid = appid.substr(0, appid.indexOf('&'));
				}
				if (appid.length <= 0)return;
				const $elements = $nodes.filter(`[href$="appid=${appid}"]`);
				$elements.attr('data-loaded', 'loaded');
				const url = 'https://api.push7.jp/api/v1/:app_id/head'.replace(':app_id', appid);
				$.when($.ajax({
					url,
					dataType: 'json'

				}).then((res: Push7_HEAD)=> {
					if (res.error) {
						return $.Deferred().reject(res.error);

					}
					$elements.attr('href', "https://" + (res.alias ? res.alias : res.domain));
					$elements.each(function (index, elem) {
						Push7SubscribeButton.addCount(elem, res.subscribers);

					})
				})).fail(()=>{$elements.parent().remove()});

			})
		}

		/**
		 * inject count
		 * @param element parent element
         * @param count
         */
		private static addCount(element:Element, count:number) {
			const c = document.createElement('span');
			c.className = 'share-count';
			c.textContent = Push7SubscribeButton.format_count(count);
			element.firstChild.appendChild(c);
		}


		protected onLoadInfinityScroll(e:JQueryEventObject) {
			this.updateCount();

		}

		private static format_count(count:number):string {
			if (count < 1000) {
				return count.toString();
			}
			if (count >= 1000 && count < 10000) {
				return String(count).substring(0, 1) + 'K+';
			}
			return '10K+';
		}



	}

	jQuery(()=>{ new Push7SubscribeButton()});

}
