<?php namespace amekusa\wpelib; main::required;

class Action extends Hook {

	protected function _register() {
		add_action($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}

	protected function _deregister() {
		remove_action($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}
}
