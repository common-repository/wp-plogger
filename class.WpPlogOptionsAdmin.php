<?php

/**
* Options form input fields
*/
class WpPlogOptionsForm {

	public $plogFolder;

	/**
	* initialise from form post, if posted
	*/
	public function __construct() {
		if ($this->isFormPost()) {
			$this->plogFolder = isset($_POST['plogFolder']) ? stripslashes(trim($_POST['plogFolder'])) : '';
		}
	}

	/**
	* Is this web request a form post?
	*
	* Checks to see whether the HTML input form was posted.
	*
	* @return boolean
	*/
	public static function isFormPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}

	/**
	* Validate the form input, and return error messages.
	*
	* Return a string detailing error messages for validation errors discovered,
	* or an empty string if no errors found.
	* The string should be HTML-clean, ready for putting inside a paragraph tag.
	*
	* @return string
	*/
	public function validate() {
		$errmsg = '';

		if (strlen($this->plogFolder) === 0)
			$errmsg .= "# Please enter the name of the folder containing Plogger.<br/>\n";

		return $errmsg;
	}
}

/**
* Options admin
*/
class WpPlogOptionsAdmin {

	private $plugin;							// handle to the plugin object
	private $menuPage;							// slug for admin menu page
	private $scriptURL = '';
	private $frm;								// handle for the form validator

	/**
	* @param WpPlogPlugin $plugin handle to the plugin object
	* @param string $menuPage URL slug for this admin menu page
	*/
	public function __construct($plugin, $menuPage) {
		$this->plugin = $plugin;
		$this->menuPage = $menuPage;
		$this->scriptURL = "{$_SERVER['SCRIPT_NAME']}?page={$menuPage}";
	}

	/**
	* process the admin request
	*/
	public function process() {
		$this->frm = new WpPlogOptionsForm();
		if ($this->frm->isFormPost()) {

			if (!wp_verify_nonce($_POST[$this->menuPage . '_wpnonce'], 'save'))
				die('Security exception');

			$errmsg = $this->frm->validate();
			if (empty($errmsg)) {
				$this->plugin->options['plogFolder'] = $this->frm->plogFolder;

				update_option(WP_PLOG_PLUGIN_OPTIONS, $this->plugin->options);
				$this->plugin->showMessage(__('Options saved.'));
			}
			else {
				$this->plugin->showMessage($errmsg);
			}
		}
		else {
			// initialise form from stored options
			$this->frm->plogFolder = $this->plugin->options['plogFolder'];
		}

		?>

		<div class='wrap'>
		<?php screen_icon(); ?>
		<h2>WP Plogger Options</h2>

		<form action="<?php echo $this->scriptURL; ?>" method="post">
			<table class="form-table">

			<tr valign='top'>
				<th>Plogger folder name:</th>
				<td>
					<input type="text" class="regular-text" name="plogFolder" id="plogFolder" value="<?php echo esc_attr($this->frm->plogFolder); ?>" />
				</td>
			</tr>

			</table>

			<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php echo __('Save Changes'); ?>" />
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field('save', $this->menuPage . '_wpnonce', false); ?>
			</p>
		</form>

		<?php if (defined('PLOGGER_DIR')): ?>
			<p><a href="<?php echo site_url("/{$this->plugin->options['plogFolder']}/plog-admin/"); ?>" target='_blank'>go to Plogger admin</a></p>
		<?php endif; ?>

		</div>

		<?php
	}
}
