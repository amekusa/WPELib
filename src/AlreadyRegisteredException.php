<?php namespace amekusa\wpelib;

class AlreadyRegisteredException extends \RuntimeException {

	/**
	 * @param Registerable $X
	 * @throws static
	 */
	public static function check(Registerable $X) {
		if ($X->isRegistered()) throw new static();
	}
}
