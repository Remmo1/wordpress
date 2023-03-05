<?php

require_once('db/dao.php');

function announcements_page_handler() {
    global $wpdb;

    $table = new Announcements_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        error_log(implode('|', $_REQUEST));
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'announcements'), count($_REQUEST['html'])) . '</p></div>';
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

    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        $item = shortcode_atts($default, $_REQUEST);
        $result = $wpdb->insert($table_name, $item);
        $item['id'] = $wpdb->insert_id;
        if ($result) {
            $message = __('Item was successfully saved', 'announcements');
        } else {
            $notice = __('There was an error while saving item', 'announcements');
        }
    }
    add_meta_box('announcement_form_meta_box', 'Announcement data', 'announcements_form_meta_box_handler', 'announcement', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Announcement', 'announcements')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=announcements');?>"><?php _e('back to list', 'announcements')?></a>
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
                    <input type="submit" value="<?php _e('Save', 'announcements')?>" id="submit" class="button-primary" name="submit">
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
            <label for="title"><?php _e('Title', 'announcements')?></label>
        </th>
        <td>
            <input id="title" name="title" type="text" style="width: 95%" value="<?php echo esc_attr($item['title'])?>"
                    size="50" class="code" placeholder="<?php _e('Title', 'announcements')?>" required>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="html"><?php _e('HTML', 'announcements')?></label>
        </th>
        <td>
            <input id="html" name="html" type="text" style="width: 95%; resize: vertical" value="<?php echo esc_attr($item['html'])?>"
                    size="50" class="code" placeholder="<?php _e('HTML', 'announcements')?>" required>
        </td>
    </tr>
    </tbody>
</table>
<?php
}

function ra_admin_page() {
    return announcements_page_handler();
}
