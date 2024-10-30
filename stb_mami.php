<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Move Admin Menu Items
 * Plugin URI:        https://wordpress.org/plugins/move-admin-menu-items/
 * Description:       Move admin menu items to an overview menu page.
 * Version:           1.0.2
 * Author:            Sebastian Brieschenk
 * Author URI:        https://profiles.wordpress.org/sierie85/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stb_mami
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 */
define('stb_mami_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 */
function activate_stb_mami()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-stb_mami-activator.php';
	stb_mami_Activator::activate();
}

register_activation_hook(__FILE__, 'activate_stb_mami');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-stb_mami.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stb_mami()
{
	$plugin = new stb_mami();
	$plugin->run();
}
run_stb_mami();
