<?php namespace amekusa\wpelib; main::required;

class Action extends Hook {

	public function isDoing() {
		return doing_action($this->hookPoint);
	}

	public function register() {
		add_action($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}

	public function deregister() {
		remove_action($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}
}
