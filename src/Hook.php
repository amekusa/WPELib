<?php namespace amekusa\wpelib; main::required;

/**
 * A WordPress hook represetation.
 * @author amekusa <post@amekusa.com>
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

	public function setPriority($Priority) {
		$this->priority = $Priority;
		return $this;
	}

	public function setNArgs($NArgs) {
		$this->nArgs = $NArgs;
		return $this;
	}

	public function setAsRecursable($X = true) {
		$this->isRecursable = $X;
		return $this;
	}

	public final function register() {
		if ($this->isRegistered) return;
		$this->_register();
		$this->isRegistered = true;
		return $this;
	}

	public final function deregister() {
		if (!$this->isRegistered) return;
		$this->_deregister();
		$this->isRegistered = false;
		return $this;
	}

	public function invoke() {
		static $invoking = false;
		if (!$this->isRecursable && $invoking) return; // Prevent recursion
		$invoking = true;
		if (!is_callable($this->callback)) throw new \RuntimeException('Uncallable callback');
		$args = func_get_args();
		$this->preInvoke($args);
		$r = $args ? call_user_func_array($this->callback, $args) : call_user_func($this->callback);
		$invoking = false;
		$this->hasDone++;
		return $r;
	}

	protected function preInvoke($Args) {}

	protected abstract function _register();
	protected abstract function _deregister();
}
