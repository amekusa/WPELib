<?php namespace amekusa\wpelib; main::required;

class Type {

	/**
	 * @param string|\stdClass $Type
	 */
	public static function getInstance($Type) {
		return new static($Type);
	}

	/**
	 * @return array
	 */
	public static function getDefaultParams() {
		return array ();
	}

	protected
		$name,
		$raw;

	/**
	 * @param string|\stdClass $Type
	 */
	protected function __construct($Type) {
		if (is_string($Type)) $this->name = $Type;
		if ($Type instanceof \stdClass) {
			$this->raw = $Type;
			$this->name = $Type->name;
		}
	}

	public function __get($Prop) {
		return $this->getRaw()->$Prop;
	}

	public function __set($Prop, $Value) {
		$this->getRaw()->$Prop = $Value;
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
	public function getRaw() {
		if (!$this->raw) $this->updateCore();
		return $this->raw;
	}

	protected function updateCore() {
		if (!post_type_exists($this->name)) $this->register();
		$this->raw = get_post_type_object($this->name);
	}

	public function reserveRegister(array $Params = array ()) {
		add_action('init', function () {
			$this->register($Params);
		});
	}

	public function register(array $Params = array ()) {
		$params = array_merge_recursive(static::getDefaultParams(), $Params);
		if (!$params) register_post_type($this->name);
		else register_post_type($this->name, $params);
	}
}
