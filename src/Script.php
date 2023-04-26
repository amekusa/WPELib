<?php namespace amekusa\wpelib;

class Script extends Dependency {
	protected
		$isFooter;

	public function __construct($Slug, $Url = '', $Deps = array (), $Ver = false, $IsFooter = false) {
		parent::__construct($Slug, $Url, $Deps, $Ver);
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
	public function setAsFooter($X = true) {
		AlreadyRegisteredException::check($this);
		$this->isFooter = $X;
		return $this;
	}

	public function register() {
		wp_register_script($this->slug, $this->url, $this->deps, $this->ver, $this->isFooter);
	}

	public function deregister() {
		wp_deregister_script($this->slug);
	}

	public function queue() {
		if ($this->isRegistered()) wp_enqueue_script($this->slug);
		else wp_enqueue_script($this->slug, $this->url, $this->deps, $this->ver, $this->isFooter);
	}

	public function dequeue() {
		wp_dequeue_script($this->slug);
	}

	/**
	 * Passes values in PHP to an object in JS
	 * @param string $To Name of a JS object
	 * @param array $Data An associative array that contains the values to pass
	 */
	public function bind($To, array $Data) {
		if (!$this->isRegistered()) $this->register();
		wp_localize_script($this->slug, $To, $Data);
	}
}
