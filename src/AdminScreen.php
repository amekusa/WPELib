<?php namespace amekusa\wpelib;

class AdminScreen {
	protected
		$raw,
		$deps,
		$content,
		$fnOnLoad;

	/**
	 * @param string|callable $Content The screen content
	 * @return AdminScreen New instance
	 */
	public static function create($Content = null) {
		return new static($Content);
	}

	/**
	 * @param string|callable $Content The screen content
	 */
	public function __construct($Content = null) {
		$this->deps = array ();
		$this->content = $Content;
	}

	public function __get($Prop) {
		if ($this->raw) return $this->raw->$Prop;
	}

	public function __set($Prop, $Value) {
		if ($this->raw) $this->raw->$Prop = $Value;
	}

	/**
	 * @return \WP_Screen
	 */
	public function getRaw() {
		return $this->raw;
	}

	/**
	 * @param string|callable $X
	 */
	public function setContent($X) {
		$this->content = $X;
	}

	/**
	 * @param callable $X
	 */
	public function setFnOnLoad($X) {
		$this->fnOnLoad = $X;
	}

	public function addScript(Script $X) {
		$this->deps[] = $X;
	}

	public function addStyle(Style $X) {
		$this->deps[] = $X;
	}

	public function onLoad() {
		$this->raw = get_current_screen();
		if ($this->deps) {
			add_action('admin_enqueue_scripts', function () {
				foreach ($this->deps as $item) $item->queue();
			});
		}
		if (is_callable($this->fnOnLoad)) call_user_func($this->fnOnLoad);
	}

	public function render() {
		if (is_string($this->content)) echo $this->content;
		else if (is_callable($this->content)) call_user_func($this->content);
	}
}
