<?php namespace amekusa\wpelib; main::required;

class MetaBox {

	/**
	 * @param string $Id
	 * @return MetaBox
	 */
	public static function getInstance($Id) {
		return new static($Id);
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
	 * @param string $Id
	 */
	public function __construct($Id) {
		$this->id = $Id;
	}

	/**
	 * @param callable $Save
	 */
	public function setSave($Save) {
		$this->save = $Save;
		return $this;
	}

	/**
	 * @param callable $View
	 */
	public function setView($View) {
		$this->view = $View;
		return $this;
	}

	/**
	 * @param string $Priority
	 */
	public function setPriority($Priority) {
		$this->priority = $Priority;
		return $this;
	}

	/**
	 * @param string $Context
	 */
	public function setContext($Context) {
		$this->context = $Context;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $Title
	 */
	public function setTitle($Title) {
		$this->title = $Title;
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
	 * @param string|Type|array $Type
	 */
	public function setType($Type) {
		$this->type = $Type;
		return $this;
	}

	public function register() {

		$add = function () {
			add_meta_box(
				$this->id,
				$this->title,

				function ($Post) {
					if ($this->save) wp_nonce_field('save', "nonce-{$this->id}");
					call_user_func($this->view, $Post);
				},
				null, $this->context, $this->priority
			);
		};

		$save = $this->save ? function ($PostId, $Post, $Update) {

			// Check the nonce
			if (!isset($_POST["nonce-{$this->id}"])) return;
			if (!wp_verify_nonce($_POST["nonce-{$this->id}"], 'save')) return;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return; // Abort autosave
			if (!current_user_can('edit_post', $PostId)) return; // Check the user's permissions

			call_user_func_array($this->save, array ($PostId, $Post, $Update));

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