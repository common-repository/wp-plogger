=== WP Plogger ===
Contributors: webaware
Plugin Name: WP Plogger
Plugin URI: http://snippets.webaware.com.au/wordpress-plugins/wp-plogger/
Author URI: http://www.webaware.com.au/
Tags: plogger
Requires at least: 3.2.1
Tested up to: 3.5.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow Plogger, the open-source photo gallery, to be called by placing the shortcode [wp_plogger] in a page.

== Description ==

Allow Plogger, the open-source photo gallery, to be called by placing the shortcode [wp_plogger] in a page. Plogger will then be embedded in the page correctly, and will become part of your WordPress website rather than sitting outside it.

> NB: there are many better gallery plugins available for WordPress, and Plogger doesn't appear to be being maintained any more; I personally like [NextGEN Gallery](http://wordpress.org/plugins/nextgen-gallery/). Plogger can still be useful though, especially for those "Trade/Media" areas that allow journalists to download lots of product images, but I now have a plugin that lets you do that with NextGEN Gallery: [NextGEN Download Gallery](http://wordpress.org/plugins/nextgen-download-gallery/).

== Installation ==

1. install Plogger into a subfolder, following the [Plogger documentation](http://www.plogger.org/docs/install/)
2. install and activate this plugin
3. tell this plugin where Plogger is, under Settings / WP Plogger (just the name of the folder containing Plogger, e.g. plogger, trade-media, photos/gallery)
4. replace Plogger's index.php file with the code below (or just copy the file plog-index-php.txt from the plugin folder to the Plogger folder, and rename it index.php)
5. create a page with the same slug as the Plogger folder, and add [wp_plogger] to its content

`<?php
  // replace the contents of index.php in the plogger folder with this,
  // so that WordPress can render the page correctly
  define('WP_USE_THEMES', true);
  require dirname(dirname(__FILE__)) . '/wp-blog-header.php';`

You will most likely want to customise the Plogger theme, which can be done either by adding some CSS to your WordPress theme to override Plogger's CSS, or by writing a custom Plogger theme (see the Plogger website).

== Changelog ==

= 1.0.2 [2013-06-29] =
* fixed: valid nonce handling in settings screen
* changed: stop messing with PHP error levels, which was hiding some warnings
* changed: dust off the aging code a bit, refresh external links

= 1.0.1 [2012-04-06] =
* fixed: use plugin_dir_url() to get url base (SSL compatible)

= 1.0.0 [2012-01-08] =
* final cleanup for public release
