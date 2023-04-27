<?php namespace amekusa\wpelib;

/**
 * A WordPress hook represetation.
 * @author amekusa (https://github.com/amekusa)
 */
abstract class Hook implements Registerable {
	protected
		$hookPoint,
		$callback,
		$priority = 10,
		$nArgs = 1,
		$hasDone = 0,
		$isRecursable = false,
		$isRegistered = false;

	/**
	 * @param string $HookPoint
	 * @param callable $Callback
	 * @return Hook
	 */
	public static function create($HookPoint, $Callback) {
		$r = new static($HookPoint, $Callback);
		return $r;
	}

	public function __construct($HookPoint, $Callback) {
		$this->hookPoint = $HookPoint;
		$this->callback = $Callback;
	}

	public function isRecursable() {
		return $this->isRecursable;
	}

	public function isRegistered() {
		return $this->isRegistered;
	}

	public abstract function isDoing();

	public function hasDone() {
		return $this->hasDone;
	}

	public function getHookPoint() {
		return $this->hookPoint;
	}

	public function setPriority($X) {
		$this->priority = $X;
		return $this;
	}

	public function setNArgs($X) {
		$this->nArgs = $X;
		return $this;
	}

	public function setAsRecursable($X = true) {
		$this->isRecursable = $X;
		return $this;
	}

	public abstract function register();
	public abstract function deregister();

	public function invoke() {
		static $invoking = false;
		if (!$this->isRecursable && $invoking) return; // Prevent recursion
		$invoking = true;
		if (!is_callable($this->callback)) throw new \RuntimeException('Invalid Callback');
		$args = func_get_args();
		$r = $args ? call_user_func_array($this->callback, $args) : call_user_func($this->callback);
		$invoking = false;
		$this->hasDone++;
		return $r;
	}
}
