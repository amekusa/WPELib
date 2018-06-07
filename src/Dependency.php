<?php namespace amekusa\wpelib; main::required;

abstract class Dependency implements Registerable {
	protected
		$slug,
		$uri,
		$deps,
		$ver;

	public function __construct($Slug, $Uri = '', $Deps = array (), $Ver = false) {
		$this->slug = $Slug;
		$this->uri = $Uri;
		$this->deps = $Deps;
		$this->ver = $Ver;
	}

	/**
	 * @return mixed
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * @return array
	 */
	public function getDeps() {
		return $this->deps;
	}

	/**
	 * @return string
	 */
	public function getVer() {
		return $this->ver;
	}

	/**
	 * @param string $X
	 */
	public function setUri($X) {
		$this->uri = $X;
		return $this;
	}

	/**
	 * @param array $X
	 */
	public function setDeps($X) {
		$this->deps = $X;
		return $this;
	}

	/**
	 * @param string $X
	 */
	public function setVer($X) {
		$this->ver = $X;
		return $this;
	}

	abstract public function isRegistered();
	abstract public function isQueued();
	abstract public function register();
	abstract public function deregister();
	abstract public function queue();
	abstract public function dequeue();
}
