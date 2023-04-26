<?php namespace amekusa\wpelib;

class SettingsSection implements Registerable {
	protected
		$name,
		$screen,
		$content,
		$title;

	public function isRegistered() {
		global $wp_settings_sections;
		return
			isset($wp_settings_sections[$this->getScreen()]) &&
			isset($wp_settings_sections[$this->getScreen()][$this->getName()]);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getScreen() {
		return $this->screen;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $X
	 */
	public function setName($X) {
		$this->name = $X;
		return $this;
	}

	/**
	 * @param string|AdminScreen $X
	 */
	public function setScreen($X) {
		$this->screen = $X;
		return $this;
	}

	/**
	 * @param string|callable $X
	 */
	public function setContent($X) {
		$this->content = $X;
		return $this;
	}

	/**
	 * @param string $X
	 */
	public function setTitle($X) {
		$this->title = $X;
		return $this;
	}

	public function register() {
		add_settings_section($this->getName(), $this->getTitle(), array ($this, 'render'), $this->getScreen());
	}

	public function render() {
		if (is_string($this->content)) echo $this->content;
		else if (is_callable($this->content)) call_user_func($this->content);
	}
}
