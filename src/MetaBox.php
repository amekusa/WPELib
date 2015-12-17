<?php namespace amekusa\wpelib;

class MetaBox {

	/**
	 * @param string $xId
	 * @return MetaBox
	 */
	public static function getInstance($xId) {
		return new static($xId);
	}

	protected
		$save = null,
		$view = null,
		$priority = 'default',
		$context = 'advanced',
		$title,
		$id,
		$type;

	/**
	 * @param string $xId
	 */
	public function __construct($xId) {
		$this->id = $xId;
	}

	/**
	 * @param callable $xSave
	 */
	public function setSave($xSave) {
		$this->save = $xSave;
		return $this;
	}

	/**
	 * @param callable $xView
	 */
	public function setView($xView) {
		$this->view = $xView;
		return $this;
	}

	/**
	 * @param string $xPriority
	 */
	public function setPriority($xPriority) {
		$this->priority = $xPriority;
		return $this;
	}

	/**
	 * @param string $xContext
	 */
	public function setContext($xContext) {
		$this->context = $xContext;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $xTitle
	 */
	public function setTitle($xTitle) {
		$this->title = $xTitle;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the unknown_type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string|Type|array $xType
	 */
	public function setType($xType) {
		$this->type = $xType;
		return $this;
	}

	public function register() {

		$add = function () {
			add_meta_box(
				$this->id,
				$this->title,

				function ($xPost) {
					if ($this->save) wp_nonce_field('save', "nonce-{$this->id}");
					call_user_func($this->view, $xPost);
				},
				null, $this->context, $this->priority
			);
		};

		$save = $this->save ? function ($xPostId, $xPost, $xUpdate) {

			// Check the nonce
			if (!isset($_POST["nonce-{$this->id}"])) return;
			if (!wp_verify_nonce($_POST["nonce-{$this->id}"], 'save')) return;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return; // Abort autosave
			if (!current_user_can('edit_post', $xPostId)) return; // Check the user's permissions

			call_user_func_array($this->save, array ($xPostId, $xPost, $xUpdate));

		} : null;

		if (!$this->type) {
			add_action('add_meta_boxes', $add);
			if ($save) add_action('save_post', $save, 10, 3);

		} else {
			$types = is_array($this->type) ? $this->type : array ($this->type);

			foreach ($types as $iType) {
				add_action("add_meta_boxes_{$iType}", $add);
				if ($save) add_action("save_post_{$iType}", $save, 10, 3);
			}
		}
	}
 }