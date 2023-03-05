<?php

function get_random_post() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'announcements';

    $sql = "SELECT * FROM $table_name
    ORDER BY RAND()
    LIMIT 1
    ";

    return $wpdb->get_row($sql);
}