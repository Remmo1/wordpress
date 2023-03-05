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
require_once('db/random.php');

register_activation_hook(__FILE__, 'create_announcements_table');
register_uninstall_hook(__FILE__, 'remove_announcements_table');

function announcements_admin_menu() {
    add_menu_page('Announcements', 'Announcements', 'activate_plugins', 'announcements', 'announcements_page_handler', 'dashicons-bell', '5');
    add_submenu_page('announcements', 'Announcements', 'Announcements', 'activate_plugins', 'announcements', 'announcements_page_handler');
    add_submenu_page('announcements', 'Add new', 'Add new', 'activate_plugins', 'announcements_form', 'announcements_page_add_form');
}

add_action('admin_menu', 'announcements_admin_menu');

function random_announcements_register_styles(){
    wp_register_style('ra_styles', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style('ra_styles');
}
add_action('init', 'random_announcements_register_styles');

function build_announcement($announcement) {
    ?>
    <div class="announcement">
        <h2 class="announcement-title"><?php echo($announcement->title) ?></h2>
        <div class="announcement-html"><?php echo($announcement->html) ?></div>
    </div>
    <?php
}



function add_random_announcement_to_post($content) {
    $announcement = get_random_post();
    if (null === $announcement) {
        return;
    }
    return null === $announcement ? $content : build_announcement($announcement) . $content;
}

add_filter("the_content", "add_random_announcement_to_post", 10, 1);