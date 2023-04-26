<?php namespace amekusa\wpelib;

class Setting implements Registerable {
	protected
		$name,
		$group,
		$type,
		$description,
		$default = false,
		$sanitizer;

	public static function create($Name, $Group = null) {
		return new static($Name, $Group);
	}

	public function __construct($Name, $Group = null) {
		$this->name = $Name;
		$this->group = $Group;
	}

	public function isRegistered() {
		$settings = get_registered_settings();
		return isset($settings[$this->name]);
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group ?: $this->getName();
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * @return mixed
	 */
	public function getSanitizer() {
		return $this->sanitizer;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return get_option($this->getName(), $this->getDefault());
	}

	/**
	 * @param mixed $X
	 */
	public function setType($X) {
		$this->type = $X;
		return $this;
	}

	/**
	 * @param mixed $X
	 */
	public function setDescription($X) {
		$this->description = $X;
		return $this;
	}

	/**
	 * @param mixed $X
	 */
	public function setDefault($X) {
		$this->default = $X;
		return $this;
	}

	/**
	 * @param mixed $X
	 */
	public function setSanitizer($X) {
		$this->sanitizer = $X;
		return $this;
	}

	public function register($Priority = 10) {
		$fn = function () {
			$options = array ();
			if ($x = $this->getType()) $options['type'] = $x;
			if ($x = $this->getDescription()) $options['description'] = $x;
			if ($x = $this->getDefault()) $options['default'] = $x;
			if ($x = $this->getSanitizer()) $options['sanitize_callback'] = $x;
			register_setting($this->getGroup(), $this->getName(), $options);
		};
		$action = 'admin_init';
		if (doing_action($action)) call_user_func($fn);
		else add_action($action, $fn, $Priority);
	}
}
