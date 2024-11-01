<?php
/*
Plugin Name: WP Plogger
Plugin URI: http://snippets.webaware.com.au/wordpress-plugins/wp-plogger/
Description: Allow Plogger, the open-source photo gallery, to be called by placing the shortcode [wp_plogger] in a page
Version: 1.0.2
Author: WebAware Pty Ltd
Author URI: http://www.webaware.com.au/
*/

/*
copyright (c) 2011-2013 WebAware Pty Ltd (email : rmckay@webaware.com.au)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('WP_PLOG_PLUGIN_ROOT')) {
	define('WP_PLOG_PLUGIN_ROOT', dirname(__FILE__) . '/');
	define('WP_PLOG_PLUGIN_NAME', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
	define('WP_PLOG_PLUGIN_OPTIONS', 'wp_plog_plugin');

	// shortcode tags
	define('WP_PLOG_PLUGIN_TAG_PLOGGER', 'wp_plogger');
}

/**
 * autoload classes as/when needed
 *
 * use clues from names of library classes to locate them
 *
 * @param string $class_name name of class to attempt to load
 */
function wp_plog_autoload($class_name) {
	static $classMap = array (
		'WpPlogAdmin'						=> 'class.WpPlogAdmin.php',
		'WpPlogOptionsAdmin'				=> 'class.WpPlogOptionsAdmin.php',
		'WpPlogPlugin'						=> 'class.WpPlogPlugin.php',
	);

	if (isset($classMap[$class_name])) {
		require WP_PLOG_PLUGIN_ROOT . $classMap[$class_name];
	}
}

// register a class (static) method for autoloading required classes
spl_autoload_register('wp_plog_autoload');

// instantiate the plug-in
$WpPlogPlugin = WpPlogPlugin::getInstance();
