=== Push7 Subscribe button ===
Contributors: hnle
Donate link: https://hinaloe.net/donate
Tags: Push Notification, desktop notifications, notifications, web push
Requires at least: 4.2
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy setup Push7 Subscribe Button, and inject Jetpack

== Description ==

This plugin is a one of the best way to setup Push7 subscribe button.

When you use [Push7](https://push7.jp/) on your WordPress site,
you have to register and install [Official plugin](https://wordpress.org/plugins/push7/).

This plugin only provide Subscribe button interface.

= Feature =

- put official button everywhare with shortcode
- Various types of announcement under article (developing)
- Add official or unified button into Jetpack Share Buttons(Shardaddy).
- Use App ID set by Official Plugin, or your custom APP ID

= Shortcode =

= Basic usage =
`[push7-sb]`

= Advanced Usage =
`[push7-sb id="YOUR_CUSTOM_APPID" type="BUTTON_TYPE"]`

Options:

- `id`: Push7 AppId
- `type`: Button type to show. `r`(Count in right),`t`(vertical balloon),`n`(No count). default is `n`

----

This plugin is developing on [GitHub](https://github.com/hinaloe/push7-subscribe-button/)

== Installation ==

1. Install from plugin directory on dashboard
1. Activate it



== Frequently Asked Questions ==

= Can I use this plugin without Push7 Official plugin? =

Yes, but You couldn't send notification without that.



== Screenshots ==

1. Jetpack integration
2. Jetpack ShareButton (icon only)
3. Jetpack ShareButton (Icon+Text)
4. Jetpack ShareButton (Official)
5. Subscribe Button Widget

== Changelog ==

= 1.1.0 =
* Optimize performance with omit depulicate request

= 1.0.4 =

* fix: SocialBuzz couldn't show official button

= 1.0.3 =
* fix translation issue

= 1.0.2 =
* fix text domain

= 0.1 =
* Init release

== Upgrade Notice ==

none
