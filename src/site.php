<?php namespace amekusa\wpelib; main::required;

abstract class site {

	/**
	 * In a multisite, returns the id of the primary site in the network.
	 * Otherwise, returns the same value as get_current_blog_id()
	 * @return integer
	 */
	static function primary() {
		if (is_multisite()) return get_current_site()->blog_id;
		return get_current_blog_id();
	}

	/**
	 * @param integer $SiteId
	 * @return boolean True on success, False on failure
	 */
	static function bind($SiteId) {
		if (!function_exists('switch_to_blog')) return false;
		return switch_to_blog($SiteId);
	}

	/**
	 * @return boolean True on success, False on failure
	 */
	static function unbind() {
		if (!function_exists('restore_current_blog')) return false;
		return restore_current_blog();
	}
}
