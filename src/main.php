<?php namespace amekusa\wpelib;

/**
 * @ignore
 */
interface main {
	const required = true;
}

/**
 * @ignore
 */
function init() {
	static $done = false;
	if ($done) return;
	set_exception_handler(function (\Exception $E) {
		if (!$E instanceof LocalException) throw $E; // Not WPELib issue

		// TODO: Do special (ex. Show bug-report instructions)

		throw $E;
	});
	$done = true;
}

init();
