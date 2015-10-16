<?php namespace amekusa\WPELib;

interface functions {
	const required = true;
}

/**
 * In a multisite, returns the id of the primary site in the network.
 * Otherwise, returns the same value as get_current_blog_id()
 * @return integer
 */
function primary_site() {
	if (is_multisite()) return get_current_site()->blog_id;
	return get_current_blog_id();
}

/**
 * @param integer $xSiteId
 * @return boolean True on success, False on failure
 */
function bind_site($xSiteId) {
	if (!function_exists('switch_to_blog')) return false;
	return switch_to_blog($xSiteId);
}

/**
 * @return boolean True on success, False on failure
 */
function unbind_site() {
	if (!function_exists('restore_current_blog')) return false;
	return restore_current_blog();
}
