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
	set_exception_handler(function ($E) {
		if (!$E instanceof UnexpectedException) throw $E; // Not WPELib issue
		// TODO Encourage users to see the issue reports
		throw $E;
	});
	$done = true;
}

init();
