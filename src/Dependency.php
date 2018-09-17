<?php namespace amekusa\wpelib; main::required;

abstract class Dependency implements Registerable {
	protected
		$slug,
		$url,
		$deps,
		$ver;

	public function __construct($Slug, $Url = '', $Deps = array (), $Ver = false) {
		$this->slug = $Slug;
		$this->url = $Url;
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
	public function getUrl() {
		return $this->url;
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
	public function setUrl($X) {
		$this->url = $X;
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
