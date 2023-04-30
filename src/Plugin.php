<?php namespace amekusa\wpelib;

abstract class Plugin {
	protected static $_inst = null;
	protected
		$_isRegistered = false,
		$_entryPoint = '';

	/**
	 * @return \amekusa\wpelib\Plugin
	 */
	public static function instance() {
		if (!static::$_inst) static::$_inst = new static();
		return static::$_inst;
	}

	protected function __construct() {}

	/**
	 * @param string $EntryPoint
	 * @return \amekusa\wpelib\Plugin
	 */
	public function register($EntryPoint) {
		if ($this->_isRegistered) {
			warn('Already Registered');
			return $this;
		}
		$this->_entryPoint = $EntryPoint;
		if (is_callable($fn = [$this, 'onActivate'])) register_activation_hook($EntryPoint, $fn);
		if (is_callable($fn = [$this, 'onDeactivate'])) register_deactivation_hook($EntryPoint, $fn);
		add_action('init', [$this, 'init']);
		$this->_isRegistered = true;
		return $this;
	}

	public abstract function onActivate();

	public abstract function onDeactivate();

	public abstract function init();
}
