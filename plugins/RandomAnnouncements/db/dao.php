<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Announcements_List_Table extends WP_List_Table {
    
	function __construct() {
        global $status, $page;
        $this->screen = get_current_screen();
    }

    function column_default($item, $column_name) {
        return $item[$column_name];
    }

    function column_title($item) {
        return '<em>' . $this->column_default($item, 'title') . '</em>';
    }
    
    function column_html($item) {
        return '<em>' . $this->column_default($item, 'html') . '</em>';
    }
    
    function column_name($item) {
        $actions = array(
            'edit' => sprintf('<a href="?page=announcements_form&id=%s">%s</a>', $item['id'], 'Edit'),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], 'Delete'),
        );

        return sprintf('%s %s',
            $item['title'],
            $this->row_actions($actions)
        );
    }

    function get_columns() {
        $columns = array(
            'title' => 'Title',
            'html' => 'Text',
        );
        return $columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'announcements';

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'announcements';

        $per_page = 5;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
	
}
