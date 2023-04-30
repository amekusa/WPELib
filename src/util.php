<?php namespace amekusa\wpelib;

interface util {
	public const required = true;
}

function warn($Msg) {
	trigger_error($Msg, E_USER_WARNING);
}
