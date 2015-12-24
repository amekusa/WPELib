<?php namespace amekusa\wpelib; main::required;

/**
 * @author amekusa <post@amekusa.com>
 * @todo Test
 */
class AjaxAction extends Action {
	protected
		$name,
		$nonce,
		$isNoAuth = false;

	/**
	 * @param string $Name
	 * @param callable $Callback
	 * @return AjaxAction
	 */
	public static function create($Name, $Callback) {
		$r = new static($Name, $Callback);
		return $r;
	}

	public function __construct($Name, $Callback) {
		parent::__construct('wp_ajax_'.$Name, $Callback);
		$this->name = $Name;
		$this->nonce = wp_create_nonce($this->name);
	}

	public function getName() {
		return $this->name;
	}

	public function getNonce() {
		return $this->nonce;
	}

	public function setNoAuth($IsNoAuth = true) {
		$this->isNoAuth = $IsNoAuth;
		return $this;
	}

	protected function _register() {
		parent::_register();
		if ($this->isNoAuth) add_action('wp_ajax_nopriv_'.$this->name, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}

	protected function preInvoke() {
		if (!check_ajax_referer($this->name, 'nonce', false)) {
			header('HTTP/1.1 500 Internal Server Error');
			die();
		}
		if (!defined('AJAX_ACTION')) define('AJAX_ACTION', $this->name);
	}

	public function toJQuery() {
		return array (
			'url' => admin_url('admin-ajax.php'),
			'method' => 'POST', // jQuery >= 1.9.0
			'type' => 'POST',   // jQuery <  1.9.0
			'data' => array (
				'action' => $this->getName(),
				'nonce' => $this->getNonce()
			),
			'dataType' => 'json',
			'cache' => false
		);
	}
}
