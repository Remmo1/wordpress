<?php

require_once('db/dao.php');

function announcements_page_handler() {
    global $wpdb;

    $table = new Announcements_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'announcements'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
    <div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Announcements', 'announcements')?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=announcements_form');?>"><?php _e('Add new', 'announcements')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="announcements-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

function ra_admin_page() {
    return announcements_page_handler();
}
