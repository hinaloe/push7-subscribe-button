/// <reference path="../../typings/browser.d.ts" />
/**
 * Created by hina on 2016/02/13.
 */


module p7sb {



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
	"use strict";

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
		public updateCount():void {
			const $nodes = $('a.share-push7');
			$nodes.each((index:number, element:HTMLAnchorElement)=> {
				if (element.dataset['loaded'])return;
				element.dataset['loaded'] = 'loaded';
				const href = element.href;
				if (!href || typeof href !== 'string' || href.indexOf('appid=') === -1)return;
				let appid = href.substr(href.indexOf('appid=') + 6);
				if (appid.indexOf('&') !== -1) {
					appid = appid.substr(0, appid.indexOf('&'));
				}
				if (appid.length <= 0)return;
				const url = 'https://dashboard.push7.jp/api/v1/:app_id/head'.replace(':app_id', appid);
				$.ajax({
					url,
					dataType: 'json'

				}).done((res:Push7_HEAD)=> {
					element.href = "https://" + (res.alias ? res.alias : res.domain);
					Push7SubscribeButton.addCount(element, res.subscribers);
				});

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

	jQuery(()=> new Push7SubscribeButton());

}
