<?php namespace amekusa\WPELib;

class Type {

	/**
	 * @param string|\stdClass $xType
	 */
	public static function getInstance($xType) {
		return new static($xType);
	}

	/**
	 * @return array
	 */
	public static function getDefaultParams() {
		return array ();
	}

	protected
		$name,
		$core;

	/**
	 * @param string|\stdClass $xType
	 */
	protected function __construct($xType) {
		if (is_string($xType)) $this->name = $xType;
		if ($xType instanceof \stdClass) {
			$this->core = $xType;
			$this->name = $xType->name;
		}
	}

	public function __get($xProp) {
		return $this->getCore()->$xProp;
	}

	public function __set($xProp, $xValue) {
		$this->getCore()->$xProp = $xValue;
	}

	public function __toString() {
		return $this->name;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return \stdClass A post type object
	 */
	public function getCore() {
		if (!$this->core) $this->updateCore();
		return $this->core;
	}

	protected function updateCore() {
		if (!post_type_exists($this->name)) $this->register();
		$this->core = get_post_type_object($this->name);
	}

	public function reserveRegister(array $xParams = array ()) {
		add_action('init', function () {
			$this->register($xParams);
		});
	}

	public function register(array $xParams = array ()) {
		$params = array_merge_recursive(static::getDefaultParams(), $xParams);
		if (!$params) register_post_type($this->name);
		else register_post_type($this->name, $params);
	}
}
