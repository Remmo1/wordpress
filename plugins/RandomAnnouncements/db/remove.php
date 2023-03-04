<?php

function remove_announcements_table(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'announcements';
    $wpdb->query("DROP TABLE IF EXISTS $table_name"); 
}