<?php namespace amekusa\WPELib;

use amekusa\WPELib\site;

class Post {

	/**
	 * @return string|array
	 */
	public static function getAllowedTypeNames() {
		return '';
	}

	/**
	 * @param integer|\WP_Post $xPost
	 * @param integer $xSiteId
	 * @return Post
	 */
	public static function getInstance($xPost, $xSiteId = null) {
		return new static($xPost, $xSiteId);
	}

	protected
		$type,
		$siteId,
		$id,
		$core;

	/**
	 * @param integer|\WP_Post $xPost A post to wrap
	 * @param integer $xSiteId
	 */
	protected function __construct($xPost, $xSiteId = null) {
		$this->siteId = isset($xSiteId) ? $xSiteId : get_current_blog_id();

		if ($xPost instanceof \WP_Post) {
			$this->core = $xPost;
			$this->id = $xPost->ID;

		} else $this->id = (int) $xPost;
	}

	public function __get($xProp) {
		return $this->getCore()->$xProp;
	}

	public function __set($xProp, $xValue) {
		$this->getCore()->$xProp = $xValue;
	}

	/**
	 * @return \WP_Post
	 */
	public function getCore() {
		if (!$this->core) $this->updateCore();
		return $this->core;
	}

	protected function updateCore() {
		$bound = $this->bindSite();
		if (!$post = get_post($this->id)) throw new \RuntimeException('Post not found');

		if ($types = static::getAllowedTypeNames()) { // Type check

			if (
				(is_array($types) && !in_array($post->post_type, $types)) ||
				$types != $post->post_type

			) throw new \RuntimeException('Type mismatch');
		}

		$this->core = $post;
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
		$this->type = Type::getInstance($this->getCore()->post_type);
	}

	public function getMeta($xKey, $xIsSingle = false) {
		$bound = $this->bindSite();
		$r = get_post_meta($this->id, $xKey, $xIsSingle);
		if ($bound) $this->unbindSite();
		return $r;
	}

	/**
	 * TODO Saves this post to the database
	 * @see http://rudrastyh.com/wordpress/duplicate-post.html
	 * @param boolean $xForcesCreation
	 */
	public function save($xForcesCreation = false) {
		$bound = $this->bindSite();

		$params = array (
			'comment_status' => $this->getCore()->comment_status,
			'ping_status'    => $this->getCore()->ping_status,
			'post_author'    => $this->getCore()->post_author,
			'post_content'   => $this->getCore()->post_content,
			'post_excerpt'   => $this->getCore()->post_excerpt,
			'post_name'      => $this->getCore()->post_name,
			'post_parent'    => $this->getCore()->post_parent,
			'post_password'  => $this->getCore()->post_password,
			'post_status'    => 'draft',
			'post_title'     => $this->getCore()->post_title,
			'post_type'      => $this->getCore()->post_type,
			'to_ping'        => $this->getCore()->to_ping,
			'menu_order'     => $this->getCore()->menu_order
		);

		if (!$xForcesCreation) $params['ID'] = $this->id;

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