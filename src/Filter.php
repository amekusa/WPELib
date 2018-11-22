<?php namespace amekusa\wpelib; main::required;

class Filter extends Hook {

	public function isDoing() {
		return doing_filter($this->hookPoint);
	}

	public function register() {
		add_filter($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}

	public function deregister() {
		remove_filter($this->hookPoint, array ($this, 'invoke'), $this->priority, $this->nArgs);
	}
}
