<?php

/**
 *
 * @link       https://sbrie.com
 * @since      1.0.0
 *
 * @package    stb_mami
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

delete_option('stb_mami_settings');
