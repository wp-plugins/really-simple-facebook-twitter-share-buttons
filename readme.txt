=== Really simple Facebook Twitter share buttons ===
Contributors: whiletrue
Donate link: http://www.whiletrue.it/
Tags: facebook, twitter, facebook share, twitter share, facebook share button, twitter share button, linkedin, google buzz, buzz, digg, stumbleupon, hyves, links, post, page, mail, email, reddit, shortcode
Requires at least: 2.9+
Tested up to: 3.1
Stable tag: 1.5.1

Puts Facebook, Twitter, LinkedIn and other share buttons of your choice above or below your posts.

== Description ==
This plugin shows Facebook, Twitter and other share buttons above or below your posts.
Easy customization of active buttons and position in the Settings menu.

Facebook Like and Twitter Share buttons are loaded by default. 
Other buttons, including Digg, LinkedIn, Google Buzz, Stumbleupon, Hyves, Email, Reddit (and the deprecated Facebook Share button), can be added through the `Settings->Really simple share` menu.

Please be careful when selecting the `Show buttons in these pages` options : it can interact badly with other slide/fade/carousel/sidebar active plugins.

If you want to place the active buttons only in selected posts, use the [really_simple_share] shortcode.

If you want to hide the share buttons inside selected posts, set the "really_simple_share_disable" custom field with value "yes".

For more informations: http://www.whiletrue.it/en/projects/wordpress/22-really-simple-facebook-twitter-share-buttons-per-wordpress.html

== Installation ==
1. Upload the `really-simple-facebook-twitter-share-buttons` directory into the `/wp-content/plugins/` directory
2. Set your favourite values in the `Settings->Really simple share` menu in Wordpress
3. Activate the plugin through the `Plugins` menu in WordPress
4. Enjoy!

== Frequently Asked Questions ==

= What's the difference between Facebook Like and Share buttons? =
Facebook Like's behaviour is similar to Facebook Share: it is a counter and if you click it a story is published inside your Wall on Facebook.
We suggest you to use Facebook Like because it works better identifying title and text for the story to be published and it's the only one currently developed by Facebook.

= Why users can't choose which image to share when using Facebook Like button ? =
This is an automated Facebook behaviour: clicking Facebook Like the user can't choose each time which image to share, 
but you can set the right image inside the code using the 
<a href="http://developers.facebook.com/docs/reference/plugins/like/">Open Graph Tag</a> og:image.

= When I activate the plugin it messes up with other plugins showing post excerpts in different ways (fade, carousel, sidebar). What can I do? =
Uncheck all the "Show buttons in these pages" options in the `Settings->Really simple share` menu, except for "Single posts".
This way all the share buttons should disappear, except the one displayed beside the post in every Single post page.

= Is it possible to modify the style/css of the buttons? =
Yes, every button has its own div class (e.g. "really_simple_share_twitter") for easy customization inside the theme css files.
Plus, the div surrounding all buttons has its own class "really_simple_share".

= Is it possible to show the buttons anywhere inside my theme, using a PHP function? =
Yes, you can call the really_simple_share_publish($link='', $title='') PHP function, passing it the link and the title used by the buttons.
You shouldn't leave the parameters blank, unless the code is put inside the WP loop.
For example, use this code to create buttons linking to the website home page:
echo really_simple_share_publish(get_bloginfo('url'), get_bloginfo('name')); 

= How about other social networks? =
We'll see!
 

== Screenshots ==
1. Sample content, activating the Facebook Share and Twitter buttons  
2. Options available in the Settings menu 

== Changelog ==

= 1.5.1 =
* Added: possibility to hide the Twitter button counter 

= 1.5.0 =
* Added: possibility to use the "really_simple_share_publish" PHP function to publish the buttons inside the PHP code, for themes and other plugins
* Changed: single permalink and title loading, for better performance

= 1.4.16 =
* Fixed: css improvements

= 1.4.15 =
* Added: Facebook Like button new "Send" option (currently via FBML)
* Changed: admin css improvements

= 1.4.13 =
* Fixed: css improvements

= 1.4.12 =
* Fixed: more vertical space (for the current Facebook Like button)

= 1.4.11 =
* Changed: removed redundant <br /> element

= 1.4.10 =
* Fixed: Digg button JS removed from the <head> section

= 1.4.9 =
* Fixed: Email share button image absolute path

= 1.4.8 =
* Added: Reddit share button

= 1.4.7 =
* Added: Email share button
* Added: Possibility to position the buttons above and below the post content
* Fixed: PHP Notices

= 1.4.6 =
* Changed: [really_simple_share] shortcode works even when "really_simple_share_disable" is used (thanks to Chestel!)

= 1.4.5 =
* Added: "really_simple_share_disable" custom field, if set to "yes" hides share buttons inside post content
* Added: Facebook Like and Twitter button width customization via the options menu 

= 1.4.4 =
* Added: [really_simple_share] shortcode, shows active share buttons inside post content

= 1.4.3 =
* Added: Hyves (the leading Duch social network) button
* Fixed: Twitter button fixed-width style for WPtouch compatibility

= 1.4.2 =
* Fixed: Excerpt/Content and JavaScript loading

= 1.4.1 =
* Added: Facebook Like text customization (like/recommend)
* Fixed: Show in Search results

= 1.4.0 =
* Changed: Avoid multiple external JavaScript files loading when possible, for better performance
* Added: "Show in Search results" option
* Fixed: Twitter title button

= 1.3.1 =
* Added: Twitter additional text option, e.g. ' (via @authorofblogentry)'
* Changed: Settings display improvement

= 1.3.0 =
* Added: Digg and Stumbleupon share buttons
* Added: CSS classes for easy styling

= 1.2.3 =
* Fixed: Facebook share button

= 1.2.2 =
* Added: Facebook like button (Facebook share is still present but deprecated)
* Added: Google Buzz share button
* Fixed: Button positioning
* Changed: Save/retrieve options standardization

= 1.2.1 =
* Fixed: Button links

= 1.2.0 =
* Added: Active buttons option
* Added: Active locations (home page, single posts, pages, tags, categories, date based archives, author archives) option

= 1.1.0 =
* Added: LinkedIn share button

= 1.0.1 =
* Fixed: Uninstall

= 1.0.0 =
Initial release


== Upgrade Notice ==

= 1.4.2 =
Users having version 1.4.0 and 1.4.1 are advised to upgrade due to an Excerpt/Content and JavaScript loading bugfix

= 1.2.2 =
Facebook Share button is deprecated in favor of Facebook Like button

= 1.0.0 =
Initial release


== Upcoming features ==

* Button text customization
* Share buttons widget
* Shortcodes for single buttons
* Support for more share buttons (flattr etc.)