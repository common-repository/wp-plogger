<?php

/**
* class for managing the plugin
*/
class WpPlogPlugin {
	public $options;
	public $urlBase;									// string: base URL path to files in plugin

	/**
	* static method for getting the instance of this singleton object
	* @return WpPlogPlugin
	*/
	public static function getInstance() {
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	* hook the plug-in's initialise event to handle all post-activation initialisation
	*/
	private function __construct() {
		// record plugin URL base
		$this->urlBase = plugin_dir_url(__FILE__);

		// grab options, setting new defaults for any that are missing
		$this->initOptions();

		// tell Plogger that it's embedded, so that it won't output page title etc.
		define('PLOGGER_EMBEDDED', '1');

		// load Plogger
		$plogPath = ABSPATH . $this->options['plogFolder'] . '/plogger.php';
		if (file_exists($plogPath)) {
			include $plogPath;

			// fix the URL to Plogger base folder, in case the shortcode is in a page not under that folder
			global $config;
			$config['baseurl'] = site_url('/' . $this->options['plogFolder'] . '/');
		}

		if (is_admin()) {
			// kick off the admin handling
			new WpPlogAdmin($this);
		}
		else {
			// add shortcodes
			add_shortcode(WP_PLOG_PLUGIN_TAG_PLOGGER, array($this, 'shortcodePlogger'));

			add_action('wp_head', array($this, 'plogHead'));
		}
	}

	/**
	* initialise plug-in options, handling undefined options by setting defaults
	*/
	private function initOptions() {
		static $defaults = array (
			'plogFolder' => 'plogger',
		);

		$this->options = (array) get_option(WP_PLOG_PLUGIN_OPTIONS);

		if (count(array_diff(array_keys($defaults), array_keys($this->options))) > 0) {
			$this->options = array_merge($defaults, $this->options);
			update_option(WP_PLOG_PLUGIN_OPTIONS, $this->options);
		}
	}

	/**
	* output head info for plogger, on plogger pages only
	*/
	public function plogHead() {
		global $post;

		if (is_page() && stripos($post->post_content, '[' . WP_PLOG_PLUGIN_TAG_PLOGGER) !== false) {
			the_plogger_head();
		}
	}

	/**
	* handle shortcode for plogger
	* @param array shortcode attributes as supplied by the WP shortcode API
	* @return string output to substitute for the shortcode
	*/
	public function shortcodePlogger($attrs) {
		// capture PHP output, so can return it as a string that WordPress can present in correct place on page
		ob_start();
		the_plogger_gallery();
		return ob_get_clean();
	}

	/**
	* display a message (already HTML-conformant)
	* @param string $msg HTML-encoded message to display inside a paragraph
	*/
	public static function showMessage($msg) {
		echo "<div class='updated fade'><p><strong>$msg</strong></p></div>\n";
	}
}
