<?php

/**
* class for admin screens
*/
class WpPlogAdmin {

	private $plugin;

	/**
	* @param WpPlogPlugin $plugin
	*/
	public function __construct($plugin) {
		$this->plugin = $plugin;

		// add admin menu items
		add_action('admin_menu', array($this, 'addAdminMenu'));

		// add action hook for adding plugin action links
		add_action('plugin_action_links_' . WP_PLOG_PLUGIN_NAME, array($this, 'addPluginActionLinks'));

		// add filter hook for adding plugin meta links
		add_filter('plugin_row_meta', array($this, 'addPluginMetaLinks'), 10, 2);
	}

	/**
	* action hook for building admin menu
	*/
	public function addAdminMenu() {
		// add menu item to Options area
		add_options_page('WP Plogger', 'WP Plogger', 'manage_options', 'wpplogger', array($this, 'optionsAdmin'));
	}

	/**
	* action hook for adding plugin action links
	*/
	public function addPluginActionLinks($links) {
		// add settings link
		$settings_link = sprintf('<a href="options-general.php?page=wpplogger">%s</a>', __('Settings'));
		array_unshift($links, $settings_link);

		return $links;
	}

	/**
	* action hook for adding plugin meta links
	* @param array $links
	* @param string $file
	* @return array
	*/
	public function addPluginMetaLinks($links, $file) {
		if ($file === WP_PLOG_PLUGIN_NAME) {
			// add plogger admin link if plogger is installed
			if (defined('PLOGGER_DIR')) {
				$plogAdmin = site_url("/{$this->plugin->options['plogFolder']}/plog-admin/");
				$links[] = sprintf('<a href="%s" target="_blank" title="Open Plogger admin in new window">Plogger admin</a>', $plogAdmin);
			}
		}

		return $links;
	}

	/**
	* action hook for processing admin menu item
	*/
	public function optionsAdmin() {
		$admin = new WpPlogOptionsAdmin($this->plugin, 'wpplogger');
		$admin->process();
	}
}
