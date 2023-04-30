<?php namespace amekusa\wpelib;

util::required;

abstract class Theme {
	protected static $_inst = null;
	protected $_isRegistered = false;

	/**
	 * @return \amekusa\wpelib\Theme
	 */
	public static function instance() {
		if (!static::$_inst) static::$_inst = new static();
		return static::$_inst;
	}

	protected function __construct() {}

	/**
	 * @return \amekusa\wpelib\Theme
	 */
	public function register() {
		if ($this->_isRegistered) {
			warn('Already Registered');
			return $this;
		}
		add_action('after_switch_theme', [$this, 'onActivate']);
		add_action('after_setup_theme', [$this, 'setup']);
		add_action('init', [$this, 'init']);
		$this->_isRegistered = true;
		return $this;
	}

	/**
	 * Runs on activation
	 *
	 * Things can be done here:
	 *  - `add_role(...)` to register custom roles
	 *  - `$role->add_cap(...)` to add capabilities to roles
	 *  - `create_page(...)` to create pages
	 *  - `create_terms(...)` to create taxonomy terms
	 */
	public abstract function onActivate();

	/**
	 * Early Setup
	 *
	 * Things can be done here:
	 *  - `add_theme_support(...)` to activate additional features
	 *  - `load_theme_textdomain(...)` to load language files
	 *
	 * NOTE: This action runs before user is authenticated
	 */
	public abstract function setup();

	/**
	 * Initialize
	 */
	public abstract function init();
}
