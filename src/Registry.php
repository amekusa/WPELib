<?php namespace amekusa\wpelib;

/**
 * This is DRAFT and currently NOT-IN-USE
 */
class Registry {
	private static $instance;
	private $entries;

	public static function instance() {
		if (!self::$instance) self::$instance = new self();
		return self::$instance;
	}

	private function __construct() {
		$this->entries = array ();
	}

	/**
	 * @param Registerable $Entry
	 */
	public function register($Entry) {

	}
}
