<?php namespace amekusa\wpelib; main::required;

class Filter extends Hook {

	protected function _register() {
		add_filter($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}

	protected function _deregister() {
		remove_filter($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}
}
