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

function ra_admin_actions_register_menu()  {
    add_options_page("Random Announcements", "Announcements", 'manage_options', "ra", "ra_admin_page");
}
add_action('admin_menu', 'ra_admin_actions_register_menu');
