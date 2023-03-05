<?php

require_once('db/dao.php');

function announcements_page_handler() {
    global $wpdb;

    $table = new Announcements_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
        $deleted_count = is_array($ids) ? count($ids) : 1;
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf('Items deleted: %d', $deleted_count) . '</p></div>';
    }
    ?>
    <div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2>Announcements <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=announcements_form');?>">Add new</a>
    </h2>
    <?php echo $message; ?>

    <form id="announcements-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

function announcements_page_add_form() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'announcements';

    $message = '';
    $notice = '';

    $default = array(
        'id' => 0,
        'title' => '',
        'html' => '',
    );

    $item = shortcode_atts($default, $_REQUEST);

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $result = $wpdb->replace($table_name, $item);
        $item['id'] = $wpdb->insert_id;
        if ($result) {
            $message = 'Item was successfully saved';
        } else {
            $notice = 'There was an error while saving item';
        }
    }
    add_meta_box('announcement_form_meta_box', 'Announcement data', 'announcements_form_meta_box_handler', 'announcement', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2>Announcement<a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=announcements');?>">Back to list</a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php do_meta_boxes('announcement', 'normal', $item); ?>
                    <input type="submit" value="Save" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function announcements_form_meta_box_handler($item) {
    ?>

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="title">Title</label>
        </th>
        <td>
            <input id="title" name="title" type="text" style="width: 95%" value="<?php echo esc_attr($item['title'])?>"
                    size="50" class="code" placeholder="Title" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="html">HTML</label>
        </th>
        <td>
            <input id="html" name="html" type="text" style="width: 95%; resize: vertical" value="<?php echo esc_attr($item['html'])?>"
                    size="50" class="code" placeholder="HTML" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function ra_admin_page() {
    return announcements_page_handler();
}
