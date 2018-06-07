<?php namespace amekusa\wpelib; main::required;

class Capability {
	const
		LOWEST = 'read';

	protected $raw;

	public function __construct($Cap = null) {
		$this->raw = $Cap ?: static::LOWEST;
	}

	public function __toString() {
		return $this->raw;
	}

	public function getRaw() {
		return $this->raw;
	}
}
