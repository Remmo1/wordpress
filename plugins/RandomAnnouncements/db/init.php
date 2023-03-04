<?php

function create_announcements_table() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'announcements';

    $sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL AUTO_INCREMENT,
        title tinytext NOT NULL,
        html mediumtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    error_log('SQL CREATED');
    error_log($sql);

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    error_log(implode(',', dbDelta($sql)));

    error_log('dbDelta done');
}

