<?php namespace amekusa\wpelib; main::required;

class AdminScreen {
	protected
		$raw,
		$deps,
		$fnOnLoad,
		$fnRender;

	/**
	 * @param callable $FnRender A callback to render the screen content
	 */
	protected function __construct($FnRender = null) {
		$this->deps = array ();
		$this->fnRender = $FnRender;
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
	 * @param callable $X
	 */
	public function setFnOnLoad($X) {
		$this->fnOnLoad = $X;
	}

	/**
	 * @param callable $X
	 */
	public function setFnRender($X) {
		$this->fnRender = $X;
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
		if (is_callable($this->fnRender)) call_user_func($this->fnRender);
	}
}
