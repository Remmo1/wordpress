<?php
/**
* Plugin Name: Random Announcements
* Plugin URI: https://example.com/plugins/random-announcements/
* Description: Displays announcements before the text of each posts
* Version: 1.0
* Requires at least: 5.0
* Requires PHP: 7.2
* Author: Remigiusz Pisarski & Bartosz Mękarski
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require_once('admin-page.php');
require_once('db/init.php');
require_once('db/remove.php');

register_activation_hook(__FILE__, 'create_announcements_table');
register_uninstall_hook(__FILE__, 'remove_announcements_table');

function announcements_admin_menu() {
    add_menu_page(__('Announcements', 'announcements'), __('Announcements', 'announcements'), 'activate_plugins', 'announcements', 'announcements_page_handler', 'dashicons-welcome-learn-more', '5');
    add_submenu_page('announcements', __('Announcements', 'announcements'), __('Announcements', 'announcements'), 'activate_plugins', 'announcements', 'announcements_page_handler');
    add_submenu_page('announcements', __('Add new', 'Announcements'), __('Add new', 'announcements'), 'activate_plugins', 'announcements_form', 'announcements_page_add_form');
}

add_action('admin_menu', 'announcements_admin_menu');