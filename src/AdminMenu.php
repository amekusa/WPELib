<?php namespace amekusa\wpelib; main::required;

/**
 * An admin menu item
 * @author amekusa
 */
class AdminMenu implements Registerable {
	protected
		$slug,
		$parent,
		$screen,
		$title,
		$label,
		$icon,
		$position,
		$capability,
		$hookSuffix,
		$isRegistered = false;

	/**
	 * @param string $Title The title
	 * @param string $Slug=null An unique slug
	 * @param string $Parent=null The parent menu slug
	 * @return AdminMenu New instance
	 */
	public static function create($Title, $Slug = null, $Parent = null) {
		return new static($Title, $Slug, $Parent);
	}

	/**
	 * @param string $Title The title
	 * @param string $Slug=null An unique slug
	 * @param string $Parent=null The parent menu slug
	 */
	public function __construct($Title, $Slug = null, $Parent = null) {
		$this->title = $Title;
		$this->slug = $Slug;
		$this->parent = $Parent;
	}

	public function __toString() {
		return $this->getSlug();
	}

	/**
	 * @return boolean
	 */
	public function isRegistered() {
		return $this->isRegistered;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug ?: $this->getTitle();
	}

	/**
	 * The parent menu identifier
	 * @return string
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return AdminScreen
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
	 * @return string
	 */
	public function getLabel() {
		return isset($this->label) ? $this->label : $this->getTitle();
	}

	/**
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * @return int
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @return Capability
	 */
	public function getCapability() {
		if (!isset($this->capability)) $this->capability = new Capability();
		return $this->capability;
	}

	/**
	 * @return string
	 */
	public function getHookSuffix() {
		return $this->hookSuffix;
	}

	/**
	 * @param string|AdminMenu $X
	 * Media Library:    'upload.php'
	 * Tools:            'tools.php'
	 * Options:          'options-general.php'
	 * Custom Post Type: 'edit.php?post_type=***'
	 * @return AdminMenu This
	 */
	public function setParent($X) {
		AlreadyRegisteredException::check($this);
		$this->parent = $X instanceof AdminMenu ? $X->getSlug() : $X;
		return $this;
	}

	/**
	 * @param AdminScreen $X
	 * @return AdminMenu This
	 */
	public function setScreen(AdminScreen $X) {
		AlreadyRegisteredException::check($this);
		$this->screen = $X;
		return $this;
	}

	/**
	 * @param string $X
	 * @return AdminMenu This
	 */
	public function setTitle($X) {
		AlreadyRegisteredException::check($this);
		$this->title = $X;
		return $this;
	}

	/**
	 * @param string $X
	 * @return AdminMenu This
	 */
	public function setLabel($X) {
		AlreadyRegisteredException::check($this);
		$this->label = $X;
		return $this;
	}

	/**
	 * @param string $X
	 * @return AdminMenu This
	 */
	public function setIcon($X) {
		AlreadyRegisteredException::check($this);
		$this->icon = $X;
		return $this;
	}

	/**
	 * @param int $X
	 * @return AdminMenu This
	 */
	public function setPosition($X) {
		AlreadyRegisteredException::check($this);
		$this->position = $X;
		return $this;
	}

	/**
	 * @param string|Capability $X
	 * @return AdminMenu This
	 */
	public function setCapability($X) {
		AlreadyRegisteredException::check($this);
		$this->capability = $X instanceof Capability ? $X : new Capability($X);
		return $this;
	}

	/**
	 * Registers itself to WP
	 * @param int $Priority Hook priority
	 */
	public function register($Priority = 10) {
		$fn = function () use ($Priority) {
			if ($this->getParent()) {
				$this->hookSuffix = add_submenu_page( // @formatter:off
					$this->getParent(), // Parent menu
					$this->getTitle(), // Page title
					$this->getLabel(), // Menu title
					$this->getCapability()->getRaw(), // Capability
					$this->getSlug(), // Menu slug
					$this->getScreen() ? array ($this->getScreen(), 'render') : '' // Callback to render the screen
				); // @formatter:on

			} else {
				$this->hookSuffix = add_menu_page( // @formatter:off
					$this->getTitle(), // Page title
					$this->getLabel(), // Menu title
					$this->getCapability()->getRaw(), // Capability
					$this->getSlug(), // Menu slug
					$this->getScreen() ? array ($this->getScreen(), 'render') : '' // Callback to render the screen
				); // @formatter:on
			}
			if ($this->getScreen()) {
				add_action("load-{$this->hookSuffix}", function () {
					$this->getScreen()->onLoad();
					$this->isRegistered = true;
				}, $Priority);

			} else $this->isRegistered = true;
		};
		$action = 'admin_menu';
		if (doing_action($action)) call_user_func($fn);
		else add_action($action, $fn, $Priority);
	}
}
