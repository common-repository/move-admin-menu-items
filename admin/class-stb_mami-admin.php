<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    stb_mami
 * @subpackage stb_mami/admin
 * @author     Sebastian Brieschenk <info@sbrie.com>
 */
class stb_mami_Admin
{
	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin Version
	 *
	 * @var int
	 */
	private $version;

	/**
	 * Array of menu-slugs which will be excluded from the plugin settings
	 *
	 * @var array
	 */
	private $locked_menu_items;

	/**
	 * Array of all menu items which are not excluded
	 *
	 * @var array
	 */
	public $menu;

	/**
	 * Set Plugin Name, Plugin Version and Locked Menu-items
	 *
	 * @param string plugin name
	 * @param int plugin version number
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->locked_menu_items = ['index.php', $this->plugin_name];
	}

	/**
	 * Output plugin activation message
	 *
	 * @return void
	 */
	public function stb_mami_plugin_actived_message()
	{
		if (get_transient('stb_mami_example_transient_for_activation_message')) : ?>
<div class="updated notice is-dismissible">
	<p>
		<?php echo sprintf(
						__('Move Admin Menu Items: Go to the <a href="%s">settings-page</a> and select which menu items you want to move to the admin-menu.', $this->plugin_name),
						esc_url(
							menu_page_url('stb_mami_settings', false)
						)
					); ?>
	</p>
</div>
<?php
			delete_transient('stb_mami_example_transient_for_activation_message');
		endif;
	}

	/**
	 * Register stylesheet for the admin area.
	 *
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/stb_mami-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Add Admin Menu Pages
	 *
	 * @return void
	 */
	public function stb_mami_add_menu()
	{
		add_menu_page(
			__('Admin Menu', $this->plugin_name),
			__('Admin Menu', $this->plugin_name),
			'manage_options',
			$this->plugin_name,
			array(
				$this,
				'stb_mami_admin_page_overview'
			)
		);
		add_submenu_page(
			$this->plugin_name,
			__('Settings', $this->plugin_name),
			__('Settings', $this->plugin_name),
			'manage_options',
			'stb_mami_settings',
			array(
				$this,
				'stb_mami_admin_page_settings'
			)
		);
	}

	/**
	 * Admin menu page callback function
	 *
	 * @return void
	 */
	public function stb_mami_admin_page_overview()
	{
		include_once('partials/stb_mami-admin-display.php');
	}

	/**
	 * Admin menu settings page callback function
	 *
	 * @return void
	 */
	public function stb_mami_admin_page_settings()
	{
		include_once('partials/stb_mami-admin-display-settings.php');
	}

	/**
	 * Generate menu url
	 *
	 * @param string menu_slug e.g edit.php
	 * @return string menu-page url
	 */
	private function stb_mami_get_menu_url($menu_slug)
	{
		if (preg_match('/.php/', $menu_slug)) {
			return $menu_slug;
		}
		if (menu_page_url($menu_slug, false)) {
			return menu_page_url($menu_slug, false);
		}
		return get_admin_url() .  $menu_slug;
	}

	/**
	 * Generate menu icon markup
	 *
	 * @param string $icon
	 * @return string html markup for menu-icon
	 */
	private function stb_mami_get_menu_icon($icon)
	{
		if (preg_match('/^https?/', $icon)) {
			return '<img class="stb_mami_icon" src="' . $icon . '">';
		} elseif (preg_match('/^data:image/', $icon)) {
			return '<img class="stb_mami_icon" src="' . $icon . '">';
		} else {
			return '<span class="dashicons ' . $icon . '"></span>';
		}
	}

	/**
	 * Generate menu-item array
	 *
	 * @return void
	 */
	public function stb_mami_admin_menu_items()
	{
		$global_admin_mainmenu = $GLOBALS['menu'];
		$submenu = $GLOBALS['submenu'];
		return array_map(function ($mainmenu) use ($submenu) {
			if (!empty($mainmenu[0]) && !in_array($mainmenu[2], $this->locked_menu_items)) {
				return [
					"menu_name" => $mainmenu[0],
					"menu_slug" => $mainmenu[2],
					"menu_url" => $this->stb_mami_get_menu_url($mainmenu[2]),
					"menu_icon" => $this->stb_mami_get_menu_icon($mainmenu[6]),
					"menu_submenu" => empty($submenu[$mainmenu[2]]) ? false : array_map(function ($submenu) {
						return [
							"submenu_name" => $submenu[0],
							"submenu_url" => $this->stb_mami_get_menu_url($submenu[2])
						];
					}, $submenu[$mainmenu[2]])
				];
			}
		}, $global_admin_mainmenu);
	}

	/**
	 * Register settings, settings-section and fields
	 *
	 * @return void
	 */
	public function stb_mami_settings_init()
	{
		register_setting('stb_mami', 'stb_mami_settings');

		add_settings_section(
			'stb_mami_settings_section',
			__('Settings', $this->plugin_name),
			array(
				$this,
				'stb_mami_settings_section_callback'
			),
			'stb_mami'
		);

		add_settings_field(
			'stb_mami_field_menuitems',
			__('Menu Items', $this->plugin_name),
			array(
				$this,
				'stb_mami_field_menuitems_render'
			),
			'stb_mami',
			'stb_mami_settings_section'
		);
	}

	/**
	 * Render menuitem field for settings page
	 *
	 * @return void
	 */
	public function stb_mami_field_menuitems_render()
	{
		$options = get_option('stb_mami_settings');
		?>
<ul class=" stb_mami_settings_container">
	<?php foreach ($this->menu as $menu) : ?>
	<?php if (!empty($menu)) : ?>
	<?php
					$checked = "";
					if (!empty($options['stb_mami_field_menuitems'])) {
						if (in_array($menu['menu_slug'], $options['stb_mami_field_menuitems'])) {
							$checked = "checked";
						}
					}
					?>
	<li class="stb_mami_settings__item">
		<label class="stb_mami_settings__label">
			<input type="checkbox" name="stb_mami_settings[stb_mami_field_menuitems][]" value="<?php echo $menu['menu_slug']; ?>" <?php echo $checked; ?> />
			<span class="stb_mami_settings__menu-item">
				<?php echo $menu['menu_icon']; ?>
				<span><?php echo $menu['menu_name']; ?></span>
			</span>
		</label>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php
	}

	/**
	 * return info for settings-api section
	 *
	 * @return void
	 */
	public function stb_mami_settings_section_callback()
	{
		echo __('Select the menu items you want to move to the Admin menu page.', $this->plugin_name);
	}

	/**
	 * save global menu items to $menu. remove all menu-pages from settings
	 *
	 * @return void
	 */
	public function stb_mami_remove_menu_pages()
	{
		$this->menu = $this->stb_mami_admin_menu_items();
		$options = get_option('stb_mami_settings');
		if (!empty($options['stb_mami_field_menuitems'])) {
			foreach ($options['stb_mami_field_menuitems'] as $menuitem) {
				remove_menu_page($menuitem);
			}
		}
	}

	/**
	 * create admin menu array
	 *
	 * @return array|bool selected menu items|false if no options are set now
	 */
	public function stb_mami_create_admin_menu()
	{
		$options = get_option('stb_mami_settings');
		if ($options) {
			$filtered_menu_items = array_filter($this->menu, function ($item) use ($options) {
				if (in_array($item['menu_slug'], $options['stb_mami_field_menuitems'])) {
					return $item;
				}
			});
			usort($filtered_menu_items, function ($a, $b) {
				return $a['menu_name'] > $b['menu_name'];
			});
			return $filtered_menu_items;
		} else {
			return false;
		}
	}
}
