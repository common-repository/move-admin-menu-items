<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sbrie.com
 * @since      1.0.0
 *
 * @package    stb_mami
 * @subpackage stb_mami/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    stb_mami
 * @subpackage stb_mami/includes
 * @author     Sebastian Brieschenk <info@sbrie.com>
 */
class stb_mami_Activator
{

	/**
	 * Check if necessary php and wp versions are running
	 *
	 * @return void
	 */
	public static function activate()
	{

		set_transient('stb_mami_example_transient_for_activation_message', true, 5);

		global $wp_version;

		$php = '5.6';
		$wp  = '4.9';

		if (version_compare(PHP_VERSION, $php, '<')) {
			deactivate_plugins(basename(__FILE__));
			wp_die(
				'<p>' .
					sprintf(
						__('This plugin cannot be activated because it requires a PHP version greater than %1$s. Your PHP version can be updated by your hosting company.', 'stb_mami'),
						$php
					)
					. '</p> <a href="' . admin_url('plugins.php') . '">' . __('go back', 'stb_mami') . '</a>'
			);
		}

		if (version_compare($wp_version, $wp, '<')) {
			deactivate_plugins(basename(__FILE__));
			wp_die(
				'<p>' .
					sprintf(
						__('This plugin cannot be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard -> Updates to gran the latest version of WordPress.', 'stb_mami'),
						$wp
					)
					. '</p> <a href="' . admin_url('plugins.php') . '">' . __('go back', 'stb_mami') . '</a>'
			);
		}
	}
}
