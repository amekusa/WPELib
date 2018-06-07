<?php namespace amekusa\wpelib; main::required;

class Script extends Dependency {
	protected
		$isFooter;

	public function __construct($Slug, $Uri = '', $Deps = array (), $Ver = false, $IsFooter = false) {
		parent::__construct($Slug, $Uri, $Deps, $Ver);
		$this->isFooter = $IsFooter;
	}

	public function isRegistered() {
		return in_array($this->slug, wp_scripts()->registered);
	}

	public function isQueued() {
		return in_array($this->slug, wp_scripts()->queue);
	}

	/**
	 * @return boolean
	 */
	public function isFooter() {
		return $this->isFooter;
	}

	/**
	 * @param boolean $X
	 * @return Script This
	 */
	public function beFooter($X = true) {
		AlreadyRegisteredException::check($this);
		$this->isFooter = $X;
		return $this;
	}

	public function register() {
		wp_register_script($this->slug, $this->uri, $this->deps, $this->ver, $this->isFooter);
	}

	public function deregister() {
		wp_deregister_script($this->slug);
	}

	public function queue() {
		if ($this->isRegistered()) wp_enqueue_script($this->slug);
		else wp_enqueue_script($this->slug, $this->uri, $this->deps, $this->ver, $this->isFooter);
	}

	public function dequeue() {
		wp_dequeue_script($this->slug);
	}

	/**
	 * Synchronize data from PHP to a object in JS
	 * @param string $To A global object name to bind data
	 * @param array $Data An associated array that contains data
	 */
	public function bind($To, array $Data) {
		if (!$this->isRegistered()) $this->register();
		wp_localize_script($this->slug, $To, $Data);
	}
}
