<?php namespace amekusa\wpelib;

use amekusa\wpelib\site;

class Post {

	/**
	 * @return string|array
	 */
	public static function getAllowedTypeNames() {
		return '';
	}

	/**
	 * @param integer|\WP_Post $Post
	 * @param integer $SiteId
	 * @return Post
	 */
	public static function getInstance($Post, $SiteId = null) {
		return new static($Post, $SiteId);
	}

	protected
		$type,
		$siteId,
		$id,
		$raw;

	/**
	 * @param integer|\WP_Post $Post A post to wrap
	 * @param integer $SiteId
	 */
	protected function __construct($Post, $SiteId = null) {
		$this->siteId = isset($SiteId) ? $SiteId : get_current_blog_id();

		if ($Post instanceof \WP_Post) {
			$this->raw = $Post;
			$this->id = $Post->ID;

		} else $this->id = (int) $Post;
	}

	public function __get($Prop) {
		return $this->getRaw()->$Prop;
	}

	public function __set($Prop, $Value) {
		$this->getRaw()->$Prop = $Value;
	}

	/**
	 * @return \WP_Post
	 */
	public function getRaw() {
		if (!$this->raw) $this->updateRaw();
		return $this->raw;
	}

	protected function updateRaw() {
		$bound = $this->bindSite();
		if (!$post = get_post($this->id)) throw new \RuntimeException('Post not found');

		if ($types = static::getAllowedTypeNames()) { // Type check

			if (
				(is_array($types) && !in_array($post->post_type, $types)) ||
				$types != $post->post_type

			) throw new \RuntimeException('Type mismatch');
		}

		$this->raw = $post;
		if ($bound) $this->unbindSite();
	}

	/**
	 * @return Type
	 */
	public function getType() {
		if (!$this->type) $this->updateType();
		return $this->type;
	}

	protected function updateType() {
		$this->type = Type::getInstance($this->getRaw()->post_type);
	}

	public function getMeta($Key, $IsSingle = false) {
		$bound = $this->bindSite();
		$r = get_post_meta($this->id, $Key, $IsSingle);
		if ($bound) $this->unbindSite();
		return $r;
	}

	/**
	 * TODO Saves this post to the database
	 * @see http://rudrastyh.com/wordpress/duplicate-post.html
	 * @param boolean $ForcesCreation
	 */
	public function save($ForcesCreation = false) {
		$bound = $this->bindSite();

		$params = array (
			'comment_status' => $this->getRaw()->comment_status,
			'ping_status'    => $this->getRaw()->ping_status,
			'post_author'    => $this->getRaw()->post_author,
			'post_content'   => $this->getRaw()->post_content,
			'post_excerpt'   => $this->getRaw()->post_excerpt,
			'post_name'      => $this->getRaw()->post_name,
			'post_parent'    => $this->getRaw()->post_parent,
			'post_password'  => $this->getRaw()->post_password,
			'post_status'    => 'draft',
			'post_title'     => $this->getRaw()->post_title,
			'post_type'      => $this->getRaw()->post_type,
			'to_ping'        => $this->getRaw()->to_ping,
			'menu_order'     => $this->getRaw()->menu_order
		);

		if (!$ForcesCreation) $params['ID'] = $this->id;

		$id = wp_insert_post($params);

		// TODO Sets taxonomies

		// TODO Sets custom fields

		if ($bound) $this->unbindSite();

		return $id;
	}

	/**
	 * @return boolean
	 */
	public function bindSite() {
		return site::bind($this->siteId);
	}

	/**
	 * @return boolean
	 */
	public function unbindSite() {
		return site::unbind();
	}
}