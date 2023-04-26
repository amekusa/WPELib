<?php namespace amekusa\wpelib;

class Style extends Dependency {
	protected
		$media;

	public function __construct($Slug, $Url = '', $Deps = array (), $Ver = false, $Media = 'all') {
		parent::__construct($Slug, $Url, $Deps, $Ver);
		$this->media = $Media;
	}

	public function isRegistered() {
		return in_array($this->slug, wp_styles()->registered);
	}

	public function isQueued() {
		return in_array($this->slug, wp_styles()->queue);
	}

	/**
	 * @return string
	 */
	public function getMedia() {
		return $this->media;
	}

	/**
	 * @param string $X
	 * @return Style This
	 */
	public function setMedia($X) {
		AlreadyRegisteredException::check($this);
		$this->media = $X;
		return $this;
	}

	public function register() {
		wp_register_style($this->slug, $this->url, $this->deps, $this->ver, $this->media);
	}

	public function deregister() {
		wp_deregister_style($this->slug);
	}

	public function queue() {
		if ($this->isRegistered()) wp_enqueue_style($this->slug);
		else wp_enqueue_style($this->slug, $this->url, $this->deps, $this->ver, $this->media);
	}

	public function dequeue() {
		wp_dequeue_style($this->slug);
	}
}
