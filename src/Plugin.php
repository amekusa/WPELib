<?php namespace amekusa\wpelib;

class Plugin {
	protected static $instance;

	protected
		$isActiveForNetwork,
		$expression = '',
		$homeUrl = '',
		$homeDir = '';

	/**
	 * Starts the app
	 * @param string $EntryPoint The plugin entry point
	 */
	public static function main($EntryPoint) {
		$instance = static::getInstance();
		$instance->homeDir = dirname($EntryPoint).'/';
		$instance->homeUrl = plugin_dir_url($EntryPoint);
		$instance->expression = basename($instance->homeDir).'/'.basename($EntryPoint);

		if (is_callable($x = array ($instance, 'onActivate')))
			register_activation_hook($EntryPoint, $x);

		if (is_callable($x = array ($instance, 'onDeactivate')))
			register_deactivation_hook($EntryPoint, $x);

		if (is_callable($x = array ($instance, 'init')))
			add_action('init', $x);

		if (is_callable($x = array ($instance, 'adminInit')))
			add_action('admin_init', $x);

		if (is_callable($x = array ($instance, 'setupMenus')))
			add_action('admin_menu', $x);
	}

	/**
	 * @return Plugin
	 */
	public static function getInstance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}

	protected function __construct() {
	}

	/**
	 * @return boolean
	 */
	public function isActiveForNetwork() {
		if (!isset($this->isActiveForNetwork)) {
			if (!function_exists('is_plugin_active_for_network')) return false; // Called too early
			$this->isActiveForNetwork = is_plugin_active_for_network($this->expression);
		}
		return $this->isActiveForNetwork;
	}

	/**
	 * @return string
	 */
	public function getHomeDir() {
		return $this->homeDir;
	}

	/**
	 * @return string
	 */
	public function getHomeUrl() {
		return $this->homeUrl;
	}

	/**
	 * @return string
	 */
	public function getExpression() {
		return $this->expression;
	}
}
