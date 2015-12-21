<?php namespace amekusa\wpelib; main::required;

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
		$instance->homeDir = dirname($EntryPoint) . '/';
		$instance->homeUrl = plugin_dir_url($EntryPoint);
		$instance->expression = basename($instance->homeDir) . '/' . basename($EntryPoint);
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
